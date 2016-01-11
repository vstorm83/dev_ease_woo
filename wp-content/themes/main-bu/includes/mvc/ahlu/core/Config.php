<?php
    class Config {
        private $items = array();
        
        public function __construct(){
            $this->items["default_controller"] = "Home";
            $this->items["default_theme"] = "default";
            $this->items["default_theme_folder"] = "default";
            $this->items["enable_function_wp"] = false;  
            $this->items["enableDefault"] = true; 
            
            //config DB
            //config settings
            $this->items["db"]["settings"]["prefix"] = "ME_";
            
            //config format to display data on form with select format.
            $this->items["db"]["formatDisplay"]["wp_users"] = array("ID","user_login"); 
            $this->items["db"]["formatDisplay"]["Table-B"] = array("name"); 
        }
        
        public function item($key){
            if(isset($this->items[$key])){
                $a = $this->items[$key];
                if(is_array($a) || is_object($a))
                    return (object) $a;
               
               return  $a;
            }
            return "";
        }
        public function setItem($key,$value){
               return  $this->items[$key] = $value;
        }
    }
?>