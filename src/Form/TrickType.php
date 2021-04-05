<?php

namespace App\Form;

use App\Entity\Trick;
use App\Form\ImageType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class TrickType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('coverImage', Filetype::class, [
                'label' => 'Photo principale de la figure',
                'multiple' => true,
                'mapped' => false, 
                'required' => true
            ])
            ->add('images', Filetype::class, [
                'label' => 'Photo(s) de présentation de la figure',
                'multiple' => true,
                'mapped' => false, 
                'required' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Trick::class,
        ]);
    }
}
