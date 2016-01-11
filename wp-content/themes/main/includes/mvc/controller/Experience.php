<?php

/**

* Category Experience Taxanomy

* 

*/

    class Experience extends Ahlu_category{

        public function __construct(){

            parent::__construct();

			//enable theme worpress;

            //$this->enWP =true; 

		    //call ovveride

            //$this->custom = Ahlu::Call("Custom_template");

			//because of no creating Post type, we will use post type in specific plugin

			//so we must defined

			$this->post_type = strtolower(get_class($this));

			$this->prefix_type="";

			////

            

        }

        

        public function index(){

			$this->template->assign("header",$this->loader->view($this->pathTheme."/compoment/header.php",array("SEO"=>null),true));

		   //assign data   for content 

		   $this->experience_model = Ahlu::Call("Experience_model","default"); 
		   $this->template->assign("meta",$this->experience_model->SEO());
		   
		   $this->template->assign("content",$this->loader->view("ahlu-{$this->post_type}-category",array("WP_enable"=>$this->enWP,"category"=>$this->experience_model),true));

		   //assign data   for footer 

		   $this->template->assign("footer",$this->loader->view($this->pathTheme."/compoment/footer.php",null,true));

		//output html

           $this->template->render(FALSE);
        }

        

        //default post detail

        public function post($id){ 
			
		   
           $this->post_model = Ahlu::Call("Experience_item_model")->load($this->post); 
		   $this->template->assign("meta",$this->post_model->SEO());
           //assign data   

           $this->template->assign("header",$this->loader->view($this->pathTheme."/compoment/header.php",null,true));

           $this->template->assign("content",$this->loader->view("ahlu-{$this->post_type}-{$this->onView}",array("WP_enable"=>$this->enWP,"post"=>$this->post_model),true));

           $this->template->assign("footer",$this->loader->view($this->pathTheme."/compoment/footer.php",null,true));

           //output html
           $this->template->render(FALSE);

        }

        

        //default category detail

        public function category(){

           $this->template->assign("header",$this->loader->view($this->pathTheme."/compoment/header.php",array("SEO"=>null),true));

           

		   //assign data   for content 

		    $this->experience_model = Ahlu::Call("Experience_model",$this->category); 
			$this->template->assign("meta",$this->experience_model->SEO());
		   $this->template->assign("content",$this->loader->view("ahlu-{$this->post_type}-{$this->onView}",array("WP_enable"=>$this->enWP,"category"=>$this->experience_model),true));

           

		   //assign data   for footer 

		   $this->template->assign("footer",$this->loader->view($this->pathTheme."/compoment/footer.php",null,true));

		

		//output html

           $this->template->render(FALSE);

        }

        //default category detail

        //////////////////example about product : product/gas 

       
	}
?>