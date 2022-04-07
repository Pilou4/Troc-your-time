<?php

namespace App\Form;

use App\Entity\Department;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DepartmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'number',
                IntegerType::class,
                [
                    "label" => "numéro",
                    "label_attr" => ["class" => "admin__content__form__label"],
                    "attr" => ["class" => "admin__content__form__int"]
                ]
            )
            ->add(
                'name',
                TextType::class,
                [
                    "label" => "nom",
                    "label_attr" => ["class" => "admin__content__form__label"],
                    "attr" => ["class" => "admin__content__form__input"]
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Department::class,
        ]);
    }
}
