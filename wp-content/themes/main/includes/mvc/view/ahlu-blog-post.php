<?php
//$page = get_page_by_title( 'home' );
 //$meta = get_post_meta($page->ID);
 //print_r($meta);
	$me = $post->getMe();
  $category = new Category_model();
  $category->post_type=$post->post_type;
  $view = $category->viewOnPost();
  
?>
<div class="wrapper ahlu-box panel-1">
		
		<div class="wrapper ahlu-box panel-6 " style="background:url(<?php echo $me->thumbnail ?>) no-repeat center  center;background-attachment: fixed; background-size: cover;">
				<div class="ahlu-body" style="margin:0;">
					<div class="ahlu-body-layer"></div>
					<div class="panel-box col-md-9 intro-blog">
						<p style="text-align: center;">
							<img src="<?php echo get_the_author_meta( 'avatar' , $me->post_author ); ?> " width="140" height="140" class="avatar" alt="<?php echo the_author_meta( 'display_name' , $author_id ); ?>" />
								<br />
							<h2 class="intro"><?php echo $me->post_excerpt;?></h2>
								<?php $t = strtotime($me->post_date); ?>

							<span class="date"><i class="fa fa-calendar"></i> on <?php echo date("F",$t)?> <?php echo date("d",$t)?>, <?php echo date("Y",$t)?></span>
						</p>

						
					</div>
				</div>
			</div>
		
		
		
		<div class="wrapper ahlu-box panel-2">
				<div class="ahlu-body">
					<div class="ahlu-body-layer" style="background-color:white"></div>
					<div class="panel-box col-md-10">
							<div class="col-md-12 post-contain">
								<div class="col-md-8 ">
									<h1><?php echo $me->post_title; ?></h1>
									
									<div class="content">
										<?php echo $me->post_content; ?>
									</div>
								</div>
								
								<div class="col-md-4">
									<div class="col-md-12 no-space news-list">
										<h2 class="title">ALL TIME <span>HITS</span></h2>
										<ul>	
											<?php
												if(is_array($view->data) && count($view->data)>0){
													foreach($view->data as $item){
												
											?>
											 <li><a href="<?php echo site_url($item->post_name); ?>"><?php echo $item->post_title; ?></a></li>
											<?php
												}
											}?>
										</ul>
									</div>
								</div>
							</div>
					</div>
				</div>
			</div>
		
	
		
		</div>