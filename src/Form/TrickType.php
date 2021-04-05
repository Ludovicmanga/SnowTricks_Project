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
                'label' => false,
                'multiple' => true,
                'mapped' => false, 
                'required' => false
            ])
            ->add('groupId')
            ->add('images', Filetype::class, [
                'label' => false,
                'multiple' => true,
                'mapped' => false, 
                'required' => false
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
