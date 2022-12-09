<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class,[
                'label'=>'Titre du produit',
                'required'=>false,
                'attr'=>[
                    'placeholder'=>'Saisissez le titre du produit'
    ]
            ])

            ->add('category', EntityType::class,[
                'label'=>'Catégorie',
                'required'=>false,
                'class'=>Category::class,
                'choice_label'=>'title',
                'placeholder'=>'Sélectionner une catégorie'
            ])

            ->add('price', NumberType::class,[
                'label'=>'Prix du produit',
                'required'=>false,
                'attr'=>[
                'placeholder'=>'Saisissez le prix du produit'
    ]
            ])
            ->add('description', TextareaType::class,[
                'label'=>'Description du produit',
                'required'=>false,
                'attr'=>[
                    'placeholder'=>'Saisissez une description du produit'
                ]
            ])
            ->add('picture', FileType::class,[
                'label'=>'Photo',
                'required'=>false,
                'constraints'=>[
                    new File([
                        'mimeTypes'=>[
                            "image/png",
                            "image/jpg",
                            "image/jpeg",
                            "image.gif",
                            "image/jfif",
                            "image.webp"
                        ],
                        'mimeTypesMessage'=>'Format non géré'
                    ])
                ]
            ])
            ->add('enregistrer',SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
