<?php

namespace App\Form;

use App\Entity\Trick;
use App\Form\ImageType;
use App\Form\VideoType;
use App\Entity\TrickGroup;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class TrickCreateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => false
            ])
            ->add('description', TextType::class, [
                'label' => false
            ])
            ->add('coverImage', FileType::class, [
                'label' => false,
                'multiple' => true,
                'mapped' => false, 
                'required' => true
            ])
            ->add('trickGroup', EntityType::class, [
                'class' => TrickGroup::class, 
                'choice_label' => 'name', 
                'label' => false
            ])
            ->add('images', FileType::class, [
                'label' => false,
                'multiple' => true,
                'mapped' => false, 
                'required' => true
            ])
            ->add('videos', CollectionType::class, [
                'label' => false,
                'entry_type' => VideoType::class, 
                'entry_options' => ['label' => false], 
                'allow_add' => true,
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
