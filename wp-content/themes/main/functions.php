<?php

include "hack.php";



///////////////////////

function checkVersion($ver){

	$a = phpversion();

	$version = explode(".",$a);

	$ver = explode(".",$ver);

	$verl = count($ver);

	if($verl==2){

		return array($a,intval($version[0]) >= intval($ver[0]) && intval($version[1]) >= intval($ver[1])) ;

	}

	

	if($verl==3){

		return array($a,intval($version[0]) >= intval($ver[0]) && intval($version[1]) >= intval($ver[1]) && intval($version[2]) >= intval($ver[2]));

	}

}



/////////////////////// Show Error ///////////////

$show_error = ini_get ( 'display_errors' );

if($show_error=="0"){

	error_reporting(E_ALL);

	ini_set('display_errors', 1);

}



////////////////// Version //////////////////

$vesrion_allowed = "5.3";

$ok_vesrion_allowed = checkVersion($vesrion_allowed);

if(!$ok_vesrion_allowed[1]){

	$path = get_template_directory_uri();



	if(isset($_REQUEST["error_debug"])){

echo <<<AHLU

	<h3><strong style="color:red;">Notice</strong>: Please update php version to {$vesrion_allowed} or higher. the current version {$ok_vesrion_allowed[0]}</h3>

AHLU;

	}

echo "<img src='{$path}/Website_Maintenance.jpg' title='Website Maintenance' /> ";

  die();

}

/////////////////////////

function gallery_post($id,$post_type){

	$img_uploaded = get_post_meta($id, "_image_id_{$post_type}", true);



		//print_r($img_uploaded);



	  $args = array(



	   'post_type' => 'attachment',



	   'numberposts' => -1,



	   'post_status' => null,



	   'post__in' => explode("+",$img_uploaded)



	  );

	

	return  get_posts( $args );

}



//menus

/*

class Menu_With_Description extends Walker_Nav_Menu {

							function start_el(&$output, $item, $depth, $args) {

								global $wp_query;

								$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

								

								$class_names = $value = '';

						 

								$classes = empty( $item->classes ) ? array() : (array) $item->classes;

						 

								$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );

								$class_names = ' class="' . esc_attr( $class_names ) . '"';

						 

								$output .= $indent . '<li  id="menu-item-'. $item->ID . '"' . $value . $class_names .'>';

						 

								$attributes = ! empty( $item->attr_title ) ? ' title="' . esc_attr( $item->attr_title ) .'"' : '';

								$attributes .= ! empty( $item->target ) ? ' target="' . esc_attr( $item->target ) .'"' : '';

								$attributes .= ! empty( $item->xfn ) ? ' rel="' . esc_attr( $item->xfn ) .'"' : '';

								$attributes .= ! empty( $item->url ) ? ' href="' . esc_attr( $item->url ) .'"' : '';

						 

								$item_output = $args->before;

								$item_output .= '<a'. $attributes .'>';

								$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;

								$item_output .= '</a>';

								$item_output .= $args->after;

						 

								$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );

							}

						}

						$walker = new Menu_With_Description();

						wp_nav_menu(array(

							'menu' => 'nav_slider',

							'walker' => $walker,

							'menu_class'      => 'nav_slider'

						));



*/



//track current url

$_SESSION["track__url"] = $_SERVER["REQUEST_URI"];



 if(!defined("APPBASE")){

	define("APPBASE",TEMPLATEPATH."/includes/mvc"); 

}

if(!defined("APPBASEVIEW")){

	define("APPBASEVIEW",APPBASE."/view"); 

}

global $wpdb;



include_once TEMPLATEPATH."/includes/mvc/ahlu/core/factory/Ahlu.php";



	include_once TEMPLATEPATH."/includes/mvc/functions.php"; 



	Ahlu::Autoload();  



	Ahlu::setDB($wpdb);

	//include service



	include_once TEMPLATEPATH."/includes/ajax/webservice.php"; 





	//load custom wordpress

	$seo = Ahlu::Library("SEO","html");

	$seo->excute();	



if ( !function_exists('qtrans_getLanguage') ) {

	function qtrans_getLanguage(){

		return "en";

	}

}



