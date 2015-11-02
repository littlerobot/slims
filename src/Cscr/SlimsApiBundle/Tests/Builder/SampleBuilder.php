<?php

namespace Cscr\SlimsApiBundle\Tests\Builder;

use Cscr\SlimsApiBundle\Entity\Sample;
use Cscr\SlimsApiBundle\ValueObject\SamplePosition;

class SampleBuilder
{
    /** @var SamplePosition */
    private $position;

    public function __construct()
    {
        $this->position = SamplePosition::fromRowAndColumn(0, 0);
    }

    public function build()
    {
        return (new Sample())
            ->setPosition($this->position);
    }
}
