<?php

    /**

    *  Model Product

    */

    class Product_model extends Category_model
    {
         public function __construct(){
            parent::__construct();
			
			$this->post_type = "product";
			$this->taxonomy = $this->post_type."_ahlu";
		 
            return $this; 
	
         }

	}

    ?>