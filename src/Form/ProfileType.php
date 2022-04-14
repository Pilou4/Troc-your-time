<?php

namespace App\Form;

use App\Entity\Profile;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'imageFile',
                FileType::class, [
                    'required' => false,
                    'label' => 'image de profil'
                    // 'mapped' => false
                ]
            )
            ->add('gender', ChoiceType::class, [
                'label' => 'genre',
                'choices' => [
                    'masculin' => 'masculin',
                    'feminin' => 'feminin'
                ]
            ])
            ->add(
                'username',
                TextType::class,
                [
                    'label' => "nom d'utilisateur",
                    'label_attr' => ['class' => 'profileAdd__form__label'],
                    'attr' => ['class' => 'profileAdd__form__input']
                ]
            )
            ->add(
                'firstname',
                TextType::class,
                [
                    'label' => 'prénom',
                    'label_attr' => ['class' => 'profileAdd__form__label'],
                    'attr' => ['class' => 'profileAdd__form__input']
                ]
            )
            ->add(
                'lastname',
                TextType::class,
                [
                    'label' => 'nom',
                    'label_attr' => ['class' => 'profileAdd__form__label'],
                    'attr' => ['class' => 'profileAdd__form__input']
                ]
            )
            ->add(
                'birthday',
                BirthdayType::class,
                [
                    'label' => 'Date de naissance',
                    'label_attr' => ['class' => 'profileAdd__form__label'],
                    'attr' => ['class' => 'profileAdd__form__input'],
                    'placeholder' => [  
                        'day' => 'jour',
                        'month' => 'mois',
                        'year' => 'année',
                    ],
                    'format' => 'dd-MM-yyyy',
                    'required' => false
                ]
            )
            ->add(
                'address',
                TextType::class,
                [
                    'mapped' => false,
                    'label' => 'adresse compléte',
                    'label_attr' => ['class' => 'profileAdd__form__label'],
                    'attr' => ['class' => 'profileAdd__form__input']
                ]
            )
            ->add(
                'research',
                TextType::class,
                [
                    'label' => 'recherche',
                    'label_attr' => ['class' => 'profileAdd__form__label'],
                    'attr' => ['class' => 'profileAdd__form__input'],
                    'required' => false,
                ]
            )
            ->add(
                'propose',
                TextType::class,
                [
                    'label' => 'propose',
                    'label_attr' => ['class' => 'profileAdd__form__label'],
                    'attr' => ['class' => 'profileAdd__form__input'],
                    'required' => false,
                ]
            )
            ->add('street', HiddenType::class)
            ->add('city', HiddenType::class)
            ->add('zipcode', HiddenType::class)
            ->add('department', HiddenType::class)
            ->add('region', HiddenType::class)
            ->add('lat', HiddenType::class)
            ->add('lng', HiddenType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Profile::class,
        ]);
    }
}
