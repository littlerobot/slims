<?php

namespace Cscr\SlimsApiBundle\Form\Type;

use Cscr\SlimsApiBundle\Entity\Container;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CreateContainerType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text')
            ->add('parent', 'integer')
            ->add('parent', 'entity', [
                'class' => 'Cscr\SlimsApiBundle\Entity\Container',
                'property' => 'id',
            ])
            ->add('research_group', 'entity', [
                'class' => 'Cscr\SlimsApiBundle\Entity\ResearchGroup',
                'property' => 'id',
            ])
            ->add('rows', 'integer')
            ->add('columns', 'integer')
            ->add('stores', 'choice', [
                'choices' => [
                    Container::STORES_CONTAINERS => 'Containers',
                    Container::STORES_SAMPLES => 'Samples',
                ]
            ])
            ->add('comment', 'text')
            ->add('colour', 'text')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'      => 'Cscr\SlimsApiBundle\Entity\Container',
            'csrf_protection' => false,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'create_container';
    }
}
