<?php

namespace App\Form;

use App\Entity\AnnouncementSearch;
use App\Entity\SubCategory;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class AnnouncementSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('category', EntityType::class, [
            'class' => SubCategory::class,
            'placeholder' => 'catégorie',
            "choice_label" => function (SubCategory $subCategory) {
                return $subCategory->getName();
            },
            'label' => false,
            'required' => false,
        ])
        ->add('address', null, [
            'label' => false,
            'required' => false,
        ])
        ->add('distance', ChoiceType::class, [
            'label' => false,
            'placeholder' => 'distance',
            'required' => false,
            'choices' => [
                '10 km' => 10,
                '1000 km' => 1000
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
