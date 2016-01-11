<?php

    class URI{

       protected $segments = null;
       protected static $me = null;

       protected $uri = null;
		
       public $page =1;

       protected $query = null;
       protected $queryString = "";

       public static $domain = null;

       protected $subdomain = null;

       protected $slug = null;
	   protected $lastSlug  = null;
       protected $root = null;
	   protected $SEO = array();
	   protected $currentEXT = null;

       public $is_301 = false;
       public $isIndex = false;

       		//set rules redirect
        protected $rules = array();
        protected $rules_default = array(
			":any"=>".*",
			":num"=>"[0-9]",
			":string"=>"[a-zA-Z]",
		);

       public function __construct(){
			
		   URI::$me = $this;
		   
           return $this;
       }

       public static function getInstance(){
		   if(URI::$me==null){
			URI::$me = new self();
		   }
           return URI::$me;
       }
		
       public function setRule($k,$v){
			if(is_string($k) && is_string($v)){
				$this->rules[$k] =$v;
			}
			return $this;
		}
		public function Rules($arr){
			if(is_array($arr))
				$this->rules = $this->rules==null ? $arr : array_merge($this->rules,$arr);
			return $this;
		}
		/*
		*set Rule, default is MVC rewrite url
		*/
		public function RulesInfo($uri,$newPath,$isMVC=true){
			if(is_string($uri) && is_string($newPath)){
				$this->rules[$uri] = $newPath; 
			}
			return $this;
		}
		public function setRoot($root){
			$this->root =  $root;
		}
		
		public function setSEO($k,$v){
			if(is_string($k) && is_string($v)){
				$this->SEO[$k] =$v;
			}
			return $this;
		}
		public function getSEOExt(){
			return $this->currentEXT;
		}
		
       public function process(){

           self::$domain = (isset($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS']) == "on" || '1' == $_SERVER['HTTPS'])? "https://" : "http://").$_SERVER['HTTP_HOST'];
		   
			//turn www into non-www
			$this->root = str_replace("www.","",$this->root); 
			
			//Check real path for behide domain name
			/*
			* abc.com -> host.com/web/....
			* abc.com -> abc.com/
			*/

			//check real path for behide domain name
			if(strpos($this->root,$_SERVER['HTTP_HOST'])=== false){
				$this->root = self::$domain;
				$i = self::$domain;
				$seo = isset($this->SEO["html"]) ? ".".$this->SEO["html"] : "";


				//override site_url
				add_filter( 'site_url',function ($url) use ($i,$seo)
	            {
					if(get_bloginfo('url')==$url){
						return $url;
					}
	                if( is_admin() ) // you probably don't want this in admin side
				        return $url;
					
					$replace = basename($url);
					if(strripos($seo,$url) !==FALSE) return $i."/".$replace;
				    return $i."/".$replace.$seo ;
	            });

				//we need change .htaccess file for compatibility
			}else{
				$seo = $this->SEO;
				//override site_url
				add_filter( 'site_url',function ($url) use ($seo)
	            {
					if(get_bloginfo('url')==$url){
						return $url;
					}
	                if( is_admin() ) // you probably don't want this in admin side
				        return $url;
						
				    $a = explode("/", $url);
				    $a = $a[count($a)-1];
				    if(!empty($a) &&  !preg_match('/\.[a-zA-Z]{2,4}$/is',$a,$m)){
				    	return $url.(isset($seo["html"]) ? ".".$seo["html"] : "") ;
				    }
				    return $url ;
	            });
			}
		   
            

			$uri = empty($this->root)? $_SERVER["REQUEST_URI"]: str_replace($this->root,"",self::$domain.$_SERVER["REQUEST_URI"]);
			//find query 
		   $pos= strpos($uri,"?");
			
			//remove query
           if($pos !== false){
				$this->queryString = substr($uri,$pos);
				parse_str(substr($uri,$pos+1),$this->query);
				$uri=substr($uri,0,$pos);
				$this->uri_name = $uri;
	
		   }
			
			if(self::$domain.$_SERVER["REQUEST_URI"]==$this->root."/"){
				$this->isIndex = true;
			}else if(str_replace("www.","",self::$domain.$_SERVER["REQUEST_URI"])==$this->root."/"){
				$this->isIndex = true;
			}else if($uri=="/"){
				$this->isIndex = true;
			}

			if(preg_match("/^((http|https|ftp|mail):\/\/(.*)\/)(.*)/i", $uri,$a)){
				$uri = str_replace($a[1],"",$uri);
			}

			
		   //join with post
		   //parse query 
		   if(count($_POST)>0 && is_array($this->query)){
				
				$this->query = array_merge($this->query,$_POST);

			}

		   
		   if($uri!="/"){
		   // remove double '-'
           $uri = preg_replace('/(\\-)+/',"-",$uri);
		   //set current
		   $this->lastSlug = ltrim($uri,"/");
		   
		   if($uri=="/") return;
           //check redirect
		   
		   if(is_array($this->rules)){
				
				foreach($this->rules as $f => $t){
					
					if(preg_match('#'.$f.'#is',$uri,$m)){

						//check language
						$uri = trim(preg_replace("#$f#",$t,$uri),"/");
						
						$this->is_301 = true;
						//check query
						break;
					}
				}
		   }
			//http://localhost/dartist/archive-blog-2014-12-page-1.html
			//http://localhost/dartist/archive-blog-2014-12-page-1 
			//http://localhost/dartist/archive-blog-2014-12-page-1/
			//http://localhost/dartist/archive-blog-2014-12/page-1
			//http://localhost/dartist/archive-blog-2014-12/page-1/ 
			//http://localhost/dartist/archive-blog-2014-12/page-1/.html
			//http://localhost/dartist/archive-blog-2014-12.html
			//http://localhost/dartist/archive-blog-2014-12/
			//http://localhost/dartist/archive-blog-2014-12
			
           //parse page 
           if(preg_match('/([\/\-]?(page|pagenum|num|paging))[\-\/](\d+)[\/]?'.(is_array($this->SEO) && count($this->SEO)>0?"(\.(".implode("|",$this->SEO)."))?":"").'$/is',$uri,$a)){

			   $this->currentEXT = $a[4];
			   //set real url
			    $this->uri = ltrim(str_replace($a[0],"",$uri),"/");
				$this->lastSlug = $this->uri;
				$this->page = intval($a[3]);
				
				
			    //get last name folder in current url
				// ab
				// sub/../ab
			    $this->segments = explode("/",$this->uri);
				$this->slug = end($this->segments);
			
				$this->url=rtrim(self::$domain."/".$this->uri,"/"). (!empty($this->currentEXT) ? "" : "$this->currentEXT/");
				//echo ($this->url);die();
           }else if( is_array($this->SEO) && count($this->SEO)>0 && preg_match('/\.('.implode("|",$this->SEO).')$/is',$uri,$a)){ //check extension
				$this->currentEXT = $a[0];
				$this->uri =  ltrim(str_replace($a[0],"",$uri),"/");
				$this->lastSlug = $this->uri;
				$this->segments = explode("/",$this->uri);
				
				//get last name folder in current url
				// ab
				// sub/../ab
				$this->slug = end($this->segments);
				//echo $this->slug;
				//update
				$this->uri = rtrim(self::$domain.$_SERVER["REQUEST_URI"],"/")."/";
		   }else{

					//try to find the extension, maybe for behind url
					 if($this->is_301){
						if(is_array($this->SEO) && count($this->SEO)>0 && preg_match('/\.('.implode("|",$this->SEO).')$/is',$this->lastSlug,$a))
						{
							$this->currentEXT = $a[0];
						   $this->lastSlug = trim(str_replace($a[0],"",$this->lastSlug),"/"); 
					   }
					  
					   if(preg_match('/[\/\-](page|pagenum|num|paging)[\-\/](\d+)[\/]?$/is',$this->lastSlug,$a)){
								$this->lastSlug = trim(str_replace($a[0],"",$this->lastSlug),"/"); 
								$this->page = intval($a[2]);
						}

					 }
					 
					$this->segments = explode("/",trim($uri,"/"));
					// ab
					// sub/../ab
					if($uri[strlen($uri)-1] !="/"){
						//get last item as slug name
						
						$a  =explode("/",$uri);
						//print_r($a);
						$this->slug = end($a);

						$this->uri = self::$domain.$_SERVER["REQUEST_URI"];
						
						
					}else{
						
						// ab/
						// sub/..ab/
						$uri = explode("/",trim($uri,"/"));
						$this->slug = array_pop($uri);
						
						$this->uri = rtrim(self::$domain.$_SERVER["REQUEST_URI"],"/")."/";
					}
				
				
				}
		
           }else{
				$this->uri = $uri;
		   }
			

         //$this->query = Ahlu::Library("URL")->QueryString();

         return $this;

       }
		
		public function  getMVCRewrite(){
			return $this->segments;
		}
       

       public function Location($return = true,$queryandanchor=true){ 

            $uri = null;

            $root_domain = ABSPATH;//Document_root physical

            $theme_path = TEMPLATEPATH;  //theme root path physical

            

            $uri_to_theme = get_template_directory_uri();

            $uri_to_root = site_url();

            //echo site_url();

            

            if(!$queryandanchor)

            {

               $uri = parse_url($this->uri);

               $uri = self::$domain.$uri["path"];  

            }elsE{

               $uri = self::$domain . $this->uri;  

            }

            

           // $subdomain = str_replace(str_replace(array("/","\\"),DIRECTORY_SEPARATOR,$_SERVER["DOCUMENT_ROOT"]),"",TEMPLATEPATH); 

           // echo $subdomain;

           //now if subdomain exist

          $subdomain= str_replace(site_url(),"",$uri); 

          //echo $subdomain;

           if(strlen($subdomain)>1){

               $this->_is_subdomain["is_sub"] = true;

                 $this->_is_subdomain["name"] = $subdomain;  

                  

                 $subdomain = explode("/",$subdomain);

                 $as =array();

                //print_r($subdomain);   

                 

                 foreach($subdomain as $v){

                     if(!empty($v))

                        $as[]=$v ;

                 }

                 $this->_is_subdomain["level"] = count($as);

                 

               

                 //print_r($as);

                 $uri = parse_url($uri);

                 $a = explode("/",$uri["path"]);

                 

                 //check url request must be greater than subdomain

                 if(count($a)<= count($as)){

                     return "";

                 }

                  //print_r($as);

                  return  !$return? $as : implode("/",$as);   



           }

        }

       

       public function getSlug(){ 

            return $this->slug;

        }

       /**

       * Create url paging with current  url

       * 

       * @param mixed $uri

       */

       public function createPage($num=1){
              return rtrim(str_replace($this->currentEXT,"",$this->lastSlug),"/")."-page-".$num.($this->currentEXT?"{$this->currentEXT}":"").$this->queryString; ; 
			  //return $this->createRandomPage($num);
       }
	   public function createRandomPage($num=1){
			$a = array("%s-%s-%s","%s/%s-%s/");
			$i = rand(0,1);
			
			$s = sprintf($a[$i],rtrim(str_replace($this->currentEXT,"",$this->lastSlug),"/"),"page",$num).($this->currentEXT?"{$this->currentEXT}":"");
              return $s.$this->queryString; 
       }
	   /*
	   * Get full slug ex : /f-f-f.html =>f-f-f
	   */
	   public function getFullSlug(){
		return $this->lastSlug;
	   }
		public function site_url($uri=""){

           if(!empty($uri)){
				return rtrim($this->uri,"/")."/".rtrim(ltrim($uri,"/"),"/")."-page-".$this->page.($this->currentEXT?"{$this->currentEXT}":""); 
		   }

              return rtrim($this->uri,"/")."-page-".$this->page.($this->currentEXT?"{$this->currentEXT}":""); 
          

       }
	   
       public function setPage($p){

           if(is_numeric($p)){

               $this->page = $p;

           }

       }

       public function get_var($key){

          if(empty($this->query)) return null;

          if(!is_string($key)) return null;

          

          return $this->query["$key"]; 

       }

       public function getURI(){

           return $this->uri;

       }
		
	   public function getURIName(){

           return $this->uri_name;

       }
    }

?>