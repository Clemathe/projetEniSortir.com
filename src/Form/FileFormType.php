<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class FileFormType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * Utiliser pour uploader un fichier (template admin)
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('urlFile', FileType::class
            , [
                'label' => 'Ajouter des utilisateurs (user.csv)',
                'attr' => [
                    "aria-describedby" => "fileHelp",
                    'class' => "form-control-file"],
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '4096k',
                        'mimeTypes' => [],
                        'mimeTypesMessage' => 'Merci d\'uploader un format et une taille de fichier valide',

                    ])
                ]
            ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            File::class,
        ]);
    }
}
