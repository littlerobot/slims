<?php

namespace Cscr\SlimsApiBundle\Tests\Renderer;

use Cscr\SlimsApiBundle\Entity\Container;

class ContainerRenderer
{
    /**
     * @param Container $container
     * @return string
     */
    public static function renderCreateAsJson(Container $container)
    {
        return json_encode(self::renderCreateAsArray($container));
    }

    /**
     * @param Container $container
     * @return string
     */
    public static function renderUpdateAsJson(Container $container)
    {
        return json_encode(self::renderUpdateAsArray($container));
    }

    /**
     * @param Container $container
     * @return array
     */
    public static function renderCreateAsArray(Container $container)
    {
        return [
            'name' => $container->getName(),
            'research_group' => $container->getResearchGroup()->getId(),
            'rows' => $container->getRows(),
            'columns' => $container->getColumns(),
            'stores' => $container->getStores(),
            'comment' => $container->getComment(),
        ];
    }

    /**
     * @param Container $container
     * @return array
     */
    public static function renderUpdateAsArray(Container $container)
    {
        return [
            'name' => $container->getName(),
            'comment' => $container->getComment(),
            'colour' => $container->getColour(),
        ];
    }
}
