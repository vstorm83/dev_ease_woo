<?php
    class Home extends Ahlu_post{
        public function __construct(){
          parent::__construct();

			//enable theme worpress;

            //$this->enWP =true; 

		    //call ovveride

            //$this->custom = Ahlu::Call("Custom_template");

			if(!isset($_COOKIE['NHIEM']))

				setcookie('NHIEM',$first_name,time() + (86400 * 7)); // 86400 = 1 day
	
			
			
			
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
			
	$this->template->assign("cls","index");
	
	$meta= Ahlu::Library("Ahlu_SEO");
	$meta->setTitle("EASE");
	$meta->setKeyword("EASE");
	$meta->setDescription("EASE");
	$meta->setCanonical(site_url(__FUNCTION__));
	$meta = $meta->Meta();
			
		   $this->template->assign("meta",$meta);		
			
            $this->template->assign("header",$this->loader->view($this->pathTheme."/compoment/header.php",null,true));

           $this->template->assign("content",$this->loader->view("ahlu-{$this->post_type}",array("WP_enable"=>$this->enWP,"post"=>$this->post),true));

           $this->template->assign("footer",$this->loader->view($this->pathTheme."/compoment/footer.php",null,true));

           //output html
           $this->template->render(FALSE);
        }
	
    }
?>