<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'email',
                EmailType::class,
                [
                    "label" => "Adresse email",
                    'label_attr' => ['class' => 'form__label'],
                    'attr' => ['class' => 'form__input'],
                ]                
            )
            ->add(
                'password', 
                RepeatedType::class, 
                [
                    'type' => PasswordType::class,
                    'invalid_message' => 'Les deux mot de passe ne sont pas identiques',
                    'first_options'  => [
                        'label' => 'Mot de passe',
                        'label_attr' => ['class' => 'form__label'],
                        'attr' => ['class' => 'form__input'],
                    ],
                    'second_options' => [
                        'label' => 'Repeter le mot de passe',
                        'label_attr' => ['class' => 'form__label'],
                        'attr' => ['class' => 'form__input'],
                    ],
                ]
            )
            // ->add('plainPassword', PasswordType::class, [
            //     // instead of being set onto the object directly,
            //     // this is read and encoded in the controller
            //     'mapped' => false,
            //     'attr' => ['autocomplete' => 'new-password'],
            //     'constraints' => [
            //         new NotBlank([
            //             'message' => 'Please enter a password',
            //         ]),
            //         new Length([
            //             'min' => 6,
            //             'minMessage' => 'Your password should be at least {{ limit }} characters',
            //             // max length allowed by Symfony for security reasons
            //             'max' => 4096,
            //         ]),
            //     ],
            // ])
            ->add('agreeTerms', CheckboxType::class, [
                'label_attr' => ['class' => 'register__form__agree'],
                'mapped' => false,
                'label' => "En soumettant ce formulaire, j'accepte 
                que mes données personnelles soient utilisées pour 
                me recontacter. Aucun autre traitement ne sera effectué 
                avec mes informations.",
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
