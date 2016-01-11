<?php
    class User extends Ahlu_post{
        
        public function __construct(){
            parent::__construct();
        
		$_SESSION['TRACKING_URL'] =  $_REQUEST['REQUEST_URI'];
         //call ovveride
         //$this->custom = Ahlu::Call("Custom_template");
		
        }
        
        public function index(){
           //echo "Home index"; 
        }
        
        //default
        public function post($id){
           echo $id; 
        }
        
        
        public function item($id){
            echo $this->url->relativeURLCodeigniter();
            
           $this->template->assign("hello",$id);
           //print_r($this->template);
           $this->template->render(FALSE);
        }
        
        
        ///////some page called if defined 
		public function login(){
			//assign data   for header 
		   $this->template->assign("meta",$this->seo->setTitle("Login")->setKeyword("Login")->setDescription("Login")->Meta());
		   
           $this->template->assign("header",$this->loader->view($this->pathTheme."/compoment/header.php",array("SEO"=>null),true));
           
		   $this->template->assign("content",$this->loader->view("ahlu-{$this->post_type}-{$this->onView}",array("WP_enable"=>$this->enWP),true));
           
		   //assign data   for footer 
		   $this->template->assign("footer",$this->loader->view($this->pathTheme."/compoment/footer.php",null,true));
		
		//output html
           $this->template->render(FALSE);
		}
		public function signup(){
			//assign data   for header 
			$this->template->assign("meta",$this->seo->setTitle("Sign up")->setKeyword("Sign up")->setDescription("Sign up")->Meta());
           $this->template->assign("header",$this->loader->view($this->pathTheme."/compoment/header.php",array("SEO"=>null),true));
           
		   $this->template->assign("content",$this->loader->view("ahlu-{$this->post_type}-{$this->onView}",array("WP_enable"=>$this->enWP),true));
           
		   //assign data   for footer 
		   $this->template->assign("footer",$this->loader->view($this->pathTheme."/compoment/footer.php",null,true));
		
		//output html
           $this->template->render(FALSE);
		}
		public function forgotpassword(){
			//assign data   for header 
			$this->template->assign("meta",$this->seo->setTitle("Forget your pasword?")->setKeyword("Forget your pasword")->setDescription("Forget your pasword")->Meta());
           $this->template->assign("header",$this->loader->view($this->pathTheme."/compoment/header.php",array("SEO"=>null),true));
           
		   $this->template->assign("content",$this->loader->view("ahlu-{$this->post_type}-{$this->onView}",array("WP_enable"=>$this->enWP),true));
           
		   //assign data   for footer 
		   $this->template->assign("footer",$this->loader->view($this->pathTheme."/compoment/footer.php",null,true));
		
		//output html
           $this->template->render(FALSE);
		}
        public function logout(){
       // SEO
         $this->custom->override("wp_title",array("title"=>$this->post->post_title)); 
         $this->custom->excute();
         
         
         
          Ahlu_insert_js("js/jquery-ui");
          
          //example buil form
          $form = Ahlu::Call("Ahlu_Form");
          $form->load();
          
          $form->assign("username",array("error"=>"require|email", "label"=>"Username","value"=>"enter your name","class"=>"required","title"=>"Please enter your name")); 
          $form->setRuleValidation("username","no"); 
          $form->Build();
         //
          $this->template->assign("hello",$this->post->post_content);  
          $this->template->render(FALSE);
        }
    }
?>