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

class ProfileProposeType extends AbstractType
{
    public function __construct(private UrlGeneratorInterface $url){

    }
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
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
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Profile::class,
        ]);
    }
}
