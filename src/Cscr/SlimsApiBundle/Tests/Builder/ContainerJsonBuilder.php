<?php

namespace Cscr\SlimsApiBundle\Tests\Builder;

use Cscr\SlimsApiBundle\Entity\Container;

class ContainerJsonBuilder
{
    /**
     * @param Container $container
     * @return string
     */
    public static function buildCreate(Container $container)
    {
        return json_encode([
            'name' => $container->getName(),
            'research_group' => $container->getResearchGroup()->getId(),
            'rows' => $container->getRows(),
            'columns' => $container->getColumns(),
            'stores' => $container->getStores(),
            'comment' => $container->getComment(),
        ]);
    }

    /**
     * @param Container $container
     * @return string
     */
    public static function buildUpdate(Container $container)
    {
        return json_encode([
            'name' => $container->getName(),
            'comment' => $container->getComment(),
            'colour' => $container->getColour(),
        ]);
    }
}
