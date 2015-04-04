<?php

namespace Cscr\SlimsApiBundle\Entity\Repository;

use Cscr\SlimsApiBundle\Entity\SampleType;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;

class SampleTypeRepository extends EntityRepository
{
    /**
     * Find all {@see SampleType}s and order them alphabetically.
     *
     * @return SampleType[]|null
     */
    public function findAll()
    {
        $q = $this
            ->createQueryBuilder('s')
            ->select('s, attributes, attribute_template, template')
            ->innerJoin('s.attributes', 'attributes')
            ->innerJoin('attributes.template', 'attribute_template')
            ->innerJoin('s.template', 'template')
            ->orderBy('s.name')
            ->getQuery();

        try {
            $types = $q->getResult();
        } catch (NoResultException $e) {
            return null;
        }

        return $types;
    }
}
