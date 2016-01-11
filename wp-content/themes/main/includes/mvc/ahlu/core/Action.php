<?php
    class Action{
        public function __construct(){
            return $this;
        }
        public  function Add_action(){
            if(function_exists("add_action"))
               call_user_func_array("add_action",func_get_args());  
              
              // add_action($hook,$func,$priority,$accepted_args);
        }
        
        public  function Do_action(){
            if(function_exists("do_action")) 
                call_user_func_array("do_action",func_get_args());
            //do_action($tag,arg1,arg2,argect,...);
        }
    }
?>
