<?php
 $page = get_page_by_title( 'home' );
 $meta = get_post_meta($page->ID);
 //print_r($meta);
 
 //die();
?>
<div class="wrapper slideshow-container">
	<script src="http://webdesignandsuch.com/posts/fancybox-download/responsive-youtube-videos/js/fancybox/jquery.fancybox.js"></script>
		<script src="http://webdesignandsuch.com/posts/fancybox-download/responsive-youtube-videos/js/fancybox/jquery.fancybox-buttons.js"></script>
		<script src="http://webdesignandsuch.com/posts/fancybox-download/responsive-youtube-videos/js/fancybox/jquery.fancybox-thumbs.js"></script>
		
		 <script src="http://webdesignandsuch.com/posts/fancybox-download/responsive-youtube-videos/js/fancybox/jquery.easing-1.3.pack.js"></script>
		<script src="http://webdesignandsuch.com/posts/fancybox-download/responsive-youtube-videos/js/fancybox/jquery.mousewheel-3.0.6.pack.js"></script>
		<link rel="stylesheet" href="http://webdesignandsuch.com/posts/fancybox-download/responsive-youtube-videos/css/fancybox/jquery.fancybox-buttons.css">
        <link rel="stylesheet" href="http://webdesignandsuch.com/posts/fancybox-download/responsive-youtube-videos/css/fancybox/jquery.fancybox-thumbs.css">
        <link rel="stylesheet" href="http://webdesignandsuch.com/posts/fancybox-download/responsive-youtube-videos/css/fancybox/jquery.fancybox.css">
	<link rel="stylesheet" href="<?php echo plugins_url(); ?>/revslider/rs-plugin/css/settings.css">
					<style>
						.tp-caption.big_white{
									position: absolute; 
									color: #fff; 
									text-shadow: none; 
									font-weight: 500; 
									font-size: 36px; 
									line-height: 36px; 
									font-family: Conv_AGaramondPro-Bold; 
									padding: 0px 4px; 
									padding-top: 2px;
									margin: 0px; 
									border-width: 0px; 
									border-style: none; 
									letter-spacing: -0.5px;										
								}

						.tp-caption.big_orange{
									position: absolute; 
									color: #ff7302; 
									text-shadow: none; 
									font-weight: 700; 
									font-size: 36px; 
									line-height: 36px; 
									font-family: Arial; 
									padding: 0px 4px; 
									margin: 0px; 
									border-width: 0px; 
									border-style: none; 
									background-color:#fff;	
									letter-spacing: -1.5px;															
								}	
											
						.tp-caption.big_black{
									position: absolute; 
									color: #000; 
									text-shadow: none; 
									font-weight: 700; 
									font-size: 36px; 
									line-height: 36px; 
									font-family: Arial; 
									padding: 0px 4px; 
									margin: 0px; 
									border-width: 0px; 
									border-style: none; 
									background-color:#fff;	
									letter-spacing: -1.5px;															
								}		

						.tp-caption.medium_grey{
									position: absolute; 
									color: #fff; 
									text-shadow: none; 
									font-weight: 700; 
									font-size: 20px; 
									line-height: 20px; 
									font-family: Arial; 
									padding: 2px 4px; 
									margin: 0px; 
									border-width: 0px; 
									border-style: none; 
									background-color:#888;		
									white-space:nowrap;	
									text-shadow: 0px 2px 5px rgba(0, 0, 0, 0.5);		
								}	
											
						.tp-caption.small_text{
									position: absolute; 
									color: #fff; 
									text-shadow: none; 
									font-weight: 700; 
									font-size: 14px; 
									line-height: 20px; 
									font-family: Arial; 
									margin: 0px; 
									border-width: 0px; 
									border-style: none; 
									white-space:nowrap;	
									text-shadow: 0px 2px 5px rgba(0, 0, 0, 0.5);		
								}
											
						.tp-caption .medium_text{
									position: absolute; 
									color: #fff; 
									text-shadow: none; 
									font-weight: 700; 
									font-size: 20px; 
									line-height: 20px; 
									font-family: Arial; 
									margin: 0px; 
									border-width: 0px; 
									border-style: none; 
									white-space:nowrap;	
									text-shadow: 0px 2px 5px rgba(0, 0, 0, 0.5);		
								}
											
						.tp-caption .large_text{
									position: absolute; 
									color: #fff; 
									text-shadow: none; 
									font-weight: 700; 
									font-size: 40px; 
									line-height: 40px; 
									font-family: Arial; 
									margin: 0px; 
									border-width: 0px; 
									border-style: none; 
									white-space:nowrap;	
									text-shadow: 0px 2px 5px rgba(0, 0, 0, 0.5);		
								}	
											
						.tp-caption.very_large_text{
									position: absolute; 
									color: #fff; 
									text-shadow: none; 
									font-weight: 700; 
									font-size: 60px; 
									line-height: 60px; 
									font-family: Arial; 
									margin: 0px; 
									border-width: 0px; 
									border-style: none; 
									white-space:nowrap;	
									text-shadow: 0px 2px 5px rgba(0, 0, 0, 0.5);
									letter-spacing: -2px;		
								}
											
						.tp-caption.very_big_white{
									position: absolute; 
									color: #fff; 
									text-shadow: none; 
									font-weight: 700; 
									font-size: 60px; 
									line-height: 60px; 
									font-family: Arial; 
									margin: 0px; 
									border-width: 0px; 
									border-style: none; 
									white-space:nowrap;	
									padding: 0px 4px; 
									padding-top: 1px;
									background-color:#000;		
											}	
											
						.tp-caption.very_big_black{
									position: absolute; 
									color: #000; 
									text-shadow: none; 
									font-weight: 700; 
									font-size: 60px; 
									line-height: 60px; 
									font-family: Arial; 
									margin: 0px; 
									border-width: 0px; 
									border-style: none; 
									white-space:nowrap;	
									padding: 0px 4px; 
									padding-top: 1px;
									background-color:#fff;		
											}
											
						.tp-caption.boxshadow{
								-moz-box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.5);
								-webkit-box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.5);
								box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.5);
							}
																	
						.tp-caption.black{
								color: #000; 
								text-shadow: none;		
							}	
											
						.tp-caption.noshadow {
								text-shadow: none;		
							}	
											
						.tp-caption a { 
							color: #ff7302; text-shadow: none;	-webkit-transition: all 0.2s ease-out; -moz-transition: all 0.2s ease-out; -o-transition: all 0.2s ease-out; -ms-transition: all 0.2s ease-out;	 
						}			
							
						.tp-caption a:hover { 
							color: #ffa902; 
						}
						
						.tp-rightarrow {
							z-index: 100;
							cursor: pointer;
							position: relative;
							background: url(<?php bloginfo('template_directory');?>/images/arraw_right_gray.png) no-Repeat 0 0!important;
							width: 40px;
							height: 40px;
						}
						.tp-rightarrow:hover{
							background: url(<?php bloginfo('template_directory');?>/images/arrow_right.png) no-repeat 0 0!important;
							    right: 19px !important;
								top: 328px !important;
						}
						
						.tp-leftarrow{
							z-index: 100;
							cursor: pointer;
							position: relative;
							background: url(<?php bloginfo('template_directory');?>/images/arraw_left_gray.png) no-Repeat 0 0!important;
							width: 40px;
							height: 40px;
						}
						.tp-leftarrow:hover{
							background: url(<?php bloginfo('template_directory');?>/images/arraw_left.png) no-repeat 0 0!important;
							    left: 21px !important;
								top: 328px !important;
								
						}
						
						.tp-bullets .bullet {
							background: url(<?php bloginfo('template_directory');?>/images/bullet.png) no-Repeat -27px -3px !important;
							width: 22px !important;
							height: 22px !important;
						}
						.tp-bullets .bullet.selected {
							background: url(<?php bloginfo('template_directory');?>/images/bullet.png) no-Repeat -5px -2px  !important;
							width: 22px !important;
							height: 22px !important;
						}
					</style>
					
					<?php echo do_shortcode('[rev_slider slideshow]') ?>
				</div>
				<div class="wrapper slider-container">
						<script type="text/javascript" language="javascript" src="<?php bloginfo('template_directory');?>/js/jquery.carouFredSel-6.2.1.js"></script>
						<!-- optionally include helper plugins -->
						<script type="text/javascript" language="javascript" src="<?php bloginfo('template_directory');?>/js/helper-plugins/jquery.mousewheel.min.js"></script>
						<script type="text/javascript" language="javascript" src="<?php bloginfo('template_directory');?>/js/helper-plugins/jquery.touchSwipe.min.js"></script>
						<script type="text/javascript" language="javascript" src="<?php bloginfo('template_directory');?>/js/helper-plugins/jquery.transit.min.js"></script>
						<script type="text/javascript" language="javascript" src="<?php bloginfo('template_directory');?>/js/helper-plugins/jquery.ba-throttle-debounce.min.js"></script>
						<script type="text/javascript" language="javascript">
							jQuery(document).ready(function() {
									jQuery.fn.goTo = function() { $('html, body').animate({ scrollTop:($(this).offset().top-77) + 'px' }, 'fast'); return this; };
									$.fn.goTo = function() { $('html, body').animate({ scrollTop:($(this).offset().top-77) + 'px' }, 'fast'); return this; };
									$(".go_to_1").live("click",function(e){ 
										$('.go_to_2').closest(".panel-2").goTo(); $(this).die(e.type); 
									});
									$(".go_to_2").live("click",function(e){ 
										$('.go_to_3').closest(".panel-1").goTo(); $(this).die(e.type); 
									});
									
									//Responsive layout, resizing the items
									jQuery('.nav_slider').carouFredSel({
										responsive: true,
										width: '100%',
										auto: false,
										prev: '.prev',
										next: '.next',
										items: {
											width: '200px',
											height: '150px',	//	optionally resize item-height
											visible: {
												min: 1,
												max: 6
											}
										}
									}).find("li").prepend("<div class='ahlu-body-layer'></div>").prepend("<div class='line'></div>");
									
									jQuery(".various").fancybox({
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
						<div class="list_carousel">
					<?php
						
						function md_nmi_custom_content( $content, $item_id, $original_content ) {
							//get content get_post_field( 'post_content', $item_id )
						    $content = '<span class="page-title">' . $original_content . '</span> <br />'.$content;
						    return $content;
						}
						add_filter( 'nmi_menu_item_content', 'md_nmi_custom_content', 10, 3 );
							$defaults = array(

								'menu'            => 'nav_slider',

								//s'container'       => 'div',

								'menu_class'      => 'nav_slider',

								'echo'            => true,

								'fallback_cb'     => 'wp_page_menu',

								'items_wrap'      => '<ul class="%2$s">%3$s</ul>',

							);

							wp_nav_menu( $defaults );
					?>
						<a style="position:absolute;top:37%;" class="prev" style="float:left;" href="#">&nbsp;</a>
						<a style="position:absolute;top:37%;right: -20px;" class="next" style="float:right;" href="#">&nbsp;</a>
						<div class="clearfix"></div>
					</div>
				</div>
				<?php
								$a = new Category_model();
								$a->post_type = "homesession";
								$home= $a->get_posts();
								
								if(is_array($home) && count($home)>0){
									$home= array_reverse($home);
									
									foreach($home as $i=> $item){
									if($i==4)break;
									$bg = isset($item->bg)? $item->bg: "";
									$img_bg = isset($item->thumbnail)? "background:url({$item->thumbnail}) {$bg} no-repeat center  center;background-attachment: fixed; background-size: cover;": "";
									$img_bg = empty($img_bg)?$bg:$img_bg;
									$content = do_shortcode($item->post_content);
echo <<<AHLU
									<div class="wrapper ahlu-box panel-{$item->ID}" style="{$img_bg}">
										<div class="ahlu-body">
											{$content}
										</div>
									</div>
AHLU;
									}
								}
				?>
				
				<div class="wrapper ahlu-box panel-50" style="background:url(<?php bloginfo('template_directory');?>/images/pdf-bg-section.jpg) #000 no-repeat center  center;background-attachment: fixed; background-size: cover;">
					<div class="ahlu-body">
						<div class="panel-box col-md-12" style="padding: 80px 0;">
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
										selectByClick: true,
										enableMouseWheel : false
									});
								});
							</script>
				<div class="sky-carousel" style="background:none;">
					<div class="sky-carousel-wrapper">
						<ul class="sky-carousel-container">
							<?php
								$a->post_type = "pdfsession";
								$posts = $a->get_posts();
								
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
					</div>
				</div>
						</div>
					</div>
				</div>
				<div class="wrapper ahlu-box panel-6 ahlu-box-last" style="background:url(<?php echo $home[5]->thumbnail; ?>) no-repeat top center;background-attachment: fixed; background-size: cover;"
					<div class="ahlu-body">
						<div class="ahlu-body-layer"></div>
						<div class="panel-box ">
							<?php echo $home[5]->post_content; ?>
						</div>
					</div>
				</div>