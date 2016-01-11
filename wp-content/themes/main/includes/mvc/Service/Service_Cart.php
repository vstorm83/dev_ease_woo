<?php
class Service_Cart extends BaseService implements IService{
	protected $ecommercial_model = null;
	protected $cart_model = null;
	
	public function __construct(){
		$this->ecommercial_model = Ahlu::Call("Ecommercial_model");
		$this->cart_model = Ahlu::Library("Cart_model");
		return $this;
	}
	///////////////implement
	public function index(){
	
		return get_class($this);
	}
	///////////////////////
	///////////////override
	public function setAhlu($ahlu){
		$this->ahlu = (object)$ahlu;
		//set code for cart
		$this->cart_model->code = isset($this->ahlu->code)?$this->ahlu->code:null;
		return $this;
	}
	//////////////////////////
	//get cart
	public function getCart(){
		$cart = $this->cart_model->getCart();
		if($cart==101){
			throw new Exception("You must login to get some action.");
			return null;
		}
		
		if(is_array($cart)){
			$products = array();
			//{id:1,name:"item",quantity:1,price:"$30.00",thumbnail:"pic.png",slug:"item-1",useVariation:false,id_variation:0,type_variation:null}
			foreach($cart as $i=> $obj){
				$id ="{$obj->ID}";
				$products[$id] = new stdClass();
				$products[$id]->id = $obj->ID;
				$products[$id]->name = $obj->post_title;
				$products[$id]->slug = $obj->post_name;
				$products[$id]->thumbnail = $obj->thumbnail;
				$products[$id]->quantityItem = array("-1"=>array("price"=>$obj->wpsc_price,"quantity"=>$obj->quantity));	
			}
			return $products;
		}
		return null;
	}
	public function clear($cart=null){
		return $this->cart_model->clear()!==false;
	}
	public function order($billing=null,$payment=1,$codeCart=null,$user=0){
		if(empty($codeCart)) return false;
		//print_r($billing);
		if(is_string($codeCart)){
			//set code for cart
			$this->cart_model->code = $codeCart;
			//get user
			
			//send mail
			$email = 'nhiem111@gmail.com';
			$subject = "Email Contact by PHU TUNG O TO - OEM PARTS Website";
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
			$headers .= 'From: '.$billing["email"]."\r\n";
			
			$review = $this->ecommercial_model->orderReview($codeCart);

			$message="<div>
					<h3>Information Customer:</h3>
					<p>
						Fullname: {$billing["firstname"]} {$billing["lastname"]} <br />
						Email: <b>{$billing["email"]} </b><br />
						Address 1: {$billing["street1"]} <br />
						Address 2: {$billing["street2"]} <br />
						Phone: <b>{$billing["telephone"]} </b><br />
						Fax: {$billing["fax"]} <br />
						Zip/Postal Code: {$billing["postcode"]} <br />
					</p>
				</div>
				<table style='width: 100%;border-collapse: collapse;'>";
				$message.='<thead>
			<tr>
				<th rowspan="1" style="white-space: nowrap;text-align:left;border: 1px solid #ececec;padding: 12px 20px 12px 20px;font-size: 14px;font-weight: bold;color: #000000;">Product Name</th>
				<th colspan="1" style="white-space: nowrap;text-align:center;border: 1px solid #ececec;padding: 12px 20px 12px 20px;font-size: 14px;font-weight: bold;color: #000000;">Price</th>
				<th rowspan="1" style="white-space: nowrap;text-align:center;border: 1px solid #ececec;padding: 12px 20px 12px 20px;font-size: 14px;font-weight: bold;color: #000000;">Quantity</th>
				<th colspan="1" style="white-space: nowrap;text-align:center;border: 1px solid #ececec;padding: 12px 20px 12px 20px;font-size: 14px;font-weight: bold;color: #000000;">Subtotal</th>
			</tr>
		</thead>
				';
				foreach($review->items as $item){
					$message.='<tr>
				<td style="border: 1px solid #ececec;padding: 15px 20px;"><h3 style="font-size: 14px;font-weight: normal;">'.$item->name.' </h3></td>
				<td style="text-align:center;border: 1px solid #ececec;padding: 15px 20px;">
								<span><span style="color: #fc0f0f;font-weight: bold;">'.Ahlu_Current_helper::formatCurrency($item->unique_price,"USD",false).'</span>            
					</span>
				</td>
				<td style="text-align:center;border: 1px solid #ececec;padding: 15px 20px;">'.$item->quantity.'</td>
				<td style="text-align:center;border: 1px solid #ececec;padding: 15px 20px;">
					<span><span style="color: #fc0f0f;font-weight: bold;">'.Ahlu_Current_helper::formatCurrency($item->unique_price,"USD",false).'</span>            
					</span>
						</td>
					</tr>';
				}
				$message.='<tfoot>
						<tr>
					<td style="text-align:right;border: 1px solid #ececec;padding: 15px 20px;font-weight: bold;" colspan="3">
						Subtotal    </td>
					<td style="text-align:center;border: 1px solid #ececec;padding: 15px 20px;">
						<span style="color: #fc0f0f;font-weight: bold;">'.Ahlu_Current_helper::formatCurrency($review->subTotal,"USD",false).'</span>    </td>
				</tr>
				
					<tr>
					<td style="text-align:right;border: 1px solid #ececec;padding: 15px 20px;font-weight: bold;" colspan="3">
						<strong>Grand Total</strong>
					</td>
					<td style="text-align:center;border: 1px solid #ececec;padding: 15px 20px;">
						<strong><span style="color: #fc0f0f;font-weight: bold;">'.Ahlu_Current_helper::formatCurrency($review->total,"USD",false).'</span></strong>
					</td>
				</tr>
					</tfoot></table>';
			//send mail
			if(mail($email, $subject, $message, $headers)){
				//clear cart
				return $this->cart_model->clear()!==false ? site_url("") : "";
			}
		}
		return false;
	}
	public function tracking($cart=null){
		return $this->cart_model->URLTracking();
	}
	public function add(){
		if($this->cart_model->code==null){
			$this->cart_model->code = $this->ahlu->code;
		}
		return  $this->cart_model->add($this->ahlu->id,$this->ahlu->quantity,isset($this->ahlu->isCount)?$this->ahlu->isCount:false);
		if($ok) return $ok;
		throw new Exception("The product is no longer exist on our system, please choose another one.");
	}
	public function remove($id=-1,$quantity=0){
		$ok = $this->cart_model->delete($this->ahlu->id,$this->ahlu->quantity);
		if($ok) return $ok!==false;
		throw new Exception("The product is no longer exist on our system.");
	
	}
	public function delete($id=-1){
		$ok = $this->cart_model->delete($this->ahlu->id);
		if($ok) return $ok!==false;
		throw new Exception("The product is no longer exist on our system.");
	}
}
?>