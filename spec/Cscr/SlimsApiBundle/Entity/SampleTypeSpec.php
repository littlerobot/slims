<?php

namespace spec\Cscr\SlimsApiBundle\Entity;

use PhpSpec\ObjectBehavior;

class SampleTypeSpec extends ObjectBehavior
{
    public function it_has_a_name()
    {
        $name = 'Sample type name';
        $this->setName($name);
        $this->getName()->shouldReturn($name);
    }
}
