<?php
    /**
    *  Model Testimonial
    */
    class Testimonial_model extends Category_model
    {
         public function __construct(){
            parent::__construct();	
			$this->post_type = "testimonial";
			$this->taxonomy = $this->post_type."_ahlu";
            return $this; 
         }
	}
    ?>