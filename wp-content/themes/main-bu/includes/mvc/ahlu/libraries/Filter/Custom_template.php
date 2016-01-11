<?php
    class Custom_template extends Override_Filter{
          public function __construct(){
            return $this;
        }
        
       public function override_content($data){
           $data =  apply_filters("go_action",$data);
           return $data;
       }

       public function go_content($data){
          return $data."hello";
       }
       
       public function override_title($title){
           $title =  apply_filters("go_action",$title);
           return $title;
       }

       public function go_title($title){
          return $title." hello";
       } 
        
    }
?>