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
        $storedFrom = null,
        $storedTo = null
    ) {
        $q = $this->createQueryBuilder('sample')
            ->select('sample')
            ->innerJoin('sample.container', 'container')
            ->innerJoin('sample.template', 'sample_instance_template')
            ->innerJoin('sample.type', 'sample_type')
            ->innerJoin('sample_type.template', 'sample_type_template')
            ->innerJoin('sample_type_template.attributes', 'sample_type_template_attributes')
            ->innerJoin('sample_type.attributes', 'sample_type_attributes')
        ;

        if ($name) {
            $nameLike = $this->getLikePattern($name);
            $q
                ->innerJoin('sample.attributes', 'instance_attributes')
                ->innerJoin('instance_attributes.template', 'instance_template_attribute')
                ->andWhere("instance_template_attribute.label = 'Name'")
                ->andWhere('instance_attributes.value LIKE :name')
                ->setParameter('name', $nameLike)
            ;
        }

        if ($type) {
            $typeLike = $this->getLikePattern($type);
            $q
                ->innerJoin('sample_type_template.attributes', 'sample_type_template_attributes')
                ->innerJoin('sample_type.attributes', 'sample_type_attributes')
                ->andWhere("sample_type_template_attributes.label = 'Type'")
                ->andWhere('sample_type_attributes.value LIKE :type')
                ->setParameter('type', $typeLike)
            ;
        }

        if ($user) {
            $userLike = $this->getLikePattern($user);
            $q
                ->innerJoin('sample.attributes', 'instance_attributes_user')
                ->innerJoin('instance_attributes_user.template', 'instance_template_attribute_user')
                ->andWhere("instance_template_attribute_user.label = 'Staff'")
                ->andWhere('instance_attributes_user.value LIKE :user')
                ->setParameter('user', $userLike);
        }

        if ($storedFrom && $storedTo) {
            $q
                ->innerJoin('sample.attributes', 'instance_attributes_stored')
                ->innerJoin('instance_attributes_stored.template', 'instance_template_attribute_stored')
                ->andWhere("instance_template_attribute_stored.label = 'Stored'")
                ->andWhere('instance_attributes_stored.value BETWEEN :storedFrom AND :storedTo')
                ->setParameter('storedFrom', $storedFrom)
                ->setParameter('storedTo', $storedTo);
        }

        if ($container) {
            $containerLike = $this->getLikePattern($container);
            $q
                ->andWhere('container.name LIKE :container')
                ->setParameter('container', $containerLike);
        }

        return $q
            ->getQuery()
            ->getResult();
    }

    /**
     * @param $string
     * @return string
     */
    private function getLikePattern($string)
    {
        return sprintf('%%%s%%', $string);
    }
}
