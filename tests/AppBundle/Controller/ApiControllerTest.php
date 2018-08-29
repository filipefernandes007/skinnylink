<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiControllerTest extends WebTestCase
{
    public function testGet()
    {
        $client = static::createClient();

        $crawler = $client->request('POST', 'http://192.168.33.89:8000/api', ['url' => 'http://www.publico.pt']);

        $json = json_decode($client->getResponse()->getContent());

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertGreaterThanOrEqual(1, (int) $object->id);

        //$this->assertContains('Welcome to Symfony', $crawler->filter('#container h1')->text());
    }
}
