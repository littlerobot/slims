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
     * @param mixed $object
     *
     * @return ExtJsResponse
     *
     * @throws \InvalidArgumentException If there is no matching response type for the passed object.
     */
    public function getFor($object)
    {
        foreach ($this->classes as $class) {
            if ($class::supports($object)) {
                return new $class($object);
            }
        }

        throw new \InvalidArgumentException(
            sprintf("There is no matching response type for '%s'", get_class($object))
        );
    }
}
