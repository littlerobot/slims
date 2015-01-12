<?php

namespace Cscr\SlimsApiBundle\Form\Type;

use Cscr\SlimsApiBundle\Entity\SampleTypeAttribute;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SampleTypeAttributeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('order', 'integer')
            ->add('label')
            ->add('type', 'choice', [
                'choices' => $this->getChoices(),
            ])
            ->add('options', 'collection', [
                'type' => 'text',
                'allow_add' => true,
            ])
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Cscr\SlimsApiBundle\Entity\SampleTypeAttribute',
            'csrf_protection' => false,

        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'create_sample_type_attribute';
    }

    private function getChoices()
    {
        return [
            SampleTypeAttribute::TYPE_BRIEF_TEXT => 'Brief text',
            SampleTypeAttribute::TYPE_LONG_TEXT => 'Long text',
            SampleTypeAttribute::TYPE_OPTION => 'Option',
            SampleTypeAttribute::TYPE_DOCUMENT => 'Document',
            SampleTypeAttribute::TYPE_DATE => 'Date',
            SampleTypeAttribute::TYPE_COLOUR => 'Colour',
            SampleTypeAttribute::TYPE_USER => 'User',
        ];
    }
}
