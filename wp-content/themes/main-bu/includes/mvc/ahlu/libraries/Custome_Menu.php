<?php
class Custome_Menu
{
	public $name = "hey_you";
	//registter path to excute
	public $path = null;
	public $view = null;
	public $icon = null;
	
	protected $events = null;
	
	
	protected $submenus = array();
	protected $link_sid = "index";
	
	public function __construct($name=null){
		$this->path =  __FILE__;
		if($name!=null){
			$this->name = $name;
		}
	}
	public function setLink($link){
		$this->link_sid = $link;
	}	
	public function getLink(){
		return $this->link_sid;
	}	
		public function onLoad($f){
			$this->events = $f;
		}
        /**
        * Add submenu
        * 
        * @param mixed $arr
        * @param mixed $view
        */

        public function add_submenu($arr=array()){

            $def =array(

             "label"=>"submenu-".time(),
			 "title"=> "submenu-",
			 "view" => "hello"
            );

           $this->submenus[] = array_merge($def,$arr);
		   return $this;
        }
        
		public function process(){
			
			//Register the main menu name
			add_action( 'admin_menu', array(&$this,"_createMenu"));
			
			//fire event
			call_user_func_array($this->events,array($this));
			return $this;	
		}

		public function _createMenu(){

			//add some information about page
			$title = $this->name ." ".time();;
			$label = $this->name;
			add_menu_page( $title,$label,'manage_options',$this->link_sid,array(&$this,'_displayview'),$this->icon );
			
			//submenus
			
			foreach($this->submenus as $menu){
				$view = $menu["view"];
				$link = isset($menu["link"]) ? $menu["link"] : $this->link_sid."/item/".str_replace(" ","-",$menu["title"]);
				
				add_submenu_page($this->link_sid, $menu["title"],$menu["label"], 'manage_options',$link,function($view) use($view){
					if(file_exists($view) && is_file($view)){
						include $view;
					}else{
						echo $view;
					}
				});	
			}
			
			
		}
		
		//display view for main link
		public function _displayview(){
			if(file_exists($this->view) && is_file($this->view)){
				include $this->view;
			}else{
				echo $this->view;
			}
		}
}
?>