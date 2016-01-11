 <?php
    /**
    *  Model Ecommercial in plugin
    */
    class Media_model extends Model
    {
	     public $limit = array(0,6);
		 protected $post_type ='attachment';
		 protected $post_status = "inherit";
		 protected $slideshow = null;
		 
         public function __construct(){
            parent::__construct();
			
			$this->slideshow = Ahlu::Library("Ahlu_WP_Slideshow",$this->db);
			$this->slideshow->post_type=$this->post_type;
			$this->slideshow->post_status=$this->post_status;
			
            return $this; 
         }
         
        //////////////// other method from plugin
		
		public function byNamePost($name){
			
			$query = "SELECT post.*,metap.meta FROM ( SELECT group_concat(meta) as meta,mp.ID  from (SELECT
                        CASE meta_value
                            WHEN  NULL THEN CONCAT(meta_key, '=', 'null')
			   ELSE CONCAT(meta_key, '=', meta_value)
                        END AS meta,post_id as ID
                    FROM {$this->db->postmeta}
                ) as mp GROUP BY ID )  as metap, {$this->db->posts} post
 WHERE metap.ID=post.ID  and post.post_status='inherit' and post.post_type='attachment'  and  post.post_parent  in (select ID from {$this->db->posts} where post_name='".strtolower($name)."')
order by post.ID DESC
"; 
		   //echo $query ;
		   
		   mysql_query("SET SESSION group_concat_max_len = 20480");
		   $a =  $this->db->get_results($query);  
		   
		   if(is_array($a) && count($a)>0){
				foreach($a as $obj){
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
			}
			return $a;
		}
		
		public function byTitlePost($title){
			
			$query = "SELECT post.*,metap.meta FROM ( SELECT group_concat(meta) as meta,mp.ID  from (SELECT
                        CASE meta_value
                            WHEN  NULL THEN CONCAT(meta_key, '=', 'null')
			   ELSE CONCAT(meta_key, '=', meta_value)
                        END AS meta,post_id as ID
                    FROM {$this->db->postmeta}
                ) as mp GROUP BY ID )  as metap, {$this->db->posts} post
 WHERE metap.ID=post.ID  and post.post_status='inherit' and post.post_type='attachment'  and  post.post_parent  in (select ID from {$this->db->posts} where post_name='{$title}')
order by post.ID DESC
"; 
		   //echo $query ;
		   
		   mysql_query("SET SESSION group_concat_max_len = 20480");
		   $a =  $this->db->get_results($query);  
		   
		   if(is_array($a) && count($a)>0){
				foreach($a as $obj){
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
			}
			return $a;
		}
		
		public function byNamePostID($id){
			if(intval($id))
			{
				$query = "SELECT post.*,metap.meta FROM ( SELECT group_concat(meta) as meta,mp.ID  from (SELECT
							CASE meta_value
								WHEN  NULL THEN CONCAT(meta_key, '=', 'null')
				   ELSE CONCAT(meta_key, '=', meta_value)
							END AS meta,post_id as ID
						FROM {$this->db->postmeta}
					) as mp GROUP BY ID )  as metap, {$this->db->posts} post
	 WHERE metap.ID=post.ID  and post.post_status='inherit' and post.post_type='attachment'  and  post.post_parent  in (select ID from {$this->db->posts} where ID={$id})
	order by post.ID DESC
	"; 
			   //echo $query ;
			   
			   mysql_query("SET SESSION group_concat_max_len = 20480");
			   $a =  $this->db->get_results($query);  
			   
			   if(is_array($a) && count($a)>0){
						foreach($a as $obj){
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
					}
					return $a;
				}
			return null;
		}
		
		public function byName($name){
			if(!is_string($name)) trigger_error("The param must be string type. in line:".__LINE__);
			
			
			$this->slideshow->setName($name);
			$data = $this->slideshow->getSlideShow($this->limit);
			
			//echo $this->slideshow->getQueryString();
			//die();
			$thumbnails = array();
			foreach($data as $obj){
				$o = new Thumbnail();
				$o->setID($obj->ID);
				$o->setTitle($obj->post_title); //alt
				$o->setContent($obj->post_content); //description
				$o->setSlug($obj->post_name);
				$o->setDate($obj->post_date);
				$o->setImage($obj->guid);
				$o->setLink($obj->post_excerpt); //caption
				
				$meta = explode(",",$obj->meta);
				foreach($meta as  $v){
					$k=explode("=",ltrim($v,"_"));
					$obj->{$k[0]} =$k[1];
				}
				unset($obj->meta);
				unset($meta);
				
				if(isset($obj->wp_attached_file)){
					$o->setFile($obj->wp_attached_file);
				}
				if(isset($obj->wp_attachment_metadata)){
					$o->setThumbs(StringUtil::is_serialized($obj->wp_attachment_metadata) ? unserialize($obj->wp_attachment_metadata) : $obj->wp_attachment_metadata);
				}
				$thumbnails[$obj->post_name] = $o;
			}
			return $thumbnails;
		}
		public function bySlug($slug){
			if(!is_string($name)) trigger_error("The param must be string type. in line:".__LINE__);
			
			$this->slideshow->setSlug($slug);
			return $this->slideshow->getSlideShow($this->limit);
		}
		public function byID($id){
			if(!is_numeric($id)) trigger_error("The param must be int type. in line:".__LINE__);
			
			$this->slideshow->setID($id);
			return $this->slideshow->getSlideShow($this->limit);
		}
		
		
    }
    ?>