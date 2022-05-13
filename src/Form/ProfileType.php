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

class ProfileType extends AbstractType
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
                'imageFile',
                FileType::class, [
                    'required' => false,
                    'label' => 'Image de profil',
                    'label_attr' => ['class' => 'profile__form__label'],
                    'attr' => [
                        'class' => 'profile__form__file'
                    ]
                ]
            )
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

            ->add(
                'propose',
                SearchableEntityType::class,
                [
                    'class' => SubCategory::class,
                    'search' => $this->url->generate('api_sub_category_search'),
                    // 'choice_label' => 'name',
                    // 'required' => false
                    'label_property' => 'name',
                    // 'required' => false
                    'help' => "Vous pouvez sélectionner rien pour le moment",
                ]
            )
            ->add(
                'research',
                SearchableEntityType::class,
                [
                    'label' => 'Recherche',
                    'class' => SubCategory::class,
                    'search' => $this->url->generate('api_sub_category_search'),
                    // 'choice_label' => 'name',
                    // 'required' => false
                    'label_property' => 'name',
                    // 'required' => false
                    'help' => "Vous pouvez sélectionner rien pour le moment",
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
