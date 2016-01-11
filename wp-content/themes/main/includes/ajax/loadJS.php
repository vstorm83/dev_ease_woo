<?php
$rootTheme = get_template_directory_uri();  


//insert some js into admin page
    add_action('admin_init', 'admin_load_scripts');
    
//notification new contact sent
function admin_load_scripts() {
    global $rootTheme,$post;
    
    $js_file = $rootTheme.'/js/admin_my_utils.js';
    $js = $rootTheme.'/js/jquery-1.7.2.min.js';
    wp_enqueue_script('jquery-1.7.2.min', $js, array('jquery'));
    if($_REQUEST["post_type"]!="contact")
        wp_enqueue_script('admin_my_utils', $js_file, array('jquery'));
    
}


add_action('wp_ajax_insertContact', 'insertContact');
add_action('wp_ajax_nopriv_insertContact', 'insertContact');//for users that are not logged in.
?>