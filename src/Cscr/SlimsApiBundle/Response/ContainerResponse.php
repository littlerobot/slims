<?php

namespace Cscr\SlimsApiBundle\Response;

use JMS\Serializer\Annotation as JMS;

class ContainerResponse extends ExtJsResponse
{
    /**
     * @var
     *
     * @JMS\SerializedName("container")
     */
    protected $data;
}
