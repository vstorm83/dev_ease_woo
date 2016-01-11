<?php
/*
*
*/
class SEO{
	private $seo = null;

	public function __construct(){

		return $this->init();
	}

	public function load($id="html"){
		$this->seo = $id;
		return $this;
	}

	public function excute(){
		//follow post type
		add_action('init', array(&$this,"on_post_types"));
		add_filter('redirect_canonical', array(&$this,"remove_redirect_canonical"));
		//follow page
		add_action('init', array(&$this,"html_page_permalink"), -1);
		add_filter('user_trailingslashit', array(&$this,"no_page_slash"),66,2);
		add_filter('the_permalink', array(&$this,"on_permalink") );
		//override site_url function
		add_filter( 'site_url', array(&$this,"on_site_url") );

		return $this;
	}
	//////
	/////////////////////////
	public function on_post_types() {
		global $wp_rewrite;
		global $wpdb;
		
		$r = $wpdb->get_results("select post_type from {$wpdb->posts} where post_type not in('revision','attachment','post','page')  group by post_type");
      if(is_array($r) && count($r)>0){
      	foreach ($r as $post) {
      		add_rewrite_rule('^'.$post->post_type.'/([^/]+)\.'.$this->seo, 'index.php?'.$post->post_type.'=$matches[1]', 'top');
      	}
      	$wp_rewrite->flush_rules();
      }
	}

	public function remove_redirect_canonical($redirect_url) {
	    return false;
	}

	public function on_permalink($url) {
		if(!preg_match("/\.([a-z]){1,4}$/is", $url, $output_array)){
		
			$now = untrailingslashit($url);
				
		
			return  $now.".{$this->seo}";

		}
	  
	  return $url;
	}

	public function on_site_url( $url )
	{
		global $router;
	    if( is_admin() ) // you probably don't want this in admin side
	        return $url;
		

		if(!preg_match("/\.([a-z]){1,4}$/is", $url, $output_array)){
			$now = rtrim($url);
			if(home_url()==$now) //is home page
				return $now;
				
			return  $now. (isset($router) ? (method_exists($router,"getSEO")?$router->getSEO($this->seo):"") :"");
		
		}
	    return $url;
	}

	public function no_page_slash($string, $type){
	   global $wp_rewrite;
		if ($wp_rewrite->using_permalinks() && $wp_rewrite->use_trailing_slashes==true && $type == 'page'){
			return untrailingslashit($string);
	  }else{
	   return $string;
	  }
	}
	public function html_page_permalink() {
		global $wp_rewrite;
		 if ( !strpos($wp_rewrite->get_page_permastruct(), ".{$this->seo}")){
				$wp_rewrite->page_structure = $wp_rewrite->page_structure . ".{$this->seo}";
		 }
	}
	/////
	private function init(){

		return $this;
	}
}
?>