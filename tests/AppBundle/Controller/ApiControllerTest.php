<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiControllerTest extends WebTestCase
{
    const API_ROUTE = 'http://192.168.33.89:8000/api';

    protected $url  = 'http://www.wikipedia.com';
    protected static $id;

    public function testCreate()
    {
        $client  = static::createClient();
        $crawler = $client->request('POST', self::API_ROUTE, ['url' => $this->url]);
        $object  = json_decode($client->getResponse()->getContent());

        self::$id = (int) $object->id;

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertGreaterThanOrEqual(1, self::$id);
    }

    public function testGet()
    {
        $client  = static::createClient();
        $crawler = $client->request('GET', self::API_ROUTE . '/' . self::$id);
        $object  = json_decode($client->getResponse()->getContent());

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertGreaterThanOrEqual(1, (int) $object->id);
        $this->assertEquals($this->url, $object->url);
    }
}
