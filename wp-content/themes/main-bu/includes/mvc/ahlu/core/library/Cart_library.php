<?php
  abstract class Cart_library extends Model{
	private $user = null;
	private $onlyUser = false;
	
	public $code = null;
	
	public function load(){
		
		return $this;
	}
	public function setUser($user){
		$this->user = $user;
		return $this; 
	}
	public function allowedUser(){
		$this->onlyUser = isset($_SESSION["user"]);
		return $this; 
	}
	public function URLTracking(){
		return isset($_SESSION['cart_url']) ? $_SESSION['cart_url'] : null; 
	}
	public function clear(){
		//clear
		unset($_COOKIE["democart"]);
		unset($_SESSION["democart"]);
		return $this->db->delete($this->tableName("wpsc_cart_contents"), array("purchaseid"=>$this->code ), array( '%s' ) );
	}
	
	public function getCart(){
		if($this->onlyUser){
			return 101; // the user must  login
		}
		//update id for this cart
		
		mysql_query("SET SESSION group_concat_max_len = 1000000;");
			$query = "Select cart.*,post.*,metap.meta from  ( SELECT group_concat(meta) as meta,mp.ID  from (SELECT
                        CASE meta_value
                            WHEN  NULL THEN CONCAT(meta_key, '=', \"null\")
			   ELSE CONCAT(meta_key, '=', meta_value)
                        END AS meta,post_id as ID
                    FROM {$this->db->postmeta}
                ) as mp GROUP BY ID ) as metap,{$this->db->posts} post,{$this->tableName("wpsc_cart_contents")} cart where cart.prodid=post.ID and metap.ID= post.ID and cart.purchaseid='{$this->code}'";
            
			//
			//echo $query;
			$data = $this->db->get_results($query);
			//print_r($data);
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
				return $data;  
			}
			return null;
	}
	
	public function add($id,$quantity=0,$isCount=true){
		$item = $this->existItem($id);
		if($item){ //update
			//print_r($item);
			return $this->db->update( 
			$this->tableName("wpsc_cart_contents"), 
			array( 
				'quantity' => $isCount ? $item->quantity+$quantity : $quantity,	// string
			), 
			array( 'prodid' =>$id,"purchaseid"=>$this->code )
			);	
		}else{//new product
			$product = $this->post($id);
			
			if($product){
				$this->db->insert( 
					$this->tableName("wpsc_cart_contents"), 
					array( 
						'prodid' => $id, 
						'purchaseid' => $this->code,
						'price' => $product->wpsc_price,
						'name' => $product->post_title,
						'quantity' => $quantity,
					));
				return $product;	
			}
			//print_r($this->db->last_query);
		}
		return false;
	}
	public function delete($id,$quantity=0){
		$item = $this->existItem($id);
		if($item){ //update	
			//print_r($item);
			if($quantity==0){
				//delete
				return $this->db->delete($this->tableName("wpsc_cart_contents"), array( 'prodid' =>$id,"purchaseid"=>$this->code ), array( '%d','%s' ) );
			}else{
				//check if item has only one quantity, so delete
				if($item->quantity==1){
					return $this->db->delete($this->tableName("wpsc_cart_contents"), array( 'prodid' =>$id,"purchaseid"=>$this->code ), array( '%d','%s' ) );
				}
				
				return $this->db->update( 
				$this->tableName("wpsc_cart_contents"), 
				array( 
					'quantity' => $item->quantity-$quantity,	// string
				), 
				array( 'prodid' =>$id,"purchaseid"=>$this->code )
				);	
			}
		}
		return false;
	}
	public function order($cart=null,$user=null){
	
	}
	///////////////////////////////////
	protected function existItem($id){
		$query = "SELECT cart.* from  {$this->tableName("wpsc_cart_contents")} cart where cart.prodid={$id} and cart.purchaseid='{$this->code}'";
		 //echo $query;
		$a = $this->db->get_results($query);
		//print_r($a);
		if(count($a)>0){
			return $a[0];
		}
		return false;
	}
	
 }
?>