<?php
class AhluStore{
	private $session;
	public $key="ahlu-id-store";
	private $path="";
	public $file="";
	
	public function __construct($path){
		//check path and code together
		if(!file_exists($path)){
			mkdir($path,"0775",true);
		}
		
		$this->path=$path;
	}
	private function _create($code=null){
		//clear all
		$this->clear();
		
		$this->session = $code!=null?$code:time()."-".str_replace("-","",$this->guid());
		$number_of_days = 15 ;
		$t = time();
		$date_of_expiry = $t  + 60 * 60 * 24 * $number_of_days ;
		@setcookie($this->key, $this->session, $date_of_expiry, "/" ) ;
		//assign to session
		$_SESSION[$this->key] = $this->session;
	}
	public function check($code=null){
	echo $this->file;
		//check file exist
		if(!empty($code) && file_exists($this->path."/{$code}.ahlu")){
			//store file path
			$this->file = $this->path."/{$code}.ahlu";
			
			//try to attemp from cookie
			if(!isset($_COOKIE[$this->key])){
				//create new
				$this->_create();
			}else{
				//store session code
				$this->session = $code;
			}
		}else{
			if(!isset($_COOKIE[$this->key])){
				$this->_create();
				//store file
				$this->file = $this->path."/{$this->session}.ahlu";
				touch($this->file);

			}else{
				//check exist key
				$v = $_COOKIE[$this->key];
				$_SESSION[$this->key] = $v;
				$this->session = $v;

				//check fired
				$a = explode("-",$v);
				$t = time();

				$max = intval($a[0]);
				if($max>$t){
		
					$this->_create();
					//store file
					touch($this->file);
				}

				$this->file = $this->path."/{$this->session}.ahlu";
			}

		}
	}
	/*
	* Get session global
	*/
	public function getHash(){
		if(isset($_COOKIE[$this->key])){
			$this->session = $_COOKIE[$this->key];
			//create file if not exist
			if(!file_exists($this->path."/{$this->session}.ahlu")){
				$this->file = $this->path."/{$this->session}.ahlu";
				touch($this->file);
			}
			$_SESSION[$this->key] = $_COOKIE[$this->key];
			return $this->session;
		}
		//realy happens
/*
		if(empty($this->session)){
			$this->_creat();
			$this->file = $this->path."/{$this->session}.ahlu";
			touch($this->file);
		}
*/
	
	}
	//update current session
	public function update($code){
		//create new one
		$this->_create();
		//store file
		$this->file = $this->path."/{$this->session}.ahlu";
	}
	public function clear(){
		//if we clear the cookie via ajax, is not effected by orinal page one page becuase different SESSION_ID
		//clear all
		if(file_exists($this->file)){
			unlink($this->file);
			$this->file = "";
		}
		@setcookie( $this->key, $this->session, time() - 3600, '/' );
		if(isset($_SESSION[$this->key])){
			unset($_SESSION[$this->key]);
		}
		$this->session = null;
	}
	
