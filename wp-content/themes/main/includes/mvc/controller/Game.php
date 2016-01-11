 <?php
    /**
    *  Class Game
    */
    class Game extends Ahlu_post
    {
                         public function __construct(){
            parent::__construct();
            
           //enable theme worpress;
           $this->enWP =true; 
           
          //call ovveride
          $this->custom = Ahlu::Call("Custom_template");

        }
        
        public function index(){
           echo "Game index."; 
        }
        
        public function item($id){
         
        }
        
         
        ///////some page called if defined 
        public function example(){
              echo "Action Example.";
        }
    }
    ?>