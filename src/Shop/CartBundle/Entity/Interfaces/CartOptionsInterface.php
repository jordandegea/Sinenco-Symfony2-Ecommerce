<?php

namespace Shop\CartBundle\Entity\Interfaces ;


interface CartOptionsInterface{
    
    const FIRST_TIME = 0 ;
    
    const TYPE = 0;
    
    const TYPE_FIELD = 1 ; 
    const TYPE_PRICE = 2 ;
    
    // Dans TYPE_FIELD
    const CHANGE_FIELDS = 3 ; 
    const HIDE_FIELDS = 4 ; 
    
    
    const FIELD_HIDE = 0 ; 
    const FIELD_READ_ONLY = 1 ;
    const FIELD_ADD = 2 ;
    const FIELD_NAME = 3 ;
    const FIELD_VALUE = 4 ;
    
    const PRICE_TOTAL = 0 ; 
    
    const PRICE_TOTAL_AMOUNT = 0 ; 
    const PRICE_TOTAL_CURRENCY = 1 ; 
    
    public function _initCartOptionsTrait();
}

