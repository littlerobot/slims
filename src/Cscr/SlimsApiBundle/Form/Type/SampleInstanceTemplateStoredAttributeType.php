<?php

namespace Cscr\SlimsApiBundle\Form\Type;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SampleInstanceTemplateStoredAttributeType extends SampleTypeTemplateAttributeType
{
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Cscr\SlimsApiBundle\Entity\SampleInstanceTemplateStoredAttribute',
            'csrf_protection' => false,
        ]);
    }

    public function getName()
    {
        return 'sample_instance_stored_attribute';
    }
}
