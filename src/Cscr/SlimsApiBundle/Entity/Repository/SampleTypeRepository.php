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
            ->createQueryBuilder('t')
            ->select('t, attributes, attribute_template, template')
            ->leftJoin('t.attributes', 'attributes')
            ->leftJoin('attributes.template', 'attribute_template')
            ->leftJoin('t.template', 'template')
            ->orderBy('t.name')
            ->getQuery();

        try {
            $types = $q->getResult();
        } catch (NoResultException $e) {
            return;
        }

        return $types;
    }
}
