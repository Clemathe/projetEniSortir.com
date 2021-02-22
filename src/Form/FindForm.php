<?php


namespace App\Form;


use App\data\FindSortie;
use App\Entity\Campus;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FindForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('q', TextType::class, [
                'required' => false,
                'label' => false,

                'attr'=>['placeholder' =>'rechercher...'],

            ])
            ->add('campus', EntityType::class, [
                'label' => false,
                'required' => false,
                'class' => Campus::class,
                'expanded'=>true,
                'multiple'=>false,
            ])
//
            ->add('startDate', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'début',
                'required' => false,
            ])

            ->add('endDate', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'fin',
                'required' => false,
            ])
            ->add('createdByMe', CheckboxType::class, [
                'label' => 'mes sorties crée',
                'required' => false
            ])

            ->add('subscrided', CheckboxType::class, [
                'label' => 'mes inscriptions',
                'required' => false
            ])
            ->add('unSubscrided', CheckboxType::class, [
                'label' => 'Pas encore inscrit',
                'required' => false
            ])
        ;
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FindSortie::class,
            'method' => 'GET',
            'csrf_protection' => false
        ]);
    }
    public function getBlockPrefix()
    {
        return '';
    }

}