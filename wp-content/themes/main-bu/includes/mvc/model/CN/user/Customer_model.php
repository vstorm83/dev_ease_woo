<?php
    class Customer_model extends User_model implements IModel{
         protected $meinfo = array();
         
         public function __construct($id=-1){
            parent::__construct();
            if($id!=-1)
                $this->load($id);
            return $this; 
         }
        
        /* implement */
         public function loadInfo($id=-1){
          $this->meDataInfo = array(
            //load from meta
            ); 
           
           //load all data
           $this->infoME = array_merge($this->meData,$this->meDataInfo,$this->meinfo);
             
            return $this;
         }
         public function addInfo(){
            return false; 
        }
        
        public function cloneObject($obj){
           if(is_object($obj)){
              $obj = (array)$obj; 
           } 
           
           foreach($obj as $k=>$v){
                //Data
                if(array_key_exists($k,$this->meData)){
                    $this->meData[$k]= is_string($v)? urldecode($v) : $v;
                    continue;
                }
                
                //Data
                if(array_key_exists($k,$this->meinfo)){
                    $this->meinfo[$k]= is_string($v)? urldecode($v) : $v;
                    continue;
                }
                
                if(array_key_exists($k,$this->meDataInfo)){
                    $this->meDataInfo[$k]= is_string($v)? urldecode($v) : $v;
                    continue;
                }
           }
           //now convert.
           $this->infoME = array_merge($this->meData,$this->meDataInfo,$this->meinfo);
           $this->removePrefix();
        }
                public function clear(){
          $this->meinfo = array();  
          $this->meData = array();  
          $this->meDataInfo = array();
          $this->infoME = array();
          $this->infoMEAlias = array();  
        } 
        public function view($format=array()){
             $def= array(
                "render"=>"form",
                "template"=>null,
                "excludeView"=>null
             );
             
           $format = array_merge($def,$format);
           if($format["excludeView"]!=null){
               if(!is_array($format["excludeView"])){
                   $format["excludeView"] = array($format["excludeView"]);
               }
           }
            
           switch(strtolower($format["render"])){
               
           }
           $view =  Ahlu::View("Ahlu_Form");
           
           
           
        }
        /*End implement*/
        
        public function children($array=null){
            $db = $this->db;
             $query = "SELECT t4.* from $db->terms as t1 , $db->term_taxonomy as t2,$db->term_relationships as t3,$db->posts as t4 where t1.term_id = t2.term_id and t3.term_taxonomy_id = t2.term_taxonomy_id and t4.ID=t3.object_id and t1.term_id={$this->category->term_id}";
            //echo $query;
            $a = $db->get_results($query);
               return count(a)==1 ? $a[0] :$a;
        }
    }
?>