<?php

namespace Cscr\SlimsApiBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SampleTypeTemplateType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name')
            ->add('attributes', 'collection', [
                'type' => new SampleTypeTemplateAttributeType(),
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => true,
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Cscr\SlimsApiBundle\Entity\SampleTypeTemplate',
            'csrf_protection' => false,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'create_sample_type_template';
    }
}
