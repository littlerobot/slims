<?php

namespace Cscr\SlimsApiBundle\Tests\Functional;

use Cscr\SlimsApiBundle\Entity\Container;
use Cscr\SlimsApiBundle\Tests\Builder\ContainerJsonBuilder;

/**
 * @group functional
 */
class ContainerApiTest extends WebTestCase
{
    public function testListContainersReturnsValidResponse()
    {
        $this->client->request('GET', '/api/containers');
        $this->assertJsonResponse($this->client->getResponse());
    }

    public function testCreateCompleteContainer()
    {
        $name = 'ABCDEFGH1234567';
        $builder = new ContainerJsonBuilder();
        $builder->withName($name);

        $this->client->request('POST', '/api/containers', [], [], [], $builder->buildCreate());
        $this->assertJsonResponse($this->client->getResponse());

        $container = $this->getContainerByName($name);
        $this->assertNotNull($container, 'Container was not saved to database');
    }

    public function testUpdateExistingContainer()
    {
        $id = 1;
        $name = 'UPDATED-ABCDEFGH1234567';
        $builder = new ContainerJsonBuilder();
        $builder->withName($name);

        $this->client->request('POST', sprintf('/api/containers/%d', $id), [], [], [], $builder->buildUpdate());
        $this->assertJsonResponse($this->client->getResponse());

        $container = $this->getContainerByName($name);
        $this->assertNotNull($container, 'Container was not saved to database');
    }

    /**
     * @param  string    $name
     * @return Container
     */
    private function getContainerByName($name)
    {
        return $this->getContainer()->get('doctrine')->getRepository('CscrSlimsApiBundle:Container')->findOneBy([
            'name' => $name,
        ]);
    }
}
