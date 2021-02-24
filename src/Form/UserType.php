<?php

namespace App\Form;


use App\Entity\Campus;
use App\Entity\User;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;


class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom:',
                'required' => true,
                'attr' => ['placeholder' => 'dupont']
            ])
            ->add('surname', TextType::class, [
                'label' => 'Prénom:',
                'required' => true,
                'attr' => ['placeholder' => 'jean']
            ])
            ->add('username', TextType::class, [
                'label' => 'Pseudo:',
                'required' => true,
                'attr' => ['placeholder' => 'Votre pseudo']
            ])
            ->add('email', EmailType::class,[
                'attr' => [
                'placeholder' => 'Votre email']
            ])
            ->add('telephone', TelType::class,[
                'attr' => ['placeholder' => 'Votre numéro de téléphone']])

            ->add('photo', FileType::class, [
                'label' => 'Photo (PNG, JPEG)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '2048k',
                        'mimeTypes' => [
                            'image/jpg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Merci d\'uploader un format et une taille de fichier valide',

                    ])
                ]
            ])

            ->add('campus', EntityType::class,[
                'class'=> Campus::class,
                'choice_label' => 'name',
                'attr'=>['placeholder'=>'indiquez votre campus']
            ])

            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'le mot de passe est different',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options' => ['label' => 'mot de passe'],
                'second_options' => ['label' => 'confirmation du mot de passe'],
            ]);


    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
