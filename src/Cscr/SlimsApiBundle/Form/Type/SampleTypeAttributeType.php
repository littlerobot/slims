<?php

namespace Cscr\SlimsApiBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SampleTypeAttributeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $config)
    {
        $builder
            ->add(
                'id',
                'entity',
                [
                    'class' => 'Cscr\SlimsApiBundle\Entity\SampleTypeTemplateAttribute',
                    'choice_label' => 'id',
                    'property_path' => 'template',
                ]
            )
            ->add('value')
            ->add(
                'mime_type',
                'text',
                [
                    'property_path' => 'mimeType',
                ]
            )
            ->add('filename');
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => 'Cscr\SlimsApiBundle\Entity\SampleTypeAttribute',
                'cscr_protection' => false,
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'sample_type_attribute_create';
    }
}
