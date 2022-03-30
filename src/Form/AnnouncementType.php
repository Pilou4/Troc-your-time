<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Announcement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnnouncementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('description')
            ->add(
                'category',
                EntityType::class,
                [
                    "class" => Category::class,
                    "choice_label" => function (Category $category) {
                        return $category->getName();
                    },
                    "required" => true,
                    "multiple" => false,
                    "attr" => [
                        "class" => "compact-select-list"
                    ]
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
