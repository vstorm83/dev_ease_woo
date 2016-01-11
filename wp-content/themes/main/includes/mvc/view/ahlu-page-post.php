<?php if(isset($WP_enable) && $WP_enable) get_header();
$me = $post->getMe();
?>
	
<div class="wrapper default-page"> 
	<div class="container"> 
		<h1 class="title-page"><?php echo $me->post_title; ?></h1>
		<div class="col-md-12 menu-tab no-space">
			<?php echo $me->post_content; ?>
		</div>
	</div>
</div>
<?php
 if(isset($WP_enable) && $WP_enable) get_footer(); 
 ?>