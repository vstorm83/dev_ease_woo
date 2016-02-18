<?php
	// create shortcode with parameters so that the user can define what's queried - default is to list all blog posts
add_shortcode( 'slider_scroller', 'slider_scroller_123' );
function slider_scroller_123( $atts ) {
    ob_start();
 
    // define attributes and their defaults
    extract( shortcode_atts( array (
        'type' => 'pdfsession',
        'order' => 'date',
        'posts' => -1,
    ), $atts ) );
 
    $a = new Category_model();
	$a->post_type = $type;
	$posts = $a->get_posts();
?>
<link href="<?php bloginfo('template_directory');?>/css/default_skin_variation.css" type="text/css" rel="stylesheet" />
							<style>
								.sc-prev,.sc-next{    display: block!important;}
							</style>
							<script type="text/javascript" src="<?php bloginfo('template_directory');?>/js/jquery.sky.carousel-1.0.2.min.js"></script>
							<script type="text/javascript">
								jQuery(function() {
									jQuery('.sky-carousel').carousel({
										itemWidth: 200,
										itemHeight: 240,
										distance: 15,
										selectedItemDistance: 50,
										selectedItemZoomFactor: 1,
										unselectedItemZoomFactor: 0.67,
										unselectedItemAlpha: 0.6,
										motionStartDistance: 170,
										topMargin: 119,
										gradientStartPoint: 0.35,
										gradientOverlayColor: "#000",
										gradientOverlaySize: 190,
										reflectionDistance: 1,
										reflectionAlpha: 0.35,
										reflectionVisible: true,
										reflectionSize: 70,
										selectByClick: true
									});
								});
							</script>
				<div class="sky-carousel" style="background:none;">
					<div class="sky-carousel-wrapper">
						<ul class="sky-carousel-container">
<?php
    if(is_array($posts) && count($posts)>0){
		foreach($posts as $item){
		$link = strip_tags($item->post_content);
echo <<<AHLU
<li>
	<img src="{$item->thumbnail}" alt="" />
	<a href="{$link}" target="_blank" class="btn-reader btn btn-success" style="position: absolute;top: 35%;left: 15px;background:#0cd411;border: none;padding: 7px 30px;    font-size: 18px;position:absolute;">Read .Pdf</a>
	<div class="sc-content">
		<h2>{$item->post_title}</h2>
		<p>{$item->post_excerpt}</p>
	</div>
</li>
AHLU;
		}
		
	}
	?>
	</ul>
<?php								
        $myvariable = ob_get_clean();
        return $myvariable;
    }
}
?>