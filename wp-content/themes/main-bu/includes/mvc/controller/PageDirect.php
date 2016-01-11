 <?php
/**
* Category Perfume PageDirect
* 
*/
    class PageDirect extends Ahlu_category{
        
        public function __construct(){
            parent::__construct();
			//enable theme worpress;
            //$this->enWP =true; 
		    //call ovveride
            //$this->custom = Ahlu::Call("Custom_template");
			
			//because of no creating Post type, we will use post type in specific plugin
			//so we must defined
			$this->post_type = "page";
			$this->prefix_type="";
			////
            
        }
        
        public function index(){
			echo "hello";
           //$this->showTemplate(array());
        }
        
        //default post detail
        public function post($id){ 
            $this->post_model = Ahlu::Call("Ecommercial_item_model")->load($this->post); 
           //assign data   
           $this->template->assign("header",$this->loader->view($this->pathTheme."/compoment/header.php",null,true));
           $this->template->assign("content",$this->loader->view("ahlu-{$this->post_type}-{$this->onView}",array("WP_enable"=>$this->enWP,"post"=>$this->post_model),true));
           $this->template->assign("footer",$this->loader->view($this->pathTheme."/compoment/footer.php",null,true));
        
        //output html
           $this->template->render(FALSE);
        }
        
        //default category detail
        public function category(){
			//now stor session tracking url for cart
			$_SESSION['cart_url'] = $_SERVER['REQUEST_URI'];
			
			
			//assign data   for header 
           $this->template->assign("header",$this->loader->view($this->pathTheme."/compoment/header.php",array("SEO"=>null),true));
           
		   //assign data   for content 
		   $this->ecommercial_model = Ahlu::Call("Ecommercial_model",$this->category); 
		   //print_r($this->ecommercial_model->getMe());
        
		   $this->template->assign("content",$this->loader->view("ahlu-{$this->post_type}-{$this->onView}",array("WP_enable"=>$this->enWP,"category"=>$this->ecommercial_model),true));
           
		   //assign data   for footer 
		   $this->template->assign("footer",$this->loader->view($this->pathTheme."/compoment/footer.php",null,true));
		
		//output html
           $this->template->render(FALSE);
        }
        //default category detail
        public function search(){
			//assign data   for header 
           $this->template->assign("header",$this->loader->view($this->pathTheme."/compoment/header.php",null,true));
           
		   //assign data   for content 
		   $this->ecommercial_search_model = Ahlu::Call("Ecommercial_search_model",isset($_REQUEST["q"]) ? $_REQUEST["q"] : null); 
		   //print_r($this->ecommercial_model->getMe());
        
		   $this->template->assign("content",$this->loader->view("ahlu-{$this->post_type}-{$this->onView}",array("WP_enable"=>$this->enWP,"search"=>$this->ecommercial_search_model),true));
           
		   //assign data   for footer 
		   $this->template->assign("footer",$this->loader->view($this->pathTheme."/compoment/footer.php",null,true));
		
		//output html
           $this->template->render(FALSE);
        }
        //////////////////example about product : product/gas 
            
        public function item($id){
          //  echo $this->url->relativeURLCodeigniter();
          
          print_r(Database::CategoryByPost_Type("hello"));
          
           $this->template->assign("hello",$id);
           //print_r($this->template);
           $this->template->render(FALSE);
        }

        public function checkout(){
		   $data = array();
		   $data["SEO"] =  "checkout";
		   
           //assign data   for header 
           $this->template->assign("header",$this->loader->view($this->pathTheme."/compoment/header.php",null,true));
           
		   //assign data   for content 
		   $this->cart_model = Ahlu::Library("Cart_model"); 
		   $this->cart_model->name_page = "Check out";
		   
		   //print_r($this->ecommercial_model->getMe());
        
		   $this->template->assign("content",$this->loader->view("ahlu-{$this->post_type}-{$this->onView}",array("WP_enable"=>$this->enWP,"cart"=>$this->cart_model),true));
           
		   //assign data   for footer 
		   $this->template->assign("footer",$this->loader->view($this->pathTheme."/compoment/footer.php",null,true));
		
		//output html
           $this->template->render(FALSE);
        }
		public function cart(){
            $data = array();
		   $data["SEO"] =  "shopping cart";
		   
           //assign data   for header 
           $this->template->assign("header",$this->loader->view($this->pathTheme."/compoment/header.php",null,true));
           
		   //assign data   for content 
		   $this->cart_model = Ahlu::Library("Cart_model"); 
		   $this->cart_model->name_page = "Shopping Cart";
		   
		   //print_r($this->ecommercial_model->getMe());
        
		   $this->template->assign("content",$this->loader->view("ahlu-{$this->post_type}-{$this->onView}",array("WP_enable"=>$this->enWP,"cart"=>$this->cart_model),true));
           
		   //assign data   for footer 
		   $this->template->assign("footer",$this->loader->view($this->pathTheme."/compoment/footer.php",null,true));
		
		//output html
           $this->template->render(FALSE);
        }
    }
?>