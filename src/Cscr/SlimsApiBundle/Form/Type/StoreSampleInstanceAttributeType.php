<?php

namespace Cscr\SlimsApiBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StoreSampleInstanceAttributeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'id',
                'entity',
                [
                    'class' => 'Cscr\SlimsApiBundle\Entity\SampleInstanceTemplateStoredAttribute',
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

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => 'Cscr\SlimsApiBundle\Entity\SampleInstanceAttribute',
                'csrf_protection' => false,
            ]
        );
    }

    public function getName()
    {
        return 'slims_sample_instance_attribute';
    }
}
