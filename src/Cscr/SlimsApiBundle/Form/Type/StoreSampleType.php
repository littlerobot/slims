<?php

namespace Cscr\SlimsApiBundle\Form\Type;

use Cscr\SlimsApiBundle\Entity\Sample;
use Cscr\SlimsApiBundle\Entity\SampleInstanceAttribute;
use Cscr\SlimsApiBundle\ValueObject\Colour;
use Cscr\SlimsApiBundle\ValueObject\SamplePosition;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StoreSampleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('colour', 'text', [
                'mapped' => false,
            ])
            ->add('row', 'integer')
            ->add('column', 'integer')
            ->add(
                'type',
                'entity',
                [
                    'class' => 'Cscr\SlimsApiBundle\Entity\SampleType',
                    'choice_label' => 'id',
                ]
            )
            ->add(
                'template',
                'entity',
                [
                    'class' => 'Cscr\SlimsApiBundle\Entity\SampleInstanceTemplate',
                    'choice_label' => 'id',
                ]
            )
            ->add(
                'attributes',
                'collection',
                [
                    'type' => new StoreSampleInstanceAttributeType(),
                    'allow_add' => true,
                    'by_reference' => false,
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => 'Cscr\SlimsApiBundle\Entity\Sample',
                'csrf_protection' => false,
                'empty_data' => function (FormInterface $form) {
                    $hexString = $form->get('colour')->getData();
                    $colour = $hexString ? Colour::fromHex($hexString) : null;

                    $position = SamplePosition::fromRowAndColumn(
                        $form->get('row')->getData(),
                        $form->get('column')->getData()
                    );

                    $sample = new Sample();
                    $sample->setColour($colour)
                           ->setPosition($position)
                           ->setType($form->get('type')->getData())
                           ->setTemplate($form->get('template')->getData());

                    /** @var SampleInstanceAttribute $attribute */
                    foreach ($form->get('attributes')->getData() as $attribute) {
                        $sample->addAttribute($attribute);
                    }

                    return $sample;
                }
            ]
        );
    }

    public function getName()
    {
        return 'slims_store_sample';
    }
}
