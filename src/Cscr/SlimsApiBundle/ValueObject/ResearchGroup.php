<?php

namespace Cscr\SlimsApiBundle\ValueObject;

use JMS\Serializer\Annotation as JMS;

class ResearchGroup
{
    /**
     * @var string
     */
    private $name;

    public function __construct($name)
    {
        \Assert\that($name)->string()->betweenLength(1, 255);
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