	protected function guid($is=true){
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
	
	public function write($str){
		file_put_contents($this->file,$str);
	}
	public function read(){
		return file_get_contents($this->file);
	}
}

function AhluStore(){
	static $AhluStore;
	if(!isset($AhluStore)){
		$AhluStore = new AhluStore(dirname(__FILE__)."/cache/woo");
	}
	return $AhluStore;

}

function read_cart_enquiry(){
	$store = AhluStore();
	$store->check(isset($_POST["cart_hash"])?$_POST["cart_hash"]:null);
	//read file
	$data = $store->read();
	if(empty($data)){
		$store->write(serialize(array()));
		return array();
		die();
	}
	return unserialize($data);
}

function add_service_enquiry(){
	if(isset($_POST) && count($_POST)>0){
		
		//look up the file to check if this product has been added as service enquiry
		$store = AhluStore();
		$store->check(isset($_POST["cart_hash"])?$_POST["cart_hash"]:null);
		$data = $store->read();
		if(empty($data)){
			$store->write(serialize(array($_POST["id_product"]=>$_POST["type"])));
			echo 1;
			die();
		}

		$data = unserialize($data);
		//just add once
		
		if(isset($data[$_POST["id_product"]])) {echo 0; die();}

		//here we go
		$data[$_POST["id_product"]] = $_POST["type"];

		//save 
		$store->write(serialize($data));
		echo 1;
		die();
	}
}
add_action( 'wp_ajax_add_service_enquiry', 'add_service_enquiry' );
add_action( 'wp_ajax_nopriv_add_service_enquiry', 'add_service_enquiry' );

function get_cart_service(){
	if(isset($_POST) && count($_POST)>0){
		//look up the file to check if this product has been added as service enquiry
		$store = AhluStore();
		$store->check(isset($_POST["cart_hash"])?$_POST["cart_hash"]:null);
		//read file
		$data = $store->read();
		if(empty($data)){echo 0;die();}
		$data = unserialize($data);
		echo  count($data);
		die();
	}
}
add_action( 'wp_ajax_get_service_enquiry', 'get_cart_service' );
add_action( 'wp_ajax_nopriv_get_service_enquiry', 'get_cart_service' );


function delete_cart_service($id=null,$ok=false){
	if(isset($_POST) && count($_POST)>0){
		//look up the file to check if this product has been added as service enquiry
		$store = AhluStore();
		$store->check(isset($_POST["cart_hash"])?$_POST["cart_hash"]:null);
		//read file
		$data = $store->read();
		if(empty($data)){echo 0;die();}

		$data = unserialize($data);
		if($id!=null && isset($data[$id])){
			unset($data[$id]);
			
			if($ok){
				$store->write(serialize($data));
				return;
			}
		}
		if(isset($data[$_POST["id_product"]])) unset($data[$_POST["id_product"]]);
		//save
		$store->write(serialize($data));
		echo 1;
		die();
	}
}
add_action( 'wp_ajax_delete_cart_service', 'delete_cart_service' );
add_action( 'wp_ajax_nopriv_delete_cart_service', 'delete_cart_service' );


function clear_cart_service(){
	if(isset($_POST) && count($_POST)>0){
		//look up the file to check if this product has been added as service enquiry
		$store = AhluStore();
		$store->check(isset($_POST["cart_hash"])?$_POST["cart_hash"]:null);
		$store->clear();

		echo 1;
		die();

	}
}
add_action( 'wp_ajax_clear_cart_service', 'clear_cart_service' );
add_action( 'wp_ajax_nopriv_clear_cart_service', 'clear_cart_service' );

/*
//custom breadcrumb
add_filter( 'woocommerce_breadcrumb_defaults', 'jk_woocommerce_breadcrumbs' );
function jk_woocommerce_breadcrumbs() {
    return array(
            'delimiter'   => ' &#47; ',
            'wrap_before' => '<nav class="woocommerce-breadcrumb" itemprop="breadcrumb">',
            'wrap_after'  => '</nav>',
            'before'      => '',
            'after'       => '',
            'home'        => _x( 'Home', 'breadcrumb', 'woocommerce' ),
        );
}
*/
/*
//custom detail product
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50 );
add_action( 'woocommerce_single_product_summary', function(){
	echo "ok here";
}, 50 );
*/
/**
 * Product Add to cart
 *
 * @see woocommerce_template_single_add_to_cart()
 * @see woocommerce_simple_add_to_cart()
 * @see woocommerce_grouped_add_to_cart()
 * @see woocommerce_variable_add_to_cart()
 * @see woocommerce_external_add_to_cart()
 */
/*
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
add_action( 'woocommerce_simple_add_to_cart', 'woocommerce_simple_add_to_cart', 30 );
add_action( 'woocommerce_grouped_add_to_cart', 'woocommerce_grouped_add_to_cart', 30 );
add_action( 'woocommerce_variable_add_to_cart', 'woocommerce_variable_add_to_cart', 30 );
add_action( 'woocommerce_external_add_to_cart', 'woocommerce_external_add_to_cart', 30 );
add_action( 'woocommerce_single_variation', 'woocommerce_single_variation', 10 );
add_action( 'woocommerce_single_variation', 'woocommerce_single_variation_add_to_cart_button', 20 );
*/

/////////////////
/**
 * Clear Cart for WooCommerce
 */
add_action( "init", "we_woocommerce_clear_cart_url" );
function we_woocommerce_clear_cart_url() {
	global $woocommerce;
	if(isset($_REQUEST["ahlu"])) {
		$ahlu = json_decode(stripslashes($_POST["ahlu"]));
		if($ahlu->clear_cart){
			//clear cart from woo

			$woocommerce->cart->empty_cart();
			echo site_url()."/shop";
			//remove fake cart
			$store = AhluStore();
			$store->check(isset($ahlu->cart_hash)?$ahlu->cart_hash:null);
			$store->clear();
			
			die();
		}
	}
	
	
}

/**
 * Remove product on begin to load template 
 */
//main point
add_filter('template_include', 'remove_product_from_cart');
function remove_product_from_cart($template) {
	
    // Run only in the Cart or Checkout Page
        // Set the product ID to remove
      

		if(isset($_POST) && count($_POST)>0){
			//global $woocommerce;
			$ahlu = json_decode(stripslashes($_POST["ahlu"]));
			//print_r($_POST["ahlu"]);
			$prod_to_remove = $ahlu->id_product;
			//print_r($woocommerce->cart->cart_contents);
			
			delete_cart_service($prod_to_remove,true);
 			//die();
	
	        // Cycle through each product in the cart
	        foreach( WC()->cart->cart_contents as $i=> $prod_in_cart ) {
	            // Get the Variation or Product ID
	            $prod_id = ( isset( $prod_in_cart["variation_id"] ) && $prod_in_cart["variation_id"] != 0 ) ? $prod_in_cart["variation_id"] : $prod_in_cart["product_id"];
			
	            // Check to see if IDs match
	            if( $prod_to_remove == $prod_id ) {
	                // Get it's unique ID within the Cart
	                $prod_unique_id = WC()->cart->generate_cart_id( $prod_id );
	                // Remove it from the cart by un-setting it
	                WC()->cart->remove_cart_item($prod_unique_id);
					echo "Product Deleted Successfully.";
					die();
	            }
	        }
			echo "error";
			die();
		}

	return $template;
}
?>