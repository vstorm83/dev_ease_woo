<?php if(isset($WP_enable) && $WP_enable) get_header();
$me = $post->getMe();

?>
	
<div class="wrapper default-page"> 
		<?php echo $me->post_content; ?>
</div>
<?php
 if(isset($WP_enable) && $WP_enable) get_footer(); 
 ?>