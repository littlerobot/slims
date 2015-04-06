<?php

namespace Cscr\SlimsApiBundle\EventListener;

use Cscr\SlimsApiBundle\Entity\SampleTypeAttribute;
use Cscr\SlimsApiBundle\Service\SampleTypeDocumentAttributeUrlGenerator;
use Doctrine\ORM\Event\LifecycleEventArgs;

class SampleTypeAttributeListener
{
    /**
     * @var SampleTypeDocumentAttributeUrlGenerator
     */
    private $generator;

    public function __construct(SampleTypeDocumentAttributeUrlGenerator $generator)
    {
        $this->generator = $generator;
    }

    public function postLoad(LifecycleEventArgs $event)
    {
        $entity = $event->getEntity();

        if ($entity instanceof SampleTypeAttribute) {
            if ($entity->getFilename()) {
                $entity->setUrl($this->generator->generateUrl($entity->getId(), $entity->getFilename()));
            }
        }
    }
}
