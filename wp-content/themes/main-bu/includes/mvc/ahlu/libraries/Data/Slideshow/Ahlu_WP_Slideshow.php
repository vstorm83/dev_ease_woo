<?php
/**
*   Class Ahlu_WP_Slideshow
*/
   class Ahlu_WP_Slideshow implements ISlideshow{
       protected $ID = -1;
       public $post_type = 'attachment';
       public $post_status = 'inherit';
       
       public function __construct($db=null){
         $this->db = $db;
       }
       public function load($db){
           $this->db = $db; 
       }
       public function getSlideShow($limit=array(0,30)){
       $query = $this->getQueryString()." LIMIT {$limit[0]},{$limit[1]}"; 
       //echo $query ;
	   mysql_query("SET SESSION group_concat_max_len = 20480");
       $data =  $this->db->get_results($query);  
	   //print_r($data);
       return count($data)>0 ?  $data :null;
       }
		/**
		* Set ID ctaegory or name category, even the slug of category
		* @param $id
		*/
       public function setID($id){
           if(is_numeric($id))
              $this->ID = "term_id={$id}";
		   return $this;
       }        
	   public function setName($name){
           if(is_string($name))
              $this->ID = "name='{$name}'";
		return $this;
	   } 
	   public function setSlug($slug){
           if(is_string($slug))
              $this->ID = "slug='{$slug}'";
		return $this;
	   } 
       ////////////////////////// 
       public function getQueryString(){
           if($this->ID==-1){
              trigger_error("Can not excute Slideshow Data, because of no parameter, the default is {$this->ID}.",E_USER_WARNING); 
           }
         return "SELECT post.*,metap.meta FROM ( SELECT group_concat(meta) as meta,mp.ID  from (SELECT
                        CASE meta_value
                            WHEN  NULL THEN CONCAT(meta_key, '=', \"null\")
			   ELSE CONCAT(meta_key, \"=\", meta_value)
                        END AS meta,post_id as ID
                    FROM {$this->db->postmeta}
                ) as mp GROUP BY ID )  as metap, {$this->db->posts} post, {$this->db->term_relationships} tr, {$this->db->term_taxonomy} tx, {$this->db->terms} t
 WHERE metap.ID=post.ID and post.ID = tr.object_id and tr.term_taxonomy_id = tx.term_taxonomy_id  and  t.term_id=tx.term_id  and post.post_status='{$this->post_status}' and post.post_type='{$this->post_type}'  and t.{$this->ID}
order by post.ID DESC";
       }
       ////////////////////////
   }
 
?>