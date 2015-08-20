<?php
namespace Company\BlogBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PagesControllerTest extends WebTestCase
{
    public function testShow()
    {
        $client = $this->createClient();

        $crawler = $client->request('GET', '/about');

        $this->assertTrue($client->getResponse()->getStatusCode() == '404' );

    }
}
