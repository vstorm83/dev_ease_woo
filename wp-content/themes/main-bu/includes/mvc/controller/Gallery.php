<?php

/**

* Category Gallery Taxanomy

* 

*/

    class Gallery extends Ahlu_category{

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

			$this->template->assign("header",$this->loader->view($this->pathTheme."/compoment/header.php",array("SEO"=>null),true));
			
		   //assign data   for content 
		   $this->Gallery_model = Ahlu::Call("Gallery_model")->load(); 

		   $this->template->assign("meta",$this->Gallery_model->SEO());
		   $this->template->assign("content",$this->loader->view("ahlu-{$this->post_type}",array("WP_enable"=>$this->enWP,"title"=>ucfirst($this->class)."s","category"=>$this->Gallery_model),true));

		   //assign data for footer 
		   $this->template->assign("footer",$this->loader->view($this->pathTheme."/compoment/footer.php",null,true));

		//output html
           $this->template->render(FALSE);
        }

        

        //default post detail
		public function comment($id){ 
			
           echo $id;

        }
		
        public function post($id){ 
			
			//check is comment post
			if(isset($_POST["ahlu"])){
				$ahlu = json_decode(stripslashes(urldecode($_POST["ahlu"])));
				$data = array(
					'comment_post_ID' => $id,
					'comment_author' => $ahlu->name,
					'comment_author_email' => $ahlu->email,
					'comment_author_url' => '',
					'comment_content' => $ahlu->comment,
					'comment_type' => '',
					'comment_parent' => 0,
					'user_id' => 0,
					'comment_author_IP' => $_SERVER['REMOTE_ADDR'],
					'comment_agent' => $_SERVER['HTTP_USER_AGENT'],
					'comment_date' => current_time('mysql'),
					'comment_approved' => 0,
				);

				wp_insert_comment($data);
				print_r(json_encode(array("d"=>array("code"=>1,"message"=>"Your Comment is sent."),"error"=>"")));
				die();
			}
			
           $this->post_model = Ahlu::Call("Gallery_item_model")->load($this->post); 
		   $this->template->assign("meta",$this->post_model->SEO());
           //assign data   

           $this->template->assign("header",$this->loader->view($this->pathTheme."/compoment/header.php",null,true));

           $this->template->assign("content",$this->loader->view("ahlu-{$this->post_type}-{$this->onView}",array("WP_enable"=>$this->enWP,"post"=>$this->post_model),true));

           $this->template->assign("footer",$this->loader->view($this->pathTheme."/compoment/footer.php",null,true));

           //output html
           $this->template->render(FALSE);

        }

        

        //default category

        public function category($id=-1){

           $this->template->assign("header",$this->loader->view($this->pathTheme."/compoment/header.php",array("SEO"=>null),true));

           

		   //assign data   for content 

		    $this->news_model = Ahlu::Call("News_model",$this->category); 
			$this->template->assign("meta",$this->news_model->SEO());
		   $this->template->assign("content",$this->loader->view("ahlu-{$this->post_type}-{$this->onView}",array("WP_enable"=>$this->enWP,"category"=>$this->ecommercial_model),true));

           

		   //assign data   for footer 

		   $this->template->assign("footer",$this->loader->view($this->pathTheme."/compoment/footer.php",null,true));

		

		//output html

           $this->template->render(FALSE);

        }
	
		//default archive
		public function archive($year=0,$moth=0,$day=0){
			$year=intval($year);
			$month=intval($moth);
			$day=intval($day);
			$data = null;
			$title =null;
			
			$this->Gallery_model = Ahlu::Call("Gallery_model"); 
			$this->template->assign("header",$this->loader->view($this->pathTheme."/compoment/header.php",array("SEO"=>null),true));
			
			if($year>0 && $moth==0 && $day==0){	
				$data = $this->Gallery_model->archiveByYear($year);
				$title = ucfirst($this->class)." {$this->onView} {$year}:";
			}else if($year>0 && $month>0 && $day==0){
				$data = $this->Gallery_model->archiveByYearMonth($year,$month);

				$title = ucfirst($this->class)." {$this->onView} on {$month}/{$year}:";
			}else if($year>0 && $month>0 && $day>0){
				$data = $this->Gallery_model->archiveByYearMonthDate($year,$month,$day);
				$title = ucfirst($this->class)." {$this->onView} on {$month}/{$day}/{$year}:";
			}	
			//
			$this->template->assign("content",$this->loader->view("ahlu-{$this->post_type}-{$this->onView}",array("WP_enable"=>$this->enWP,"title"=>$title,"data"=>$data,"category"=>$this->Gallery_model),true));

			$this->template->assign("meta",$this->Gallery_model->SEO());
			$this->template->assign("footer",$this->loader->view($this->pathTheme."/compoment/footer.php",null,true));
	
			//output html
            $this->template->render(FALSE);
		}

        //////////////////example about product : product/gas 
		
		
       
	}
?>