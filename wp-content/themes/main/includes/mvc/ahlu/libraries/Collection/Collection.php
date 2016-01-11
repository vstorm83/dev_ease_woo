<?php
  /**
  * Class Collection  
  */
 abstract class Collection{
      protected $items = array();
      
      
      /**
      * Add item
      * 
      * @param mixed $item
      * @param mixed $key
      */
      public function Addme($item,$key=null){
         if($key==null){
              array_push($this->items,$item);
         }else{
             if(is_string($key)){
                 $this->items[$key]= $item;
             }else{
                 array_push($this->items,$item);
             }

         }
      }
      
      /**
      * Get item
      * 
      * @param mixed $item
      * @param mixed $key
      */
      public function Getme($key){
          return isset($this->items[$key]) ? $this->items[$key] : "";
      }
      /**
      * Remove item
      * 
      * @param mixed $item
      * @param mixed $key
      */
      
      public function Removeme($key){
         if(is_string($key) || is_numeric($key)){
             if(array_key_exists($key,$this->items))
                unset($this->items[$key]);
         }else{
             trigger_error("Can not find the key '{$key}'.",E_USER_WARNING);
         }
      }
   
      public function toString(){
          var_dump($this->items);
      }  
          public function Data(){
          return $this->items;
      }   
  }
  ?>