<?php
class Ecommercial_search_model extends Post_model{

	protected $query = null;
	//public $name = "Search";
	public $name = "Tìm kiếm";
	public $empty = "Không có kết quả bạn đang tìm";
	
	protected $pagation =null;
	
	public function __construct(){
		parent::__construct();
		
		$this->post_type = "wpsc-product";
		return $this; 
    }
	
	//override load
	public function load($id){
		$this->query = $id;
		if($this->query==null){
			die("Can not process in line: ".__LINE__);
		}
		$this->name = "Bạn đang tìm kiếm: '{$this->query}'";
	}
	   /**
      * Get breadCrumbs from post
      *  
      * @param mixed $home
      * @param mixed $ch
      */
       public function breadCrumbs($home="home",$ch=" &raquo; "){
           $crumbs = array();
		    array_push($crumbs,$home);
            array_push($crumbs,$this->name);
            $s = array();
           
           foreach($crumbs as $i=>$p) {
               //only in post type as seo
               $s[]='<a '.($i==count($crumbs)-1 ? 'class="current"': '').' href="'.urldecode($p=="home"?"/":"/".$p).'" title="'.htmlspecialchars($p).'">'.ucwords(htmlspecialchars($p=="home"?"home":$p)).'</a>';
           }
           
           return "<div class=\"breadCrumbs\" style=\"margin-bottom: 5px;\">".implode($ch,$s)."</div>";
       }
	//search by name in same category
	public function querySearch($limit = 9){
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
			) as mp GROUP BY ID )  as metap, {$this->db->term_taxonomy} term_tax,{$this->db->term_relationships} term_tax_re,{$this->db->posts} post where metap.ID= post.ID and post.post_status='publish' and post.post_type='{$this->post_type}' and post.ID=term_tax_re.object_id and  term_tax.term_taxonomy_id=term_tax_re.term_taxonomy_id and term_tax.term_taxonomy_id = term_tax_re.term_taxonomy_id and MATCH(post.post_title) AGAINST ('".trim($this->query)."')";
			
			$this->pagation->excute($query); 
			//echo $query;
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
				return $a;
			}
			return null;
	}
	
	//override
	/*
	* get category
	*/
	public function parent(){
		return null;
	}
	public function hasParent(){
		return false;
	}
}

?>