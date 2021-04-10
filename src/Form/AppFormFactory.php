<?php

namespace App\Form; 

use App\Form\CommentType;
use App\Form\TrickCreateType;
use App\Form\TrickUpdateType;
use Symfony\Component\Form\FormFactoryInterface; 

class AppFormFactory implements AppFormFactoryInterface
{
    private $formFactory; 

    public function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory; 
    }

    public function create($name, $object)
    {
        switch ($name) {
            //form 
            case 'trick-create': 
                $form = TrickCreateType::class; 
                break; 
            case 'trick-update': 
                $form = TrickUpdateType::class; 
                break; 
            case 'trick-comment': 
                $form = CommentType::class; 
                break; 
            case 'registration': 
                $form = CommentType::class; 
                break;
            //default
            default: 
                $form = null; 
                break; 
        }   

        if (null !== $form) {
            return $this->formFactory->create($form, $object); 
        }

        return false; 
    }
}