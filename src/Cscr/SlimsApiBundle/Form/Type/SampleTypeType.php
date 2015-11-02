<?php

namespace Cscr\SlimsApiBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SampleTypeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add(
                'sample_type_template',
                'entity',
                [
                    'class' => 'Cscr\SlimsApiBundle\Entity\SampleTypeTemplate',
                    'choice_label' => 'id',
                    'property_path' => 'template',
                ]
            )
            ->add(
                'attributes',
                'collection',
                [
                    'type' => new SampleTypeAttributeType(),
                    'allow_add' => true,
                    'by_reference' => false,
                ]
            );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => 'Cscr\SlimsApiBundle\Entity\SampleType',
                'csrf_protection' => false,
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'sample_type_create';
    }
}
