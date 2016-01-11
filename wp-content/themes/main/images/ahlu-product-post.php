<!-- header wrapper end -->
<?php
$me = $post->getMe();
?>
<div class="wrapper product-bg">
	<div class="ahlu-box panel-1 col-md-10 center-no-center" style="clear:inherit">
		<div class="ahlu-body">
			<div class="well" style="display:none;">Add To Cart successfully</div>
			<a href="#" onclick="window.history.back();"; class="return-back" title="return back" alt="return back" ><img src="<?php bloginfo('template_directory');?>/images/go_back.png" title="return back" alt="return back" /></a>
			
			<div class="col-md-12 no-space product-items">
				<div class="col-md-8 col-xs-12 no-space content-post left-product">
					<h1 class="head-title left"><?php echo $me->display; ?></h1>
					<?php echo $me->post_content; ?>
				</div>
				<div class="col-md-4 col-xs-12 no-space slider-module right-product">
					<div class="right add-cart1"><a class="add-cart-product" href="#" onclick="receiveFromURL('<?php echo site_url($me->post_name); ?>?add_cart',{id:'<?php echo $me->ID; ?>',sku:'<?php echo $me->sku; ?>',quantity:1},function(data){if(data==1){var value=$('.cart-holder span').html();$('.cart-holder span').html(parseInt(value)+1);$('.well').show('slow',function(){setTimeout(function(){$('.well').hide()},3000)})};},true); return false;"><img src="<?php bloginfo('template_directory');?>/images/add_to_enquiry.png"  /></a></div>
					<div class="right add-cart2"><a class="enquiry-service" href="#"><img src="http://dev.dusted.com.au/dev_ease/wp-content/themes/main/images/service-button.png"></a></div>
		
					<div class="col-md-12 no-space slider">
						 <link rel="stylesheet" href="<?php bloginfo('template_directory');?>/js/bxslider/jquery.bxslider.css" type="text/css" />
						  <script src="<?php bloginfo('template_directory');?>/js/bxslider/jquery.bxslider.js"></script>
						  <script>
							$(document).ready(function(){
							  $('.bxslider').bxSlider({
								auto: true
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
							<ul class="bxslider">
						<?php
							echo implode("\n",array_map(function($img){
								return '<li><img src="'.$img.'" /></li>';
							},$post->gallery()));
						?>
							</ul>
						</div>
						</div>
						
					<div  class="read-brochure">
						<a href="#"><img src="<?php bloginfo('template_directory');?>/images/read-brochure_1.png" style="position: relative;right:-68px;" /></a>
					</div>
				</div>
			</div>
			
		</div>
	</div>
</div>