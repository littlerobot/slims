<?php

namespace Cscr\SlimsApiBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text')
            ->add('username', 'text')
            ->add(
                'research_group',
                'entity',
                [
                    'class' => 'Cscr\SlimsApiBundle\Entity\ResearchGroup',
                    'choice_label' => 'id',
                ]
            )
            ->add(
                'is_active',
                'checkbox',
                [
                    // Backwards compatibility with previous version of API.
                    'property_path' => 'active',
                ]
            );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Cscr\SlimsUserBundle\Entity\User',
                'csrf_protection' => false,
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'user';
    }
}
