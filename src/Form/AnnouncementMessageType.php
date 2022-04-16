<?php

namespace App\Form;

use App\Entity\Message;
use App\Entity\Profile;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AnnouncementMessageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add(
            //     'title',
            //     TextType::class,
            //     [
            //         'label' => 'Sujet',
            //         "attr" => ["class" => "send__form__input"]
            //     ]
            // )
            ->add(
                'message',
                TextareaType::class,
                [
                    "attr" => [
                        "class" => "send__form__input"
                    ]
                ]
            )
            // ->add(
            //     'recipient',
            //     EntityType::class,
            //     [
            //         "class" => Profile::class,
            //         "choice_label" => "username",
            //         "attr" => [
            //             "class" => "send__form__input"
            //         ]
            //     ]
            // )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Message::class,
        ]);
    }
}
