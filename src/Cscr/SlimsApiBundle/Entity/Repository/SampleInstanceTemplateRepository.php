<?php

namespace Cscr\SlimsApiBundle\Entity\Repository;

use Cscr\SlimsApiBundle\Entity\SampleInstanceTemplate;
use Doctrine\ORM\EntityRepository;

class SampleInstanceTemplateRepository extends EntityRepository
{
    /**
     * @return SampleInstanceTemplate[]
     */
    public function findAll()
    {
        return $this->createQueryBuilder('templates')
            ->select('templates, stored, removed')
            ->innerJoin('templates.storedAttributes', 'stored')
            ->innerJoin('templates.removedAttributes', 'removed')
            ->orderBy('templates.name')
            ->getQuery()
            ->getResult();
    }
}
