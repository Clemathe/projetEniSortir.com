<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\User;
use App\Entity\Ville;
use phpDocumentor\Reflection\DocBlock\Description;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => array('placeholder' => 'Le titre...'),
                'label' => 'Nom de la sortie'])
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
                'label' => 'Durée'])
            ->add('deadline', DateType::class, [
                'label' => 'Date limite d\'inscription',
                'widget' => 'single_text',
            ])
            ->add('maxNbOfRegistration', IntegerType::class, [
                'attr' => array('placeholder' => 'Le nombre maximal de particpants...'),
                'label' => 'Nombre de particpant maximum'])
            ->add('description', TextareaType::class, [
                'attr' => array('placeholder' => 'Description de la sortie'),
                'label' => 'Description'])
            ->add('campus', EntityType::class, [
                'attr' => array(
                    'readonly' => true,
                ), 'disabled' => true,
                'class' => Campus::class,
            ])
//            ->add('ville', EntityType::class, [
//                'placeholder' => 'Sélectionnez une ville...',
//                'class' => Ville::class,
//                'mapped' => false,

//                'choice_label' => function ($ville, $lieu) {
//                    return $ville->getName();
//                }
//            ])

//
//            ->add('lieu', CollectionType::class, [
//
//                'entry_type' => FindForm::class,
//
//                'allow_add' => true,
//                'allow_delete' => true,
//                'prototype' => true,
//
//
//            ])
            ->add('lieu', EntityType::class, [
                'class' => Lieu::class,

            ]);

        $builder->get('lieu')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {

                $form = $event->getForm();

                $form->getParent()->add('ville', EntityType::class, [
                    'class' => Lieu::class,
                    'mapped' => false,
                    'choice_label' => 'ville',
                    'attr' => array(
                        'readonly' => true,
                    ), 'disabled' => true,
                ]);
                $form->getParent()->add('rue', EntityType::class, [
                    'class' => Lieu::class,
                    'mapped' => false,
                    'choice_label' => 'rue',
                    'disabled' => true,
                ]);

                $form->getParent()->add('latitude', EntityType::class, [
                    'class' => Lieu::class,
                    'attr' => array('placeholder' => 'Indiqué la lattitude du lieu ...',),
                    'label' => 'Lattitude (optionel)',
                    'mapped' => false,
                    'required' => false,
                    'choice_label' => 'latitude',
                    'disabled' => true,
                ]);
                $form->getParent()->add('longitude', EntityType::class, [
                    'class' => Lieu::class,
                    'attr' => array('placeholder' => 'Indiqué la lattitude du lieu ...'),
                    'label' => 'longitude (optionel)',
                    'mapped' => false,
                    'required' => false,
                    'choice_label' => 'longitude',
                    'disabled' => true,
                ]);
            });
//        $builder->addEventListener(
//            FormEvents::POST_SET_DATA,
//            function (FormEvent $event) {
//
//                $form = $event->getForm();
//                $data = $event->getData();
//                $rue = $data->getRue();
//                $ville = $data->getVille();
//                $longitude = $data->getLongitude();
//                $latitude = $data->getLatitude();
//
//                if ($rue) {
//                    dump('post-set-data');
//                    $form->get('lieu')->setData($lieux->getVille());
//
//                    $form->add('lieu', EntityType::class, [
//                        'class' => Lieu::class,
//                        'placeholder' => 'Sélectionnez un lieu...',
//                        'choices' => $lieux->getLieu()->getVille(),
//                        'mapped' => false
//                    ]);
//                }
//            });
    }



    //            ->add('rue', EntityType::class, [
//                'attr' => array(
//                    'readonly' => true,
//                ), 'disabled' => true,
//                'mapped' => false,
//                'class' => Lieu::class,
//                'choice_label' => function ($lieu) {
//                    return $lieu->getRue();
//                }]);


//        $builder->get('ville')->addEventListener(
//            FormEvents::POST_SUBMIT,
//            function (FormEvent $event) {
//                $form = $event->getForm();
//
////                $form->getParent()->get('lieu')->setData($form->getData()->getLieux());
////
//////                $form->getParent()->get('lieu')->setData($form->getData()->getLieux());
//
//                $form->getParent()->add('lieu', EntityType::class, [
//                    'class' => Lieu::class,
//                    'placeholder' => 'Sélectionnez un lieu',
//                    'choices' => $form->getData()->getLieux(),
//                ]);
////                $form->getParent()->add('codePostal', EntityType::class, [
//////                    'attr' => array(
//////                        'readonly' => true,
//////                    ),'disabled' => true,
////                    'mapped' => false,
////                    'class' => Ville::class,
////                    'choice_label' => function ($ville) {
////                        return $ville->getCodePostal();
////                    }]);
//            }
//        );
//        $builder->addEventListener(
//            FormEvents::POST_SET_DATA,
//            function (FormEvent $event) {
//
//                $form = $event->getForm();
//                $data = $event->getData();
//                $lieux = $data->getLieu();
//
//                if ($lieux) {
//                    dump('post-set-data');
//                    $form->get('ville')->setData($lieux->getVille());
//
//                    $form->add('lieu', EntityType::class, [
//                        'class' => Lieu::class,
//                        'placeholder' => 'Sélectionnez un lieu...',
//                        'choices' => $lieux->getLieu()->getVille(),
//                        'mapped' => false
//                    ]);
//
////                    $form->add('codePostal', EntityType::class, [
////                        'attr' => array(
////                            'readonly' => true,
////                        ),'disabled' => true,
////                        'mapped' => false,
////                        'class' => Ville::class,
////                        'choices' => $lieux->getVille()->getCodePostal()
////                        ]);
////                    $form->add('codePostal', EntityType::class, [
////                        'attr' => array(
////                            'readonly' => true,
////                        ),'disabled' => true,
////                        'mapped' => false,
////                        'class' => Ville::class,
////                        'choice_label' => function ($ville) {
////                            return $ville->getCodePostal();
////                        }]);
//                } else {
//                    $form->add('lieu', EntityType::class, [
//                        'class' => Lieu::class,
//                        'placeholder' => 'Sélectionnez une ville d\'abord...',
//                        'choices' => [],
//                    ]);
//
//                }
//            }
//        );
//    }
//        /////////////////////////////////////////////////////////////////////////

//        $formModifier = function (FormInterface $form, Ville $ville = null) {
//            $lieux = null === $ville ? [] : $ville->getLieux();
//
//            $form->add('lieu', EntityType::class, [
//                'class' => Lieu::class,
//                'placeholder' => 'Veuillez choisir une ville',
//                'choices' => $lieux
//            ]);
//        };
//        $builder->addEventListener(
//            FormEvents::PRE_SET_DATA,
//            function (FormEvent $event) use ($formModifier) {
//            $data = $event->getData();
//            $formModifier($event->getForm(), $data->getLieu());
//}
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
