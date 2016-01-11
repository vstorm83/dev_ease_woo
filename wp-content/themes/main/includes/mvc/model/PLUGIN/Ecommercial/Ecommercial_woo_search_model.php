<?php
class Ecommercial_woo_search_model extends Ecommercial_woo_item_model{

	public $query = null;
	//public $name = "Search";
	public $name = "Seach";
	public $empty = "No Products Found";
	
	protected $pagation =null;
	
	public function __construct(){
		parent::__construct();
		
		$this->post_type = 'product';
		$this->taxonomy = 'product_cat';
		
		return $this; 
    }
	
	//override load
	public function load($id){
		$this->query = $id;
		if($this->query==null){
			die("Can not process your request in line: ".__LINE__);
		}
		
	}
	   /**
      * Get breadCrumbs from category
      *  
      * @param mixed $home
      * @param mixed $ch
      */
       public function breadCrumbs($home="home",$url="/",$ch=" &raquo; ",$return=false,$more=null){
           $crumbs = array();
		   
		    array_push($crumbs,array($home,$url));
		   //add more
			if(is_array($more)){
				$crumbs = array_merge($crumbs,$more);
			}
            array_push($crumbs,array($this->name,"#"));
			
			if($return) return $crumbs;
            $s = array();
           
           foreach($crumbs as $i=> $p) {
               $l = htmlspecialchars($p[1]=="/"?"home":$p[0]);
               //only in post type as seo
               $s[]='<a href="'.urldecode($p[1]).'" '.($i==count($crumbs)-1 ? 'class="current"':"" ).' title="'.$l.'">'.ucwords($l).'</a>';
           }
           
           return "<div class=\"breadCrumbs\" style=\"margin-bottom: 5px;\">".implode($ch,$s)."</div>";
       }
	//search by name in same category
	public function querySearch($limit = 9,$page=1){
			$this->pagation = Ahlu::Library("Ahlu_WP_Pagation",$this->db);

            $this->pagation->setPage($page);
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
				$a->data = $data;
				$a->link = $this->pagation->PageLinks(true);
				return $a;
			}
			return null;
	}
	
	public function search_post_type($query,$limit,$page){
		$data = parent::search_post_type($query,$limit,$page);
		if(!isset($data->total)){
			$total = 0;
		}else{
			$total = $data->total;
		}
		$this->name = "Your Request: '{$this->query}' ({$total})";
		return $data;
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