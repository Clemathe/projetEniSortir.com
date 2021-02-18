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

            ->add('description', TextareaType::class, [
                'label' => 'Description'])

            ->add('campus', EntityType::class, [
                'class' => Campus::class,
                'choice_label' => function ($campus ) {
                    return $campus->getName();}])

            ->add('ville', EntityType::class, [
                'class' => Ville::class,
                'mapped'=>false,
                'required' => false,
            ]);


        $builder->get('ville')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event){
                $form = $event->getForm();
                $form->getParent()->add('lieu', EntityType::class, [
                    'class' => Lieu::class,
                    'placeholder' => 'Sélectionnez un lieu',
                    'choices' => $form->getData()->getLieux(),
                    ]);
            }
        );
//        $builder->get('ville')->addEventListener(
//            FormEvents::POST_SUBMIT,
//            function (FormEvent $event){
//                $form = $event->getForm();
//;                $this->addLieuField($form->getParent(), $form->getData());
//            }
//        );
//        $builder->addEventListener(
//            FormEvents::POST_SET_DATA,
//            function (FormEvent $event){
//                $data = $event->getData();
//
//                $lieux = $data->getLieu();
//                $form = $event->getForm();
//                if($lieux){
//                    $ville = $lieux->getVille();
//                    $this->addLieuField($form, $ville);
//                    $form->get('lieu')->setData($lieux);
//                }else{
//                    $this->addLieuField($form, null);
//                }
//            }
//        );
//    }
//
//        private function addLieuField(FormInterface $form, ?Ville $ville){
//        $form->add('lieu', EntityType::class, [
//            'class' => Lieu::class,
//            'placeholder' => 'Sélectionnez un lieu',
//            'choices' => $ville ? $ville->getLieux() : [],
//            ]);
    }




    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
