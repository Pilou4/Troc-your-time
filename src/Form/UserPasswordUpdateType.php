<?php
namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;

class UserPasswordUpdateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {// je crée un champs du formulaire qui devra contenir le mot de passe actuel de l'utilisateur
        // ce champs ne vient pas mofifier l'objet manipulé par le formulaire (User) car l'option mapped=false
        // ce champs ne correspond a aucune propriété de l'objet User
        // pour ajouter une contrainte de validation on doit donc le faire directement dans le formulaire
        // pour cela on rajoute l'option "constraints" dans laquelle on ajoute toute les contraintes necessaires
        // la contrainte UserPassword permet de verifier avec le composant Security de symfony que le contenu de ce champs correspond au mot de passe de 'lutilisateur actuel 
        $builder->add(
            'oldPassword',
            PasswordType::class,
            [
                "label" => "Mot de passe actuel",
                'label_attr' => ['class' => 'form-label'],
                'attr' => ['class' => 'form-input'],
                "mapped" => false,
                "constraints" => [
                    new UserPassword()
                ]
            ]
        );

        // ce deuxieme champs est non mappé lui aussi donc il ne va pas sremplir une propriété de mon objet User
        // d'ailleur ca ne servirait a rien car le mot de passe doit d'abord être encodé avant de replacer celui de l'utilisateur
        $builder->add(
            'newPassword', 
            RepeatedType::class, 
            [
                "mapped" => false,
                'type' => PasswordType::class,
                'invalid_message' => 'Les deux mot de passe ne sont pas identiques',
                'first_options'  => [
                    'label' => 'Nouveau mot de passe',
                    'label_attr' => ['class' => 'form-label'],
                    'attr' => ['class' => 'form-input'],         
                ],
                'second_options' => [
                    'label' => 'Repeter le nouveau mot de passe',
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