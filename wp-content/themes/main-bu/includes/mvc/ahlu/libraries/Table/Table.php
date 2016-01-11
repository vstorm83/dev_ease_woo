<?php
    class Table extends Collection {
         protected $post = null;
         
         protected function init(){
             global $post;
             $this->post = $post;
             return $this; 
         }
         
    }
?>
