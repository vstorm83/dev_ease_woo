<?php
  class Cart_model extends Cart_library{
    
	public function __construct(){
		parent::__construct();
	}
	//override
	public function add($id,$quantity=0,$isCount=true){
		$obj = parent::add($id,$quantity,$isCount);
		if(is_object($obj)){ //insert new
			$product = new stdClass();
				$product->id = $obj->ID;
				$product->name = $obj->post_title;
				$product->slug = $obj->post_name;
				$product->thumbnail = $obj->thumbnail;
				$product->quantityItem = array("-1"=>array("price"=>$obj->wpsc_price,"quantity"=>1));	
			return $product;
		}
		
		return $obj;
	}
	
 }
?>