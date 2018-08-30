<?php

namespace Tests\AppBundle\Controller;

use AppBundle\Service\SkinnyLinkService;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiControllerTest extends WebTestCase
{
    /**
     * The test url to be checked. Change if you want to any VALID url.
     *
     * @var string
     */
    protected $testUrl  = 'http://www.wikipedia.com';

    /**
     * Preserve id in order to check it after create test url
     *
     * @var int
     */
    protected static $id;

    /**
     * Preserve skinnyUrl in order to check it after create test url
     *
     * @var string
     */
    protected static $skinnyUrl;

    protected function setUp() : void
    {
        echo "\n" . $this->getName();
    }

    public function testCreate() : void
    {
        $client  = static::createClient();

        $crawler = $client->request('POST', '/api', ['url' => $this->testUrl]);
        $object  = json_decode($client->getResponse()->getContent());

        self::$id         = (int) $object->id;
        self::$skinnyUrl = $object->skinnyUrl;

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertGreaterThanOrEqual(1, self::$id);
    }

    public function testGet() : void
    {
        $client  = static::createClient();
        $crawler = $client->request('GET', '/api/' . self::$id);
        $object  = json_decode($client->getResponse()->getContent());

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertGreaterThanOrEqual(1, (int) $object->id);
        $this->assertEquals($this->testUrl, $object->url);
        $this->testCreate(self::$skinnyUrl, $object->skinnyUrl);
    }

    public function testFail1() : void {
        $client  = static::createClient();

        $crawler = $client->request('POST', '/api', ['url' => 'www.wikipedia.org']);
        $object  = json_decode($client->getResponse()->getContent());

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertObjectHasAttribute('error', $object);
        $this->assertEquals(SkinnyLinkService::INVALID_URL, $object->error);
    }

    public function testFail2() : void {
        $client  = static::createClient();

        $crawler = $client->request('POST', '/api', ['url' => 'http://www.wikipediaxyz.org']);
        $object  = json_decode($client->getResponse()->getContent());

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertObjectHasAttribute('error', $object);
        $this->assertEquals(SkinnyLinkService::URL_DOES_NOT_EXIST, $object->error);
    }

    public function testFailSQLInjection1() : void {
        $client  = static::createClient();

        $crawler = $client->request('POST', '/api', ['url' => $this->testUrl .  '; truncate table skinny_link']);
        $object  = json_decode($client->getResponse()->getContent());

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertObjectHasAttribute('error', $object);
        $this->assertEquals(SkinnyLinkService::INVALID_URL, $object->error);
    }

    public function testFailSQLInjection2() : void {
        $client  = static::createClient();

        $crawler = $client->request('POST', '/api', ['url' => $this->testUrl .  ' OR 1 = 1']);
        $object  = json_decode($client->getResponse()->getContent());

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertObjectHasAttribute('error', $object);
        $this->assertEquals(SkinnyLinkService::INVALID_URL, $object->error);
    }
}
