<?php /*
  add_action('wp_dashboard_setup', 'my_custom_dashboard_widgets');
function my_custom_dashboard_widgets() {

global $wp_meta_boxes;

 

wp_add_dashboard_widget('custom_help_widget', 'Theme Support', 'custom_dashboard_help');

}

 

function custom_dashboard_help() {

echo '<p>Welcome to Custom Blog Theme! Need help? Contact the developer <a href="mailto:yourusername@gmail.com">here</a>. For WordPress Tutorials visit: <a href="http://www.wpbeginner.com" target="_blank">WPBeginner</a></p>';
}
*/
// example custom dashboard widget
function custom_dashboard_widget() {
   $type ='solve-puzzles';
  $args = array(
    'post_type'=>$type,
    'post_status'=>'publish',
    'posts_per_page'=>5,
    'caller_get_posts'=>1
    );

  $my_query = null;
  $my_query = new WP_Query($args);
  if($my_query->have_posts()){
    while($my_query->have_posts()) : $my_query->the_post(); ?>
  
<div class="bg-3 margin-bot">
    <div class="indent">
    <div class="wrapper">
    <figure class="img-indent img_border">
    <?php the_post_thumbnail( 'thumbnail') ?>
    </figure>
    <div class="extra-wrap">
        <?php the_ID(); ?>
    <h4 class="reg2"><?php the_title(); ?></h4>
    <a href="<?php echo admin_url( ); ?>post.php?post=<?php the_ID(); ?>&action=edit">edit</a>
    <div class="size-1 color2"><?php echo substr(get_the_content(),0,130); ?> <a href="<?php the_permalink(); ?>" class="link-1">[more]</a></div>
    </div>
</div>
</div>
</div>
<?php endwhile;
}
wp_reset_query();

}
function add_custom_dashboard_widget() {
    wp_add_dashboard_widget('custom_dashboard_widget', 'How to Do Something in WordPress', 'custom_dashboard_widget');
}
add_action('wp_dashboard_setup', 'add_custom_dashboard_widget');
// disable default dashboard widgets
function disable_default_dashboard_widgets() {

    remove_meta_box('dashboard_right_now', 'dashboard', 'core');
    remove_meta_box('dashboard_recent_comments', 'dashboard', 'core');
    remove_meta_box('dashboard_incoming_links', 'dashboard', 'core');
    remove_meta_box('dashboard_plugins', 'dashboard', 'core');

    remove_meta_box('dashboard_quick_press', 'dashboard', 'core');
    remove_meta_box('dashboard_recent_drafts', 'dashboard', 'core');
    remove_meta_box('dashboard_primary', 'dashboard', 'core');
    remove_meta_box('dashboard_secondary', 'dashboard', 'core');
}
add_action('admin_menu', 'disable_default_dashboard_widgets');

if (function_exists('register_sidebar')) {

    register_sidebar(array(
        'name' => 'Widgetized Area',
        'id'   => 'widgetized-area',
        'description'   => 'This is a widgetized area.',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',

        'after_widget'  => '</div>',
        'before_title'  => '<h4>',

        'after_title'   => '</h4>'
    ));

}
?>