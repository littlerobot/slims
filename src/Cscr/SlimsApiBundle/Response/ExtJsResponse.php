<?php

namespace Cscr\SlimsApiBundle\Response;

class ExtJsResponse
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }
}
