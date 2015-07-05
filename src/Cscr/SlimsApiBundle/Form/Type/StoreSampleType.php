<?php

namespace Cscr\SlimsApiBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class StoreSampleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('colour')
            ->add('row', 'integer')
            ->add('column', 'integer')
            ->add('type', 'entity', [
                'class' => 'Cscr\SlimsApiBundle\Entity\SampleType',
                'property' => 'id',
            ])
            ->add('template', 'entity', [
                'class' => 'Cscr\SlimsApiBundle\Entity\SampleInstanceTemplate',
                'property' => 'id',
            ])
            ->add('attributes', 'collection', [
                'type' => new StoreSampleInstanceAttributeType(),
                'allow_add' => true,
                'by_reference' => false,
            ])
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Cscr\SlimsApiBundle\Entity\Sample',
            'csrf_protection' => false,
        ]);
    }

    public function getName()
    {
        return 'slims_store_sample';
    }
}
