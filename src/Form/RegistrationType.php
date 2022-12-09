<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class,[
                'label'=>'Prénom',
                'required'=>false,
                'attr'=>[
                    'placeholder'=>'Saisissez votre prénom'
                ]
            ])
            ->add('lastname', TextType::class,[
                'label'=>'Nom',
                'required'=>false,
                'attr'=>[
                    'placeholder'=>'Saisissez votre nom'
                ]
            ])
            ->add('email', EmailType::class,[
                'label'=>'Email',
                'required'=>false,
                'attr'=>[
                    'placeholder'=>'Saisissez votre email'
                ]
            ])
            ->add('password', PasswordType::class,[
                'label'=>'Mot de passe',
                'required'=>false,
                'attr'=>[
                    'placeholder'=>'Saisissez votre mot de passe'
                ]
            ])
            ->add('confirmPassword', PasswordType::class,[
                'label'=>'Confirmation du mot de passe',
                'required'=>false,
                'attr'=>[
                    'placeholder'=>'Confirmez votre mot de passe'
                ]
            ])

            ->add('tel', TextType::class,[
                'label'=>'Numéro de téléphone',
                'required'=>false,
                'attr'=>[
                    'placeholder'=>'Saisissez votre numéro de mobile'
                ]
            ])
            ->add('enregistrer', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
