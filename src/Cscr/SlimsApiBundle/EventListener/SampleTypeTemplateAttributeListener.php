<?php

namespace Cscr\SlimsApiBundle\EventListener;

use Cscr\SlimsApiBundle\Entity\SampleTypeTemplateAttribute;
use Cscr\SlimsUserBundle\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\LifecycleEventArgs;

class SampleTypeTemplateAttributeListener
{
    /**
     * @var EntityManager
     */
    private $em;

    public function postLoad(SampleTypeTemplateAttribute $attribute, LifecycleEventArgs $event)
    {
        $this->em = $event->getEntityManager();

        if ($attribute->getType() === SampleTypeTemplateAttribute::TYPE_USER) {
            $this->loadUserOptions($attribute);
        }
    }

    private function loadUserOptions(SampleTypeTemplateAttribute $attribute)
    {
        $users = $this->em->getRepository('CscrSlimsUserBundle:User')->findAll();
        $users = new ArrayCollection($users);
        $names = $users->map(function (User $user) {
            return $user->getName();
        });

        $attribute->setOptions($names->toArray());
    }
}
