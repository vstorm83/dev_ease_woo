<?php

interface IPagation{
    
    function PageLinks($template=null,$isFile =false);
    function PageData($default=true,$template=false);
}

abstract class Pagation{
    protected $db = null; 
    protected $query = null;
    protected $page = 1;
    protected $limit = 10;
    
    protected $dataObject = null;
    ///////
    public function excute($query){  

      if($query==null && $this->query==null){
          trigger_error("Can not find querys string.",E_USER_WARNING);
      }else{
          $this->setQuery($query); 
      }  
      
      $offset = ( $this->page - 1 ) * $this->limit;
      
        $this->query = str_replace(array("select","Select","SELECT"),"select SQL_CALC_FOUND_ROWS ",$this->query)." Limit $offset,{$this->limit}";
        //echo $this->query;
        $sqlTotal = "SELECT FOUND_ROWS()";
        $sql = $this->db->get_results($this->query);
        //print_r($sql);
        if(count($sql)>0){
            $this->dataObject = $sql;
            //get total rows
            $this->setTotalRows($this->db->get_var($sqlTotal));
        } 
    }
    ///////
    abstract public function setTotalRows($rows);
    
    public function setQuery($query){
        
        if(is_string($query)){
           $this->query = $query; 
        }  
    }
    public function setPage($page){
        if(is_numeric($page))
           $this->page = $page;
    }
    public function setLimit($limit){
        if(is_numeric($limit)) 
           $this->limit = $limit;
    }
}
/*
*       global $wpdb;
               $sl = new Ahlu_WP_Slideshow($wpdb);
                
               $pagation = new Ahlu_WP_Pagation($wpdb);
               $pagation->setPage($_REQUEST["page"]);
               $pagation->setLimit(12);
                $sl->setID(intval($id));
                   
                   $pagation->excute($sl->getQueryString());     
            $data = $pagation->PageData();
* 
*/
 class Ahlu_WP_Pagation extends Pagation implements IPagation{
     private $_rows =0;
     
     public function __construct($db){
         $this->db = $db;
     }
    //////////////////////////// 
    public function PageLinks($template=null,$isFile =false){
         // template
         $num_of_pages = ceil($this->_rows / $this->limit );
         $page_links = paginate_links( array(
                'base' => add_query_arg( 'page', '%#%' ),
                'format' => '',
                'prev_text' => __( '&laquo;', 'aag' ),
                'next_text' => __( '&raquo;', 'aag' ),
                'total' => $num_of_pages,
                'current' => $this->page
            ) );
         if($template==null){
             if ( $page_links ) {
                echo '<div class="tablenav-pages" style="clear:both;"><p>' . $page_links . '</p></div>';
            } 
         }else{
            if($isFile){
                 include_once($isFile);
            }
         }    
    }
    public function PageData($default=true,$template=false){
        
        if(!$template){
            //print_r($this->dataObject);
            return ($default) ? $this->dataObject : (array)$this->dataObject; 
        }
             
        //parse template
        //{ID} => {$ID}
    } 
    //////////////////////////// 
    
    public function setTotalRows($rows){
        $this->_rows = $rows;
    }  
 }
 
  interface  ISlideshow{
      function getQueryString(); 
  }
  
  abstract class Slideshow{
       protected $db = null;
       protected $data;    
   }
   
   
   class Ahlu_WP_Slideshow implements ISlideshow{
       protected $ID = -1;
       
       public function __construct($db){
         $this->db = $db;
       }
       
       public function getSlideShow($limit=array(0,30)){
       $query = $this->getQueryString()." LIMIT {$limit[0]},{$limit[1]}"; 
       //echo $query ;
       $data =  $this->db->get_results($query);  
       return count($data)>0 ?  $data :null;
       }
    
       public function setID($id){
           if(is_numeric($id) || is_string($id))
              $this->ID = $id;
       } 
       ////////////////////////// 
       public function getQueryString(){
           if($this->ID==-1){
              trigger_error("Can not excute Slideshow Data, because of no parameter, the default is {$this->ID}.",E_USER_WARNING); 
           }
         return "SELECT post.* FROM {$this->db->posts} post,{$this->db->term_relationships} tr,{$this->db->term_taxonomy} tx WHERE post.ID = tr.object_id and tr.term_taxonomy_id = tx.term_taxonomy_id and tx.term_id IN({$this->ID}) order by post.ID DESC";
       }
       ////////////////////////
   }
 
 
 ///////////////////////////////////////  show nay type of post 
abstract class Ahlu_Post extends Slideshow{

}    

class Ahlu_WP_PostByType extends Ahlu_Post implements ISlideshow{
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
