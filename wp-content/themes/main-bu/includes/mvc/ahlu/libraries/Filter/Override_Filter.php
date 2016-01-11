<?php
       abstract class Override_Filter{
        protected $hook = null;
        protected $hooks = array();
        protected $params = null;
        public $data = null;
        
        protected $type = "string";
         
        public function __construct(){
        }
      
       public function override($hook,$params=null){
           $def = array(
               "priority"=>10,
               "accepted_args"=>1
            );
            
          if($hook!=null && is_string($hook)){
              $this->hook = $hook; 
              
              
             //print_r($this->params);
             $name = str_replace(array("the_"),"",$hook); 
             $this->hooks[]= $name; 
             $this->params[$name] = $params;
              //override method hook
               call_user_func_array("add_filter",array($hook,array(&$this,"override_{$name}")));
      
              
           }else{
               trigger_error("The hook must be a string.");
           } 
       }
       
       public function excute(){
           if(count($this->hooks)>0){
               foreach($this->hooks as $v){
                  //apply action hook
                  $argsnum = $this->params[$v]!=null ? count($this->params[$v]) : 1;
                  call_user_func_array("add_filter",array("go_action",array(&$this,"go_{$v}"),10,$argsnum)); 
               }
           }
       }
              /////////////////////////////////////////////// SEO /////////////////////// 
       /**
       * ovveride this function
       *  
       * @param mixed $data
       */
       public function override_wp_title($title){
             
              return apply_filters("go_action",$title);
       }
       
        /**
         * Filters the page title appropriately depending on the current page
         *
         * This function is attached to the 'wp_title' fiilter hook.
         *
         * @uses    get_bloginfo()
         * @uses    is_home()
         * @uses    is_front_page()
         */
       public function go_wp_title($title){
             $data  = $this->params[str_replace("go_","", __FUNCTION__)];  
             //print_r($data);
                $site_description = get_option("blogdescription");

                $filtered_title = $title . get_bloginfo( 'name' )." &raquo; ".$data["title"];
              
          return $filtered_title;
       }
       
       
       public function setHook($i){$this->override($i); return $this;}
       
       public function Type(){return $this->type;} 
    }
?>
