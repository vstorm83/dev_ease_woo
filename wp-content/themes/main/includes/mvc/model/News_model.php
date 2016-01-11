<?php

    /**

    *  Model Service

    */

    class News_model extends Category_model
    {
         public function __construct(){
            parent::__construct();
			
			$this->post_type = "news";
			$this->taxonomy = $this->post_type."_ahlu";
		 
            return $this; 

         }

	}

    ?>