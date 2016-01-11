<?php

    class Router{

        //may be a post or term(we can get this term via post_parent of post)

        protected $post;
        protected $category;
        private $is_cate = false;
        protected $segments;

        protected $folder;
        protected $class;
        protected $control;
        protected $method;  
        protected $params = null;
        protected $tranlatePage = array();

        public  $is_wp = 0;
		public $url_routed = null;
        
        //store plugin as post type

        protected $plugins = array();
        protected $pagesMVC = array();
        

        public function __construct(){

          $this->config = Ahlu::Core("Config");  

          $this->db = Ahlu::Core("Database"); 

          $this->url = Ahlu::Library("URL");

          $this->lang = Ahlu::Call("Language");  

          $this->uri = Ahlu::Library("URI");

          
			return $this;
        }

        
		public function setSEO($k,$v){
			if(is_string($k) && is_string($v)){
				$this->SEO[$k] =$v;
			}
			return $this;
		}
		
		public function getSEO($k){
			return isset($this->SEO[$k]) ? $this->SEO[$k]: "";
		}
		public function is_WP(){
			return $this->is_wp;
		}
		
        public function load(){
			
			//check if is index page
			if($this->uri->isIndex){
				$this->class = $this->config->item("default_controller");

				 $this->method = "index"; 

				 $this->params = array();
				return;
			}
			
			//check if direct page for MVC
			if($this->uri->is_301){
				$segments = $this->uri->getMVCRewrite();
				$this->parseRoute($segments);
				return;
			}
			
		   //for wordpress
		   /*
			url rewirite:
				http://car.workteamwp.com:2491/lien-he.html/ with extension in wordpress
				http://car.workteamwp.com:2491/lien-he.html may be invalid url for rewrite in wordpress
		   */
		   
		   if($this->uri->getSEOExt()){//with extension
				$id = url_to_postid($this->uri->getURI());
		   }else{ //
				$id = url_to_postid($this->uri->getURI().".html/");	
		   }
		   
		   
           if($id!=0){ //default wordpress
			
                $this->is_wp = true; 

               //exist page,post,attachment

               $this->post = Database::getPost($id);

               //get slug
               $slug = $this->post->post_name;
               //set mvc

               switch(strtolower($this->post->post_type)){
                  //mot so mat dinh

                   case "post":

                   case "page":
                         $this->class =  "page";

                         //check if class exist and have the method the same as the post

                          //$this->method = $slug; 
                         $this->method = "post"; 

                         $this->params = array($id);

                   break;

                   case "attachment": 

                        $this->class = "Ahlu_".$this->post->post_type;

                        $this->method = "index";

                        $this->params = array($id);  

                   break;
					default:
					
						$this->class = $this->config->item("default_controller");

                         //check if class exist and have the method the same as the post

                         $this->method = $slug; 

                         $this->params = array();
					break;
               }
			   
			   return;
           }else{ // not is wordpress
				
               $this->is_cate =true;
				
               //check is slug  
				if(!$this->uri->is_301){
				  $slug = $this->uri->getSlug();
			
				  //get slug from post , category, user
				  
					$data = $slug!=null ? (array)Database::getIdBySlug($slug) : null;
					
                  //here we go, we check this url is not is 301 , because 301 is for MVC
					if(is_array($data) && count($data)>0){

						 foreach($data as $k=>$v){
					
							 if($v!=null){

								 if($this->$k($v)){
								    
									break;
								}								 

							 }

						 }  

                    }
                  }

				// here we go, get full MVC url
				$this->segments =  $this->uri->getMVCRewrite(); 
				//now we parse rount
				$this->parseRoute($this->segments);
				
           }

          

          //load language

          $this->lang->load("en");    
		  return $this;
		  
		
        }

        //////////////////// Set action from slug

        private function post($post){

               $this->post =  $post;
					
               //get meta post

			   mysql_query("SET SESSION group_concat_max_len = 1000000;");

             $a =   Ahlu::DB()->get_results("

               SELECT p.* ,group_concat(meta separator '\$ahlu\$') as meta FROM (

                        SELECT

                            CASE

                                WHEN meta_value IS NULL THEN CONCAT(meta_key, '=', \"null\")

                                ELSE  CONCAT(meta_key, \"=\", meta_value)

                            END AS meta,post_id

                        FROM ".Ahlu::DB()->postmeta." 

                    ) as mp ,".Ahlu::DB()->posts." p, ".Ahlu::DB()->term_relationships." term_r

where mp.post_id= p.ID and p.ID={$post->ID}");

              //alway

                if(is_array($a) && count($a)>0){

						$obj = $a[0];

						if(isset($obj->meta)){

						$meta = explode('$ahlu$',$obj->meta);

						foreach($meta as  $v){
								
								


				
							$k=explode("=",ltrim($v,"_"));

							$obj->{$k[0]} = StringUtil::is_serialized($k[1]) ? unserialize($k[1]) : $k[1];

						}

						unset($obj->meta);

						unset($meta);

						

						if(isset($obj->thumbnail_id)){

							$query = "SELECT post.guid from  ".Ahlu::DB()->posts." as post where post.ID={$obj->thumbnail_id}";

							 //echo $query;

							$a = Ahlu::DB()->get_results($query);

							//print_r($a);

							if(count($a)>0){

								unset($obj->thumbnail_id);

							   $obj->thumbnail =$a[0]->guid;

							}

						}

					}

					 $this->post = $obj;

				}			

				$this->category = Database::CategoryByPost($post->ID);
		
			return true;
        }

        private function category($category){

            $this->category = $category;
		
			 if(!$this->uri->is_301){ //we know that slug has data and post type
				 $post_type = Database::PostTypeByCategory($category->slug);
				 if(!empty($post_type)){
					$this->class = str_replace("_ahlu","",$post_type);
					$this->params = array($this->category->id_term);
					$this->method = $category->slug; 
					return true;
				 }													
				return false;
			}else{ //check url , normal is MVC
				$segments = $this->uri->Location(false); 
				$len = count($segments);
				
				$this->class = array_shift($segments);
				$this->method = array_shift($segments);
				array_values($segments);
				if($len == 2){
					//we have C,M
					
				}else if($len >2){
					if(strtolower($this->method)=="category"){
						//defualt category ,so we params +1
						array_shift($segments);
						$this->params = $segments;
					}
				}
			}
			return true;
        }

        private function taxanomy($obj){

        }

        private function attachment($obj){

        }

        //.............................

        private function setDefault(){

            //we use WP

            $this->is_WP = true;  

            

              $this->class = $this->config->item("default_controller");

              $this->method = "index"; 

        }

        

        private function parseRoute($segments){
			

            if($segments==null){

                //set default

                $this->setDefault();

                return;

            }

			

             //post_tpe -> controller

             //only controller/   or slug   

             if(count($segments)==1){
			 
				$except = array("nav_menu_item");
				if($this->post!=null && $this->category!=null &&  !in_array($this->post->post_type,$except)){
						$this->class = str_replace("_ahlu","",$this->post->post_type);
					  $this->method = $this->post->post_name;
					  $this->params = array($this->post->ID);
					  
					
					return $this;
				}
				if($this->post==null && $this->category!=null){

					return $this;
				}
				
				if($this->post!=null && $this->category==null &&  !in_array($this->post->post_type,$except)){
					$this->is_cate =true;
					  $this->class = !empty($this->post->post_type)? str_replace("_ahlu","",$this->post->post_type) : "home";
					  $this->method = $this->post->post_name;
					  $this->params = array($this->post->ID);
					return $this;
				}

				//first get controller 	
				$this->is_cate =true;
				  $this->class = $segments[0];
				  $this->method = "index";
				  $this->params = array();
				return $this;

			 }else{
				//check the folder follow MVC pattern
				// controller/method/slug or controller/slug 
				$this->class = array_shift($segments);  
                array_values($segments);
				$this->method = array_shift($segments);
				$this->params = $segments; 
			}

		  return $this;
			
        }

        /**

        * Regiater plugin as post type

        * 

        * @param mixed $class

        * @param mixed $plugin

        */

        public function register($class,$plugin){

            if(is_string($class) && is_string($plugin)){

                $this->plugins[strtolower($class)] = strtolower($plugin);

            }else if(is_array($plugin)){

                $this->plugins[strtolower($class)] = $plugin;

            }

                

            return $this;

        }

		/**

        * Regiater Page for specific MVC

        * 

        * @param mixed $class

        * @param mixed $actions

        */

        public function registerPage($class,$actions){

            if(is_string($class) && is_string($actions)){

                $this->pagesMVC[strtolower($class)] = strtolower($actions);

            }else if(is_array($actions)){

                $this->pagesMVC[strtolower($class)] = $actions;

            }


            return $this;

        }
		
		public function translatePage($controller,$needleC){

            if(is_string($controller) && is_string($needleC)){

                $this->tranlatePage[strtolower($controller)] = strtolower($needleC);

            }else if(is_array($needleC)){

                $this->tranlatePage[strtolower($controller)] = $needleC;

            }

               

            return $this;

        }
        /**

        * Get object post

        * 

        */

        public function getObject(){

            return $this->post;

        }

        public function getCategory(){

            return $this->category;

        }

        public function isCategory(){

             return $this->is_cate;

        }

        ////////////////////////////////////////

        public function getFolder(){

             return $this->folder;

        }

        public function getController(){

          //check the class is plugin will be replaced by defined class


          foreach ($this->plugins as $class => $plugin) {

          //echo $this->class;

            if (is_string($plugin) && strtolower($this->class) == $plugin) {
				$this->is_wp = false;
               return $class;

            }else if(is_array($plugin)){

                 

                  foreach ($plugin as $cls) {

                      if(strtolower($this->class) == strtolower($cls)){  
						$this->is_wp = false;
                          return $class;
						}
                  }

             }

          }
				
          // check whether the page registered with MVC, the action is Class now

			foreach ($this->pagesMVC as $class => $action) {
				//if this request belong to wp, this request is overrided by post_name
				//otherwise determinate as MVC slug
			
				$slug = strtolower($this->post?$this->post->post_name :$this->method);
				//ask for html page
				if(preg_match("/\.([a-z]){1,4}$/is", $slug, $u)){
						if(in_array($u[0],$this->SEO)){
							$slug = str_replace($u[0],"",$slug);
						}
				  }

				if (is_string($action) &&  $slug== $action) {
					$this->is_wp = false;
				   $this->method = $slug;  

				   $this->class = $slug;

				 }else if(is_array($action) && in_array($slug,$action)){
					//
					$this->is_wp = false;
					$this->method = $slug;  

				    $this->class = $class;

				 }else if((is_array($action) && in_array($this->uri->getSlug(),$action)) || ($this->uri->getSlug()== $action)){
					//treat the slug as a page with is register as MVC via method registerPage function
					$this->is_wp = false;
					$this->method = $this->uri->getSlug();  

				    $this->class = $class;

				 }

			}  
			
			//retry to find is current slug is inside the page which is registered on MVC

			
			
          return $this->class;

        }

        public function getMethod(){
			if(empty($this->method)) return "index";
			$this->method = preg_replace('/[\+\.\-\?\'"\/;:!><\(\)\[\]\#\&\^~\s\r\t\n]+/is',"_",$this->method);
			 // sometime the method ending with seo like html,.html
             return str_replace($this->SEO,"",$this->method);

        }

        public function getParams(){

             return $this->params;

        }

        /**

        * Get post , default is Null

        * 

        */

        public function getPostItem(){

            return $this->post;

        }

    }

?>