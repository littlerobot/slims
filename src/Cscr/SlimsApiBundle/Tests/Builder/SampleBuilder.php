<?php

namespace Cscr\SlimsApiBundle\Tests\Builder;

use Cscr\SlimsApiBundle\Entity\Sample;

class SampleBuilder
{
    public function build()
    {
        return new Sample();
    }
}
