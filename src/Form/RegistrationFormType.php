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
                    "label" => false,
                    'attr' => [
                        'class' => 'form__input',
                        'placeholder' => 'Adresse email',
                    ],
                ]                
            )
            ->add(
                'password', 
                RepeatedType::class, 
                [
                    'type' => PasswordType::class,
                    'invalid_message' => 'Les deux mot de passe ne sont pas identiques',
                    'first_options'  => [
                        'label' => false,
                        'attr' => [
                            'class' => 'form__input',
                            'placeholder' => 'Mot de passe'
                        ],
                    ],
                    'second_options' => [
                        'label' => false,
                        'attr' => [
                            'class' => 'form__input',
                            'placeholder' => 'Répeter le mot de passe'
                        ],
                    ],
                ]
            )
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
