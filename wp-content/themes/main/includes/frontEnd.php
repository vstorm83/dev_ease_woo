<?php
	
        //global avaiable
         $loader = Ahlu::Core("Loader");
         $config = Ahlu::Core("Config");
         $url = Ahlu::Library("URL");
		 $uri = Ahlu::Library("URI");	
         $router = Ahlu::Core("Router"); // one-> slug , two : mvc
		 
		//set seo for mvc
        $uri->setSEO("htm","html"); 
        $uri->setSEO("html","html"); 
         //set rules
		// these rules is forced before process
		$uri->Rules(array(
		//	"thuong-hieu/([a-zA-Z0-9\-\_]+)/([^/].*)(\.html)?"=>"PageDirect/category/$2",
			"su-kien(.*)?"=>"Portal/$1",
		));
		$router->register("portal",array("skin"));
		
		
		//set seo for mvc
		$uri->setSEO("htm","html");
		$uri->setSEO("html","html");
		$uri->Rules(array(
			"archive-([a-zA-Z0-9\_]+)[-]([0-9]{4})?[-]?([0-9]{1,2})?[-]?([0-9]{1,2})?[\/]?(([\-\/]page-[1-9]+)?[\/]?(\.html)?)?$"=>"$1/archive/$2/$3/$4",
			"ajax/(.*)"=>"$1",
			"search(.*)"=>"product/search/"
		));
		
		//set Rule, default is MVC rewrite url
		//$uri->RulesInfo("[a-zA-Z0-9\_\-]+)([0-9]{4})?[-]?([0-9]{1,2})?[-]?([0-9]{1,2})?(\.html)?$","$1/archive/$2/$3/$4");
		
		$uri->setRoot(site_url());
		$uri->process();
		
		//override plugin
		$router->register("product",array("product","Product_cat"));
		//translate controller
		//$router->translatePage("portal",array("su-kien"));
		//register or override page to process
		$router->registerPage("user",array("login","signup"));
		$router->registerPage("product",array("sitemap","checkout"));
		
		//$router->registerPage("page",array("lien-he"));
		
		//here we go
		$router->load();

		//more global
		//include_once('me.php');  
		
		
		// 3 progess from router for MVC
		
		//begin
		 $class = ucfirst($router->getController());		 
		//echo $class;
         $method = $router->getMethod();		 		
		//echo $method;
		$post = $router->getObject();

		//except for who
		if(in_array($uri->getSlug(),array("shop","cart")) || in_array(strtolower($class),array("product"))){
			$router->is_wp = true;
		}
		
		//allow
		if(in_array($uri->getSlug(),array("checkout")) || $uri->isIndex){
			$router->is_wp = false;
		}
		
		//print_r($post);
		//echo $class;
		//echo $method;
		//echo $router->is_wp;
		//die();
		define("IS_WP",$router->is_wp);
		
        //the class name have to be uppercase of letter in first word 
        if(!IS_WP && class_exists($class)){ 

			$go = new $class();
			if($uri->isIndex || (!empty($post) && !isset($post->isMVC)) || (!empty($post) && isset($post->isMVC) && intval($post->isMVC)!=1))
			//if(!in_array($method,array("page","post")) || (!empty($post) && !in_array($post->post_name,array("cart","checkout"))))
			{

			   //now we check for default controller
				if($router->isCategory()){
					
					$cat = $router->getCategory();  
					 
					 //assign object
					 $go->post = $post;
					 $go->category = $cat;
					 
					 if(method_exists($go,$method)){
						
						$method = $method;
							
						
					 }else{
						//try to get the post as method if the post inside this category and this request behind us
						if($post!=null){
							  //priority title name	
							   $c1 =  trim(strtolower($post->post_title));
							   $c2 = "post_".preg_replace('/[\+\.\-\?\'"\/;:!><\(\)\[\]\#\&\^~]+/is',"_",$c1);

							   //check action for title in Class
							   if(method_exists($go,$c1)){
									
								  $method = $c1;       

							  }else if(method_exists($go,$c2)){
								
								 //check if class exist and have the method the same as the post

								 $method = $c2; 

							  }

							  else if($config->item("enableDefault")){
								
								 $method = "post"; 

							  }

						}else if($config->item("enableDefault")){    
						 // no post find behide may be it belonged to wordpress
							
					
							$c2 = preg_replace('/[\+\.\-\?\'"\/;:!><\(\)\[\]\#\&\^~]+/is',"_",$method);
							
							if(method_exists($go,$c2)){
								//set the name of category as method
								$method = $c2;
							}else if(method_exists($go,$method) || $cat!=null) 
							{
								$method = "category";    
							}else{ 
								 //404
								$go->Get404(array("filenotfound"=>"Can not find action '{$method}'."));
							}

					   }else{

							$go->Get404(array("filenotfound"=>"Can not find action '{$method}'."));  

					   }

					} 
					
				}else{ 
					//remark as post detail

					   $c1 =  trim(strtolower($router->getObject()->post_title));

					   $c2 = preg_replace('/[\+\.\-\?\'"\/;:!><\(\)\[\]\#\&\^~\s\r\t\n]+/is',"_",$c1);

					   //check action for post

					   if(method_exists($go,$c1)){

							 //check if class exist and have the method the same as the post

							 $method = $c1; 

					  }else if(method_exists($go,$c2)){

							$method = $c2; 

					  }else if($config->item("enableDefault")){

						// $method = "post"; 

					  }  
				}
				
			   //binding param to function
			   if(!method_exists($go,$method)){
				   
				   //some time the MVC patterm from URL is the same as  MVC patterm code in the system
					//then try again
					//http://run.no-ip.info:2491/wp/shophoa/product/hoa-dai-sanh-hds4.html => from plugin wordpress
					//we have Product controller in the system and we want to put forward the request to this controller, becuase we want to override the default process
					
					//check data
					$cat = $router->getCategory();  
					if(is_object($post)){
						 //assign object
						 $go->post = $post;
						 $go->category = $cat;
						 //update method
						$method = "post";
					}else if($cat){
						 //update method
						$method = "category";
						$go->category = $cat;
					}else{
						$go->Get404(array("filenotfound"=>"Sorry can not find action <strong>'{$method}'</strong> in controller '".ucfirst($class)."'."));    
					}
			   }else{
					//now Just try to find the slug as method inside Class
					$c2 = preg_replace('/[\+\.\-\?\'"\/;:!><\(\)\[\]\#\&\^~\s\r\t\n]+/is',"_",$uri->getSlug());
					
					if(method_exists($go,$c2)){
						$method = $c2;
					}
			   }


			   //////// Call Hook
			  $go->setOnFileView(str_replace("_","-",$method));
				
				add_action( 'wp_loaded',function(){

					global $go;
					global $router;
					global $method;					
					call_user_func_array(array($go,$method),$router->getParams()==null? array(null) : $router->getParams()); 
						exit();   
				  });


			

         }else if(method_exists($go,$method)){
			
			//now Just try to find the slug as method inside Class
			$c2 = preg_replace('/[\+\.\-\?\'"\/;:!><\(\)\[\]\#\&\^~\s\r\t\n]+/is',"_",$uri->getSlug());
			if(method_exists($go,$c2)){
				$method = $c2;
			}
			
			//check hanlder from registerPage router
			$go->setOnFileView($method);
			add_action( 'wp_loaded',function(){
			
				global $go;
				global $router;
				global $method;
				call_user_func_array(array($go,$method),$router->getParams()==null? array(null) : $router->getParams()); 
				
				die(); 
				
			});		

		 }else{
			
			$cat = $router->getCategory();  
			if(is_object($post)){
				$method = !method_exists($go,$method)?"post":$method;
				$go->post = $post;
				$go->category = $cat;
				
				$go->setOnFileView($method);
				add_action( 'wp_loaded',function(){
				
					global $go;
					global $router;
					global $method;
					call_user_func_array(array($go,$method),$router->getParams()==null? array(null) : $router->getParams()); 
					
					die(); 
					
				});		
			}else if(is_object($cat)){
				$method = !method_exists($go,$method)?"category":$method;
				$go->post = $post;
				$go->category = $cat;
				$go->setOnFileView($method);
				
				add_action( 'wp_loaded',function(){
				
					global $go;
					global $router;
					global $method;
					call_user_func_array(array($go,$method),$router->getParams()==null? array(null) : $router->getParams()); 
					
					die(); 
					
				});	
				
			}
			
			
			//custom wordpress
			/* Some valid url in wordprss
				param: http://car.workteamwp.com:2491/index.php?p=1
				rewrite :
					http://car.workteamwp.com:2491/lien-he/ without extension
					http://car.workteamwp.com:2491/lien-he.html/ with extension
				invalid url:
					http://car.workteamwp.com:2491/lien-he.html
			*/
			//var_dump($uri->getSlug());
			//include TEMPLATEPATH."/404.php";
			/*if(!empty($router->is_routed)){ //check no index page //
			
				$id = url_to_postid($uri->getURI());
				echo $id;
				if($id==0 && $uri->getSlug()!=""){
					include TEMPLATEPATH."/404.php";
					die();
				}
			}*/
			
		 }
        

	}
?>