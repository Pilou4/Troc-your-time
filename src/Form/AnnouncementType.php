<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\SubCategory;
use App\Entity\Announcement;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class AnnouncementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'category',
                EntityType::class,
                [
                    'mapped' => false,
                    'required'    => false,
                    'class' => Category::class,
                    'choice_label' => 'name',
                    'placeholder' => 'Catégorie',
                    'label' => 'Catégorie'
                ]
            )
            // ->add(
            //     'subCategory',
            //     ChoiceType::class,
            //     [
            //         'placeholder' => '(Choisir une catégorie)',
            //         'label' => 'Sous Catégories',
            //     ]
            // )
            ->add(
                'title',
                TextType::class,
                [
                    "label" => "titre de l'annonce",
                    'label_attr' => ['class' => 'announcementAdd__form__label'],
                    'attr' => ['class' => 'announcementAdd__form__input'],
                ]
            )
            ->add(
                'description',
                TextareaType::class,
                [
                    "label" => "description de l'annonce",
                    'label_attr' => ['class' => 'announcementAdd__form__label'],
                    'attr' => ['class' => 'announcementAdd__form__textarea'],
                ]
            )
            ->add(
                'propose',
                TextareaType::class,
                [
                    "label" => "propose en échange",
                    'label_attr' => ['class' => 'announcementAdd__form__label'],
                    'attr' => ['class' => 'announcementAdd__form__textarea'],
                ]
            )
            ->add(
                'address',
                TextType::class,
                [
                    'mapped' => false,
                    'label' => 'adresse',
                    'label_attr' => ['class' => 'profileAdd__form__label'],
                    'attr' => ['class' => 'profileAdd__form__input'],
                ]
            )
            ->add('pictureFiles', CollectionType::class, 
                [
                    "label" => "Images",
                    "entry_type" => FileType::class,
                    'entry_options' => [
                        'label' => false,
                        'attr' => [
                            'class' => "sub-form"
                        ]
                    ],
                    'allow_add' => true,
                    'by_reference' => false,
                ]
            )
            ->add('city', HiddenType::class)
            ->add('zipcode', HiddenType::class)
            ->add('department', HiddenType::class)
            ->add('region', HiddenType::class)
            ->add('lat', HiddenType::class)
            ->add('lng', HiddenType::class)
        ;
        $builder->get("category")->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                $form = $event->getForm();
                $this->addSubCategoryField($form->getParent(), $form->getData());
                // $form->getParent()->add(
                //     'subCategory',
                //     EntityType::class, [
                //         'class' => SubCategory::class,
                //         'placeholder' => 'Sélectionnez une sous catégorie',
                //         'choices' => $form->getData()->getSubCategories()
                //     ]
                // );
            }
        );
        
        $builder->addEventListener(
            FormEvents::POST_SET_DATA,
            function(FormEvent $event) {
                $data = $event->getData();
                // dd($data);
                // $address = $event->getAdr();
                // dd($data);
                /** @var $subCategory SubCategory */
                $subCategory = $data->getSubCategory();
                $form = $event->getForm();
                if ($subCategory) {
                    $category = $subCategory->getCategory();
                    // dd($category);
                    $this->addSubCategoryField($form, $category);
                    $form->get('subCategory')->setData($subCategory);
                    $form->get('category')->setData($category);
                } else {
                    $this->addSubCategoryField($form, null);
                }
            }
        );
    }

    /**
     * Rajoute un champs departement au formulaire
     * @param FormInterface $form
     * @param Category  $rcategory
     */
    private function addSubCategoryField(FormInterface $form, ?Category $category)
    {
            // dd($form);
        $form->add(
                'subCategory',
                EntityType::class, [
                    'class' => SubCategory::class,
                    'label' => 'sous-catégorie',
                    'mapped'          => true,
                    'required'        => false,
                    'auto_initialize' => false,
                    'placeholder' => $category ? 'Sélectionnez une sous catégorie' : 'selectionnez une catégorie',
                    'choices' => $category ? $category->getSubCategories() : []
                ]
            );
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Announcement::class,
        ]);
    }
}
