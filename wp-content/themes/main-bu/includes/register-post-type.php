<?php
add_action( 'init', 'tintuc_type' );// Call function register_product_post when start
function tintuc_type() {
register_post_type(
'new',
array(
'public' => true,
'label'  =>  'Lastest News',
'labels' => array(
'name' => 'Lastest News',
'singular_name' => 'News',
'add_new' => _x( 'Add News', 'add' ),
'add_new_item' => __( 'Add News' ),
'edit_item' => __( 'Update' ),
'new_item' => __( 'Add News' ),
'view_item' => __( 'View' ),
'search_items' => __( 'Search News' ),
'not_found' =>  __( 'Not News have' ),
'not_found_in_trash' => __( 'Not found in Trash' )
 
),
'show_ui' => true,
'capability_type' => 'post',
'hierarchical' => false,
'rewrite' => array('slug' => 'new'),
'query_var' => true,
'supports' => array('title','editor','thumbnail','custom-fields' ),
'menu_position' =>5,
'exclude_from_search' =>false,
'taxonomies' =>array('new')
) );
flush_rewrite_rules();
}



?>