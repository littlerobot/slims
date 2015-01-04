<?php

namespace Cscr\SlimsApiBundle\Tests\Builder;

class ContainerJsonBuilder extends ContainerBuilder
{
    protected $researchGroupId = 1;

    /**
     * @return string
     */
    public function buildCreate()
    {
        return json_encode([
            'name' => $this->name,
            'research_group' => $this->researchGroupId,
            'rows' => $this->rows,
            'columns' => $this->columns,
            'stores' => $this->stores,
            'comment' => $this->comment,
        ]);
    }

    /**
     * @return string
     */
    public function buildUpdate()
    {
        return json_encode([
            'name' => $this->name,
            'comment' => $this->comment,
            'colour' => $this->colour,
        ]);
    }
}
