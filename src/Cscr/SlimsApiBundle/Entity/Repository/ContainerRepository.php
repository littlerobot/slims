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
            ->leftJoin('c.children', 'children')
            ->leftJoin('c.researchGroup', 'researchGroup')
            ->getQuery();

        try {
            $containers = $q->getResult();
        } catch (NoResultException $e) {
            return null;
        }

        return $containers;
    }

    /**
     * @param int $id
     * @return Container|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function find($id)
    {
        return $this
            ->createQueryBuilder('c')
            ->select('c, s')
            ->leftJoin('c.samples', 's')
            ->orderBy('s.position')
            ->where('c.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
