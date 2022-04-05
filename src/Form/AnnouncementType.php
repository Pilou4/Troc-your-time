<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Announcement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class AnnouncementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'category',
                EntityType::class,
                [
                    "label" => "choix de la catégorie ",
                    'label_attr' => ['class' => 'announcementAdd__form__label'],
                    'attr' => ['class' => 'announcementAdd__form__input'],
                    "class" => Category::class,
                    "choice_label" => function (Category $category) {
                        return $category->getName();
                    },
                    "required" => true,
                    "multiple" => false,
                ]
            )
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
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Announcement::class,
        ]);
    }
}
