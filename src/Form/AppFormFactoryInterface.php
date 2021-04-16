<?php 

namespace App\Form; 

interface AppFormFactoryInterface
{
    public function create($name, $object); 
}
