<?php
    class Page extends Ahlu_post{
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
			
            $this->template->assign("header",$this->loader->view($this->pathTheme."/compoment/header.php",null,true));

           $this->template->assign("content",$this->loader->view("ahlu-{$this->post_type}",array("WP_enable"=>$this->enWP,"post"=>$this->post),true));

           $this->template->assign("footer",$this->loader->view($this->pathTheme."/compoment/footer.php",null,true));

           //output html
           $this->template->render(FALSE);
        }
        
        //default
        public function post($id){
		   $cls = strtolower(__CLASS__);
		   $this->template->assign("cls","{$cls} {$this->post->post_name} {$this->post->post_name}-{$cls} {$cls}-{$this->post->ID}");

		   $this->loadPost($id);
			
		   $this->template->assign("meta",$this->post->SEO());
		   $this->template->assign("header",$this->loader->view($this->pathTheme."/compoment/header.php",null,true));

           $this->template->assign("content",$this->loader->view("ahlu-{$this->post_type}-{$this->onView}",array("WP_enable"=>$this->enWP,"post"=>$this->post),true));

           $this->template->assign("footer",$this->loader->view($this->pathTheme."/compoment/footer.php",null,true));

           //output html
           $this->template->render(FALSE);
        }
		
								
		
	
    }
?>