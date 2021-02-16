<?php

namespace App\Form;


use App\Entity\User;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


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
            ->add('username', TextType::class, [
                'label' => 'Prénom:',
                'required' => true,
                'attr' => ['placeholder' => 'jean']
            ])
            ->add('surname', TextType::class, [
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

            ->add('campus', TextType::class,[
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
