<?php
   class Ahlu_MVC{
         protected $enWP=false;
         protected $theme="themes";
         protected $post_type = null;
         protected $prefix_type="_genre";
         protected $seo=null;
		 
		 protected $class=null;
		 
		 ///////////
		 protected $pathTheme = null;
		 
		 
		 protected $onView = null;
		 
		 
         private static $instance; 
         
         public function __construct(){
			
             $this->load(); 
         }
		 
		 
		 /*
		 * Load some settings
		 */
         private function load(){
             self::$instance =&$this;  
             $class = Ahlu::isLoaded();
             
             if(count($class)>0){
                 foreach($class as $k=>$cls){
                     $key = strtolower($k);
                     $this->$key = $cls;
                 }
             }
            
         }
		  /*
		 * Enable tempalte library
		 */
		 protected function enableTemplate($temp = "default_theme"){
			// echo $this->config->item("default_theme");
			$this->template = Ahlu::Library("Template");
			//interact with theme
			$theme = "themes/".$this->config->item("default_theme_folder");
			//load
            $this->template->load($this->config->item($temp),$theme);
             
			 //assign
			 $this->pathTheme = TEMPLATEPATH."/$theme";
			 $this->template->pathTheme=$this->pathTheme;
			 //get post from id, maybe be category, post
             $this->post = $this->router->getObject();
             $this->category = $this->router->getCategory();
			 
			 //assign some properties for template
			 $this->template->assign("class",$this->class);
			 
			 //setting some default value for view
			 if(property_exists($this,"loader")){
				$this->loader->dateController = array("class"=>$this->class);
			 }else if(property_exists($this,"load")){
				$this->load->dateController = array("class"=>$this->class);
			 }
		 
		 }
         /////////////// Implement ///////////
         public function item($id){}  
		 
		  /**
		 *
		 * Set method for controller from slug
		 */
		 public function setOnFileView($method){
		   if(is_string($method))
			$this->onView = strtolower($method);
		 }
         /**
		 * 
		 * Show any view
		 *
		 */
         protected function showTemplate($data=array(),$which=""){
			
             if($this->enWP){ //Method 1
			     $onView = (empty($which)? ($this->onView=="index" ? "" : "-".$this->onView) : "-".strtolower($which));
                 $file = APPBASEVIEW."/ahlu-{$this->post_type}".$onView.".php";
                 if(!file_exists($file)){
                     $this->Get404(array("filenotfound"=>"Can not find post type '{$this->post_type}".$onView."' in {$file} in ".__LINE__));
                 }
                   $this->loader->view($file,($data == null ? array() : $data),false); 
             }else{
                //Method 2:
                $this->template->render(FALSE);
             }      
         }
         public static function getInstance(){
             return self::$instance; 
         }
         public function setVar($obj){
             $name = strtolower(get_class($obj));
             $this->$name = $obj;
         }
        /////////////////////////////////////////////// 
        //set header and footer
        protected function Ahlu_header($data=array(),$echo=true){
		   
             if($this->enWP){
                if($echo){
                    return $this->loader->view(TEMPLATEPATH."/header.php",$data,$echo);   
                }
                else{
                     $this->loader->view(TEMPLATEPATH."/header.php",$data,$echo); 
                }
             }else{
                 
             }
			 
        }
    
        protected function Ahlu_footer($data=array(),$echo=true){
             if($this->enWP){
                if($echo){
                    return $this->loader->view(TEMPLATEPATH."/footer.php",$data,$echo);   
                }
                else{
                     $this->loader->view(TEMPLATEPATH."/footer.php",$data,$echo); 
                }
             }else{
                 
             }
        }
        
        function Get404($data=array()){
            if(file_exists(TEMPLATEPATH."/includes/mvc/view/404.php")){
                $this->loader->view(TEMPLATEPATH."/includes/mvc/view/404.php",$data,false); 
                exit();  
            }else if(file_exists(TEMPLATEPATH."/404.php")){
                $this->loader->view(TEMPLATEPATH."/404.php",$data,false);  
                exit();
            }else{
               die("Not Found 404.");  
            }    
       }

       /**
       * Get all category if exist with parent is none
       *  
       * @param mixed $is_parent
       * @return bool
       */
       public function currentCategories($is_parent=false){
           return Database::CategoryByType($this->post_type.$this->prefix_type,$is_parent);
       }
    }
?>