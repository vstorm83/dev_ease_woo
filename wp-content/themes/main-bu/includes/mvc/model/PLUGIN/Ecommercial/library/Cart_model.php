<?php
  class Cart_model extends Cart_library{
    protected $category;
    private $post;

	public function __construct(){
		parent::__construct();

		$this->category = Ahlu::Call("Ecommercial_model");
		$this->post = Ahlu::Call("Ecommercial_item_model");
	}

	
	protected function  itemProduct($id){
		$this->post->load($id);

		return $this->post->getMe();
	}


	//override inhirit
	public function add($id,$quality=0,$isCount=true){	
		$product = null;
		$info = $this->_readCart();
		
		$id_cart = $info["id_cart"];
		$products = &$info["data"]["info"];
		
		

		if(count($products)>0 && isset($products["$id"])){

			

			$item = &$products["$id"]["qualityItem"]["-1"];
			$item["quality"] = $isCount ? $item["quality"]+1 : $quality;

			$product = $products["$id"];
		}else{
			$product = $this->itemProduct($id);


			if($product){
				$o = new stdClass();
				$o->id = $product->ID;
				$o->name = $product->post_title;
				$o->slug = $product->post_name;
				$o->thumbnail = $product->thumbnail;
				$o->qualityItem = array("-1"=>array("price"=>$product->wpsc_price,"quality"=>1));

				$products["$id"] = $o;

			}else{
				throw new Exception("Can not find '{$id}' item.");
			}
			
		}

		//print_r($info);
		//save cart
		$this->_saveCart($id_cart,$info);
		return $product;
	}
	
 }
?>