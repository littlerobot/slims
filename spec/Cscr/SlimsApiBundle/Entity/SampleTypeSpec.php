<?php

namespace spec\Cscr\SlimsApiBundle\Entity;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class SampleTypeSpec extends ObjectBehavior
{
    public function it_has_a_name()
    {
        $name = 'Sample type name';
        $this->setName($name);
        $this->getName()->shouldReturn($name);
    }
}
