<?php

namespace App\Form;

use App\Entity\Region;
use App\Entity\Category;
use App\Entity\Announcement;
use App\Entity\Department;
use App\Entity\SubCategory;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormInterface;

class AnnouncementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'title',
                TextType::class,
                [
                    "label" => "titre de l'annonce",
                    'label_attr' => ['class' => 'announcementAdd__form__label'],
                    'attr' => ['class' => 'announcementAdd__form__input']
                ]
            )
            ->add(
                'description',
                TextareaType::class,
                [
                    "label" => "description de l'annonce",
                    'label_attr' => ['class' => 'announcementAdd__form__label'],
                    'attr' => ['class' => 'announcementAdd__form__textarea']
                ]
            )
            ->add(
                'region',
                EntityType::class,
                [
                    'mapped' => false,
                    'class' => Region::class,
                    'choice_label' => 'name',
                    'placeholder' => 'Région',
                    'label' => 'région'
                ]
            )
            ->add(
                'department',
                ChoiceType::class,
                [
                    'placeholder' => 'Département (Choisir une région)'
                ]
            )
            ->add(
                'category',
                EntityType::class,
                [
                    'mapped' => false,
                    'class' => Category::class,
                    'choice_label' => 'name',
                    'placeholder' => 'Catégorie',
                    'label' => 'Catégorie'
                ]
            )
            ->add(
                'subCategory',
                ChoiceType::class,
                [
                    'placeholder' => 'SousCatégorie (Choisir une catégorie)'
                ]
            )
        ;

        $formUpdateRegion = function (FormInterface $form, Region $region = null) {
            $departments = null === $region ? [] : $region->getDepartments();

            $form->add(
                'department',
                EntityType::class,
                [
                    'class' => Department::class,
                    'choices' => $departments,
                    'choice_label' => 'fullName',
                    'placeholder' => 'Département (Choisir une région)',
                    'label' => 'Département'
                ]
            );
        };

        $formUpdateCategory = function (FormInterface $form, Category $category = null) {
            $subCategories = null === $category ? [] : $category->getSubCategories();

            $form->add(
                'subCategory',
                EntityType::class,
                [
                    'class' => SubCategory::class,
                    'choices' => $subCategories,
                    'choice_label' => 'name',
                    'placeholder' => 'Sous Catégories (Choisir une catégorie)',
                    'label' => 'Sous Catégories'
                ]
            );
        };

        $builder->get('region')->addEventListener(
            FormEvents::POST_SUBMIT, function (FormEvent $event) use ($formUpdateRegion) {
                $region = $event->getForm()->getData();
                $formUpdateRegion($event->getForm()->getParent(), $region);
            }
        );

        $builder->get('category')->addEventListener(
            FormEvents::POST_SUBMIT, function (FormEvent $event) use ($formUpdateCategory) {
                $category = $event->getForm()->getData();
                $formUpdateCategory($event->getForm()->getParent(), $category);
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Announcement::class,
        ]);
    }
}
