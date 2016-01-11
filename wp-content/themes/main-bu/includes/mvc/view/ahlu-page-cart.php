<?php if(isset($WP_enable) && $WP_enable) get_header();
$me = $post->getMe();
global $lang;
$upload_dir = wp_upload_dir();

?>

<div class="breadcrumb-wrapper row">

            <div class="container">
                <!-- breadcrumbs start -->
                <div class="row">
                    <div class="col-xs-12">
                        <ul class="breadcrumb">
							<?php 
								$breadCrumbs = $post->breadCrumbs("Home","/","&raquo; ",array(),true);
									foreach($breadCrumbs as $i=> $v){
									 echo '<li><a href="'.$v[1].'">'.$v[0].'</a></li>';
									}
							?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
<div class="content-wrapper container">
            <div id="bodyContent" class="row">
			<?php echo do_shortcode($me->post_content); ?>
        </div>
        </div>			
<?php
 if(isset($WP_enable) && $WP_enable) get_footer(); 
 ?>