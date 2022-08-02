<?php
/**
 * Template Class
 * 
 * @package lnpedia
 * 
 */
namespace fusfan\inc; //Namespace Definition
use fusfan\inc\traits\Singleton; //Singleton Directory using namespace

class template{ //Assests Class

    use Singleton; //Using Sinlgeton

    protected function __construct(){ //Constructor function

        //Load Class
         $this->set_hooks(); //Setting the hook below
    }

    protected function set_hooks() {
        
         /**
          * Actions
          */

        //Adding functions to the hooks
    }
}
?>