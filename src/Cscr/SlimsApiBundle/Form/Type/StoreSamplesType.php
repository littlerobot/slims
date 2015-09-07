<?php

namespace Cscr\SlimsApiBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StoreSamplesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'samples',
                'collection',
                [
                    'type' => new StoreSampleType(),
                    'allow_add' => true,
                    'allow_delete' => true,
                    'by_reference' => false,
                    'cascade_validation' => true,
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => 'Cscr\SlimsApiBundle\Entity\Container',
                'csrf_protection' => false,
            ]
        );
    }

    public function getName()
    {
        return 'slims_store_samples';
    }
}
