<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\SubCategory;
use App\Entity\AnnouncementSearch;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;

class AnnouncementSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('title', SearchType::class, [
            'label' => false,
            'required' => false,
            'attr' => [
                'placeholder' => "titre",
            ],
        ])
        ->add('category', HiddenType::class)
        ->add('subCategory', HiddenType::class)
        // ->add('category', EntityType::class, [
        //         'class' => Category::class,
        //         "choice_label" => function (Category $category) {
        //             return $category->getName();
        //         },
        //         'placeholder' => '-- Sélectionnez une catégorie --',
        //         'label' => false,
        //         'required' => false,
        //         'attr' => [
        //         ]
        //     ])
        // ->add('subCategory', EntityType::class, [
        //     'class' => SubCategory::class,
        //     'placeholder' => 'catégorie',
        //     "choice_label" => function (SubCategory $subCategory) {
        //         return $subCategory->getName();
        //     },
        //     'label' => false,
        //     'required' => false,
        // ])
        ->add('address', null, [
            'label' => false,
            'required' => false,
            'attr' => [
                'placeholder' => 'ville, régions, département',
            ]
        ])

        ->add('distance', ChoiceType::class, [
            'label' => false,
            'placeholder' => '-- distance --',
            'attr' => [
            ],
            'required' => false,
            'choices' => [
                '10 km' => 10,
                '20 km' => 20,
                '50 km' => 50,
                '100 km' => 100,
                '200 km' => 200,
                'toute la France' => 1200
            ]
        ])
        ->add('lat', HiddenType::class)
        ->add('lng', HiddenType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AnnouncementSearch::class,
            'method' => 'get',
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
