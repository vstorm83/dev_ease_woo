<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);
///////////////////////////////////

$a = new AhluRemove();
$a->menu("edit.php","bor-plugin","edit-comments.php","wpseo_dashboard","yit_plugin_panel","tools.php");
$a->submenu("Bảng tin","update-core.php");
$a->go();

function my_enqueue() {
	
	$file = get_template_directory()."/js/remove_menu.js";
	if(!is_dir(get_template_directory()."/js/")){
		mkdir(get_template_directory()."/js/","0775",true);
	}
	if(!file_exists($file)){
		touch($file);
	}
	file_put_contents($file,'
		jQuery(document).ready(function(){
			jQuery(".update-nag").hide();
		});
	');
	wp_enqueue_script( 'remove_menu', get_template_directory_uri() . '/js/remove_menu.js', array('jquery'));
}

add_action( 'admin_enqueue_scripts', 'my_enqueue' );


$page = Custom_Post::get("Page");
if(is_object($page)){
	
	if(isset($_GET["post"]) && $_GET["post"]==724 && isset($_GET["action"]) && $_GET["action"]=="edit"){
		$page->add_meta_box(   
			'Session 1',   
			array(  
				'Content' => 'editor',
				'Image Background' => 'file'
			)  
		);
		
		$page->add_meta_box(   
			'Session 2',   
			array(  
				'Content' => 'editor',
				'Image Background' => 'file'
			)  
		); 
		$page->add_meta_box(   
			'Session 3',   
			array(  
				'Content' => 'editor',
				'Image Background' => 'file'
			)  
		);
		$page->add_meta_box(   
			'Session 4',   
			array(  
				'Content' => 'editor',
				'Image Background' => 'file'
			)  
		);
		$page->add_meta_box(   
			'Session 5',   
			array(  
				'Content' => 'editor',
				'Image Background' => 'file'
			)  
		); 
		
		$page->add_meta_mutil_upload();
	}
	
	
}
/*
$Contact = new Custom_Post( 'Contact' );   
$Contact->add_taxonomy( 'contact' );
$Contact->load(function($post_type,$action,$post_id){
	//we admin click onto the link contact
	if ($post_type == 'contact' && $action=="edit") {
		//update meta post
		update_post_meta($post_id, 'readEmail', 1);
	}
});

//create a load post
$cols_contact = $Contact->getColumns(array("content"=>"Content"));
$cols_contact->addFieldColumn("Email","emailSender");
$cols_contact->addFieldColumn("Status","readEmail",function($data){
	$yes = intval($data);
	return $yes?"<strong>{$yes}</strong>":'<strong style="color:red;">'.$yes.'</strong>';
},true);
$cols_contact->load();
*/

$news = new Custom_Post( 'blog');   
$news->add_taxonomy( 'blog' );
$news->load(function($post_type,$action,$post_id){
	
});
//$news->add_meta_mutil_upload();

//////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////
/* add metabox
$book->add_meta_box(   
    'information',   
    array(  
        'description' => 'editor', 
        'upload' => 'file'   
    )  
); 
$book->add_meta_box(   
    'Author Info',   
    array(  
        'Name' => 'text',  
        'Nationality' => 'text',  
        'Birthday' => 'text'  
    )  
); 
*/

//////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////
$Custome_Menu  = new Custome_Menu("Web settings");
$Custome_Menu->view = dirname(__FILE__)."/mvc/admin/view/genneral.php";
$Custome_Menu->setLink("setting");

$Custome_Menu->onLoad(function($sender){
	if(isset($_GET["page"]) && $_GET["page"]==$sender->getLink())
		$upload = new Custom_Upload();
	
});


//////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////
/*

$Custome_Menu->add_submenu(array(
             "label"=>"menu 1",
			 "title"=> "menu 1"
            ),function(){});

$Custome_Menu->add_submenu(array(
             "label"=>"menu 2",
			 "title"=> "menu 2"
            ),function(){});
*/
$Custome_Menu->process();


?>