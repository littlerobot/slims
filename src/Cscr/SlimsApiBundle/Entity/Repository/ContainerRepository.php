<?php

namespace Cscr\SlimsApiBundle\Entity\Repository;

use Cscr\SlimsApiBundle\Entity\Container;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;

class ContainerRepository extends EntityRepository
{
    /**
     * Find all containers that do not have a parent container. I.e. they are the root container.
     *
     * @return Container[]|null
     */
    public function findRootContainers()
    {
        $q = $this
            ->createQueryBuilder('c')
            ->select('c, children, researchGroup')
            ->where('c.parent IS NULL')
            ->innerJoin('c.children', 'children')
            ->innerJoin('c.researchGroup', 'researchGroup')
            ->getQuery();

        try {
            $containers = $q->getResult();
        } catch (NoResultException $e) {
            return null;
        }

        return $containers;
    }
}
