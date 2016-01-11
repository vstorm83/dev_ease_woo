<?php
class Ecommercial_woo_item_model extends Post_model{
	protected $post;
	public $product;
	
	public function __construct(){
		parent::__construct();
		
		$this->post_type = "product";
		return $this; 
    }
	public function load($post){
		$this->post = $post;

		$this->product = new Ahlu_woo_product($post->ID);
		return $this; 
	}
	public function getMe(){
		return $this->post;
	}
	
	public function gallery() {

		$attachments = explode(",",$this->post->product_image_gallery);
		$a = null;
		if(is_array($attachments) && count($attachments)>0){
			$a = array();
			foreach($attachments as $i=> $attachment){
				$a[] = wp_get_attachment_url($attachment,'large');
			}
			
		}
		return $a;
	}
	/*
	* Override
	* Get category
	*/
	public function hasCategory(){
			if($this->category) return true;

			$query="SELECT s1.*,t.*,te.* from {$this->tableName('term_taxonomy')} s1, {$this->tableName('terms')} t, {$this->tableName('term_relationships')} te where te.term_taxonomy_id=s1.term_taxonomy_id and t.term_id=s1.term_id and object_id={$this->post->ID}";
			$a = $this->db->get_results($query);
		
			if(count($a)>0){
				//we index 1 because of woo
				 $this->category = $a[1];
				
				 return true;
			}
		return false;
	}
		
