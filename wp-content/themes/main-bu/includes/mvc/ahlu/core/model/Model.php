<?php
    class Model extends RecordMethod{
        protected $db = null;
        protected $table = null;
        protected $prefix = null;
        protected $subfix = null;
        protected $isRoot = true;
		
        //object general
        protected $general = null;
		
        public function __construct(){
           $this->config = Ahlu::Core("Config");
           $this->db = Ahlu::DB(); 
		   
			
           $this->init();
        }
        
        public function listLastest($limit=10){trigger_error("error: unimplemented");die();}
		
		
        public function children($array){echo "i don't know child.";}
        
        /**
        * Get all objects from specific table
        * 
        * @param mixed $format
        */
        public function getObjects($format=array()){
            $def= array(
               "limit"=>array(0,10) 
            );
            if($format==null){
                //select all
            }else{
                // 
            }
        }
        /**
        * Get One records on database
        * 
        * @param mixed $format
        * @return mixed
        */
        public function item($id=-1,$format=array()){
            $def= array(
               "limit"=>array(0,10)
            );
            
            if(count($format)>0){
                //select all
                
            }else{
               $a= $this->db->get_results("SELECT * FROM {$this->db->users} WHERE ID={$id}");
               if(count($a)>0){
                   return count($a)>1 ? $a : $a[0];
               }  
            }
           return null;
        }
        
        /////////////
        private function init(){
            
        }
        ///////////////////////////////////for post
		public function post($id){
			mysql_query("SET SESSION group_concat_max_len = 1000000;");
			$query = "Select post.*,metap.meta from  ( SELECT group_concat(meta) as meta,mp.ID  from (SELECT
                        CASE meta_value
                            WHEN  NULL THEN CONCAT(meta_key, '=', \"null\")
			   ELSE CONCAT(meta_key, '=', meta_value)
                        END AS meta,post_id as ID
                    FROM {$this->db->postmeta}
                ) as mp GROUP BY ID )  as metap,{$this->db->posts} post where metap.ID= post.ID and post.ID={$id}";
            
			//
			//echo $query;
			$a = $this->db->get_results($query);
			if(is_array($a) && count($a)>0){
				$obj = $a[0];
				if(isset($obj->meta)){
					$meta = explode(",",$obj->meta);
					foreach($meta as  $v){
						$k=explode("=",ltrim($v,"_"));
						$obj->{$k[0]} = StringUtil::is_serialized($k[1]) ? unserialize($k[1]) : $k[1];
					}
					unset($obj->meta);
					unset($meta);
					
					if(isset($obj->thumbnail_id)){
						$query = "SELECT post.guid from  {$this->db->posts} as post where post.ID={$obj->thumbnail_id}";
						 //echo $query;
						$a = $this->db->get_results($query);
						//print_r($a);
						if(count($a)>0){
							unset($obj->thumbnail_id);
						   $obj->thumbnail =$a[0]->guid;
						}
					}
				}
				return $obj;
			}
			return null;
        }
        /**
        * Access table by its name
        * 
        * @param mixed $table
        */
        protected function tableName($table=null){
            if($table==null){
                return $this->db->base_prefix.strtolower(str_replace("_model","",$this->table==null? get_class($this):$this->table));
            }
            return $this->db->base_prefix.strtolower($table);   
        }
        
        /**
        * Get info column in specific table
        * 
        */
        protected function Columns(){
           $table =  $this->tableName("users");
           //get all column
           $query = "SHOW COLUMNS FROM ".$this->db->users;
           $columns = array();
           
           //ex :Wordpress  
           $data = $this->db->get_results($query );
           //get foreign key and field
           
           return count($data)>0 ? $data : null;

        }
        
        /**
        * Find Tbale name from current column
        * 
        * @param mixed $cols
        */
        protected function searchTable($cols){
          $result =null;
          if(is_array($cols)){
              $f="";
              $i=0;
              foreach($cols as $v){
                  if(count($cols)-1==$i++){
                      $f.="'{$v}',";
                  }else{
                      $f.="'{$v}'";
                  }
              }
              $sql="SELECT DISTINCT TABLE_NAME
                    FROM INFORMATION_SCHEMA.COLUMNS
                    WHERE COLUMN_NAME
                    IN ({$f})
                    AND TABLE_SCHEMA = database()";
                    
              $result= $this->db->get_results($sql) ;
          }else if(is_string($cols)){
               $sql="SELECT DISTINCT TABLE_NAME
                    FROM INFORMATION_SCHEMA.COLUMNS
                    WHERE COLUMN_NAME
                    IN ('{$cols}')
                    AND TABLE_SCHEMA = database()";
                $result= $this->db->get_results($sql) ;
                    
          }
          if(count($result)>0){
            return count($result)>1 ? $result : $result[0];
          }
          
          return null;
      }
    }
?>