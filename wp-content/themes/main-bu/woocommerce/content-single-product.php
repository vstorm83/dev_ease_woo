<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * Override this template by copying it to yourtheme/woocommerce/content-single-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post, $product, $woocommerce;

?>

<?php
	/**
	 * woocommerce_before_single_product hook
	 *
	 * @hooked wc_print_notices - 10
	 */
	 do_action( 'woocommerce_before_single_product' );

	 if ( post_password_required() ) {
	 	echo get_the_password_form();
	 	return;
	 }
?>
<div class="col-md-12 product-bg">
	<div class="ahlu-box panel-1 col-md-10 center-no-center">
		<div class="ahlu-body">
		<div itemscope itemtype="<?php echo woocommerce_get_product_schema(); ?>" id="product-<?php the_ID(); ?>" <?php post_class(); ?>>
			<div class="col-md-12 col-xs-12 no-space">
				<a href="#" onclick="window.history.back();"; class="return-back" title="return back" alt="return back" ><img src="<?php bloginfo('template_directory');?>/images/go_back.png" title="return back" alt="return back" /></a>
			
				<div class="col-md-8 col-xs-12 no-space content-post left-product">
					<h1 class="head-title left"><?php echo get_post_meta( get_the_ID(), 'display', true ); ?></h1>
				<?php
					/**
					 * woocommerce_after_single_product_summary hook
					 *
					 * @hooked woocommerce_output_product_data_tabs - 10
					 * @hooked woocommerce_upsell_display - 15
					 * @hooked woocommerce_output_related_products - 20
					 */
					//do_action( 'woocommerce_after_single_product_summary' );
				?>
					<div class="col-md-12 no-space">
						<?php the_content(); ?>

											<?php
						/**
						 * woocommerce_single_product_summary hook
						 *
						 * @hooked woocommerce_template_single_title - 5
						 * @hooked woocommerce_template_single_rating - 10
						 * @hooked woocommerce_template_single_price - 10
						 * @hooked woocommerce_template_single_excerpt - 20
						 * @hooked woocommerce_template_single_add_to_cart - 30
						 * @hooked woocommerce_template_single_meta - 40
						 * @hooked woocommerce_template_single_sharing - 50
						*/	
						remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
						remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
						remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
						//remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
						remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
						remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50 );
						do_action( 'woocommerce_single_product_summary' );
						 
						//do_action( 'woocommerce_before_add_to_cart_form' ); ?>
					</div>
				</div>
				<div class="col-md-4 col-xs-12 right-product">


						<form class="cart" method="post" enctype='multipart/form-data'>
							<?php //do_action( 'woocommerce_before_add_to_cart_button' ); ?>

							<input type="hidden" name="add-to-cart" value="<?php echo esc_attr( $product->id ); ?>" />

							<div class="right add-cart1"><a class="add_to_cart_button product_type_simple" data-title="<?php the_title() ?>" data-product_id="<?php echo $product->id; ?>" href="?add-to-cart=<?php echo $product->id; ?>"><img src="<?php bloginfo('template_directory');?>/images/add_to_enquiry.png"  /></a></div>
					
					
							<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>
						</form>

						<?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>
					
					<div class="right add-cart2"><a class="enquiry-service add_to_cart_button product_type_simple" data-title="<?php the_title() ?>" data-product_id="<?php echo $product->id; ?>" href="?add-to-cart=<?php echo $product->id; ?>"><img src="http://dev.dusted.com.au/dev_ease/wp-content/themes/main/images/service-button.png"></a></div>
		
					<?php
					/**
					 * woocommerce_before_single_product_summary hook
					 *
					 * @hooked woocommerce_show_product_sale_flash - 10
					 * @hooked woocommerce_show_product_images - 20
					 */
					//do_action( 'woocommerce_before_single_product_summary' );
					
					
				?>
					<div class="col-md-12 no-space slider">
						<script src="http://webdesignandsuch.com/posts/fancybox-download/responsive-youtube-videos/js/fancybox/jquery.fancybox.js"></script>
		<script src="http://webdesignandsuch.com/posts/fancybox-download/responsive-youtube-videos/js/fancybox/jquery.fancybox-buttons.js"></script>
		<script src="http://webdesignandsuch.com/posts/fancybox-download/responsive-youtube-videos/js/fancybox/jquery.fancybox-thumbs.js"></script>
		
		 <script src="http://webdesignandsuch.com/posts/fancybox-download/responsive-youtube-videos/js/fancybox/jquery.easing-1.3.pack.js"></script>
		<script src="http://webdesignandsuch.com/posts/fancybox-download/responsive-youtube-videos/js/fancybox/jquery.mousewheel-3.0.6.pack.js"></script>
		<link rel="stylesheet" href="http://webdesignandsuch.com/posts/fancybox-download/responsive-youtube-videos/css/fancybox/jquery.fancybox-buttons.css">
        <link rel="stylesheet" href="http://webdesignandsuch.com/posts/fancybox-download/responsive-youtube-videos/css/fancybox/jquery.fancybox-thumbs.css">
        <link rel="stylesheet" href="http://webdesignandsuch.com/posts/fancybox-download/responsive-youtube-videos/css/fancybox/jquery.fancybox.css">

						 <link rel="stylesheet" href="<?php bloginfo('template_directory');?>/js/bxslider/jquery.bxslider.css" type="text/css" />
						  <script src="<?php bloginfo('template_directory');?>/js/bxslider/jquery.bxslider.js"></script>
						  <script>
							jQuery(document).ready(function(){
							  jQuery('.bxslider').bxSlider({
								auto: true
							  });
							  jQuery(".fancybox").fancybox({
										maxWidth	: 800,
										maxHeight	: 600,
										fitToView	: false,
										width		: '70%',
										height		: '70%',
										autoSize	: false,
										closeClick	: false,
										openEffect	: 'elastic',
										closeEffect	: 'none'
									});
							});
						  </script>
						  <style>
							.bx-wrapper .bx-viewport{    -moz-box-shadow: none;
							-webkit-box-shadow: none;
							box-shadow: none;
							border: none;}
							.bx-wrapper img{    margin: 0 auto;}
							.bx-wrapper  .bx-controls-direction{display:none;}
						  </style>
						<div class="slider-container">
							<ul class="bxslider" style=" height: 212px;   overflow: hidden;">
						
						<?php
							$data = array();
							$attachment_ids = $product->get_gallery_attachment_ids();
							if ( $attachment_ids ) {
									foreach ( $attachment_ids as $attachment_id ) {
									$image_link = wp_get_attachment_url( $attachment_id );

									if ( ! $image_link )
										continue;

									//$image_title 	= esc_attr( get_the_title( $attachment_id ) );
									//$image_caption 	= esc_attr( get_post_field( 'post_excerpt', $attachment_id ) );
									$data[] = $image_link;
								}
							}
							echo implode("\n",array_map(function($img){
								return '<li style="float: left;"><a rel="gallery_group" class="fancybox" href="'.$img.'"><img src="'.$img.'" /></a></li>';
							},$data));

						?>
							</ul>
						</div>
						</div>
						
					<div  class="read-brochure right ">
						<a href="<?php echo get_post_meta( get_the_ID(), 'link_pdf', true ); ?>" target="_blank"><img src="<?php bloginfo('template_directory');?>/images/read-brochure_1.png"  /></a>
					</div>
				</div><!-- .summary -->
			</div> 
			<meta itemprop="url" content="<?php the_permalink(); ?>" />

		</div><!-- #product-<?php the_ID(); ?> -->
		</div>
	<?php do_action( 'woocommerce_after_single_product' ); ?>
	</div>
</div>