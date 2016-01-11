<?php
class Ecommercial_item_model extends Post_model{

	
	public function __construct(){
		parent::__construct();
		
		$this->post_type = "wpsc-product";
		return $this; 
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
	
		//search by name in same category
		public function search($limit = 6,$isRandom = false){
				mysql_query("SET SESSION group_concat_max_len = 1000000;");
				$query = "Select post.*,metap.meta from  ( SELECT group_concat(meta) as meta,mp.ID  from (SELECT
                        CASE meta_value
                            WHEN  NULL THEN CONCAT(meta_key, '=', \"null\")
			   ELSE CONCAT(meta_key, '=', meta_value)
                        END AS meta,post_id as ID
                    FROM {$this->db->postmeta}
                ) as mp GROUP BY ID )  as metap, {$this->db->term_taxonomy} term_tax,{$this->db->term_relationships} term_tax_re,{$this->db->posts} post where metap.ID= post.ID and post.post_status='publish' and post.post_type='{$this->post_type}' and post.ID=term_tax_re.object_id and  term_tax.term_taxonomy_id=term_tax_re.term_taxonomy_id and term_tax.term_taxonomy_id = term_tax_re.term_taxonomy_id and post.ID<>{$this->post->ID} and MATCH(post.post_title) AGAINST ('".trim($this->post->post_title)."') limit 0,{$limit}";
            
			//echo $query;
			$data =   $this->db->get_results($query);
		  //alway
			if(is_array($data) && count($data)>0){
				foreach($data as $obj){
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
		//search by name in same category
		public function querySearch($name,$limit = 9){
			if($name==null) return null;
			$this->pagation = Ahlu::Library("Ahlu_WP_Pagation",$this->db);
            //$this->pagation->setPage($page);
            $this->pagation->setLimit($limit);

			mysql_query("SET SESSION group_concat_max_len = 1000000;");
			$query = "Select post.*,metap.meta from  ( SELECT group_concat(meta) as meta,mp.ID  from (SELECT
					CASE meta_value
						WHEN  NULL THEN CONCAT(meta_key, '=', \"null\")
		   ELSE CONCAT(meta_key, '=', meta_value)
					END AS meta,post_id as ID
				FROM {$this->db->postmeta}
			) as mp GROUP BY ID )  as metap, {$this->db->term_taxonomy} term_tax,{$this->db->term_relationships} term_tax_re,{$this->db->posts} post where metap.ID= post.ID and post.post_status='publish' and post.post_type='{$this->post_type}' and post.ID=term_tax_re.object_id and  term_tax.term_taxonomy_id=term_tax_re.term_taxonomy_id and term_tax.term_taxonomy_id = term_tax_re.term_taxonomy_id and MATCH(post.post_title) AGAINST ('".trim($name)."') limit 0,{$limit}";
			
			$this->pagation->excute($query); 
			
			$data = $this->pagation->PageData();
			if(is_array($data) && count($data)>0){
				foreach($data as $obj){
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
				$a = new stdClass();
				$a->data = $data;
				$a->link = $this->pagation->PageLinks(true);
				return ;
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