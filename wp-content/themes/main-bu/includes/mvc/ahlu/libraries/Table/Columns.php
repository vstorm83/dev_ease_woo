<?php
    class Columns {
     protected $post = null;     
     
        public function __coonstruct($arr=null){
            if($arr!=null && is_array($arr)){
                 $this->items = $arr;
            }
            
            $this->init();
            return $this;
        }
        
        
        public function getPostField($name){
            $name = "the_".strtolower($name);
            
           return $name(); 
            
        }
        
        public function getPostMeta($name,$pos=0){
            //get all meta drom this post
            $custom = get_post_custom();
            return $custom[$name][$pos];
            
        }
        
        public function getTaxanomy($name){
            //get taxanomy            
            
           return get_the_term_list($this->post->ID,  ucwords($name), '', ', ','');
        }
        
        protected function init(){
             global $post;
             $this->post = $post;
             return $this; 
         }
    }
?>