<?php

namespace Cscr\SlimsApiBundle\EventListener;

use Cscr\SlimsApiBundle\Entity\Downloadable;
use Cscr\SlimsApiBundle\Service\DownloadableEntityUrlGeneratorRegistry;
use Doctrine\ORM\Event\LifecycleEventArgs;

/**
 * Populates the URL property for entities that can be downloaded.
 */
class DownloadableEntityUrlPopulater
{
    /**
     * @var DownloadableEntityUrlGeneratorRegistry
     */
    private $registry;

    public function __construct(DownloadableEntityUrlGeneratorRegistry $registry)
    {
        $this->registry = $registry;
    }

    public function postLoad(LifecycleEventArgs $event)
    {
        $entity = $event->getEntity();

        if (!$entity instanceof Downloadable) {
            return;
        }

        if (!$entity->getFilename()) {
            return;
        }

        if ($generator = $this->registry->getGeneratorFor($entity)) {
            $entity->setUrl($generator->generateUrl($entity->getId(), $entity->getFilename()));
        }
    }
}
