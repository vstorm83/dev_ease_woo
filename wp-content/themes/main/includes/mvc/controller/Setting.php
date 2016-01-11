<?php

/**

* Category Blog Taxanomy

* 

*/

    class Setting extends Ahlu_post{

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
			
			////
			//track url root
            $_SESSION["track__url"]  = $this->post_type.".html";
			
			//use template 
			$this->enableTemplate();
        }

        

        public function index(){

			echo  $_SESSION["track__url"];
        }
        //////////////////example about product : product/gas 

       
	}
?>