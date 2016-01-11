<?php

    /**

    *  Model Service

    */

    class Service_model extends Category_model
    {
         public function __construct(){
            parent::__construct();
			
			$this->post_type = "service";
			$this->taxonomy = $this->post_type."_ahlu";
		 
            return $this; 

         }

	}

    ?>