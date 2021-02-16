<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Ville;
use phpDocumentor\Reflection\DocBlock\Description;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
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
                'label' => 'Nom de la sortie'])
            ->add('StartedDateTime', DateTimeType::class, [
                'label' => 'Date et heure de la sortie',
                "minutes" => [
                    '00','05','10',
                    '15','20','25',
                    '30','35','40',
                    '45','50','55']
               ])
            ->add('duration',IntegerType::class, [
                'label' => 'Durée'] )
            ->add('deadline',DateType::class, [
                'label' => 'Date limite d\'inscription'])
            ->add('maxNbOfRegistration', IntegerType::class, [
                'label' => 'Nombre de particpant maximum'])
            ->add('Description', TextareaType::class, ['label' => 'Description'])
            ->add('ville', EntityType::class, [
                'class' => Ville::class,
                'mapped'=>false
            ])
            ->add('campus',textType::class, ['attr' => ['value' => 'zaza']])
            ->add('lieu', EntityType::class, [
                'class' => Lieu::class
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
