<?php

namespace Shop\CartBundle\Entity\Interfaces ;


interface CartOptionsInterface{
    
    const FIRST_TIME = 0 ;
    
    const TYPE = 0;
    
    const TYPE_FIELD = 1 ; 
    const TYPE_PRICE = 2 ;
    const LOCK_FIELD = 3 ; 
    
    
    const FIELD_HIDE = 0 ; 
    const FIELD_READ_ONLY = 1 ;
    const FIELD_ADD = 2 ;
    const FIELD_NAME = 3 ;
    const FIELD_VALUE = 4 ;
    
    public function _initCartOptionsTrait();
}

