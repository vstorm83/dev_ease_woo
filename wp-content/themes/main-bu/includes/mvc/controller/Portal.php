<?php

/**

* Category Portal Taxanomy

* 

*/

    class Portal extends Ahlu_category{

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
			$this->post_type = "portal";
			$this->prefix_type="";

			////
			//track url root
            $_SESSION["track__url"]  = $this->post_type.".html";
			
			//use template 
			$this->enableTemplate();
			
			error_reporting(0);
        }

        

        public function index(){

			$this->template->assign("header",$this->loader->view($this->pathTheme."/compoment/header.php",array("SEO"=>null),true));
	
		   //assign data   for content 
		   $this->blog_model = Ahlu::Call("Portal_model"); 
		   $this->blog_model->post_type= "skin";
		   $this->blog_model->taxonomy = "skin_genre";
		   
			$meta = $this->blog_model->SEO();
			if(is_object($meta)){
				$meta->seo = Ahlu::Library("Ahlu_SEO");
				$meta->seo->setTitle("Sự Kiện");
				$meta->seo->setKeyword("Sự Kiện");
				$meta->seo->setDescription("Sự Kiện");
				$meta->seo->setCanonical(site_url($this->post_type.".html"));
				
				$meta = $meta->Meta();
			}
		   $this->template->assign("cls","{$this->post_type} {$this->post_type}-category category");
		   $this->template->assign("meta",$meta);
		   $this->template->assign("content",$this->loader->view("ahlu-portal",array("WP_enable"=>$this->enWP,"category"=>$this->blog_model),true));

		   //assign data for footer 
		   $this->template->assign("footer",$this->loader->view($this->pathTheme."/compoment/footer.php",null,true));

		//output html
           $this->template->render(FALSE);
        }
		/**
		* Search
		* 
		*/
         public function search($q=null){
			
			if($q==null){
				$q = isset($_REQUEST["s"]) ? $_REQUEST["s"] : null;
			}
			
			
			if($q==null){
			
			}
			$this->template->assign("header",$this->loader->view($this->pathTheme."/compoment/header.php",array("SEO"=>null),true));
	
		   //assign data   for content 
		   $this->blog_model = Ahlu::Call("Blog_model")->load(); 

		   $this->template->assign("meta",$this->blog_model->SEO());
		   $this->template->assign("content",$this->loader->view("ahlu-{$this->post_type}-{$this->onView}",array("WP_enable"=>$this->enWP,"title"=>ucfirst($this->class)."s","query"=>$q,"category"=>$this->blog_model,"data"=>$this->blog_model->searchPostType($q,10,URI::getInstance()->page)),true));

		   //assign data for footer 
		   $this->template->assign("footer",$this->loader->view($this->pathTheme."/compoment/footer.php",null,true));

		//output html
           $this->template->render(FALSE);
        }

        /**
		* Comments
		* 
		*/
		public function comment($id){ 
			
           echo $id;

        }
		
        public function post($id){ 
			
			//check is comment post
			if(isset($_POST["ahlu"])){
				$ahlu = json_decode(stripslashes(urldecode($_POST["ahlu"])));
				if(isset($ahlu->comment)){
					$data = array(
						'comment_post_ID' => $id,
						'comment_author' => $ahlu->name,
						'comment_author_email' => $ahlu->email,
						'comment_author_url' => $ahlu->url,
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
			}
			
           $this->post_model = Ahlu::Call("Portal_item_model")->load($this->post); 
		   $this->post_model->post_type ="skin";
		   $this->template->assign("meta",$this->post_model->SEO());

           $this->template->assign("header",$this->loader->view($this->pathTheme."/compoment/header.php",array("post"=>$this->post_model),true));
		   
		   $view = "ahlu-{$this->post_type}-{$this->onView}";
			$cls = "";
		   switch(strtolower($this->post->post_name)){
				case 'contact-us':
					$view = "ahlu-{$this->post_type}-post-contact-us";
					$cls = $this->post_model->getMe()->post_name;
				break;
				case 'news':
					$view = "ahlu-news";
					 $cls = $this->post_model->getMe()->post_name;
				break;
				defautl:
				
				break;
		   }
			$cls = $this->post_model->getMe()->post_name ." portal-post post-{$this->post_model->getMe()->ID}";

			$this->template->assign("cls",$cls);
			
           $this->template->assign("content",$this->loader->view($view,array("WP_enable"=>$this->enWP,"post"=>$this->post_model),true));

           $this->template->assign("footer",$this->loader->view($this->pathTheme."/compoment/footer.php",null,true));

           //output html
           $this->template->render(FALSE);

        }

        

        //default category

        public function category($id=-1){

           $this->template->assign("header",$this->loader->view($this->pathTheme."/compoment/header.php",array("SEO"=>null),true));

		   //assign data   for content 

		    $this->blog_model = Ahlu::Call("Blog_model",$this->category); 
			$this->template->assign("meta",$this->blog_model->SEO());
		   $this->template->assign("content",$this->loader->view("ahlu-{$this->post_type}-{$this->onView}",array("WP_enable"=>$this->enWP,"category"=>$this->blog_model),true));

           

		   //assign data   for footer 

		   $this->template->assign("footer",$this->loader->view($this->pathTheme."/compoment/footer.php",null,true));

		

		//output html

           $this->template->render(FALSE);

        }
	
		//default archive
		public function archive($year=0,$month=0,$day=0){

			$year=intval($year);
			$month=intval($month);
			$day=intval($day);
			$data = null;
			$title =null;
			
			$this->blog_model = Ahlu::Call("Blog_model"); 
			$this->template->assign("header",$this->loader->view($this->pathTheme."/compoment/header.php",array("SEO"=>null),true));
			
			if($year>0 && $month==0 && $day==0){	
				$data = $this->blog_model->archiveByYear($year,10,URI::getInstance()->page);
				$title = ucfirst($this->class)." {$this->onView} {$year}:";
			}else if($year>0 && $month>0 && $day==0){
				$data = $this->blog_model->archiveByYearMonth($year,$month,10,URI::getInstance()->page);
				$title = ucfirst($this->class)." {$this->onView} on {$month}/{$year}:";
			}else if($year>0 && $month>0 && $day>0){
				$data = $this->blog_model->archiveByYearMonthDate($year,$month,$day,10,URI::getInstance()->page);
				$title = ucfirst($this->class)." {$this->onView} on {$month}/{$day}/{$year}:";
			}	
			//
			$this->template->assign("content",$this->loader->view("ahlu-{$this->post_type}-{$this->onView}",array("WP_enable"=>$this->enWP,"title"=>$title,"data"=>$data,"category"=>$this->blog_model),true));

			$this->template->assign("meta",$this->blog_model->SEO());
			$this->template->assign("footer",$this->loader->view($this->pathTheme."/compoment/footer.php",null,true));
	
			//output html
            $this->template->render(FALSE);
		}

        //////////////////example about product : product/gas 

       
	}
?>