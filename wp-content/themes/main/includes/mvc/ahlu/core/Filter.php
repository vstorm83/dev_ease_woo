<?php
     class Filter{         
         
         public function __construct(){
            return $this;
        }
        public  function Add_filter(){
            if(function_exists("add_filter"))
               call_user_func_array("add_filter",func_get_args());  
        }
        public  function Apply_filter(){
            if(function_exists("add_filter"))
               call_user_func_array("add_filter",func_get_args());  
        }
    } 
?>