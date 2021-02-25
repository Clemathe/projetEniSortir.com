<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Sortie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => array('placeholder' => 'Le titre...'),
                'label' => 'Nom de la sortie'
            ])
            ->add('StartedDateTime', DateTimeType::class, [
                'label' => 'Date et heure de la sortie',
                'widget' => 'single_text',
                "minutes" => [
                    '00', '05', '10',
                    '15', '20', '25',
                    '30', '35', '40',
                    '45', '50', '55']
            ])
            ->add('duration', IntegerType::class, [
                'attr' => array('placeholder' => 'La durée en heure(s)...'),
                'label' => 'Durée'
            ])
            ->add('deadline', DateType::class, [
                'label' => 'Date limite d\'inscription',
                'widget' => 'single_text',
            ])
            ->add('maxNbOfRegistration', IntegerType::class, [
                'attr' => array('placeholder' => 'Le nombre maximal de particpants...'),
                'label' => 'Nombre de particpant maximum'
            ])
            ->add('description', TextareaType::class, [
                'attr' => array('placeholder' => 'Description de la sortie'),
                'label' => 'Description'
            ])
            ->add('campus', EntityType::class, [
                'attr' => array(
                    'readonly' => true,
                ), 'disabled' => true,
                'class' => Campus::class,
            ])
            ->add('ville', VilleType::class, [
                'mapped' => false,
            ])
            ->add('lieu', LieuType::class, [
            ]);

    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
