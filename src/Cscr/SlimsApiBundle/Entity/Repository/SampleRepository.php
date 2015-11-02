<?php

namespace Cscr\SlimsApiBundle\Entity\Repository;

use Cscr\SlimsApiBundle\Entity\Sample;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToStringTransformer;

class SampleRepository extends EntityRepository
{
    /**
     * @param null|string $name
     * @param null|int    $passageNumber
     * @param null|string $user
     * @param null|string $container
     * @param null|string $storedFrom
     * @param null|string $storedTo
     *
     * @return Sample[]
     */
    public function filter(
        $name = null,
        $passageNumber = null,
        $user = null,
        $container = null,
        $storedFrom = null,
        $storedTo = null
    ) {
        $q = $this->createQueryBuilder('sample')
                  ->select(
                      'sample, container, sample_instance_template, sample_type, sample_type_template, sample_attributes'
                  )
                  ->innerJoin('sample.container', 'container')
                  ->innerJoin('sample.template', 'sample_instance_template')
                  ->innerJoin('sample.type', 'sample_type')
                  ->innerJoin('sample_type.template', 'sample_type_template')
                  ->innerJoin('sample.attributes', 'sample_attributes');

        if ($name) {
            $nameLike = $this->getLikePattern($name);
            $q
                ->innerJoin('sample.attributes', 'instance_attributes_name')
                ->innerJoin('instance_attributes_name.template', 'instance_template_attribute_name')
                ->andWhere("instance_template_attribute_name.label = 'Sample/cell line name'")
                ->andWhere('instance_attributes_name.value LIKE :name')
                ->setParameter('name', $nameLike);
        }

        if ($passageNumber) {
            $q
                ->innerJoin('sample.attributes', 'instance_attributes_passage_number')
                ->innerJoin('instance_attributes_passage_number.template', 'instance_template_passage_number')
                ->andWhere("instance_template_passage_number.label = 'Passage number'")
                ->andWhere('instance_attributes_passage_number.value = :passage_number')
                ->setParameter('passage_number', $passageNumber);
        }

        if ($user) {
            $userLike = $this->getLikePattern($user);
            $q
                ->innerJoin('sample.attributes', 'instance_attributes_user')
                ->innerJoin('instance_attributes_user.template', 'instance_template_attribute_user')
                ->andWhere("instance_template_attribute_user.label = 'User Name'")
                ->andWhere('instance_attributes_user.value LIKE :user')
                ->setParameter('user', $userLike);
        }

        if ($storedFrom && $storedTo) {
            $transformer = new DateTimeToStringTransformer(null, null, 'd/m/Y');
            $storedFromDate = $transformer->reverseTransform($storedFrom)->format('Y-m-d');
            $storedToDate = $transformer->reverseTransform($storedTo)->format('Y-m-d');

            $q
                ->innerJoin('sample.attributes', 'instance_attributes_stored')
                ->innerJoin('instance_attributes_stored.template', 'instance_template_attribute_stored')
                ->andWhere("instance_template_attribute_stored.label = 'Date frozen'")
                ->andWhere('instance_attributes_stored.value BETWEEN :storedFrom AND :storedTo')
                ->setParameter('storedFrom', $storedFromDate)
                ->setParameter('storedTo', $storedToDate);
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
     * @param string $string
     *
     * @return string
     */
    private function getLikePattern($string)
    {
        return sprintf('%%%s%%', $string);
    }
}
