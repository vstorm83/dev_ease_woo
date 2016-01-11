<?php
/**
*   Class Ahlu_WP_PostByType
*/
class Ahlu_WP_PostByType extends Ahlu_WP_Post implements ISlideshow{
      protected $post_type = null;
      public $post_status = "publish";
      
      public function __construct($db){
         $this->db = $db;
       }
       
       public function getPostType($limit=array(0,30)){
       $query = $this->getQueryString()." LIMIT {$limit[0]},{$limit[1]}"; 
      // echo $query ;
       $data =  $this->db->get_results($query);
       
         return count($data)>0 ?  $data :null;
       }
       
      public function setPostType($type){
           if(is_string($type))
              $this->post_type = $type;
       }  
        //////////////////////////
       public function getQueryString(){
           if($this->post_type==null){
              trigger_error("Can not excute Slideshow Data, because of no parameter, the default is empty.",E_USER_WARNING); 
           }
         return "SELECT * from {$this->db->posts} WHERE post_type ='{$this->post_type}' and post_status='{$this->post_status}'";
       }
       ////////////////////////
}  
 
    
?>
