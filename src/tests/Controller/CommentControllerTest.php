<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CommentControllerTest extends WebTestCase
{
    public function testAdd()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/blog/category/1');
        $comments_widget_node = $crawler->filter('#comments-widget');
        $comments_thread_url = '/comment/' . $comments_widget_node->attr('data-comments-widget-thread');
        $client->request('GET', $comments_thread_url);
        $comments = json_decode($client->getResponse()->getContent(), true);

        $client->request(
            'PUT',
            $comments_thread_url,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'author' => 'Author Test',
                'content' => 'Content Test',
            ])
        );
        $this->assertResponseStatusCodeSame(422);

        $client->request(
            'PUT',
            $comments_thread_url,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'author' => 'Author Test',
                'content' => 'Content Test',
                '_token' => $comments_widget_node->attr('data-comments-widget-csrf_token'),
            ])
        );
        $this->assertResponseIsSuccessful();

        $client->request('GET', $comments_thread_url);
        $comments_updated = json_decode($client->getResponse()->getContent(), true);
        $this->assertSame(\count($comments) + 1, \count($comments_updated));
    }
}
