<?php

namespace App\Services; 

class Test 
{
    private $_name; 

    public function __construct() {
        $this->_name = 'édouard'; 
        echo $this->_name;
    }

    public function setName($name) {
        $this->_name = $name; 
        return $this; 
    }

    public function getName() { 
        return $this->_name; 
    }

}