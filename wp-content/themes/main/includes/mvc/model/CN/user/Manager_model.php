<?php
    class Manager_model extends User_model implements IModel{
        
        protected $meinfo = array(
            "has_right_to_fire"=>array(
                    "type"=>"checkbox",
                    "value"=>1
                ),
            "access"=>array(
                    "type"=>"checkbox",
                    "value"=>1
                )
        ); 
        
         public function __construct($id=null){
            parent::__construct();
            if($id!=null)
                $this->load($id);
            return $this; 
         }
        
        /* implement */
         public function loadInfo($id=null){
            $this->meDataInfo = array(
            //load from meta
             "salary"=>array(
                    "type"=>"text",
                    "value"=>0
                )
            ); 
           
           //load all data globally
           $this->infoME = array_merge($this->meData,$this->meDataInfo,$this->meinfo);
           
          //override if you want
          if(isset($this->infoME["user_pass"])){
              $this->infoME["user_pass"]["type"] = "password"; 
              $this->infoME["user_email"]["class"] = "email"; 
              $this->infoME["user_email"]["title"] = "Please enter the email."; 
          }
            
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
           //print_r($this->infoME);
           $this->removePrefix();
        }
        
        public function clear(){
            $this->clearData($this->meinfo); 
            $this->clearData($this->meData); 
            $this->clearData($this->meDataInfo); 
            $this->clearData($this->infoME); 
            $this->clearData($this->infoMEAlias); 
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
           $view = null;
           $data = $this->infoME;
           //print_r($data);
           switch(strtolower($format["render"])){
               case "form":
               case "default":
                    $view =  Ahlu::View("Ahlu_Form");
                    $view->assignRange($data);
                    $view->Build();   
               break;
           }

           
        }
        /* implement */    
        
        public function children($array=null){
            $db = $this->db;
             $query = "SELECT t4.* from $db->terms as t1 , $db->term_taxonomy as t2,$db->term_relationships as t3,$db->posts as t4 where t1.term_id = t2.term_id and t3.term_taxonomy_id = t2.term_taxonomy_id and t4.ID=t3.object_id and t1.term_id={$this->category->term_id}";
            //echo $query;
            $a = $db->get_results($query);
               return count(a)==1 ? $a[0] :$a;
        }
        /**
        * Clear all data
        * 
        * @param mixed $arr
        */
        private function clearData(&$arr){
            $a = array();
            foreach($arr as $k=> $v){
               $v["value"]=null;
               $a[$k] = $v; 
            }
            $arr = $a;
        }
    }
?>