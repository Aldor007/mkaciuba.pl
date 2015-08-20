<?php

namespace Aldor\BlogBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BlogControllerTest extends WebTestCase
{
    public function testIndex()
    {
         $client = $this->createClient();

        $crawler = $client->request('GET', '/');

        $this->assertTrue($client->getResponse()->getStatusCode() == '200' );

        $this->assertTrue($crawler->filter('title:contains("Blog")')->count() > 0);

        $this->assertTrue($crawler->filter('h3:contains("Blog")')->count() > 0);
        $this->assertTrue($crawler->filter('.container:contains("Brak")')->count()==0);
        $this->assertTrue($crawler->filter('.date:contains("Cubieboard")')->count()>0);

    }

    public function testOnePost() {
         $client = $this->createClient();

        $crawler = $client->request('GET', '/');
        $link = $crawler->selectLink('o autorze0')->link();
        $crawler = $client->click($link);
        $content = $client->getResponse()->getContent();
        $this->assertTrue($client->getResponse()->getStatusCode() == '200' );
        $this->assertContains('Cubieboard', $content);
        $this->assertContains('Kategorie', $content);
        $this->assertContains('Tagi', $content);
        $this->assertContains('Linux', $content);
        $this->assertContains('Tagi:', $content);
        $this->assertContains('blog_post', $content);
    }

    public function testCategory() {
         $client = $this->createClient();

        $crawler = $client->request('GET', '/blog/grafika');
        $content = $client->getResponse()->getContent();
        $this->assertTrue($client->getResponse()->getStatusCode() == '200' );
        $this->assertTrue($crawler->filter('h3:contains("Cubieboard")')->count() == 0);
        $this->assertTrue($crawler->filter('.post')->count() > 1);
    }
 }
