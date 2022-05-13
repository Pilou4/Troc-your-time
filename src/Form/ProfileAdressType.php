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

class ProfileAdressType extends AbstractType
{
    public function __construct(private UrlGeneratorInterface $url){

    }
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'number',
                IntegerType::class,
                [
                    'label' => 'numéro',
                    'label_attr' => ['class' => 'profile__form__label'],
                    'attr' => ['class' => 'profile__form__number'],
                ]
            )
            ->add(
                'address',
                TextType::class,
                [
                    'mapped' => false,
                    'label' => 'adresse compléte',
                    'label_attr' => ['class' => 'profile__form__label'],
                    'attr' => ['class' => 'profile__form__input'],
                    'help' => 'Rue, Ville ...',
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
