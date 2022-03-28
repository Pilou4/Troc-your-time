<?php

namespace App\Form;

use App\Entity\Profile;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
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
                'adress',
                TextType::class,
                [
                    'label' => 'adresse',
                    'label_attr' => ['class' => 'profileAdd__form__label'],
                    'attr' => ['class' => 'profileAdd__form__input']
                ]
            )
            ->add(
                'zipcode',
                TextType::class,
                [
                    'label' => 'code postal',
                    'label_attr' => ['class' => 'profileAdd__form__label'],
                    'attr' => ['class' => 'profileAdd__form__input']
                ]
            )
            ->add(
                'city',
                TextType::class,
                [
                    'label' => 'ville',
                    'label_attr' => ['class' => 'profileAdd__form__label'],
                    'attr' => ['class' => 'profileAdd__form__input']
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
