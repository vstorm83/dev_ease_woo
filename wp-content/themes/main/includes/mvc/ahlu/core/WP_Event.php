<?php

    class WP_Event extends Event{
        
         protected static $action = null    ;
         protected static $filter = null    ;     
        
         ////////////////////////////////////// 
        public function __construct(){
            parent::__construct(); 
            return $this;
        }
        
        
        public function Action(){
            if(self::$action==null){
                self::$action = new Action();
            }
            return self::$action;  
        }
        public function Filter(){
            if(self::$filter==null){
                self::$filter = new Filter();
            }
            return self::$filter;  
        }
        

        
    }
    
?>