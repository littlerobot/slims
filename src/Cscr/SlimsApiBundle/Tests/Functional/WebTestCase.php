<?php

namespace Cscr\SlimsApiBundle\Tests\Functional;

use Symfony\Component\BrowserKit\Client;
use Symfony\Component\HttpFoundation\Response;
use Liip\FunctionalTestBundle\Test\WebTestCase as BaseWebTestCase;

abstract class WebTestCase extends BaseWebTestCase
{
    /**
     * @var Client
     */
    protected $client;

    public function setUp()
    {
        $this->client = $this->getJsonClient();

        $this->loadFixtures([
            'Cscr\SlimsApiBundle\DataFixtures\ORM\LoadResearchGroupData',
            'Cscr\SlimsApiBundle\DataFixtures\ORM\LoadContainerData',
        ]);
    }

    protected function assertJsonResponse(Response $response, $statusCode = 200)
    {
        $this->assertEquals(
            $statusCode,
            $response->getStatusCode(),
            "Incorrect status code:\n".$response->getContent()
        );
        $this->assertTrue(
            $response->headers->contains('Content-Type', 'application/json'),
            "Missing 'Content-Type' header:\n".$response->headers
        );
        $this->assertJson($response->getContent(), 'Response is not in JSON format');
    }

    /**
     * @return Client
     */
    protected function getJsonClient()
    {
        $client = self::createClient();
        $client->setServerParameters([
            'CONTENT_TYPE' => 'application/json',
            'HTTP_ACCEPT' => 'application/json',
        ]);

        return $client;
    }

    /**
     * Clears the Doctrine cache so that entities will be reloaded from the database,
     * rather than the cache.
     */
    protected function clearDoctrineCache()
    {
        $this->getContainer()->get('doctrine')->getManager()->clear();
    }
}
