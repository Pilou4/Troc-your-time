<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\SubCategory;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'name',
                TextType::class,
                [
                    "label" => "nom",
                    "label_attr" => ["class" => "admin__content__form__label"],
                    "attr" => ["class" => "admin__content__form__input"]
                ]
            )
            ->add(
                'subCategories',
                CollectionType::class, 
                [
                    "entry_type" => SubCategoryType::class,
                    "label" => "Sous catégorie",
                    'entry_options' => ['label' => false],
                    'allow_add' => true,
                    'allow_delete' => true,
                    'by_reference' => false,
                ]
            )
            ->add(
                'imageFile',
                FileType::class, [
                    'required' => false,
                    "label" => "image",
                    "label_attr" => ["class" => "admin__content__form__label"],
                    "attr" => ["class" => "admin__content__form__input"]
                    // 'mapped' => false
                ]
            )
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
