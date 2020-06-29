<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Link;

class BlogCategoryControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/blog/category/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Blog categories');
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

        $this->assertSelectorTextContains('h1', 'Create new blog category');

        $client->submitForm('Save', [
            'blog_category[name]' => '',
            'blog_category[description]' => '',
        ]);
        $this->assertSelectorExists('.invalid-feedback.d-block', 'No errors!');

        $client->submitForm('Save', [
            'blog_category[name]' => 'Test category',
            'blog_category[description]' => 'Test category description',
        ]);
        $this->assertResponseRedirects('/blog/category/');

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

        $this->assertSelectorTextContains('h1', 'Blog category - Test category');

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
        $client->click($link);
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Edit blog category');

        $client->submitForm('Update', [
            'blog_category[name]' => 'Test category updated',
            'blog_category[description]' => 'Test category description updated',
        ]);
        $this->assertResponseRedirects('/blog/category/');

        $crawler = $client->followRedirect();
        $this->assertResponseIsSuccessful();

        $actual_category_name = $crawler->filter('main table tr')->last()->filter('td')->eq(1)->text();
        $this->assertSame('Test category updated', $actual_category_name, 'Category is not updated!');
    }

    /**
     * @depends testEdit
     */
    public function testDelete(){
        $client = static::createClient();
        $crawler = $client->request('GET', '/blog/category/');
        $client->submit($crawler->filter('main table tr')->last()->selectButton('Delete')->form());
        $this->assertResponseRedirects('/blog/category/');
        $crawler = $client->followRedirect();
        $this->assertResponseIsSuccessful();

        $actual_category_name = $crawler->filter('main table tr')->last()->filter('td')->eq(1)->text();
        $this->assertNotSame('Test category updated', $actual_category_name, 'Category is not deleted!');
    }
}
