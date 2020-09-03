<?php

namespace App\Form;

use App\Entity\Files;
use App\Entity\Folders;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class FilesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // ->add('createdAt')
            // ->add('updatedAt')
            // ->add('hide')
            ->add('name',TextType::class,[
                'label' => 'Nom *',
                'attr' => [
                    'placeholder' => 'Nom du fichier'
                ]
            ])
            ->add('filename', FileType::class, [
                'label' =>  'Fichier *',

                'attr' => [
                    'placeholder' => 'Choisissez un fichier'
                ],

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '5024k',
                        'mimeTypes' => [
                            'application/zip',
                            'application/x-rar-compressed',
                            'application/octet-stream',
                            'application/x-rar',
                            'application/pdf',
                            'application/x-pdf',
                            'application/msword',
                            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                            'application/vnd.ms-excel',
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                        ],
                        'mimeTypesMessage' => 'Please upload a valid document',
                    ])
                ],
            ])
            ->add('folder',EntityType::class,[
                'placeholder' => '--- Ajouter dans un dossier ---',
                'class' => Folders::class,
                'label' => 'Dossier',
                'choice_label' => 'name',
                'required' => false
            ])
            // ->add('size')
            // ->add('type')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Files::class,
        ]);
    }
}
