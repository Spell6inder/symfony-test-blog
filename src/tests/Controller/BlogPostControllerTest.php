<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Link;

class BlogPostControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/blog/post/');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('.btn.btn-primary', 'Create new');

        $this->assertSelectorTextContains('h1', 'Blog posts');
        $this->assertSelectorTextContains('.btn.btn-primary', 'Create new');

        $crawler = $client->request('GET', '/blog/category/1');
        $this->assertSelectorTextContains('.btn.btn-primary', 'Create new');

        return $crawler->selectLink('Create new')->link();
    }

    /**
     * @depends testIndex
     *
     * @param Link $link
     *
     * @return Link
     */
    public function testNew(Link $link)
    {
        $client = static::createClient();
        $crawler = $client->click($link);

        $this->assertSelectorTextContains('h1', 'Create new blog post');

        $client->submitForm('Save', [
            'blog_post[name]' => '',
            'blog_post[content]' => '',
        ]);
        $this->assertSelectorExists('.invalid-feedback.d-block', 'No errors!');

        $form = $crawler->selectButton('Save')->form([
            'blog_post[name]' => 'Test post',
            'blog_post[content]' => 'Test post content',
        ]);
        $test_file = tempnam(sys_get_temp_dir(), 'TestPost');
        file_put_contents($test_file, 'Test post file');
        $form['blog_post[file_input]']->upload($test_file);
        $client->submit($form);
        $this->assertResponseRedirects('/blog/category/1');

        $crawler = $client->followRedirect();
        $this->assertResponseIsSuccessful();
        return $crawler->filter('main table tr')->last()->selectLink('Show')->link();
    }

    /**
     * @depends testNew
     *
     * @param Link $link
     *
     * @return Link
     */
    public function testShow(Link $link){
        $client = static::createClient();
        $crawler = $client->click($link);
        $this->assertResponseIsSuccessful();

        $this->assertSelectorTextContains('h1', 'Blog post - Test post');

        return $crawler->selectLink('Edit')->link();
    }

    /**
     * @depends testShow
     *
     * @param Link $link
     */
    public function testEdit(Link $link)
    {
        $client = static::createClient();
        $crawler = $client->click($link);
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Edit blog post');

        $form = $crawler->selectButton('Update')->form([
            'blog_post[name]' => 'Test post updated',
            'blog_post[content]' => 'Test post content updated',
        ]);
        $form['blog_post[category]']->select(2);
        $client->submit($form);
        $this->assertResponseRedirects('/blog/category/2');

        $crawler = $client->followRedirect();
        $this->assertResponseIsSuccessful();

        $actual_post_name = $crawler->filter('main table tr')->last()->filter('td')->eq(1)->text();
        $this->assertSame('Test post updated', $actual_post_name, 'Post is not updated!');
    }

    /**
     * @depends testEdit
     */
    public function testDelete(){
        $client = static::createClient();
        $crawler = $client->request('GET', '/blog/category/2');
        $client->submit($crawler->filter('main table tr')->last()->selectButton('Delete')->form());
        $this->assertResponseRedirects('/blog/category/2');
        $crawler = $client->followRedirect();
        $this->assertResponseIsSuccessful();

        $actual_post_name = $crawler->filter('main table tr')->last()->filter('td')->eq(1)->text();
        $this->assertNotSame('Test post updated', $actual_post_name, 'Post is not deleted!');
    }
}