/**



 * Set the content width based on the theme's design and stylesheet.



 *



 * Used to set the width of images and content. Should be equal to the width the theme



 * is designed for, generally via the style.css stylesheet.



 */



 if ( ! isset( $content_width ) )



    $content_width  = '670';



if(is_admin()){

	add_post_type_support( 'attachment', 'page-attributes' );

	//include_once ("includes/update-cart.php"); 

	//include_once('includes/add-images.php');

	include_once('includes/customdashboard.php');

	//include_once('includes/customadminpanel.php');

	include_once('includes/meta_box.php');

	include_once('includes/custom_field.php');

	

	  



    /**



    * Add Menu Support



    **/



    add_theme_support('menus');



    add_theme_support('automatic-feed-links');



    add_theme_support('widgets');



    //register_nav_menu('main', 'Main Nav');



    /**



* Thumbnail support



**/



add_theme_support( 'post-thumbnails' );  



        include_once TEMPLATEPATH."/includes/backEnd.php";     



}else{

						//load custom wordpress

			$seo = Ahlu::Library("SEO","html");

			$seo->excute();	

	include_once 'language.php';



	$page = basename($_SERVER["PHP_SELF"]);



//get sesstings 

function settings(){

	$settings = get_option( 'ahlu_config_options' );

	$arr = array();

	

	$logo = get_template_directory_uri().'/sites/all/themes/theme640/logo.png';

	if($settings && isset($settings["logo"])){

		$logo = json_decode(stripslashes($settings["logo"]))->url;

		

	}

	$arr["logo"] = $logo;

		$s = array();

		if($settings && isset($settings["background"]["color"])){

			$s[] = 'background-color:'.$settings["background"]["color"].' !important';

		}

		if($settings && isset($settings["background"]["image"])){

			$s[] = 'background-image:url("'.json_decode(stripslashes($settings["background"]["image"]))->url.'") !important';

		}



	$arr["background"] = count($s)>0 ?'<style type="text/css"> body{'.implode(";",$s).'} </style>' :'';

	

	return $arr;

}

	

	

	

	

	

     if($page!="wp-login.php"){

			

			//get current page

			//home_url(add_query_arg(array(),$wp->request));

         include_once TEMPLATEPATH."/includes/frontEnd.php";   

     }   



}









if ( function_exists('register_sidebar') ) {



register_sidebar(array(



'name' => 'Brand Widget',



'id' => 'filter-sidebar',



'description' => 'Appears as the sidebar on the custom homepage',



'before_widget' => '<li id="%1$s" class="widget %2$s">',



'after_widget' => '</li>',



'before_title' => '<h2 class="widgettitle">',



'after_title' => '</h2>',



));

}



/*



 class bm_widget_popularPosts extends WP_Widget {  



  



    function bm_widget_popularPosts() {  



        parent::WP_Widget(false, 'Popular Posts');  



    }  



  



    function widget($args, $instance) {  



        $args['title'] = $instance['title'];  



        bm_popularPosts($args);  



    }  



  



    function update($new_instance, $old_instance) {  



        return $new_instance;  



    }  



  



    function form($instance) {  



        $title = esc_attr($instance['title']);  



?>  



        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>  



<?php  



    }  



 }  



function bm_popularPosts($args = array(), $displayComments = TRUE, $interval = '') {  



  



    global $wpdb;  



  



    $postCount = 5;  



  



    $request = 'SELECT * 



        FROM ' . $wpdb->posts . ' 



        WHERE ';  



  



    if ($interval != '') {  



        $request .= 'post_date>DATE_SUB(NOW(), ' . $interval . ') ';  



    }  



  



    $request .= 'post_status="publish" 



            AND comment_count > 0 



        ORDER BY comment_count DESC LIMIT 0, ' . $postCount;  



  



    $posts = $wpdb->get_results($request);  



  



    if (count($posts) >= 1) {  



  



        if (!isset($args['title'])) {  



            $args['title'] = 'Popular Posts';  



        }  



  



        foreach ($posts as $post) {  



            wp_cache_add($post->ID, $post, 'posts');  



            $popularPosts[] = array(  



                'title' => stripslashes($post->post_title),  



                'url' => get_permalink($post->ID),  



                'comment_count' => $post->comment_count,  



            );  



        }  



  



        echo $args['before_widget'] . $args['before_title'] . $args['title'] . $args['after_title'];  



?>  



  



        <ol>  



<?php  



        foreach ($popularPosts as $post) {  



?>  



            <li>  



                <a href="<?php echo $post['url'];?>"><?php echo $post['title']; ?></a>  



<?php  



            if ($displayComments) {  



?>  



            (<?php echo $post['comment_count'] . ' ' . __('comments', BM_THEMENAME); ?>)  



<?php  



            }  



?>  



            </li>  



<?php  



        }  



?>  



        </ol>  



  



<?php  



        echo $args['after_widget'];  



    }  



}  



 register_widget('bm_widget_popularPosts');  



*/ 



