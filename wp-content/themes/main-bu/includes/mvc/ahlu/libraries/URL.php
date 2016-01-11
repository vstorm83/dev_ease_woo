<?php

    class URL {
        private $_is_subdomain = array("is_sub"=>false,"level"=>0,"name"=>null);
        
        public function __construct(){
            return $this; 
        }
        /**
        * Get uri path in root or subdomain
        *  @param $return : true->string ,else array
        *  @param $query : true -> include query string, else exclude query string
        */
        public function Location($return = true,$queryandanchor=true){ 
            $uri = null;
            $root_domain = ABSPATH;//Document_root physical
            $theme_path = TEMPLATEPATH;  //theme root path physical
            
            $uri_to_theme = get_template_directory_uri();
            $uri_to_root = site_url();
            //echo site_url();
            
            if(!$queryandanchor)
            {
               $uri = parse_url($_SERVER["REQUEST_URI"]);
               $uri = 'http://'.$_SERVER["HTTP_HOST"].$uri["path"];  
            }elsE{
               $uri = 'http://'.$_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];  
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
        /**
        * Get root file embed like css, js,... in html from root
        * 
        */
        public function LocationFile($path=""){ 
            $subdomain = str_replace(str_replace(array("/","\\"),DIRECTORY_SEPARATOR,$_SERVER["DOCUMENT_ROOT"]),"",TEMPLATEPATH);
            return str_replace(array("/","\\"),"/",$subdomain)."/{$path}"; //uri in web, it must be "/".
        }
        /**
        * Get query string
        * 
        * @param mixed $key
        */
        public function QueryString($key=null){
            $url = parse_url($_SERVER["REQUEST_URI"]);
            $data = StringUtil::parseQueryString($url["query"],true);    
            if($key==null)
            {
                    return $data;
            }
            else{
               if(isset($data[$key])){
                   return  $data[$key];
               }
               return null; 
            }  
        }
        
        public function isSubdomain(){
             return $this->_is_subdomain["is_sub"];
        }
        
        public function pathSubdomain(){
             return $this->_is_subdomain["name"];
        }
        
        /**
        *     apply for real path not rewrite_url
        *     because on host it is virtual host 
        */
        public function relativeURL(){
            // host.com/abc.com/index.php <=> abc.host.com/index.php
            //abc.com/index.php
            $url =  $_SERVER['PHP_SELF']; // get real path file compiled where it is "/abc.com/index.php"

            $fromRoot =1; //we check root
             
             $folders = explode('/',$url);
             //print_r($folders);
             if(count($folders)==1 || count($folders)<=0){
                 return "";
             }else{
                 array_shift($folders); // remove  first "/"
                 if(DOMAIN==$folders[0]){
                  array_shift($folders); // remove folder  because it is domain
                  $fromRoot =0;
                 }
                 //print_r($folders);
                 
                 //echo $_SERVER['PHP_SELF'];
                 $s="";
                 for($i=0+$fromRoot;$i<count($folders);$i++){
                        $s.="../"; 
                 }
                 return $s;
             }
         }
         
         public function relativeURLCodeigniter(){
                //get config's path executed
                $pathexc = $_SERVER['PHP_SELF'];
                $arrPath = explode("/",$pathexc);
                //delete last
                array_pop($arrPath);
                $joinPathexc = ROOT.implode("/",$arrPath);
                //echo $joinPathexc;
                //get the original path of config file on physical address on window
                $pathconfig = str_replace("\\","/",realpath(__File__));
                $arrPathOrginal = explode("/",$pathconfig); 

                //delete last
                array_pop($arrPathOrginal);
                $pathconfig = implode("/",$arrPathOrginal);
                //echo  $pathconfig;
                if(strlen($pathconfig)!=strlen($joinPathexc))
                {
                    return $pathconfig;
                }
                return $joinPathexc;
             }
    }
?>
