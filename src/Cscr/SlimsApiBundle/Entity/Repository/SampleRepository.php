<?php

namespace Cscr\SlimsApiBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class SampleRepository extends EntityRepository
{
    public function filter(
        $name = null,
        $type = null,
        $user = null,
        $container = null,
        \DateTime $storedFrom = null,
        \DateTime $storedTo = null
    ) {
        $q = $this->createQueryBuilder('q')
            ->select('s, container')
            ->from('CscrSlimsApiBundle:Sample', 's')
            ->innerJoin('s.attributes', 'sample_attributes')
            ->innerJoin('s.template', 'template')
            ->innerJoin('s.container', 'container');

        if ($container) {
            $containerLike = sprintf('%%%s%%', $container);
            $q->andWhere('container.name LIKE :container')
                ->setParameter('container', $containerLike);
        }

        return $q
            ->getQuery()
            ->getResult();
    }
}
