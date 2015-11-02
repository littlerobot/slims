<?php

namespace Cscr\SlimsApiBundle\Tests\Builder;

use Cscr\SlimsApiBundle\Entity\Container;

class ContainerBuilder
{
    protected $name = 'Container';
    protected $rows = 10;
    protected $columns = 10;
    protected $stores = Container::STORES_CONTAINERS;
    protected $comment = 'Comment';
    protected $colour = '#ffffff';

    /**
     * @return Container
     */
    public function build()
    {
        return (new Container())
            ->setName($this->name)
            ->setResearchGroup((new ResearchGroupBuilder())->build())
            ->setRows($this->rows)
            ->setColumns($this->columns)
            ->setStores($this->stores)
            ->setComment($this->comment)
            ->setColour($this->colour)
        ;
    }

    /**
     * @param string $stores
     *
     * @return ContainerBuilder
     */
    public function withStores($stores)
    {
        $this->stores = $stores;

        return $this;
    }

    /**
     * @param int $rows
     *
     * @return ContainerBuilder
     */
    public function withRows($rows)
    {
        $this->rows = $rows;

        return $this;
    }

    /**
     * @param int $columns
     *
     * @return ContainerBuilder
     */
    public function withColumns($columns)
    {
        $this->columns = $columns;

        return $this;
    }

    /**
     * @param string $name
     *
     * @return ContainerBuilder
     */
    public function withName($name)
    {
        $this->name = $name;

        return $this;
    }
}