	public function item($id=-1,$format=array()){
		mysql_query("SET SESSION group_concat_max_len = 1000000;");
		$a =   $this->db->get_results("
		   SELECT term_r.term_taxonomy_id,p.* ,group_concat(meta) as meta FROM (
					SELECT
						CASE
							WHEN meta_value IS NULL THEN CONCAT(meta_key, '=', \"null\")
							ELSE  CONCAT(meta_key, \"=\", meta_value)
						END AS meta,post_id
					FROM ".$this->tableName('postmeta')." 
				) as mp ,".$this->tableName('posts')." p, ".$this->tableName('term_relationships')." term_r
	where mp.post_id= p.ID and term_r.object_id=p.ID and p.post_status='publish' and p.ID={$id} and p.post_type='{$this->post_type}'");

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
	
	//search by all by post_type from this post and exluse this post
	// enable show random product
	public function search_post_type($name,$limit = 6,$page=1,$isRandom = false){
			if($name==null) return null;
			
			$this->pagation = &Ahlu::Library("Ahlu_WP_Pagation",$this->db);
            $this->pagation->setPage($page);
            $this->pagation->setLimit($limit);

			mysql_query("SET SESSION group_concat_max_len = 1000000;");
			$query = "Select post.*,metap.meta from  ( SELECT group_concat(meta SEPARATOR '\$AHLU\$') as meta,mp.ID  from (SELECT
					CASE meta_value
						WHEN  NULL THEN CONCAT(meta_key, '=', \"null\")
		   ELSE CONCAT(meta_key, '=', meta_value)
					END AS meta,post_id as ID
				FROM {$this->db->postmeta}
			) as mp GROUP BY ID )  as metap, {$this->db->posts} post where metap.ID= post.ID and post.post_status='publish' and post.post_type='{$this->post_type}' and post.post_title like '%".trim($name)."%'";
			
			//echo $query;
			//die();
			$this->pagation->excute($query); 
			
			$data = $this->pagation->PageData();

			if(is_array($data) && count($data)>0){
				foreach($data as $obj){
					$obj->product = new Ahlu_woo_product($obj->ID);
					if(isset($obj->meta)){
					$meta = explode('$AHLU$',$obj->meta);
					foreach($meta as  $v){
						$k=explode("=",ltrim($v,"_"));
						$obj->{$k[0]} = StringUtil::is_serialized($k[1]) ? unserialize($k[1]) : $k[1];
					}
					unset($obj->meta);
					unset($meta);
					
					if(isset($obj->thumbnail_id)){
						$query = "SELECT post.guid from  {$this->db->posts} as post where post.ID={$obj->thumbnail_id}";
						 //echo $query;
						$a = Ahlu::DB()->get_results($query);
						//print_r($a);
						if(count($a)>0){
							unset($obj->thumbnail_id);
						   $obj->thumbnail =$a[0]->guid;
						}
					}
				  }
				}
				if($isRandom && count($data)>$limit){
					$b = array();
					$queue = array();
					for($i=0; $i<$limit ; $i++){
						$num = rand(0,$limit);
						//if(!in_array($num,$queue)){
							//$queue[] = $num;
							$b[] = $data[$num];
						//}
						
					}
					$data = $b;
				}
				$a = new stdClass();
				$a->total = $this->pagation->total;
				$a->data = $data;
				$a->link = $this->pagation->PageLinks(true);
			
				return $a;
			}
			return null;
	}
		//search by name in same category
		public function querySearch($name,$limit = 9,$page=1){
			if($name==null) return null;
			
			$this->pagation = &Ahlu::Library("Ahlu_WP_Pagation",$this->db);
            $this->pagation->setPage($page);
            $this->pagation->setLimit($limit);

			mysql_query("SET SESSION group_concat_max_len = 1000000;");
			$query = "Select post.*,metap.meta from  ( SELECT group_concat(meta SEPARATOR '\$AHLU\$') as meta,mp.ID  from (SELECT
					CASE meta_value
						WHEN  NULL THEN CONCAT(meta_key, '=', \"null\")
		   ELSE CONCAT(meta_key, '=', meta_value)
					END AS meta,post_id as ID
				FROM {$this->db->postmeta}
			) as mp GROUP BY ID )  as metap, {$this->db->term_taxonomy} term_tax,{$this->db->term_relationships} term_tax_re,{$this->db->posts} post where metap.ID= post.ID and post.post_status='publish' and post.post_type='{$this->post_type}' and post.ID=term_tax_re.object_id and  term_tax.term_taxonomy_id=term_tax_re.term_taxonomy_id and term_tax.term_taxonomy_id = term_tax_re.term_taxonomy_id and MATCH(post.post_title) AGAINST ('".trim($name)."')";
			
			$this->pagation->excute($query); 
			
			$data = $this->pagation->PageData();
			
			
			if(is_array($data) && count($data)>0){
				foreach($data as $obj){
					$obj->product = new Ahlu_woo_product($obj->ID);
					if(isset($obj->meta)){
					$meta = explode('$AHLU$',$obj->meta);
					foreach($meta as  $v){
						$k=explode("=",ltrim($v,"_"));
						$obj->{$k[0]} = StringUtil::is_serialized($k[1]) ? unserialize($k[1]) : $k[1];
					}
					unset($obj->meta);
					unset($meta);
					
					if(isset($obj->thumbnail_id)){
						$query = "SELECT post.guid from  {$this->db->posts} as post where post.ID={$obj->thumbnail_id}";
						 //echo $query;
						$a = Ahlu::DB()->get_results($query);
						//print_r($a);
						if(count($a)>0){
							unset($obj->thumbnail_id);
						   $obj->thumbnail =$a[0]->guid;
						}
					}
				  }
				}
				$a = new stdClass();
				$a->total = $this->pagation->total;
				$a->data = $data;
				$a->link = $this->pagation->PageLinks(true);
			
				return $a;
			}
			return null;
	}
	
	//search by name in same category
	public function relatedSearch($limit = 3,$isRandom = false){
				mysql_query("SET SESSION group_concat_max_len = 1000000;");
				$query = "Select post.*,metap.meta from  ( SELECT group_concat(meta) as meta,mp.ID  from (SELECT
                        CASE meta_value
                            WHEN  NULL THEN CONCAT(meta_key, '=', \"null\")
			   ELSE CONCAT(meta_key, '=', meta_value)
                        END AS meta,post_id as ID
                    FROM {$this->db->postmeta}
                ) as mp GROUP BY ID )  as metap, {$this->db->term_taxonomy} term_tax,{$this->db->term_relationships} term_tax_re,{$this->db->posts} post where metap.ID= post.ID and post.post_status='publish' and post.post_type='{$this->post_type}' and post.ID=term_tax_re.object_id and post.ID<>{$this->post->ID} and MATCH(post.post_title) AGAINST ('".trim($this->post->post_title)."')";
            
			//echo $query;
			$data =   $this->db->get_results($query);
		  //alway
			if(is_array($data) && count($data)>0){
				foreach($data as $obj){
					$obj->product = new Ahlu_woo_product($obj->ID);
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
				}
				if($isRandom && count($data)>$limit){
					$b = array();
					$queue = array();
					for($i=0; $i<$limit ; $i++){
						$num = rand(0,$limit);
						//if(!in_array($num,$queue)){
							//$queue[] = $num;
							$b[] = $data[$num];
						//}
						
					}
					$data = $b;
				}
				return $data;
			}
			return null;
	}	
	//override
	/*
	* get category
	*/
	public function parent($array = NULL){
		$db = $this->db;
		$query = "SELECT s1.*,t.* from {$db->prefix}term_taxonomy s1, {$db->prefix}terms t where t.term_id=s1.term_id and s1.term_taxonomy_id={$this->post->term_taxonomy_id}";
		//echo $query;
		$a = $db->get_results($query);
		return count(a)>0 ? $a[0] :null;
	}
	public function hasParent(){
		return $this->post->taxonomy==$this->post_type;
	}
}

?>