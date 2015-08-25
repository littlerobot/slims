<?php

namespace Cscr\SlimsApiBundle\Response;

class ContainerCollectionResponse extends ExtJsResponse
{
    public function __construct(array $containers)
    {
        parent::__construct($containers);
    }
}
