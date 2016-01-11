<?php
	class Cart_Async{
			public $cart = array();
			public $fired = false;
			
			private $default_data = array("quantity"=>0);
			private $key = "cart_ahlu_id";
			
			private $file = null;
			private $events = array();
			
			public function __construct(){
				$this->check_cart();
				$this->settings();
			}	
			public static function Session(){

			}
			public function Ajax($e,$f){
				if(!isset($events[$e])){
					$events[$e] = array();
				}
				$events[$e][] = $f;
			}	
			private function settings(){
				if(isset($_POST["ahlu"])){
					$ahlu =  json_decode(stripslashes(rawurldecode($_POST["ahlu"])));
					if(isset($_REQUEST["add_cart"])){
						$this->_add($ahlu);
					}else if(isset($_REQUEST["update_cart"])){
						$this->_update($ahlu);
					}else if(isset($_REQUEST["delete_cart"])){
						$this->_delete($ahlu);
					}else if(isset($_REQUEST["clear_cart"])){
						ob_clean();
						ob_start();
						if($this->_clear()){
							echo 1;
						}else{
							echo 0;
						}
						
						die();
					}else if(isset($_REQUEST["cart"])){
						ob_clean();
						ob_start();
						header('Content-Type: application/json');
						
						//read
						$this->read_cart();
						$data = null;
						
						$func = $_REQUEST["cart"];
						if(empty($func)){
							$data = array("data"=>$this->cart);	
						}else{
							switch(strtolower($func)){
								case "total":
									$data = array("total"=>$this->total());	
								break;
							}
						}
						
						echo json_encode($data);
						die();
					}
				}
			}
			private function _add($data) {
				ob_clean();
				ob_start();
				//print_r($data);
				
				if($this->add($data->id,$data->quantity)){
					echo 1;
				}else{
					echo 0;
				}
				
				die();
			}
			private function _update($data) {
				ob_clean();
				ob_start();
				if(isset($this->events["update"])){
					foreach($this->events["update"] as $f){
						call_user_func_array($f,array($this,$data));
					}
					
				}
				// Always die in functions echoing ajax content
				die();
			}
			private function _delete($data) {
				ob_clean();
				ob_start();
				if(isset($this->events["delete"])){
					foreach($this->events["delete"] as $f){
						call_user_func_array($f,array($this,$data));
					}
					
				}
				// Always die in functions echoing ajax content
				die();
			}
			private function _clear() {
				ob_clean();
				ob_start();
				if($this->clear()){
					echo 1;
				}else{
					echo 0;
				}
				
				die();

			}
			/////////////////////////////////////////////////////////////////////
			private function guid($is=true){
				$a = null;
				if (function_exists('com_create_guid')){
					$a= com_create_guid();
				}else{
					mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
					$charid = strtoupper(md5(uniqid(rand(), true)));
					$hyphen = chr(45);// "-"
					$uuid = chr(123)// "{"
							.substr($charid, 0, 8).$hyphen
							.substr($charid, 8, 4).$hyphen
							.substr($charid,12, 4).$hyphen
							.substr($charid,16, 4).$hyphen
							.substr($charid,20,12)
							.chr(125);// "}"
					$a = $uuid;
				}
				
				return $is?str_replace(array("{","}"),"",$a):$a;
			}
			private function write_cart($data){
				file_put_contents($this->file,$data);
			}
			private function read_cart(){
				//check file for sure
				if(!file_exists($this->file)){
					$this->cart = array();
					touch($this->file);
					return;
				}
				$this->cart = unserialize(file_get_contents($this->file));
			}
			
			
			private function check_cart(){
				//check user
				if ( !is_user_logged_in() ){
					
					//get cookie
					if(!isset($_COOKIE['cart_ahlu_id'])){
						$number_of_days = 15 ;
						$t = time();
						$v = time()."-".str_replace("-","",$this->guid());
						$date_of_expiry = $t  + 60 * 60 * 24 * $number_of_days ;
						setcookie($this->key, $v, $date_of_expiry, "/" ) ;
						//store in file
						if(!file_exists(TEMPLATEPATH."/cache/woo")){
							mkdir(TEMPLATEPATH."/cache/woo","0775",true);
						}
						$this->file = TEMPLATEPATH."/cache/woo/{$v}.ahlu";
						if(!file_exists($this->file)){
							touch($this->file);
						}
						$this->write_cart(serialize($this->cart));
						//assign to session
						$_SESSION[$this->key] = $v;
					}else{
						$v = $_COOKIE[$this->key];
						$_SESSION[$this->key] = $v;
						$this->file = TEMPLATEPATH."/cache/woo/{$v}.ahlu";
						//read cart
						$this->read_cart();
						
						//check fired
						$a = explode("-",$v);
						$t = time();
		
						$max = intval($a[0]);
						$this->fired = $t>$max;
					}
				}else{
					//get meta_user with key
					global $current_user;
					get_currentuserinfo();
					
					$id_cart = get_user_meta( $current_user->ID, "id_cart",true); 
					if(empty($id_cart)){
						
						
						$number_of_days = 15 ;
						$t = time();
						$v = time()."-".str_replace("-","",$this->guid());
						$date_of_expiry = $t  + 60 * 60 * 24 * $number_of_days ;
						setcookie( "cart_ahlu_id", $v, $date_of_expiry, "/" ) ;
						
						add_user_meta($current_user->ID, 'id_cart', $v);
						//store in file
						if(!file_exists(TEMPLATEPATH."/cache/woo")){
							mkdir(TEMPLATEPATH."/cache/woo","0775",true);
						}
						$this->file = TEMPLATEPATH."/cache/woo/{$v}.ahlu";
						if(!file_exists($this->file)){
							touch($this->file);
						}
						$this->write_cart(serialize($this->cart));
						//assign to session
						$_SESSION['cart_ahlu_id'] = $v;
					}else{
						$this->file = TEMPLATEPATH."/cache/woo/{$id_cart}.ahlu";
						$v = $_COOKIE['cart_ahlu_id'];
						$_SESSION['cart_ahlu_id'] = $v;
						//read cart
						$this->read_cart();
						
						//check fired
						$a = explode("-",$id_cart);
						$t = time();
		
						$max = intval($a[0]);
						$this->fired = $t>$max;
					}
				}
				
				//print_r($this->cart);
				//die();
			}
			
			public function total(){
				$t = 0;
				foreach($this->cart as $item){
					$t += $item["quantity"];
				}
				return $t;
			}
			public function new_cart(){
				
				return true;
			}
			
			public function add($id_product,$num){
				
			
				if(!isset($this->cart[$id_product])){
					$this->cart[$id_product] = array_merge(array(),$this->default_data);
				}
				
				$this->cart[$id_product]["quantity"] =$this->cart[$id_product]["quantity"]+intval($num);
				
				//print_r($id_product);
				$this->write_cart(serialize($this->cart));
				
				return true;
			}
			public function update($id_product){
				
				return true;
			}
			public function delete($id_product){
				
				return true;
			}
			public function clear(){
				$this->cart = array();
				
				if (isset($_COOKIE[$this->key])) {
					unset($_COOKIE[$this->key]);
					setcookie($this->key, '', time() - 3600, '/'); // empty value and old timestamp
				}
				if (isset($_SESSION[$this->key])) {
					unset($_SESSION['cart_ahlu_id']);
				}
				//
				$this->check_cart();
				return true;
			}
		}

	class Woo_Cart_Async extends 	Cart_Async{
		public function __construct(){
			parent::__construct();
		}
		
		
		public function add($id_product,$num){
		
			
			if(!isset(WC()->cart)){
				WC_AJAX::get_refreshed_fragments();
			}
			
			WC()->cart->add_to_cart( $id_product );
			return true;
		}
		public function update($id_product){
			
			return true;
		}
		public function delete($id_product){
			
			return true;
		}
		
	}
	
	
	$Ahlu_Cart = new Cart_Async();
	
	

add_action( 'wp_footer',function() use($Ahlu_Cart){
$total = "total";
$clear = "clear_cart";
echo <<<AHLU
	<script>
		$(document).ready(function(){
			$("body").bind("ahlu-get-cart",function(){
				receiveFromURL('?cart={$total}',{},function(data){
					if(typeof(data)=="object"){
						var value=$('.cart-holder span').html();
						$('.cart-holder span').html(parseInt(value)+parseInt(data.{$total}));
					}
				},true);
			});
			$("body").bind("ahlu-clear-cart",function(){
				receiveFromURL('?{$clear}',{},function(data){
					if(data==1){
						$('.cart-holder span').html(0);
					}else{
						console.log(data);
					}
				},true);
			});
			//
			$("body").trigger("ahlu-get-cart");
		});
	</script>
AHLU;
 } , 5 );
?>