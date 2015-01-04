<?php

namespace Cscr\SlimsApiBundle\Tests\Builder;

use Cscr\SlimsApiBundle\Entity\ResearchGroup;

class ResearchGroupBuilder
{
    private $name = 'Research group';

    public function build()
    {
        return (new ResearchGroup())
            ->setName($this->name)
        ;
    }
}
