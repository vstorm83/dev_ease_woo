 <?php
$a='<?php
/**
*  Class '.$name.'
*/
class '.$name.' extends '.$extend.'
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
		$cls = strtolower(__CLASS__);

	   $this->template->assign("cls","{$cls}");
	   
	    $this->template->assign("header",$this->loader->view($this->pathTheme."/compoment/header.php",null,true));
		
		//assign data   for content 

	   $this->news_model = Ahlu::Call("'.$name.'_model",150); 
	   $this->template->assign("meta",$this->news_model->SEO());
	   $this->template->assign("content",$this->loader->view("ahlu-{$this->post_type}",array("WP_enable"=>$this->enWP,"category"=>$this->news_model),true));
		$this->template->assign("footer",$this->loader->view($this->pathTheme."/compoment/footer.php",null,true));

	   //output html
	   $this->template->render(FALSE);
	}
	/**
	* Category
	* 
	*/
	public function category($id=-1){
		$cls = strtolower(__CLASS__);
		
		$this->template->assign("cls","{$cls} {$this->category->slug}-{$cls} {$cls}-{$this->category->ID}");
	   
		$this->template->assign("header",$this->loader->view($this->pathTheme."/compoment/header.php",array("SEO"=>null),true));
	   //assign data   for content 

		$this->news_model = Ahlu::Call("'.$name.'_model",$this->category); 
		$this->template->assign("meta",$this->news_model->SEO());
	    $this->template->assign("content",$this->loader->view("ahlu-{$this->post_type}-{$this->onView}",array("WP_enable"=>$this->enWP,"category"=>$this->ecommercial_model),true));

	   

	    //assign data   for footer 

	    $this->template->assign("footer",$this->loader->view($this->pathTheme."/compoment/footer.php",null,true));
		//output html
        $this->template->render(FALSE);
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
		$cls = strtolower(__CLASS__);

	       $this->template->assign("cls","{$cls} {$this->post->post_name}-{$cls} {$cls}-{$this->post->ID}");
	   
           $this->post_model = Ahlu::Call("'.$name.'_item_model")->load($this->post); 
		   $this->template->assign("meta",$this->post_model->SEO());
           //assign data   

           $this->template->assign("header",$this->loader->view($this->pathTheme."/compoment/header.php",null,true));

           $this->template->assign("content",$this->loader->view("ahlu-{$this->post_type}-{$this->onView}",array("WP_enable"=>$this->enWP,"post"=>$this->post_model),true));

           $this->template->assign("footer",$this->loader->view($this->pathTheme."/compoment/footer.php",null,true));

           //output html
           $this->template->render(FALSE);

    }

	public function search($q=null){
			
			if($q!=null){
				
				
				$q = isset($_REQUEST["s"]) ? $_REQUEST["s"] : null;
			
				
			$this->template->assign("header",$this->loader->view($this->pathTheme."/compoment/header.php",array("SEO"=>null),true));
	
		   //assign data   for content 
		   $this->blog_model = Ahlu::Call("Blog_model")->load(); 
		 
		   
		   $this->template->assign("meta",$this->blog_model->SEO());
		
		   $this->template->assign("content",$this->loader->view("ahlu-{$this->post_type}-{$this->onView}",array("WP_enable"=>$this->enWP,"title"=>ucfirst($this->class)."s","query"=>$q,"category"=>$this->blog_model,"data"=>$this->blog_model->searchPostType($q,10,URI::getInstance()->page)),true));
		
		   //assign data for footer 
		   $this->template->assign("footer",$this->loader->view($this->pathTheme."/compoment/footer.php",null,true));

		//output html
           $this->template->render(FALSE);
		   }
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
?>';
echo $a;
?>