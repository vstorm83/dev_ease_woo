<?php

    /**

    *  Model Food

    */

    class Food_model extends Category_model
    {
         public function __construct(){
            parent::__construct();
			
			$this->post_type = "food";
			$this->taxonomy = $this->post_type."_ahlu";
		 
            return $this; 
	
         }

	}

    ?>