<?php

namespace Cscr\SlimsApiBundle\Form\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;

class SampleInstanceTemplateRemovedAttributeType extends SampleTypeTemplateAttributeType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => 'Cscr\SlimsApiBundle\Entity\SampleInstanceTemplateRemovedAttribute',
                'csrf_protection' => false,
            ]
        );
    }

    public function getName()
    {
        return 'sample_instance_removed_attribute';
    }
}
