 <?php
    /**
    *  Model Contact
    */
    class Contact extends Model
    {
         public function __construct(){
            parent::__construct();
            return $this; 
         }
         
        public function children($array=null){
            $db = $this->db;
             $query = "SELECT t4.* from $db->terms as t1 , $db->term_taxonomy as t2,$db->term_relationships as t3,$db->posts as t4 where t1.term_id = t2.term_id and t3.term_taxonomy_id = t2.term_taxonomy_id and t4.ID=t3.object_id and t1.term_id={$this->category->term_id}";
            //echo $query;
            $a = $db->get_results($query);
            if(is_array($a) && count($a)>0){
                return $a;
            }
              return null;
        }
        
        /**
       * List all caterory by taxanomy or specific category
       * 
       */
       public function categories($array=null){
           $db = $this->db;
             $query = "SELECT t1.* from $db->terms as t1 , $db->term_taxonomy as t2 where t1.term_id = t2.term_id and t2.parent={$this->category->term_id}";
            //echo $query;
            $a = $db->get_results($query);
            if(is_array($a) && count($a)>0){
                return $a;
            }
              return null;
       }
       
       public function hasParent($echo=false){
            $db = $this->db;
             $query = "SELECT t2.* from  (SELECT t2.parent as ofparent from $db->terms as t1 , $db->term_taxonomy as t2 where t1.term_id = t2.term_id  and t1.term_id={$this->category->term_id}) as t , $db->terms as t2 where t2.term_id =t.ofparent";
             //echo $query;
            $a = $db->get_results($query);
            //print_r($a);
            if(count($a)!=0){
               return !$echo ? $a : $echo; 
            }
          return false;     
       } 
    }
    ?>