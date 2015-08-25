<?php

namespace Cscr\SlimsApiBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ResponseRegistryPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('slims.service.response_repository')) {
            return null;
        }

        $definition = $container->findDefinition('slims.service.response_repository');

        $taggedServices = $container->findTaggedServiceIds('slims.response_type');

        foreach ($taggedServices as $id => $tags) {
            $response = $container->findDefinition($id);
            $definition->addMethodCall('add', [$response->getClass()]);
        }
    }
}
