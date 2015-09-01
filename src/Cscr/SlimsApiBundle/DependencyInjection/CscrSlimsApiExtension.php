<?php

namespace Cscr\SlimsApiBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class CscrSlimsApiExtension extends ConfigurableExtension
{
    /**
     * {@inheritdoc}
     */
    protected function loadInternal(array $mergedConfig, ContainerBuilder $container)
    {
        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.xml');

        $this->loadResponseClasses($mergedConfig, $container);
    }

    private function loadResponseClasses(array $config, ContainerBuilder $container)
    {
        $classes = $config['response_classes'];

        if (empty($classes)) {
            return;
        }

        $definition = $container->findDefinition('slims.service.response_repository');

        foreach ($classes as $namespace) {
            $definition->addMethodCall('add', [$namespace]);
        }
    }
}