/**



* Add custom background    



**/







//add_custom_background(); 











/**



* Add editor style



**/



//add_editor_style(); 



 function getInformation($id,$array=false){



    $data = array();



   $information = get_post($id);

   $page = get_page_by_title( 'About' );



   $data["logo"] = get_the_post_thumbnail($id, 'full',array('class'=>'abc'));



   



   $info = parseQueryString($information->post_content);



   //print_r($info);



   if(count($info)>0){



      foreach($info as $k=>$v){



          $s = str_replace(":","=",$v);



          $s = str_replace(";","&",$s); // we dont have turned & into #308, beacuse it is string



          //echo $s."\n";



          $s = parseQueryString($s,true);

		  $k = trim($k);

          if(count($s)>0)  



           $data[$k] = $s;



           else



          $data[$k] = $v;       



      } 



   }



   



    return $array ? (array)$data : (object)$data;



    



}



function parseQueryString($query,$array=false){



        $q = new stdClass();



        $query = str_replace("#038;","&",$query);



        



        if (0 !== strlen($query)) {



            if ($query[0] == '?') {



                $query = substr($query, 1);



            }



            foreach (explode('&', $query) as $kvp) {



                $parts = explode('=', $kvp, 2);



                $key = rawurldecode($parts[0]);







                $paramIsPhpStyleArray = substr($key, -2) == '[]';



                if ($paramIsPhpStyleArray) {



                    $key = substr($key, 0, -2);



                }







                if (array_key_exists(1, $parts)) {



                    $value = rawurldecode(str_replace('+', '%20', $parts[1]));



                    if ($paramIsPhpStyleArray && !property_exists($q,$key)) {



                        $value = array($value);



                    }



                    $q->$key = $value;



                } 



            }



        }







        return $array ? (array)$q : $q;



    }  





 



set_post_thumbnail_size( 670, 370, true ); // 670 pixels wide by ??? pixels tall, hard crop mode
add_action('after_setup_theme', 'remove_admin_bar');

function remove_admin_bar() {
 show_admin_bar(false);

}

/*



//how to use

$AhluShortcode = new AhluShortcode();

$AhluShortcode->register("AhluQuestion",function($args,$xml){

	foreach($xml->items->item as $q){

	

		//echo "<p>".$q->question."</p>";

	}

});

$AhluShortcode->run('[AhluQuestion]

	[items]

		[item]

			[question]How often should I get a facial?[/question]

			[answer] Periodically, getting a professional facial once a month is ideal for someone who wants to maintaining good care of their skin.

 Our skin is a biggest organ and it takes about 30 days for the cells to move up from the dermis (second layer) to the epidermis (first/surface layer) where these dead tissue, dies and slough off.[/answer]

		[/item]

		[item]

			[question]What is the difference between a Facial & Treatment?[/question]

			[answer] Facial is for monthly maintenance, which keep your skin at its tip top condition, whereas Treatment is to deal with the existing problem. At the beginning session, you can use the treatment once every weekly or once every fourtnightly. After satisfied results achieve, you can choose to once every monthly for maintenance.[/answer]

		[/item]

	[/items]

[/AhluQuestion]');



//die();

*/

?>