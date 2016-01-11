<?php
    class User_model extends Model{
		public static $current_user_in_system = null;
         protected $meinfo = array();   
         protected static $defaultcolumns = null;
		 protected $user = null;
		 
         public function __construct(){
            parent::__construct();
            
            return $this; 
         }
         
         //load general
         public function load($id=null){
            //
            if($id!=null){
				$this->user = $id;
			}
            return $this;
         }
        
		/**
		* Get current user is tracking on session
		*/
        public static function getCurrent(){
			if(isset($_SESSION["USER"]) && empty(self::$current_user_in_system)){
				self::$current_user_in_system = (object)$_SESSION["USER"];
				//check fire time
			}else{
				self::$current_user_in_system = null;
			}
			return self::$current_user_in_system;
		}
        
        
        public function insert(array $fields){
            return $this->db->insert($this->tableName("users"),$fields);
        }

        public function update(array $fields,$where=array()){
            return $this->db->update($this->tableName("users"), $fields, $where);	
        }
		public function update_field($id,$key,$value){
            return $this->db->update($this->tableName("users"), array("{$key}"=>$value), array("ID"=>$id));	
        }
		public function update_meta_field($id,$key,$value){
            return $this->db->update($this->tableName("usermeta"), array("{$key}"=>$value), array("user_id"=>$id));	
        }
        public function delete($id){
            $num = $this->db->delete($this->tableName("users"),$where);
			if($num>0){
				return $this->db->delete($this->tableName("usermeta"),array("user_id"=>$id));
			}
        }
         
        public function login($login,$pass,$isRemembered=false){
            $pattern = '/^(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){255,})(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){65,}@)(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22))(?:\\.(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-+[a-z0-9]+)*\\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-+[a-z0-9]+)*)|(?:\\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\\]))$/iD';
			$query = null;
			$pass = md5(sha1($pass));
			$user = null;
			
			if(isset($_COOKIE["isRemembered"])){
				//store in session
				$user = json_decode($_COOKIE["isRemembered"]);
				//turn on status
				$this->update_field($user->ID,"user_status",1);
			}else{
				if(preg_match($pattern, $login,$m)) {
					$query = array("user_email" => $login,"user_pass"=>$pass);
				}else{
					$query = array("user_login" => $login,"user_pass"=>$pass);
				}
				$user = $this->item($query);

				if(empty($user)) return null;
				
				$a = new stdClass();
				$a->id = $user->ID;
				$a->username = $user->display_name;
				$a->user_login = $user->user_login;
				$a->user_registered = $user->user_registered;
				$a->activated = $user->user_registered;
				$a->user_status = $user->user_status;
				$a->expire = time()+(60*15);
				
				$user = $a;
				unset($a);
				if($isRemembered){
					$_COOKIE["isRemembered"]= json_encode($user);
				}
			}
			
			if(is_object($user)){
				$_SESSION["USER"] = $user;
			}
			
			return $user;
        } 
		/**
		*	Check email exist
		*/
		public function checkEmail($email){
			$f = $this->item(array("user_email" => $email));
			return empty($f);
		}
		//Override
		public function item(array $where){
			//mysql_query("SET SESSION group_concat_max_len = 1000000;");
			if(count($where)>0){
				$a = array();
				foreach($where as $k=>$v){
					$a[] = "$k=".(is_string($v) ? "'".mysql_escape_string($v)."'" : $v);
				}
				$where = " where ".implode(" and ",$a);
				unset($a);
			}else{
				$where = "";
			}
			
			$query = "Select user.* from {$this->db->users} user {$where}";
			
			//echo $query;
			$obj = $this->db->get_row($query);
			
			if(is_object($obj)){
			    $query = "Select metau.meta from  ( SELECT group_concat(meta) as meta,mp.ID  from (SELECT
					CASE meta_value
						WHEN  NULL THEN CONCAT(meta_key, '=', \"null\")
		   ELSE CONCAT(meta_key, '=', meta_value)
					END AS meta,user_id as ID
				FROM {$this->db->usermeta}
			) as mp GROUP BY ID )  as metau where metau.ID={$obj->ID}";
			
			//echo $query;
				$me = $this->db->get_row($query);
				$obj->meta = is_object($me) ? $me->meta : null;
				
				unset($me);
				if(isset($obj->meta)){
					$meta = explode(",",$obj->meta);
					foreach($meta as  $v){
						$k=explode("=",ltrim($v,"_"));
						$obj->{$k[0]} = StringUtil::is_serialized($k[1]) ? unserialize($k[1]) : $k[1];
					}
					unset($obj->meta);
					unset($meta);
				}
			}
			return $obj;
		}
        //refferer 
        public function parent($array=null){
            $db = $this->db;
             $query = "SELECT t4.* from $db->terms as t1 , $db->term_taxonomy as t2,$db->term_relationships as t3,$db->posts as t4 where t1.term_id = t2.term_id and t3.term_taxonomy_id = t2.term_taxonomy_id and t4.ID=t3.object_id and t1.term_id={$this->category->term_id}";
            //echo $query;
            $a = $db->get_results($query);
               return count(a)==1 ? $a[0] :$a;
        }
        
        
    }
?>