<?php

namespace Cscr\SlimsApiBundle\Service;

use Cscr\SlimsApiBundle\Entity\Downloadable;

class DownloadableEntityUrlGeneratorRegistry
{
    /**
     * @var DownloadableEntityUrlGeneratorInterface[]
     */
    private $generators = [];

    /**
     * @param DownloadableEntityUrlGeneratorInterface $generator
     */
    public function addUrlGenerator(DownloadableEntityUrlGeneratorInterface $generator)
    {
        $this->generators[] = $generator;
    }

    /**
     * Gets a generator that can be used for the passed object.
     *
     * If no matching generators are available, null is returned.
     *
     * @param Downloadable $object
     *
     * @return DownloadableEntityUrlGeneratorInterface|null
     */
    public function getGeneratorFor(Downloadable $object)
    {
        $namespace = get_class($object);

        foreach ($this->generators as $generator) {
            if ($generator->canGenerateFor($namespace)) {
                return $generator;
            }
        }

        return;
    }
}
