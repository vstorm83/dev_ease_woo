<?php

/**
* Category Perfume Taxanomy
* 
*/

    class Product extends Ahlu_category{

        public function __construct(){

            parent::__construct();

			//enable theme worpress;

            //$this->enWP =true; 

		    //call ovveride

            //$this->custom = Ahlu::Call("Custom_template");

			//because of no creating Post type, we will use post type in specific plugin

				//so we must defined
			$this->class = strtolower(get_class($this));	
			$this->post_type = strtolower($this->class);
			$this->prefix_type="";

			////
			//track url root
            $_SESSION["track__url"]  = $this->post_type.".html";
			
			//use template 
			$this->enableTemplate();

        }

        public function index(){

			$_SESSION['cart_url'] = $_SERVER['REQUEST_URI'];

			
			$cls = strtolower(__CLASS__);
		   $this->template->assign("cls","{$cls} {$cls}-{$this->onView}");
			//assign data   for content 

		   $this->ecommercial_model = Ahlu::Call("Ecommercial_woo_model"); 
		

			$meta = $this->ecommercial_model->SEO();
			if(is_object($meta)){
				$meta->seo = Ahlu::Library("Ahlu_SEO");
				$meta->seo->setTitle("Product");
				$meta->seo->setKeyword("Product");
				$meta->seo->setDescription("Product");
				$meta->seo->setCanonical(site_url($this->post_type.".html"));
				
				$meta = $meta->Meta();
			}

			//assign data   for header 
		   $this->template->assign("meta",$meta);
           $this->template->assign("header",$this->loader->view($this->pathTheme."/compoment/header.php",array("SEO"=>null),true));


		   //print_r($this->ecommercial_model->getMe());

        

		   $this->template->assign("content",$this->loader->view("ahlu-{$this->post_type}",array("WP_enable"=>$this->enWP,"category"=>$this->ecommercial_model),true));

           

		   //assign data   for footer 
		   $this->template->assign("footer",$this->loader->view($this->pathTheme."/compoment/footer.php",null,true));

		

		//output html

           $this->template->render(FALSE);

        }

        //default post detail
		public function post($id){ 

           $this->post_model = Ahlu::Call("Ecommercial_woo_item_model")->load($this->post); 
		   $this->ecommercial_model = Ahlu::Call("Ecommercial_woo_model"); 

			$this->template->assign("cls","post product-post post-id-{$this->post->ID} {$this->post->post_name}");
           //assign data   
		   $this->template->assign("meta",$this->post_model->SEO());
           $this->template->assign("header",$this->loader->view($this->pathTheme."/compoment/header.php",null,true));

           $this->template->assign("content",$this->loader->view("ahlu-{$this->post_type}-{$this->onView}",array("WP_enable"=>$this->enWP,"post"=>$this->post_model,"category"=>$this->ecommercial_model),true));
		
           $this->template->assign("footer",$this->loader->view($this->pathTheme."/compoment/footer.php",null,true));

           //output html
           $this->template->render(FALSE);

        }

		public function cart($id){ 
			$cls = strtolower(__CLASS__);
			
		   $this->post_model = Ahlu::Call("Ecommercial_item_model")->load($this->post); 
		   $this->ecommercial_model = Ahlu::Call("Ecommercial_woo_model"); 

		   
		   print_r($this->ecommercial_model->get_cart_detail());
		   
		   die();
		   
		   $post = get_page_by_title( 'Cart' );

		   $this->template->assign("cls","{$cls} cart");
           //assign data   
		  
           $this->template->assign("header",$this->loader->view($this->pathTheme."/compoment/header.php",null,true));

           $this->template->assign("content",$this->loader->view("ahlu-{$this->post_type}-{$this->onView}",array("WP_enable"=>$this->enWP,"post"=>$post,"woo"=>$this->ecommercial_model),true));
		
           $this->template->assign("footer",$this->loader->view($this->pathTheme."/compoment/footer.php",null,true));

           //output html
           $this->template->render(FALSE);
	    }

        //default category detail

        public function category($id){

			$_SESSION['cart_url'] = $_SERVER['REQUEST_URI'];

			
			$cls = strtolower(__CLASS__);
			
			//assign data   for content 

		   $this->ecommercial_model = Ahlu::Call("Ecommercial_woo_model",$this->category); 
			
			
			
			//check this category is the last one
			$last ="";
			if(!$this->ecommercial_model->hasChild()){
				$last ="product-list";
			}
		   $this->template->assign("cls","{$cls} {$cls}-{$this->onView} {$this->category->slug} {$last}");
			//assign data   for header 
		   $this->template->assign("meta",$this->ecommercial_model->SEO());
           $this->template->assign("header",$this->loader->view($this->pathTheme."/compoment/header.php",array("SEO"=>null),true));


		   //print_r($this->ecommercial_model->getMe());

		    $view = "";
			//check if this is top menu
			if($this->category->parent==0){
				$view = "-top";
			}
			//if($this->category->)

		   $this->template->assign("content",$this->loader->view("ahlu-{$this->post_type}-{$this->onView}{$view}",array("WP_enable"=>$this->enWP,"category"=>$this->ecommercial_model),true));

           

		   //assign data   for footer 

		   $this->template->assign("footer",$this->loader->view($this->pathTheme."/compoment/footer.php",null,true));

		

		//output html

           $this->template->render(FALSE);

        }

        //default category detail

        public function search($query=null){
			
			$query = isset($_REQUEST["q"]) ? $_REQUEST["q"] : null;
			//assign data   for header 

           $this->template->assign("header",$this->loader->view($this->pathTheme."/compoment/header.php",null,true));
		   $this->template->assign("cls","search");
			
		   //assign data for content 
		   $this->ecommercial_search_model = Ahlu::Call("Ecommercial_woo_search_model",$query); 
		  
		   print_r($this->ecommercial_model);
			
			$meta= Ahlu::Library("Ahlu_SEO");
			$meta->setTitle($this->ecommercial_search_model->name);
			$meta->setKeyword($this->ecommercial_search_model->name);
			$meta->setDescription($this->ecommercial_search_model->name);
			$meta->setCanonical(site_url(__FUNCTION__));
			$meta = $meta->Meta();
			$this->template->assign("meta",$meta);
		   $this->template->assign("content",$this->loader->view("ahlu-{$this->post_type}-{$this->onView}",array("WP_enable"=>$this->enWP,"search"=>$this->ecommercial_search_model),true));

           

		   //assign data   for footer 

		   $this->template->assign("footer",$this->loader->view($this->pathTheme."/compoment/footer.php",null,true));

		

		//output html

           $this->template->render(FALSE);

        }

        //////////////////example about product : product/gas 

        public function checkout(){
			
			if(isset($_POST["ahlu"])){
			
				$ahlu = json_decode(stripslashes(rawurldecode($_POST["ahlu"])));
				if(!isset($ahlu->item)){
					echo json_encode(array("d"=>array("data"=>null,"error"=>"No products found in this cart.","code"=>0)));
					die();
				}
				//print_r($ahlu);
				$to_email= get_userdata(1);

				$to_email = $to_email->user_email;

				$subject = "Website Enquiry";

				$email =  $ahlu->email;

				$headers  = 'MIME-Version: 1.0' . "\r\n";

				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

				$headers .= 'From: '.$email . "\r\n";
				
				$str = "";
				if(is_array($ahlu->item)){
					
					
					foreach($ahlu->item as $i=>$item){
						$str .= '<tr style=\"padding-bottom:5px;\">
							<td style=\"vertical-align:middle;boder:1px solid ;padding:10px;\">'.$item->icon.'</td> 
							<td style=\"vertical-align:middle;boder:1px solid ;padding:10px;\"><img src="'.$item->img.'" /></td> 
							<td style=\"vertical-align:middle;boder:1px solid ;padding:10px;\">'.$item->title.'</td> 
							<td style=\"vertical-align:middle;boder:1px solid ;padding:10px;\" >'.$item->note.'</td>

						</tr>';
					}	
					
				}
				
				
				
				$message="

					<table>

						<tr>

							<td style=\"vertical-align:top;boder:1px solid ;padding:10px;\">

								<table  border=\"1\" cellpadding=\"10\">
									<thead>
										<th style=\"vertical-align:middle;boder:1px solid ;padding:10px;\">Enquiry</th>
										<th style=\"vertical-align:middle;boder:1px solid ;padding:10px;\">Image</th>
										<th style=\"vertical-align:middle;boder:1px solid ;padding:10px;\">Name</th>
										<th style=\"vertical-align:middle;boder:1px solid ;padding:10px;\">Comment</th>
									</thead>
									<tbody>
										{$str}
									</tbody>
								</table>

							</td>

						</tr>

					</table>

				";

					$ok = mail($to_email,$subject, $message, $headers)?1:0;
					$id =-1;
					if($ok ){
						/*
						//store in db
						$my_post = array(
						  'post_title'    => $subject,
						  'post_content'  => $message,
						  'post_status'   => 'publish',
						  'post_type'   => 'contact',
						  'post_author'   => 1,
						  'post_excerpt' => 0
						);

						// Insert the post into the database
						$id = wp_insert_post( $my_post );
						//add more field
						add_post_meta($id, 'emailSender',$email);
						*/
						echo json_encode(array("d"=>array("data"=>null,"error"=>'',"code"=>1)));
					}
				
				
				die();
			}
			
		   $data = array();
		   $this->template->assign("cls","checkout");
		   
		   $meta= Ahlu::Library("Ahlu_SEO");
			$meta->setTitle("Your ENQUIRIES");
			$meta->setKeyword("Your ENQUIRIES");
			$meta->setDescription("Your ENQUIRIES");
			$meta->setCanonical(site_url(__FUNCTION__));
			$meta = $meta->Meta();
			
		   $this->template->assign("meta",$meta);
           //assign data   for header 

           $this->template->assign("header",$this->loader->view($this->pathTheme."/compoment/header.php",null,true));

		   $this->ecommercial_model = Ahlu::Call("Ecommercial_woo_model"); 
        
		   $cart = WC()->cart->get_cart();
		   $view = $this->onView;
		   if(count($cart)==0){
			$view = "checkout-non";
		   }
		   $this->template->assign("content",$this->loader->view("ahlu-{$this->post_type}-{$view}",array("WP_enable"=>$this->enWP,"cart"=>$cart),true));

           

		   //assign data   for footer 

		   $this->template->assign("footer",$this->loader->view($this->pathTheme."/compoment/footer.php",null,true));

		

		//output html

           $this->template->render(FALSE);

        }

		
		
		public function sitemap(){
			$this->template->assign("meta",$this->seo->setTitle("Site map")->setKeyword("Site map")->setDescription("Site map")->Meta());
			$model = Ahlu::Call("Ecommercial_model"); 

           //assign data   

           $this->template->assign("header",$this->loader->view($this->pathTheme."/compoment/header.php",null,true));

           $this->template->assign("content",$this->loader->view("ahlu-{$this->post_type}-{$this->onView}",array(),true));

           $this->template->assign("footer",$this->loader->view($this->pathTheme."/compoment/footer.php",null,true));

        

           //output html
           $this->template->render(FALSE);
		}
		
		
		 /*public function store(){
			
		
		    $this->template->assign("header",$this->loader->view($this->pathTheme."/compoment/header.php",null,true));
		   
		   
			$this->template->assign("content",$this->loader->view(TEMPLATEPATH ."/woocommerce/shop.php",null,true));
			
			$this->template->assign("footer",$this->loader->view($this->pathTheme."/compoment/footer.php",null,true));
			
			$this->template->render(FALSE);

        }*/
		
	
		
		
		
		
    }

?>