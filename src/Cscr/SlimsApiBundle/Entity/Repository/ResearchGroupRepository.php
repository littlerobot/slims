<?php

namespace Cscr\SlimsApiBundle\Entity\Repository;

use Cscr\SlimsApiBundle\Entity\ResearchGroup;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;

class ResearchGroupRepository extends EntityRepository
{
    /**
     * Find all research groups and order them alphabetically.
     *
     * @return ResearchGroup[]|null
     */
    public function findAll()
    {
        $q = $this
            ->createQueryBuilder('r')
            ->orderBy('r.name')
            ->getQuery();

        try {
            $groups = $q->getResult();
        } catch (NoResultException $e) {
            return;
        }

        return $groups;
    }
}
