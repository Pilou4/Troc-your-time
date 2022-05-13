<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Profile;
// use App\Entity\SubCategory;
use App\Entity\SubCategory;
use App\Form\SearchableEntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ProfileCivilityType extends AbstractType
{
    public function __construct(private UrlGeneratorInterface $url){

    }
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('gender', ChoiceType::class, [
            'label' => 'Genre',
            'label_attr' => ['class' => 'profile__form__label'],
            'attr' => [
                'class' => 'profile__form__choice'
            ],
            'choices' => [
                'feminin' => 'feminin',
                'masculin' => 'masculin',
            ],
            'help' => '* champ requis'
        ])
        ->add(
            'username',
            TextType::class,
            [
                'label' => "Nom d'utilisateur",
                'label_attr' => ['class' => 'profile__form__label'],
                'attr' => [
                    'placeholder' => "Nom d'utilisateur",
                    'class' => 'profile__form__input'
                ],
                'label_attr' => ['class' => 'profile__form__label'],
            ]
        )
        ->add(
            'firstname',
            TextType::class,
            [
                'label' => "Prénom",
                'label_attr' => ['class' => 'profile__form__label'],
                'attr' => [
                    'placeholder' => 'Prénom',
                    'class' => 'profile__form__input'
                ]
            ]
        )
        ->add(
            'lastname',
            TextType::class,
            [
                'label' => "Nom",
                'label_attr' => ['class' => 'profile__form__label'],
                'attr' => [
                    'placeholder' => 'Nom',
                    'class' => 'profile__form__input'
                ]
            ]
        )
        ->add(
            'birthday',
            BirthdayType::class,
            [
                'label' => 'Date de naissance',
                'label_attr' => ['class' => 'profile__form__label'],
                'attr' => ['class' => 'profile__form__date'],
                'placeholder' => [  
                    'day' => 'jour',
                    'month' => 'mois',
                    'year' => 'année',
                ],
                'format' => 'dd MM yyyy',
                'required' => false
            ]
        )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Profile::class,
        ]);
    }
}
