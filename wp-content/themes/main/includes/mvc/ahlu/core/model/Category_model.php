<?php
    class Category_model extends Model {
         protected $category ;
         protected $hasChild = false;
         public $post_type = null;
         
         public $post_status = "publish";
         protected $pagation = null;
		 
         private $_slideshow = null;
		 
         public $taxonomy = null;
         public $children =null;
         public $posts =null;
         public $parent =null;
         public $lists =null;
         public $paging =null;
         
		 //store seo
		 public $seo = null;
		 
         public function __construct(){
			parent::__construct();
            $this->pagation = Ahlu::Library("Ahlu_WP_Pagation",$this->db);
            return $this; 
         }
         ////////////////////////WP////////////////////////////////
		 /*
		 * List all post from WP
		 */
		 public function get_posts($param =null){
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
         ////////////////////////End WP////////////////////////////////
         public function load($cat=null){
			if($cat==null) return;
			
			if($this->lists==null){
				$this->lists = $this->categories();	
			}
	
			
			$this->_slideshow = Ahlu::Library("Ahlu_WP_Slideshow",$this->db);

			 if(!empty($cat)){
				if(is_numeric($cat)){ //from term id
					//find
					foreach($this->lists as $k=>$obj){
						if($obj->term_id==$cat){
							$this->category = $obj;
							$this->taxanomy = $this->category->taxanomy;
							break;
						}
						if($obj->term_id==$cat){
							$this->category = $obj;
							$this->taxanomy = $this->category->taxanomy;
							break;
						}
					}
					//
					
					$this->_slideshow->setID(isset($this->category->term_id)?$this->category->term_id:null);
					$this->_slideshow->post_type = isset($this->category->post_type)?$this->category->post_type:$this->post_type;
					$this->_slideshow->post_status = isset($this->category->post_publish)?$this->category->post_publish:$this->post_status;
				
				}else if(is_object($cat)){ //from object
					$this->category = $cat;

					$this->_slideshow->setID(isset($this->category->term_id)?$this->category->term_id:null);
					$this->_slideshow->post_type = str_replace("_ahlu","",isset($this->category->taxonomy)?$this->category->taxonomy:$this->post_type);
					$this->_slideshow->post_status = isset($this->category->post_publish)?$this->category->post_status:$this->post_status;
					
					//update taxanomy
					$this->taxanomy = $cat->taxanomy;
				}else if(is_string($cat)){
					//find
					foreach($this->lists as $k=>$obj){
						if($obj->name==$cat || $obj->slug==$cat){
							$this->category = $obj;
							$this->taxanomy = $this->category->taxanomy;
							break;
						}
					}

					$this->_slideshow->setID(isset($this->category->term_id)?$this->category->term_id:null);
					$this->_slideshow->post_type = isset($this->category->post_type)?$this->category->post_type:$this->post_type;
					$this->_slideshow->post_status = isset($this->category->post_publish)?$this->category->post_publish:$this->post_status;

				}else{
				
					trigger_error("Invalid type.");
				}
	
				//set SEO
				$this->seo = Ahlu::Library("Ahlu_SEO");
				$this->seo->setTitle($this->category->name);
				$this->seo->setKeyword($this->category->name.','.$this->category->description);
				$this->seo->setDescription($this->category->name.','.$this->category->description);
				$this->seo->setCanonical(site_url($this->category->slug));

			  }else{
				// we dont know the term id, so we ask database to find term root
				$db = $this->db;
				 $query = "SELECT * from $db->term_taxonomy where taxonomy='{$this->taxonomy}'";
				$a = $db->get_results($query);
                if(is_array($a) && count($a)>0){
					$a = $a[0];

					$this->_slideshow->setID(isset($a->term_id)?$a->term_id:null);
					$this->_slideshow->post_type = isset($this->post_type)?$this->post_type:null;
					$this->_slideshow->post_status = isset($this->post_status)?$this->post_status:null;
					
					//set seo
					$this->seo = Ahlu::Library("Ahlu_SEO");
					$this->seo->setTitle($this->post_type);
					$this->seo->setKeyword($this->post_type.','.$a->description);
					$this->seo->setDescription($this->post_type.','.$a->description);
					$this->seo->setCanonical(site_url($this->post_type.".html"));
				}else{
					//beacuse taxanomy has not created
					
					//set seo
					$this->seo = Ahlu::Library("Ahlu_SEO");
					$this->seo->setTitle($this->post_type);
					$this->seo->setKeyword($this->post_type);
					$this->seo->setDescription($this->post_type);
					$this->seo->setCanonical(site_url($this->post_type.".html"));
				}
			  }
            return $this;
         }
         public function getMe(){
           return $this->category? $this->category:(object)array("name"=>$this->post_type);
         }
		 /**
		 * Get SEO meta string
		 *
		 */
		 public function SEO(){
		 	if($this->seo==null){ 
				$this->seo = Ahlu::Library("Ahlu_SEO"); 
				return $this->seo;
			}	
			return $this->seo->Meta();
		 }
		 /**
		 * list all posts from this category , not post type
		 *
		 */
		 public function toList($limit=10,$page=1,$isPaged=false){

            $this->pagation->setPage($page);
            $this->pagation->setLimit($limit);

            mysql_query("SET SESSION group_concat_max_len = 1000000;");

			$query = "Select post.*,metap.meta from  ( SELECT group_concat(meta) as meta,mp.ID  from (SELECT

                        CASE meta_value

                            WHEN  NULL THEN CONCAT(meta_key, '=', \"null\")

			   ELSE CONCAT(meta_key, '=', meta_value)

                        END AS meta,post_id as ID

                    FROM {$this->db->postmeta}

                ) as mp GROUP BY ID )  as metap, ( select post.* from {$this->db->posts} post join {$this->db->term_relationships} t on t.object_id=post.ID where t.term_taxonomy_id={$this->category->term_taxonomy_id}) as post where metap.ID= post.ID and post.post_status='{$this->post_status}' order by post.post_date desc";

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
		
		/**
		 * list all posts from this category and sub category
		 * 
		 */
		 public function toPostAndCate($limit=100,$page=1,$isPaged=false){

            $this->pagation->setPage($page);
            $this->pagation->setLimit($limit);
			
			
			//get all child
			$id_find = array();
			if(!$this->hasChild()){
				$id_find[$this->category->term_taxonomy_id] = 	$this->category;
			}else{
				foreach($this->children as $item){
					$id_find[$item->term_taxonomy_id]=$item;
				}
			}
			
            mysql_query("SET SESSION group_concat_max_len = 1000000;");

			$query = "Select post.*,metap.meta from  ( SELECT group_concat(meta) as meta,mp.ID  from (SELECT

                        CASE meta_value

                            WHEN  NULL THEN CONCAT(meta_key, '=', \"null\")

			   ELSE CONCAT(meta_key, '=', meta_value)

                        END AS meta,post_id as ID

                    FROM {$this->db->postmeta}

                ) as mp GROUP BY ID )  as metap, ( select p.* from ( select t.object_id as ID from {$this->db->term_relationships} t where t.term_taxonomy_id in(".implode(",",array_keys($id_find)).")) as find join {$this->db->posts} p on p.ID=find.ID where p.post_status='{$this->post_status}' order by p.post_date desc) as post where post.ID=metap.ID";

			//echo $query;
			//die();
			
			$this->pagation->excute($query); 

			if($this->pagation->hasData){

				$o = new stdClass();

				$o->link = $this->pagation->PageLinks(true);
				$o->categories = count($id_find)>1?$id_find:null;
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
		
		 /**
		 * list all posts from post_type
		 *
		 */
		 public function listPostType($limit,$page=1,$isPaged=false){

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
			//die();
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
				
		
			
		/**
		 * list last test post from post type
		 * $limit : 10
		 * $page : 1
		 * $fields : array to call all meta_key
		 * $limit : 10
		 */
		 public function listDynamic($limit=10,$page=1,$fields=null,$isCate=false){

            $this->pagation->setPage($page);
            $this->pagation->setLimit($limit);
			
			//check for finding dynamic fields
			$infoDynamicfield = null;
			if(is_array($fields) && count($fields)>0){
				$field = array();
				foreach($fields as $i=>$v){
					if($i==0)
						$field[] = "postmeta.meta_key='{$i}' and postmeta.meta_value={$v}";
					else
						$field[] = "postmeta.meta_key='{$v}'";
				}
			    
				
				$infoDynamicfield = "(SELECT group_concat(meta) as meta,tb1.ID  from 
					(SELECT       CASE tb.meta_value    WHEN  NULL THEN CONCAT(tb.meta_key, '=', \"null\") ELSE CONCAT(tb.meta_key, '=', tb.meta_value)  END AS meta,tb.post_id as ID 
					FROM (SELECT postmeta.*  FROM 
					{$this->db->postmeta} postmeta,
					(SELECT postmeta.post_id   FROM {$this->db->postmeta}  postmeta where ".implode(" and ",$field)." ) popular
					   Where postmeta.post_id = popular.post_id
					)as tb
					  ) as tb1 GROUP BY ID
				  ) as mp ";
			}else{
				$infoDynamicfield = "( SELECT group_concat(meta) as meta,mp.ID  from (SELECT

                        CASE meta_value

                            WHEN  NULL THEN CONCAT(meta_key, '=', \"null\")

			   ELSE CONCAT(meta_key, '=', meta_value)

                        END AS meta,post_id as ID

                    FROM {$this->db->postmeta}

                ) as mp GROUP BY ID )  as mp ";
			}
			
			
            mysql_query("SET SESSION group_concat_max_len = 1000000;");
			if($isCate){
				$query = "select mp.meta,post.*  from {$infoDynamicfield} , {$this->db->posts} post,{$this->term_relationships} term_r 
				where post.ID=term_r.object_id and mp.ID= post.ID and post.post_status='{$this->post_status}' and post.post_type='{$this->post_type}'
				order by post.post_date desc";
			}else{
				$query = "select mp.meta,post.*  from {$infoDynamicfield} , {$this->db->posts} post 
				where mp.ID= post.ID and post.post_status='{$this->post_status}' and post.post_type='{$this->post_type}'
				order by post.post_date desc";
			}
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

		/**
		 * List view
		 */
		 public function viewOnPost($limit=10,$page=1,$desc="desc"){
			
			$this->pagation->setPage($page);
            $this->pagation->setLimit($limit);
			
			//check for finding dynamic fields

			$infoDynamicfield = "(SELECT group_concat(meta) as meta,tb1.ID  from 
				(SELECT       CASE tb.meta_value    WHEN  NULL THEN CONCAT(tb.meta_key, '=', \"null\") ELSE CONCAT(tb.meta_key, '=', tb.meta_value)  END AS meta,tb.post_id as ID 
				FROM (SELECT postmeta.*  FROM 
				{$this->db->postmeta} postmeta,
				(SELECT postmeta.post_id   FROM {$this->db->postmeta}  postmeta where postmeta.meta_key='_view_post' order by postmeta.meta_value desc) popular
				   Where postmeta.post_id = popular.post_id
				)as tb
				  ) as tb1 GROUP BY ID
			  ) as mp ";
			
            mysql_query("SET SESSION group_concat_max_len = 1000000;");

			$query = "select mp.meta,post.*  from {$infoDynamicfield} , {$this->db->posts} post 
			where mp.ID= post.ID and post.post_status='{$this->post_status}' and post.post_type='{$this->post_type}'";
			
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

        /**
       * List all childen post/page for each category with paagtion
       *   
       */ 
        public function hasPost($array=null){
			$this->pagation->setPage(isset($array["page"])? $array["page"]: 1);
			$this->pagation->setLimit(isset($array["limit"])? $array["limit"]: 10);
            $this->pagation->excute($this->_slideshow->getQueryString()); 

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
					$this->posts = $o;
					return true;
				}
            return false;
        }
        
        /**
        * Get sub categories
        * 
        * @param mixed $array
        */
        public function hasChild($array=null){
            $db = $this->db;
             $query = "SELECT t1.* from $db->terms as t1 , $db->term_taxonomy as t2 where t1.term_id = t2.term_id and t2.parent={$this->category->term_id}";
            //echo $query;
			//die();
			
            $a = $db->get_results($query);
			
            if(is_array($a) && count($a)>0){
                $this->children = $a;
                return true;
            }
            return false;
        }
       
	   /**
        * Get sub categories
        * 
        * @param mixed $array
        */
        public function hasTag($tag){
			if(is_string($tag)) $tag = array($tag);
            foreach (get_the_tags() as $tag)
			{
				
				echo "<option value=\"";
				echo get_tag_link($tag->term_id);
				echo "\">".$tag->name."</option>\n";
			}
	
            return false;
        }
		
       /**
       * List all caterory by taxanomy or specific category
       * 
       */
       public function categories($array=null){
           $db = $this->db;
		   if($this->taxanomy){
			$and = "and t2.taxanomy='{$this->taxanomy}'";
		   }
             $query = "SELECT t1.*,t2.* from $db->terms as t1 , $db->term_taxonomy as t2 where t1.term_id = t2.term_id $and";
            //echo $query;
			
            $a = $db->get_results($query);
               return is_array($a) && count($a)>0 ? $a : null; 
       }
       /*
	   * Check this category has parent
	   */
       public function hasParent($array=null){
			
            $db = $this->db;
             $query = "SELECT t2.* from  (SELECT t2.parent as ofparent from $db->terms as t1 , $db->term_taxonomy as t2 where t1.term_id = t2.term_id  and t1.term_id={$this->category->term_id}) as t , $db->terms as t2 where t2.term_id =t.ofparent";
             //echo $query;
            $a = $db->get_results($query);
            //print_r($a);
            if(count($a)>0){
               $this->parent = $a[0];
               return true;
            }
          return false;     
		    
       }
	   
	   public function getParent(){
		  if($this->category){
			   //list all submenu from this menu
               $id = $this->category->term_id;
			   
               $tables = array();
               foreach($this->lists as $row){
                   if($row->term_id==$this->category->parent){
						return $row;
				   }
					
               }
			   
           }
          return null;     
       }
	   /**
	   * Find parent cate from level
	   */
       public function getParentLevel($level=1){
            if($this->category){
				
           	   //list all submenu from this menu
               $id = $this->category->term_id;
			   
               $tables = array();
               foreach($this->lists as $row){
                   $o = new stdClass();
                   $o->id = $row->term_id;
                   $o->title = $row->name;
                   $o->link = site_url("/".$row->slug);
                   $o->parent_id = $row->parent;
                   $tables[] = $o;
               }
			   //print_r($tables);
               $menu = Ahlu::Library("MenuTree",$tables);
               $menu->selectedId = $id;
			   print_r($menu);
               $item = $menu->trackingTree();
			  if(is_array($item)){
				return $item[$level-1];
			  }
           }
          return null;     
       }
      /**
      * Get breadCrumbs
      *  
      * @param mixed $home
      * @param mixed $ch
      */
       public function breadCrumbs($home="Home",$url ="/",$ch=" &raquo; ",$except=array(),$return=false,$more=null){
           $uri = $this->_breadCrumb($this->category->term_id);
           $crumbs = $uri==null?array():$uri;
			
			
		  
           
		   
		   $except = array_map(function($v){
				return strtolower($v);
			},$except);
			
			//filter
		   foreach($crumbs as $i=>$p) {

               //only in post type as seo
			   if(in_array(strtolower($p[1]),$except)) unset($crumbs[$i]);
		   
           }
		   
		    //add more
			if(is_array($more)){
				$crumbs = array_merge($crumbs,$more);
			}
           array_push($crumbs,array($home,$url));
           $crumbs = array_reverse($crumbs);
		   
		  if($return) return $crumbs;
		  $s=array();
           foreach($crumbs as $i=> $p) {
               $l = htmlspecialchars($p[1]=="/"?"home":$p[0]);
               //only in post type as seo
               $s[]='<a href="'.urldecode($p[1]).'" '.($i==count($crumbs)-1 ? 'class="current"':"" ).' title="'.$l.'">'.ucwords($l).'</a>';
           }
           
           return "<div class=\"breadCrumbs\" style=\"margin-bottom: 5px;\">".implode($ch,$s)."</div>";
       }

       private function _breadCrumb($id,&$l=array()){
           $s = $home;
           $fme = null;
           if(!is_array($this->lists)) return null;
           foreach($this->lists as $k=>$a){
               if($a->term_id==$id){
                   $fme = $a;
                   break;
               }
           }
           if($fme != null){
              //print_r($fme);            
               $p = $this->_parent($this->lists,$fme->parent);
                $l[]=array($fme->name,$fme->slug);    
              if($p!=null){
                $s= $this->_breadCrumb($p->term_id,$l);
              }
           }
           return $l;
       }
       
       private function  _parent($arr,$id_parent){
           foreach($arr as $a){
               if($a->term_id==$id_parent){
                   return $a ;
               }
           }
           return null;
       } 
///////////////////////////////////////////////////////////////////////////
		 /**
       * List all childen post/page for each category
       *   
       */ 
        public function hasPostcategory($limit=10,$page=1){
	
			if($this->category==null) return false;
			
            $pagation = $this->postPaging($limit,$page);
			//check this ouput is process by driven class?
			if(!$pagation instanceof Ahlu_WP_Pagation){
				return $pagation;
			}
			//print_r($pagation);
			
            if($pagation->hasData){
			
					$o = new stdClass();

					$o->link = $pagation->PageLinks(true);

					$o->data = $pagation->PageData();

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
					$this->posts = $o;
					return true;
				}
			return false;
	   }
        /**
        * Pagation
        * 
        * @param mixed $page
        * @param mixed $limit
        */
         public function postPaging($limit=10,$page=1){
            $this->pagation->setPage($page);
            $this->pagation->setLimit($limit);

            $this->pagation->excute($this->_slideshow->getQueryString()); 
            return $this->pagation;
        }	   
///////////////////////////////////////////////////////////////////////////	   
       /**
       * Get Menu tree
       * Note : Reset id selected if param is set, by default from current category
       * @param mixed $formats
       */
       public function menu($formats=null,$selected = null,$f=null,$echo=false){
           $s = "";
           $def = array(
            "class"=> "nav",
            "classUL"=>""
           );
           if($formats!=null && is_array($formats)){
               $def = array_merge($def,$formats);
           }
           	
			if($this->lists==null){
				$this->lists = $this->categories();	
			}
           if($this->category){
           	   //list all submenu from this menu
               $id = $this->category->term_id;
               $tables = array();
               foreach($this->lists as $row){
				   if($id==$row->parent){
					   $o = new stdClass();
					   $o->id = $row->term_id;
					   $o->title = $row->name;
					   $o->description = $row->description;
					   $o->link = site_url("/".$row->slug);
					   $o->parent_id = $row->parent;
					   $tables[] = $o;
				   }
               }

			   if($echo){
				 return $tables;
			   }
			   
			   //print_r($tables);
               $menu = Ahlu::Library("MenuTree",$tables);
               $menu->selectedId = $id;
               $menu->setCallback($f);

               $s = $menu->toLI($selected != null ? $selected:$id);

           }else{
				foreach($this->lists as $row){
                   $o = new stdClass();
                   $o->id = $row->term_id;
                   $o->title = $row->name;
				   $o->description = $row->description;
                   $o->link = site_url("/".$row->slug);
                   $o->parent_id = $row->parent;
                   $tables[] = $o;
               }
           		$menu = Ahlu::Library("MenuTree",$tables);
                $menu->setCallback($f);
               $s = $menu->toLI();
           }

           return $s;
       } 
	   
	   public function sitemap($formats=null){
           $s = "";
           $def = array(
            "class"=> "nav",
            "classUL"=>""
           );
           if($formats!=null && is_array($formats)){
               $def = array_merge($def,$formats);
           }
           
		   $id = 0;
		   $tables = array();
		   foreach($this->lists as $row){
			   $o = new stdClass();
			   $o->id = $row->term_id;
			   $o->title = $row->name;
			   $o->link = site_url("/".$row->slug);
			   $o->parent_id = $row->parent;
			   $tables[] = $o;
		   }
		   
		    if($echo){
				 return $tables;
			   }
		   //print_r($tables);
		   $menu = Ahlu::Library("MenuTree",$tables);
		   $menu->selectedId = $id;
		   
		   $s = $menu->toLI($id);

           return '<div class="'.$def["class"].'">'.$s."</div>";
		}
	   /**
       * Get Menu tree from parent if is enable
       * 
       * @param mixed $formats
       */
       public function menuFrom($hasParent=false,$formats=null){
		   
           $s = "";
           $def = array(
            "class"=> "nav",
            "classUL"=>""
           );
           if($formats!=null && is_array($formats)){
               $def = array_merge($def,$formats);
           }
           if($this->lists==null){
				$this->lists = $this->categories();	
			}
           if($this->category){
			   $id = !$hasParent ? $this->category->term_id : ($this->hasParent()? $this->parent->term_id: $this->category->term_id);
               $tables = array();
               foreach($this->lists as $row){
                   $o = new stdClass();
                   $o->id = $row->term_id;
                   $o->title = $row->name;
                   $o->link = site_url($row->slug);
                   $o->parent_id = $row->parent;
                   $tables[] = $o;
               }
			   //print_r($tables);
               $menu = Ahlu::Library("MenuTree",$tables);
               $menu->selectedId = $this->category->term_id;
               
               $s = $menu->toLIFrom($id);
           }

           return '<div class="'.$def["class"].'">'.$s."</div>";
       } 
	   /**
       * Get Menu tree from $id
       * if category is binded , selectedId is current category , else is 0 level root
       * @param mixed $formats
       */
		public function getMenuFrom($id,$formats=null,$echo=false,$f=null){
		   
           $s = "";
           $def = array(
            "class"=> "nav",
            "classUL"=>""
           );
           if($formats!=null && is_array($formats)){
               $def = array_merge($def,$formats);
           }
           if($this->lists==null){
				$this->lists = $this->categories();	
			}
		   $tables = array();
		   foreach($this->lists as $row){
			   $o = new stdClass();
			   $o->id = $row->term_id;
			   $o->title = $row->name;
			   $o->link = site_url($row->slug);
			   $o->parent_id = $row->parent;
			   $tables[] = $o;
		   }
		   //print_r($tables);
			 $menu = Ahlu::Library("MenuTree",$tables);   
			 $menu->setCallback($f);
			 
           if($this->category){
               $menu->selectedId = $this->category->term_id;
           }
			if($echo) return $menu;
			$s = $menu->toLI($id);
		   
           return '<div class="'.$def["class"].'">'.$s."</div>";
        } 
		
		 /**
       * Get list Menu tree
       * 
       * @param mixed $formats
       */
        public function menuTop($formats=null,$hasContainer=true){
		   /*
           $s = "";
           $def = array(
            "class"=> "nav",
            "classUL"=>""
           );
            $def = array_merge($def,!is_array($formats)?array() : $formats);

           $s="";
           if(count($this->lists)>0){
		       //print_r($this->lists);
		       $s.= $hasContainer ? "<ul>":"<ul class=\"{$def["class"]}\">";
               foreach($this->lists as $row){
				   if((int)$row->parent == 0 && $this->taxonomy==$row->taxonomy){
						$s.="<li><a href='".site_url("{$row->slug}")."'>{$row->name}</a></li>";
				   }
               }
			   $s.="</ul>";
           }
		   
           return $formats !=null ? (!$hasContainer? $s:'<div class="'.$def["class"].'">'.$s."</div>") : $s;*/
		    $data = array();
			
			if($this->lists==null){
				$this->lists = $this->categories();	
			}
		   if(count($this->lists)>0){
               foreach($this->lists as $row){
				   if((int)$row->parent == 0 && $this->taxonomy==$row->taxonomy){
						$data[]=$row;
				   }
               }
           }
		   return $data;
       } 
       /**
       * Get archive
       * 
       * @param mixed $formats
       */
       public function archive($limit=10,$page=1){
			$db = $this->db;
			/*Select year,month,COUNT( id ) as post_count  From (SELECT DISTINCT MONTH( post_date ) AS month , YEAR( post_date ) AS year ,ID FROM woocommerce_50886_posts 
WHERE post_status = 'publish' and post_date <= now( ) and post_type = 'blog' 
ORDER BY post_date DESC) total 
GROUP BY month , year 

*/

				 //set seo
				$this->seo->setTitle($this->post_type.", archive {$this->post_type}");
				$this->seo->setKeyword($this->post_type.", archive, archive {$this->post_type}");
				$this->seo->setDescription($this->post_type.", archive {$this->post_type}");

				$this->pagation->setPage($page);
				$this->pagation->setLimit($limit);
				
				$query = "Select total.year,total.month,p.post_name,p.post_title  From (SELECT DISTINCT MONTH( post_date ) AS month , YEAR( post_date ) AS year ,ID FROM {$db->posts} 
	WHERE post_status = '{$this->post_status}' and DATE(post_date) <= DATE(NOW()) and post_type = '{$this->post_type}' 
	ORDER BY post_date DESC) total join {$db->posts} p on p.ID = total.ID";
				
				$this->pagation->excute($query); 

				if($this->pagation->hasData){
					$a = (array)$this->pagation->PageData();
					$o = new stdClass();
					
						$data = array();
					   foreach($a as $month){ 
						if(!isset($data[$month->year])){
							$data[$month->year] = array();
						}
						
						if(!isset($data[$month->year][$month->month])){
							$data[$month->year][$month->month] = array();
						}
						$data[$month->year][$month->month][] = array("url"=>$month->post_name,"title"=>$month->post_title);
						}
						
						
					$o->data = $data;
					$o->link = $this->pagation->PageLinks(true);

						return $o;
						
				}
			return null;
	   }
	   
	   /**
       * Get archive by Year
       * 
       * @param mixed $formats
       */
       public function archiveByYear($year=0,$limit=10,$page=1){
			if($year>0){
				//set seo
				$this->seo->setTitle($this->post_type.", archive {$this->post_type}, archive {$this->post_type} {$year}");
				$this->seo->setKeyword($this->post_type.", archive, archive {$this->post_type}, archive {$this->post_type} {$year}");
				$this->seo->setDescription($this->post_type.", archive {$this->post_type}, archive {$this->post_type} {$year}");
				$this->seo->setCanonical(site_url("archive-".$this->post_type."-{$year}.html"));
					
				$this->pagation->setPage($page);

				$this->pagation->setLimit($limit);

				mysql_query("SET SESSION group_concat_max_len = 1000000;");

				$query = "Select post.*,metap.meta from  ( SELECT group_concat(meta) as meta,mp.ID  from (SELECT

							CASE meta_value

								WHEN  NULL THEN CONCAT(meta_key, '=', \"null\")

				   ELSE CONCAT(meta_key, '=', meta_value)

							END AS meta,post_id as ID

						FROM {$this->db->postmeta}

					) as mp GROUP BY ID )  as metap, {$this->db->posts} post where metap.ID= post.ID and post.post_type='{$this->post_type}' and post.post_status='{$this->post_status}' and YEAR(post_date)={$year} order by post.post_date";

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
			}
			return null;
	   }
	   
	   /**
       * Get archive by Year and Month
       * 
       * @param mixed $formats
       */
       public function archiveByYearMonth($year=0,$month=0,$limit=10,$page=1){
			if($year>0 && $month>0){
				//set seo
				$this->seo->setTitle($this->post_type.", archive {$this->post_type}, archive {$this->post_type} {$year} {$month}");
				$this->seo->setKeyword($this->post_type.", archive, archive {$this->post_type}, archive {$this->post_type} {$year}, archive {$this->post_type} {$year} {$month}");
				$this->seo->setDescription($this->post_type.", archive {$this->post_type}, archive {$this->post_type} {$year} {$month}");
				$this->seo->setCanonical(site_url("archive-".$this->post_type."-{$year}-{$month}.html"));

				$this->pagation->setPage($page);

				$this->pagation->setLimit($limit);

				mysql_query("SET SESSION group_concat_max_len = 1000000;");

				$query = "Select post.*,metap.meta from  ( SELECT group_concat(meta) as meta,mp.ID  from (SELECT

							CASE meta_value

								WHEN  NULL THEN CONCAT(meta_key, '=', \"null\")

				   ELSE CONCAT(meta_key, '=', meta_value)

							END AS meta,post_id as ID

						FROM {$this->db->postmeta}

					) as mp GROUP BY ID )  as metap, {$this->db->posts} post where metap.ID= post.ID and post.post_type='{$this->post_type}' and post.post_status='{$this->post_status}' and YEAR(post_date)={$year} and MONTH(post_date)={$month} order by post.post_date";

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
			}
			return null;
	   }
	    /**
       * Get archive by Year and Month and Date
       * 
       * @param mixed $formats
       */
       public function archiveByYearMonthDate($year=0,$month=0,$date=0,$limit=10){
			if($year>0 && $month>0 && $date>0){
				//set seo
				$this->seo->setTitle($this->post_type.", archive {$this->post_type}, archive {$this->post_type} {$year} {$month} {$date}");
				$this->seo->setKeyword($this->post_type.", archive, archive {$this->post_type}, archive {$this->post_type} {$year} {$month}, archive {$this->post_type} {$year} {$month} {$date}");
				$this->seo->setDescription($this->post_type.", archive {$this->post_type}, archive {$this->post_type} {$year} {$month}, archive {$this->post_type} {$year} {$month} {$date}");
				$this->seo->setCanonical(site_url("archive-".$this->post_type."-{$year}-{$month}-{$date}.html"));
				


				//$this->pagation->setPage($page);

				$this->pagation->setLimit($limit);

				mysql_query("SET SESSION group_concat_max_len = 1000000;");

				$query = "Select post.*,metap.meta from  ( SELECT group_concat(meta) as meta,mp.ID  from (SELECT

							CASE meta_value

								WHEN  NULL THEN CONCAT(meta_key, '=', \"null\")

				   ELSE CONCAT(meta_key, '=', meta_value)

							END AS meta,post_id as ID

						FROM {$this->db->postmeta}

					) as mp GROUP BY ID )  as metap, {$this->db->posts} post where metap.ID= post.ID and post.post_type='{$this->post_type}' and post.post_status='{$this->post_status}' and YEAR(post_date)={$year} and MONTH(post_date)={$month} and DATE(post_date)={$date} order by post.post_date";

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
			}
			return null;
	   }
	   
	   
	   public function searchPostType($q,$limit=10,$page=1){
		
			//set SEO
 
			$this->seo->setTitle("Search {$q}");
			$this->seo->setKeyword("Search {$q}");
			$this->seo->setDescription("Search {$q}");
			
            $this->pagation->setPage($page);
            $this->pagation->setLimit($limit);

            mysql_query("SET SESSION group_concat_max_len = 1000000;");
			
			$query ="SELECT p.*,metap.meta
				FROM (SELECT post.* FROM {$this->db->posts} post,{$this->db->term_relationships} r,{$this->db->term_taxonomy} cate,{$this->db->terms} as t WHERE post.ID=r.object_id and r.term_taxonomy_id = cate.term_taxonomy_id and cate.term_id = t.term_id and post.post_type='{$this->post_type}' and (MATCH(post.post_title) AGAINST ('".trim($q)."') or post.post_title like '%".trim($q)."%' or post.post_content like '%".trim($q)."%' ) order by post.post_title ASC) as p
				left join ( SELECT group_concat(meta) as meta,mp.ID from (SELECT CASE meta_value WHEN NULL THEN CONCAT(meta_key, '=', 'null') ELSE CONCAT(meta_key, '=', meta_value) END AS meta,post_id as ID FROM {$this->db->postmeta} ) as mp GROUP BY ID ) as metap on p.ID=metap.ID";
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
	}
?>