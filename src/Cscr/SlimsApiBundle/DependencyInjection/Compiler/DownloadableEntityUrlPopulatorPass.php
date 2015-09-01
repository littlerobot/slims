<?php

namespace Cscr\SlimsApiBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class DownloadableEntityUrlPopulatorPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('slims.downloadable_entity_url_generator_registry')) {
            return;
        }

        $definition = $container->findDefinition('slims.downloadable_entity_url_generator_registry');

        $taggedServices = $container->findTaggedServiceIds('slims.downloadable_entity_url_generator');

        foreach ($taggedServices as $id => $tags) {
            $definition->addMethodCall(
                'addUrlGenerator',
                [new Reference($id)]
            );
        }
    }
}
