 <?php
/**
*  Class Comment
*/
class Comment extends Ahlu_post
{
	public function __construct(){
		parent::__construct();
		
	    //enable theme worpress;
		
        //$this->enWP =true; 
		//call ovveride
        //$this->custom = Ahlu::Call("Custom_template");
			
	   
	    //so we must defined
		$this->class = strtolower(get_class($this));	
		$this->post_type = strtolower($this->class);
		$this->prefix_type="";

		////
		//track url root
		$_SESSION["track__url"]  = $this->post_type.".html";
		
		//use template 
		$this->enableTemplate();
	}
	
	/////////////////default
	/**
	* Index
	* 
	*/
	public function index(){
	   echo "Comment index."; 
	}
	/**
	* Category
	* 
	*/
	public function category($id=-1){
	 
	}
	/**
	* Comment
	* 
	*/
	public function comment($id){
	 
	}
	/**
	* Post
	* 
	*/
	public function post($id){
	 
	}
	/**
	* Archive
	* 
	*/
	public function archive($year=0,$moth=0,$day=0){
	 
	}
	///////////////////////////////
	
	///////some page called if defined 
	public function example(){
		  echo "Action Example.";
	}
}
?>