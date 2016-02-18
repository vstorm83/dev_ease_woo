<?php
class AhluRemove{
	//store a whole menu item
	private $_info_menu = array();
	private $_menus = array();
	private $_submenus = array();
	
	
	public function __construct(){
	}

	function _remove_theme_submenus() {
		global $submenu, $current_user;

		get_currentuserinfo();
	//print_r($this->_submenus);
	
	//print_r($this->_info_menu);
		foreach($this->_submenus as $main=> $subs){
			
			
			//checking this submenu belong to root menu
			if(isset($this->_info_menu[$main])){
				//get the main page name handle
				$handle = $this->_info_menu[$main];
				//print_r($handle);
				//look up in source
				if(isset($submenu[$handle])){
					
					foreach($subs as $sub){
						foreach($submenu[$handle] as $i => $item){
								$c = strip_tags($item[0]);
								if(stripos($c,$sub)!==FALSE || $item[2]==$sub){
									//print_r("{$sub} <=> $c");
									unset($submenu[$handle][$i]);
									break;
								}
							}
						}
					}
				}
				
		}
		
	
	//	print_r($submenu);
	//	die();

		//if($current_user->user_login == 'username') {

		//}
	}
	public function _remove_menus(){
		global $menu;

		if(count($this->_menus)==0) return;
		

		foreach($menu as $i=> $item){
			
			
			if(in_array($item[0],$this->_menus) || in_array($item[2],$this->_menus)){
				unset($menu[$i]);
			}
			//store information about link root menu
			$this->_info_menu[$item[0]] = $item[2];
		}
	}
	
	/////////////////
	/*
	* Remove main Item menu from wordpress panel
	*/
	public function menu($name){
		if(func_num_args()==1){
			$this->_menus[]=$name;
		}else{
			$this->_menus = array_merge($this->_menus,func_get_args());
		}
		
		return $this;
	}
	/*
	* Remove submenu from main Item menu in wordpress panel
	*/
	public function submenu($menu,$name){
		if(!isset($this->_submenus[$menu])){
			$this->_submenus[$menu] = array();
		}
		$this->_submenus[$menu][]=$name;	
	}
	/*
	* Fired
	*/
	public function go(){
		if(is_admin()){
			add_action('admin_menu', array($this,"_remove_menus"));
			add_action('admin_init', array($this,"_remove_theme_submenus"));
		}
	}
}

/*
$a = new AhluRemove();
//menove item menu
$a->menu("Posts");
$a->submenu("Dashboard","Updates");
$a->go();


function my_scripts_method() {
	$dir = dirname(__FILE__);
	
	if(!file_exists($dir."/ahlu.js")){
		touch($dir."/ahlu.js");
	}
	//add
	file_put_contents($dir."/ahlu.js",'
	$(document).ready(function(){
		$("#menu-posts-services").hide();
		$("#menu-posts-team").hide();

	});
	');
	wp_enqueue_script(
		'ahlu-script',$dir."/ahlu.js",
		array( 'jquery' )
	);
}
my_scripts_method();
*/
?>