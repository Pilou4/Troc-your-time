<?php

namespace App\Form;

use App\Entity\ProfileSearch;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;

class ProfileSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('username', SearchType::class, [
            'label' => false,
            'required' => false,
            'attr' => [
                'class' => 'community__form__input',
                'placeholder' => "nom d'utilisateur",
            ],
        ])

        ->add('address', null, [
            'label' => false,
            'required' => false,
            'attr' => [
                'class' => 'community__form__input',
                'placeholder' => 'adresse',
            ],
        ])
        ->add('distance', ChoiceType::class, [
            'label' => false,
            'placeholder' => 'distance',
            'attr' => ['class' => 'community__form__input'],
            'required' => false,
            'choices' => [
                '5 km' => 5,
                '10 km' => 10,
                '20 km' => 20,
                '50 km' => 50,
                '100 km' => 100,
                '200 km' => 200,
                'toute la france' => 1200 
            ]
        ])
        ->add('lat', HiddenType::class)
        ->add('lng', HiddenType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProfileSearch::class,
            'method' => 'get',
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
