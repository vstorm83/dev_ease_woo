<?php

    class Post_model extends Model{

         protected $post ;
		 //get image and meta 
         protected $post_info =array() ;
		 
         protected $category =null ;

         public $post_type=null;
         public $post_status="publish";

         

         public function __construct($post=null){

            parent::__construct();
			
			if($post!=null)
				$this->load($post);
			
            return $this; 
			
         }
		/*
		 * get post from WP
		 */
         public function get_posts($id){
			$default =  array(
				'showposts' => -1,
				'post_type' => $this->post_type);
			$data = get_posts($param !=null?$param:$default);
			foreach($data as $item){
				$meta = get_post_meta($item->ID); 
				foreach($meta as $k=>$v){
					$item->{$k} = count($v)==1?$v[0] : $v;
				}
				//get image
				//$result= $this->db->get_results("select post.guid from (select meta_value as ID from {$this->db->postmeta} where post_id ={$item->ID} and meta_key='_thumbnail_id') as p join {$this->db->posts} post on post.ID = p.ID");
				$result= $this->db->get_results("select guid from {$this->db->posts} where ID={$item->_thumbnail_id}");
				$item->thumbnail = $result[0]->guid;
				//get
			}
			return $data;
		}

         public function load($post){
		 
            if(is_object($post)){
				$this->post = $post;
				//update
				$this->post_type = $post->post_type;
				$this->post_status = $post->post_status;
			}
			
			if(is_numeric($post)){
				$a = $this->byID($post);
				
			}else if(is_string($post)){
				$a = $this->byTitle($post);
			}
			
			if($a!=null){
				$this->post = $a;

			}
            return $this;

         }
			
        public function item($id,$post_status="publish",$post_type=""){
			if(empty($post_type)){
				$post_type = $this->post_type;
			}
			if(empty($post_type)){
				$post_status = $this->post_status;
			}
			mysql_query("SET SESSION group_concat_max_len = 1000000;");
			$a =   $this->db->get_results("
			   SELECT p.* ,group_concat(meta) as meta FROM (
						SELECT
							CASE
								WHEN meta_value IS NULL THEN CONCAT(meta_key, '=', \"null\")
								ELSE  CONCAT(meta_key, \"=\", meta_value)
							END AS meta,post_id
						FROM ".$this->tableName('postmeta')." 
					) as mp ,".$this->tableName('posts')." p
		where mp.post_id= p.ID and p.post_status='{$post_status}' and p.ID={$id} and p.post_type='{$post_type}'");

		  //alway
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
						$query = "SELECT post.guid from  ".Ahlu::DB()->posts." as post where post.ID={$obj->thumbnail_id}";
						 //echo $query;
						$a = Ahlu::DB()->get_results($query);
						//print_r($a);
						if(count($a)>0){
							unset($obj->thumbnail_id);
						   $obj->thumbnail =$a[0]->guid;
						}
					}
				}
				 $a = $obj;
			}
			return $a;
		}
		public function byID($id,$post_status="publish",$post_type=""){
		
			if(empty($post_type)){
				$post_type = $this->post_type;
			}
			if(empty($post_type)){
				$post_status = $this->post_status;
			}
			
			//////
			if(!empty($post_type)){
				$post_type = "and p.post_type='{$post_type}'";
			}
			
			mysql_query("SET SESSION group_concat_max_len = 1000000;");
			$a =   $this->db->get_results("
			   SELECT p.* ,group_concat(meta,'\$ahlu\$' separator ',') as meta FROM (
						SELECT
							CASE
								WHEN meta_value IS NULL THEN CONCAT(meta_key, '=', \"null\")
								ELSE  CONCAT(meta_key, \"=\", meta_value)
							END AS meta,post_id
						FROM ".$this->tableName('postmeta')." 
					) as mp ,".$this->tableName('posts')." p
		where mp.post_id= p.ID and p.post_status='{$post_status}' and p.ID={$id} {$post_type}");

		  //alway
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
						$query = "SELECT post.guid from  ".Ahlu::DB()->posts." as post where post.ID={$obj->thumbnail_id}";
						 //echo $query;
						$a = Ahlu::DB()->get_results($query);
						//print_r($a);
						if(count($a)>0){
							unset($obj->thumbnail_id);
						   $obj->thumbnail =$a[0]->guid;
						}
					}
				}
				 $a = $obj;
			}
			return $a;
		}
		public function byName($name,$post_status="publish",$post_type=""){
			if(empty($post_type)){
				$post_type = $this->post_type;
			}
			if(empty($post_type)){
				$post_status = $this->post_status;
			}
			//////
			if(!empty($post_type)){
				$post_type = "and p.post_type='{$post_type}'";
			}
			
			mysql_query("SET SESSION group_concat_max_len = 1000000;");
			$a =   $this->db->get_results("
			   SELECT p.* ,group_concat(meta,'\$ahlu\$' separator ',') as meta FROM (
						SELECT
							CASE
								WHEN meta_value IS NULL THEN CONCAT(meta_key, '=', \"null\")
								ELSE  CONCAT(meta_key, \"=\", meta_value)
							END AS meta,post_id
						FROM ".$this->tableName('postmeta')." 
					) as mp ,".$this->tableName('posts')." p
		where mp.post_id= p.ID and p.post_status='{$post_status}' and p.post_name='{$name}' {$post_type}");

		  //alway
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
						$query = "SELECT post.guid from  ".Ahlu::DB()->posts." as post where post.ID={$obj->thumbnail_id}";
						 //echo $query;
						$a = Ahlu::DB()->get_results($query);
						//print_r($a);
						if(count($a)>0){
							unset($obj->thumbnail_id);
						   $obj->thumbnail =$a[0]->guid;
						}
					}
				}
				 $a = $obj;
			}
			return $a;
		}
		public function byTitle($title,$post_status="publish",$post_type=""){
			if(empty($post_type)){
				$post_type = $this->post_type;
			}
			if(empty($post_type)){
				$post_status = $this->post_status;
			}
			/////
			if(!empty($post_type)){
				$post_type = "and p.post_type='{$post_type}'";
			}
			
			mysql_query("SET SESSION group_concat_max_len = 1000000;");
			$a =   $this->db->get_results("
			   SELECT p.* ,group_concat(meta) as meta FROM (
						SELECT
							CASE
								WHEN meta_value IS NULL THEN CONCAT(meta_key, '=', \"null\")
								ELSE  CONCAT(meta_key, \"=\", meta_value)
							END AS meta,post_id
						FROM ".$this->tableName('postmeta')." 
					) as mp ,".$this->tableName('posts')." p
		where mp.post_id= p.ID and p.post_status='{$post_status}' and p.post_title='{$title}' {$post_type}");

		  //alway
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
						$query = "SELECT post.guid from  ".Ahlu::DB()->posts." as post where post.ID={$obj->thumbnail_id}";
						 //echo $query;
						$a = Ahlu::DB()->get_results($query);
						//print_r($a);
						if(count($a)>0){
							unset($obj->thumbnail_id);
						   $obj->thumbnail =$a[0]->guid;
						}
					}
				}
				 $a = $obj;
			}
			return $a;
		}

        public function getMe(){

            return $this->post;

         }
		 public function getImage($type="thumbnail"){
		 
			if(!isset($this->post_info["image"])){
				$meta = $this->getMeta();
				$this->post_info["image"] = $meta->thumbnail;
			}
			return $this->post_info["image"];
         }	
		 
		 public function getMeta($key=null){
			
			
			if(!isset($this->post_info["meta"])){
			
				$query = "Select metap.meta from  ( SELECT group_concat(meta) as meta,mp.ID  from (SELECT

							CASE meta_value

								WHEN  NULL THEN CONCAT(meta_key, '=', \"null\")

				   ELSE CONCAT(meta_key, '=', meta_value)

							END AS meta,post_id as ID

						FROM {$this->db->postmeta}

					) as mp GROUP BY ID )  as metap  where metap.ID={$this->post->ID}";
				//echo $query;

				$a =   $this->db->get_results($query);
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
							$query = "SELECT post.guid from  ".Ahlu::DB()->posts." as post where post.ID={$obj->thumbnail_id}";
							 //echo $query;
							$a = Ahlu::DB()->get_results($query);
							//print_r($a);
							if(count($a)>0){
								unset($obj->thumbnail_id);
							   $obj->thumbnail =$a[0]->guid;
							}
						}
					}
					$this->post_info["meta"] = $obj;
				}
			}
			return $key!=null && isset($this->post_info["meta"][$key]) ? $this->post_info["meta"][$key] :$this->post_info["meta"];
         }	
		/**
		 * Get seo meta
		 *
		 */
		 public function SEO(){
			$seo = &Ahlu::Library("Ahlu_SEO");
			$seo->setTitle($this->post->post_title.($this->hasCategory()?" - ".$this->category->name:""));
			$seo->setKeyword($this->post->post_title);
			$seo->setDescription(strip_tags($seo->getDescription($this->post->post_content)));
			$seo->setCanonical(site_url($this->post->post_name));

			return $seo->Meta();
		}
		 //need to override

        public function parent($array=null){

            $db = $this->db;

            $query = "SELECT * from {$this->tableName('posts')} where post_parent={$this->post->ID}";

            //echo $query;

            $a = $db->get_results($query);

            return count(a)>0 ? $a[0] :null;

        }

        public function hasParent(){

			return $this->post->post_parent!=0;

		}
		/*
		* Get category
		*/
		public function hasCategory(){
				if($this->category) return true;

			    $query="SELECT s1.*,t.*,te.* from {$this->tableName('term_taxonomy')} s1, {$this->tableName('terms')} t, {$this->tableName('term_relationships')} te where te.term_taxonomy_id=s1.term_taxonomy_id and t.term_id=s1.term_id and object_id={$this->post->ID}";
				$a = $this->db->get_results($query);
			
				if(count($a)>0){
					 $this->category = $a[0];
					
					 return true;
				}
			return false;
		}
		/////////////////////////////need to override

		//search by name on the same post_type
		public function search($type){

			trigger_error("you should override in your subclass.");
				die();

		}

		
		//search by name in the same cate
		public function relatedSearch($limit = 3,$isRandom = false)
		{
				trigger_error("you should override in your subclass.");
				die();
		}
		/////////////////////////////

     /**
      * Get breadCrumbs from post
      *  
      * @param mixed $home
      * @param mixed $ch
      */
       public function breadCrumbs($home="home",$uri="",$ch=" &raquo; ",$except=array(),$return=false,$more=null){

           $crumbs = array();

           array_push($crumbs,array($this->post->post_title,$this->post->post_name));

           //we need check this post belong to any category?
			$except = array_map(function($v){
				return strtolower($v);
			},$except);
					
	
			//find parent post
			$allPosts = $this->menuData(-1,false);
			$collect =array();		
            //loop root			
			
			$this->_trackup($allPosts,$collect,$this->post->post_parent);

			//loop to get parent
			if(count($collect)>0){
			
			 foreach($collect as $obj){			  
				  array_push($crumbs,array($obj->post_title.'$$$'.$obj->ID,$obj->post_name));
			  }
			}
			//find category and loop
			$cate = null;
			if($this->hasCategory()){
				$cate = $this->category;
				//add into cate
				
				array_push($crumbs,array($cate->name,$cate->slug));
				//look up recruise
				 $f=  $this->_loadCategory($cate->term_taxonomy_id);
				  if($f!=null){
					  //add all subcate parentuper
					  foreach($f as $obj){

						  array_push($crumbs,array($obj->name.'$$$'.$obj->term_taxonomy_id,$obj->slug));
					  }

				  }
			}	
			
			//add more
			if(is_array($more)){
				$crumbs = array_merge($crumbs,$more);
			}
            //add home
            array_push($crumbs,array($home,$uri));
            $s = array();
            $crumbs = array_reverse($crumbs);

           
			//filter
		   foreach($crumbs as $i=>$p) {

               //only in post type as seo
			   if(in_array(strtolower($p[1]),$except)) unset($crumbs[$i]);
		   
           }

		   if($return){
				return  $crumbs;
		   }
			
           foreach($crumbs as $i=>$p) {
				$titleP = explode('$$$',$p[0]);
				$title = htmlspecialchars($titleP[0]);

				$s[]='<a '.($i==count($crumbs)-1 ? 'class="current"': '').' href="'.(empty($p[1]) || $p[1]==$uri?$uri:rtrim($uri,"/")."/".urldecode($p[1])).'" title="'.$title.'">'.ucwords($title=="home"?"home":$title).'</a>';
           }


           return "<div class=\"breadCrumbs\" style=\"margin-bottom: 5px;\">".implode($ch,$s)."</div>";
       }
		private function _trackup($array,&$collect,$id){
			if(!is_array($array) || count($array)==0) return $collect;
			foreach($array as $i){
				if($id==$i->ID){
					$collect[] = $i;
					$this->_trackup($array,$collect,$i->post_parent);
				}
			}
		}
       /**
		 * list latest post from post type
		 *
		 */
		 public function listLastest($limit=10,$page=1){

            $this->pagation = &Ahlu::Library("Ahlu_WP_Pagation",$this->db);
            $this->pagation->setPage($page);

            $this->pagation->setLimit($limit);

            mysql_query("SET SESSION group_concat_max_len = 1000000;");

			$query = "Select post.*,metap.meta from  ( SELECT group_concat(meta) as meta,mp.ID  from (SELECT

                        CASE meta_value

                            WHEN  NULL THEN CONCAT(meta_key, '=', \"null\")

			   ELSE CONCAT(meta_key, '=', meta_value)

                        END AS meta,post_id as ID

                    FROM {$this->db->postmeta}

                ) as mp GROUP BY ID )  as metap, {$this->db->posts} post where metap.ID= post.ID and post.post_type='{$this->post_type}' and post.post_status='{$this->post_status}' order by post.post_date desc";

			//echo $query;

			$this->pagation->excute($query); 

			if($this->pagation->hasData){

				$o = new stdClass();

				$o->link = $this->pagation->PageLinks(true);

				$o->data = $this->pagation->PageData();

				if(is_array($o->data)){

					foreach($o->data as $obj){

					

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

				  }
				}

				return $o;

			}

			return null;

        }
       private function _loadCategory($id){
            $db = $this->db;

             $query = "SELECT s1.*,t.* from {$db->prefix}term_taxonomy s1, {$db->prefix}terms t where t.term_id=s1.term_id ";

            //echo $query;

            $a = $db->get_results($query);

            $s= array();



            if(count($a)>0){

                foreach($a as $arr){

                    $obj = (object) $arr;

                    if($obj->term_taxonomy_id ==$id){

                       $f= $this->_findParent($obj->parent,$a);

					   //print_r($f);

                       if($f!=null){

                           $s[]=$f;

                       }

                    }

                }
				return $s;

            }

            return null;

       }

       private function _findParent($id,$a){

           foreach($a as $arr){

               $obj = (object) $arr;

               if($obj->term_taxonomy_id==$id){

                   return $obj;

               }

           }

           return null;

       }
		
	   
		/* Get comment
       * 
       * @comment
       */
       public function comments($limit=10,$page=1){
			$a = array();
			
			//$this->_comments($a,0);
			$query = "SELECT * FROM {$this->db->comments} WHERE comment_post_ID={$this->post->ID} and comment_approved=1";
            $rs = $this->db->get_results($query);
			$array = array();
			$this->_comments($array,0,$rs);
			
			$o = new stdClass();

			$o->link = $count;

			$o->data = $array;
				
			return $o;
	   }
	   private function _comments(&$array,$parent,$collection) {
			
			foreach($collection as $row) {
				if ($row->comment_parent ==$parent) {

					$array[$row->comment_ID] = $row;
					$array[$row->comment_ID]->children = array();
					
					$this->_comments($array[$row->comment_ID]->children,$row->comment_ID,$collection);
				}
			}
		}
		/*
	    private function _comments($array,$parent,$level=0) {
			$q= "SELECT a.*,Deriv1.Count FROM (SELECT * FROM woocommerce_50886_comments WHERE comment_post_ID=32) a LEFT OUTER JOIN (SELECT comment_parent, COUNT(*) AS Count FROM woocommerce_50886_comments GROUP BY comment_parent) Deriv1 ON a.comment_post_ID = Deriv1.comment_parent WHERE a.comment_parent=0";
			echo $q;
			$result = mysql_query($q);
			while ($row = mysql_fetch_assoc($result)) {
				if ($row['Count'] > 0) {
					$array[$row->comment_ID] = $row;
					$array[$row->comment_ID]["children"] = array();
					display_children($array[$row->comment_ID]["children"],$row->comment_ID,$info, $level + 1);
				} else if (empty($row['Count'])) {
					$array[$row->comment_ID] = $row;
					$array[$row->comment_ID]["children"] = null;
				} else;
			}
		}
		*/

		///////////////////////////// Menu /////////////////////////////
		/**
       * Get Menu tree
       * 
       * @param mixed $formats
       */
       public function menu($selected=-1){

           $query = "SELECT post_name,post_title, ID,post_parent,menu_order FROM {$this->db->posts} WHERE post_type='{$this->post->post_type}'";
           $rs = $this->db->get_results($query);
		   $arr = null;
           if(is_array($rs) && count($rs)>0){
				$arr = array();
			   $this->_menuData($arr,0,$selected,$rs);
           }
			return $arr;
       } 
	   /*
	   * Get Only menu tree data
	   */
	   public function menuData($selected=-1,$isRecursive=true){
           $query = "SELECT post_name,post_title, ID,post_parent,menu_order FROM {$this->db->posts} WHERE post_status='{$this->post_status}' and post_type='{$this->post_type}'";
         // echo $query;
		
		  $rs = $this->db->get_results($query);
		   $arr =null;
           if(is_array($rs) && count($rs)>0){
			   if(!$isRecursive) return $rs;
			   $arr = array();
			   $this->_menuData($arr,0,$selected,$rs);
           }

           return $arr;
       } 
	    
		private function _menuData(&$array,$parent,$selected,$collection){
			
			foreach($collection as $row) {
				
				if ($row->post_parent ==$parent) {
					$array[$row->ID] = $row;
					$array[$row->ID]->children = array();
					
					$this->_menuData($array[$row->ID]->children,$row->ID,$selected,$collection);
				}
			}	
	    }
	    
	   /**
       * Get Menu tree
       * 
       * @param mixed $formats
       * @param mixed $isRecursive : when set to true, if current item does have child , we will table menu from its parent
       */
       public function menuFrom($selected=-1,$isRecursive=false,$except=array()){

           $query = "SELECT post_name,post_title, ID,post_parent,menu_order FROM {$this->db->posts} WHERE ID<>{$this->post->ID} and post_parent={$this->post->ID} and post_type='{$this->post->post_type}'";
           $rs = $this->db->get_results($query);
		   

           if(is_array($rs) && count($rs)>0){
               $arr = array();
			   $this->_menuData($arr,$this->post->ID,$selected,$rs);
               return $arr;
           }else{
				$query = "SELECT post_name,post_title, ID,post_parent,menu_order FROM {$this->db->posts} WHERE post_parent={$this->post->post_parent} and post_type='{$this->post->post_type}' and ID not in(".(count($except)>0?implode(",",$except):-1).")";

				$rs = $this->db->get_results($query);
				if(is_array($rs) && count($rs)>0){
				   $arr = array();
				   $this->_menuData($arr,$this->post->post_parent,$selected,$rs);
				   return $arr;
			   }
		   }
		   return null;
       } 
		
		 /**
       * Get list Menu tree
       * 
       * @param mixed $formats
       */
       public function menuTop($formats=null,$hasContainer=true){

            $def = array_merge($def,!is_array($formats)?array() : $formats);
			$query = "SELECT post_name,post_title, ID,post_parent,menu_order FROM {$this->db->posts} WHERE  post_parent=0 and post_type='{$this->post->post_type}'";
           $rs = $this->db->get_results($query);
           $s="";
           if(is_array($rs) && count($rs)>0){
		       //print_r($this->lists);
		       $s.= $hasContainer ? "<ul>":"<ul class=\"{$def["class"]}\">";
               foreach($rs as $row){
					$s.='<li><a href="'.site_url("{$row->post_name}.html").'">{$row->post_title}</a></li>';
               }
			   $s.="</ul>";
           }
		   
       } 
       
		///////////////////////////// End Menu /////////////////////////////
    }

?>