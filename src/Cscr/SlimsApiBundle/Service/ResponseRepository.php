<?php

namespace Cscr\SlimsApiBundle\Service;

use Cscr\SlimsApiBundle\Response\ExtJsResponse;

class ResponseRepository
{
    private $classes = [];

    public function add($class)
    {
        $this->classes[] = $class;
    }

    /**
     * @param $object
     * @return ExtJsResponse|null
     */
    public function getFor($object)
    {
        foreach ($this->classes as $class) {
            if ($class::supports($object)) {
                return new $class($object);
            }
        }

        return null;
    }
}
