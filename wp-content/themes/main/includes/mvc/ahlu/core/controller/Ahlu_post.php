<?php
    class Ahlu_post extends Ahlu_MVC{
        
        public function __construct(){
            parent::__construct();
             
            $this->post_type = strtolower(get_class($this));
			
        }
        
        public function index(){
            
        }
        
        //////////////////////////////////////// Default
        public function post($id){
	
			trigger_error("Please overrived this method 'post' in your controller.");
			
        }
		
        ////////////////////////////////////////// End Default
        protected function loadPost($id){
			$this->post = new Post_model();
			$this->post->load($id);
		}
		
		
		public function count_view(){
		
		}
    }
?>