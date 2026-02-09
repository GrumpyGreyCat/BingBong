<?php

namespace App\Service;

class StringManagement{

    public function isString($string) {
        return !empty($string);
    }
    
    public function removeFirstCaracter($string) {
        return substr($string, 1);
    }
}

