<?php

namespace Cscr\SlimsApiBundle\Form\Type;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SampleInstanceRemovedTemplateAttributeType extends SampleTypeTemplateAttributeType
{
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Cscr\SlimsApiBundle\Entity\SampleInstanceRemovedAttribute',
            'csrf_protection' => false,
        ]);
    }

    public function getName()
    {
        return 'sample_instance_removed_attribute';
    }
}
