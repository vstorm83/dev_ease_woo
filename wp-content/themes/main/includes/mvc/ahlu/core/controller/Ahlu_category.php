<?php
   class Ahlu_category extends Ahlu_MVC{
       
       public function __construct(){
            parent::__construct();
             
        } 
		
		//////////////////////////////////////// Default
        public function post($id){
			trigger_error("Please overrived this method 'post' in your controller.");
        }
		
		public function category($id){
           trigger_error("Please overrived this method 'category' in your controller.");
        } 
        ////////////////////////////////////////// End Default
		
		protected function loadPost($id){
			$this->post = new Post_model();
			$this->post->load($id);
		}
		
		protected function loadCategory($id){
			$this->category = new Category_model();
			$this->category->load($id);
		}
		
		
		public function count_post_view(){
			//auto get post
			$view = 1;
			$id = 0;
			if($this->post instanceof Post_model){
				$id = $this->post->getMe()->ID;
				
			}else{
				$id = $this->post->ID;
			}
			 
			$ok = get_post_meta( $id, '_view_post', true);
			if(empty($ok)){
				add_post_meta($id, '_view_post', 1);
			}else{
				 update_post_meta ( $id, '_view_post',intval($ok)+1 );
			}
		}
		public function count_cate_view(){
			
		}
    }
?>