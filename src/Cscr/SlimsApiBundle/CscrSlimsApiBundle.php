<?php

namespace Cscr\SlimsApiBundle;

use Cscr\SlimsApiBundle\DependencyInjection\Compiler\DownloadableEntityUrlPopulatorPass;
use Cscr\SlimsApiBundle\DependencyInjection\Compiler\ResponseRegistryPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class CscrSlimsApiBundle extends Bundle
{
    public function build(ContainerBuilder $builder)
    {
        parent::build($builder);

        $builder
            ->addCompilerPass(new DownloadableEntityUrlPopulatorPass());
    }
}
