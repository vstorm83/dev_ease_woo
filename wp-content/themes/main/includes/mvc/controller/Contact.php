 <?php
    /**
    *  Class Contact
    */
    class Contact extends Ahlu_post
    {
                         public function __construct(){
            parent::__construct();
            
           //enable theme worpress;
           $this->enWP =true; 
           
          //call ovveride
          $this->custom = Ahlu::Call("Custom_template");
		  
		  			$this->class = strtolower(get_class($this));	
			$this->post_type = strtolower($this->class);
			$this->prefix_type="";

			////
			//track url root
            $_SESSION["track__url"]  = $this->post_type.".html";
			
			//use template 
			$this->enableTemplate();

        }
        
        public function index(){
           echo "Contact index."; 
        }
        
        public function item($id){
         
        }
        
         
        ///////some page called if defined 
        public function example(){
              echo "Action Example.";
        }
    }
    ?>