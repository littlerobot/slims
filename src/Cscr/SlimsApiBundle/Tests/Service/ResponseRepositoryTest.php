<?php

namespace Cscr\SlimsApiBundle\Tests\Service;

use Cscr\SlimsApiBundle\Entity\Container;
use Cscr\SlimsApiBundle\Service\ResponseRepository;

class ResponseRepositoryTest extends \PHPUnit_Framework_TestCase
{
    public function testReturnsAResponseForAMatchingObject()
    {
        $entityClass = 'Cscr\SlimsApiBundle\Entity\Container';
        $entity = new $entityClass();
        $responseClass = 'Cscr\SlimsApiBundle\Response\ContainerResponse';

        $repository = new ResponseRepository();
        $repository->add($responseClass);

        $this->assertInstanceOf($responseClass, $repository->getFor($entity));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testThrowsExceptionIfThereIsNoMatchingResponse()
    {
        $entity = new Container();
        $repository = new ResponseRepository();
        $repository->getFor($entity);
    }
}
