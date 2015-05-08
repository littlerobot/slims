<?php

namespace Cscr\SlimsApiBundle\Tests\Functional;

use Cscr\SlimsApiBundle\Entity\Container;
use Cscr\SlimsApiBundle\Tests\Builder\ContainerBuilder;
use Cscr\SlimsApiBundle\Tests\Renderer\ContainerRenderer;

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
        $builder = new ContainerBuilder();
        $builder->withName($name);
        $content = ContainerRenderer::renderCreateAsJson($builder->build());

        $this->client->request('POST', '/api/containers', [], [], [], $content);
        $this->assertJsonResponse($this->client->getResponse());

        $container = $this->getContainerByName($name);
        $this->assertNotNull($container, 'Container was not saved to database');
    }

    public function testUpdateExistingContainer()
    {
        $id = 1;
        $name = 'UPDATED-ABCDEFGH1234567';
        $builder = new ContainerBuilder();
        $builder->withName($name);
        $content = ContainerRenderer::renderUpdateAsJson($builder->build());

        $this->client->request('POST', sprintf('/api/containers/%d', $id), [], [], [], $content);
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
