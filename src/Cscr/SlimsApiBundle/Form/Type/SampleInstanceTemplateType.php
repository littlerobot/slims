<?php

namespace Cscr\SlimsApiBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SampleInstanceTemplateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('store', 'collection', [
                'type' => new SampleInstanceTemplateStoredAttributeType(),
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'property_path' => 'storedAttributes',
            ])
            ->add('remove', 'collection', [
                'type' => new SampleInstanceTemplateRemovedAttributeType(),
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'property_path' => 'removedAttributes',
            ])
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Cscr\SlimsApiBundle\Entity\SampleInstanceTemplate',
            'csrf_protection' => false,
        ]);
    }

    public function getName()
    {
        return 'sample_instance_template';
    }
}
