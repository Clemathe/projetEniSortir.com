<?php

namespace App\Form;

use App\Entity\Lieu;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LieuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class,
                ['label' => 'Nom',
                    'attr' => [
                        'placeholder' => 'Le nom du lieu...']
                ])
            ->add('rue', TextType::class,[
                'attr' => [
                    'placeholder' => 'La rue du lieu...']
                ])
            ->add('latitude', TextType::class,[
                'label' => 'Latitude (optionnel)',
                'attr' => [
                    'placeholder' => '47.6193757...']
            ])
            ->add('longitude', TextType::class,[
                'label' => 'Longitude (optionnel)',
                'attr' => [
                    'placeholder' => '6.1529374...']
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Lieu::class,
        ]);
    }
}
