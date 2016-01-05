<?php

namespace Cscr\SlimsApiBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UpdateContainerType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'parent',
                'entity',
                [
                    'class' => 'Cscr\SlimsApiBundle\Entity\Container',
                    'choice_label' => 'id',
                    'by_reference' => true,
                ]
            )
            ->add('name', 'text')
            ->add(
                'research_group',
                'entity',
                [
                    'class' => 'Cscr\SlimsApiBundle\Entity\ResearchGroup',
                    'choice_label' => 'id',
                ]
            )
            ->add('comment', 'text')
            ->add('colour', 'text');
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Cscr\SlimsApiBundle\Entity\Container',
                'csrf_protection' => false,
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'update_container';
    }
}
