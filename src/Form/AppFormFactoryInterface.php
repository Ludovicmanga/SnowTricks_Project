<?php 

namespace App\Form; 

use App\Entity\Trick; 

interface AppFormFactoryInterface
{
    public function create($name, $object); 

}