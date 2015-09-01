<?php

namespace Cscr\SlimsApiBundle\Response;

use Cscr\SlimsApiBundle\Entity\Container;
use JMS\Serializer\Annotation as JMS;

class ContainerResponse extends ExtJsResponse
{
    /**
     * @var
     *
     * @JMS\SerializedName("container")
     */
    protected $data;

    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        parent::__construct($container);
    }
}
