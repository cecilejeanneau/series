<?php

namespace App\Form;

use App\Entity\Serie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class SerieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('overview', TextareaType::class, ['required' => false, 'label' => 'Synopsis'])
            ->add('status', ChoiceType::class, ['choices' => [
                'Canceled' => 'canceled',
                'Returning' => 'returning',
                'Ended' => 'ended',
                ],
                'multiple' => false,
                'expanded' => false
            ])
            ->add('vote', IntegerType::class, [
//                'required' => false,
//                'label' => 'Vote',
//                'scale' => 1,
//                'html5' => true,
                'attr' => [
                    'min' => 0.0,
                    'max' => 10.0,
                    'setp' => 1
                ]
            ])
            ->add('popularity', IntegerType::class, [
//                'required' => false,
//                'label' => 'Popularity',
//                'scale' => 2,
                'attr' => [
                    'min' => 0.00,
                    'max' => 9999.99,
                    'setp' => 0.01
                ]
            ])
            ->add('genres', ChoiceType::class, [
                'choices' => [
                    'Western' => 'western',
                    'SF' => 'SF',
                    'Comedy' => 'Comedy',
                    'Polar' => 'polar',
                ],
                'multiple' => true,
                'mapped' => false,
                'expanded' => true,
            ])
            ->add('firstAirDate', null, [
                'widget' => 'single_text',
            ])
            ->add('lastAirDate', null, [
                'widget' => 'single_text',
            ])
            ->add('backdrop', FileType::class, [
                'mapped' => false,
                'constraints' => [
                    new Image(
                        [
                            'maxSize' => '5M',
                            'maxSizeMessage' => 'The file is too big ({{ file }})'

                        ]
                    )
                ]
            ])
            ->add('poster')
            ->add('tmdbId')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Serie::class,
            'required' => false,
//            'csrf_protection' => false
        ]);
    }
}
