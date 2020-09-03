<?php

namespace App\Form;

use App\Entity\Folders;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FoldersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',TextType::class,[
                'label' => 'Nom',
                'attr' => [
                    'placeholder' => 'Nom du dossier'
                ]
            ])
            // ->add('size')
            // ->add('hide')
            // ->add('archive')
            // ->add('trash')
            // ->add('createdAt')
            // ->add('updatedAt')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Folders::class,
        ]);
    }
}
