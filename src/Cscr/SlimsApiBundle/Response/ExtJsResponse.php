<?php

namespace Cscr\SlimsApiBundle\Response;

use JMS\Serializer\Annotation as JMS;

abstract class ExtJsResponse
{
    /**
     * @var bool
     */
    protected $success = false;

    /**
     * @var mixed
     *
     * @JMS\SerializedName("data")
     */
    protected $data;

    public function __construct($data)
    {
        if ($data) {
            $this->success = true;
        }

        $this->data = $data;
    }

    /**
     * Does the response support the object type?
     *
     * @param $object
     *
     * @return bool
     */
    public static function supports($object)
    {
        // Check the class specified in the constructor and see whether it matches the passed class.
        $r = new \ReflectionClass(get_called_class());
        $constructor = $r->getConstructor();
        $class = $constructor->getParameters()[0]->getClass()->getName();

        return $class === get_class($object);
    }
}
