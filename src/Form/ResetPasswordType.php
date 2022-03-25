<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class ResetPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'password', 
            RepeatedType::class, 
            [
                'type' => PasswordType::class,
                'invalid_message' => 'Les deux mot de passe ne sont pas identiques',
                'first_options'  => [
                    'label' => 'Mot de passe',
                    'label_attr' => ['class' => 'form-label'],
                    'attr' => ['class' => 'form-input'],
                ],
                'second_options' => [
                    'label' => 'Repeter le mot de passe',
                    'label_attr' => ['class' => 'form-label'],
                    'attr' => ['class' => 'form-input'],
                ],
            ]
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
