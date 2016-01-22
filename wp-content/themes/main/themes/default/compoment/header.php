 <!-- Modal HTML -->
    <div id="myModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Confirmation</h4>
                </div>
                <div class="modal-body">
                    <p>Do you want to save changes you made to document before closing?</p>
                    <p class="text-warning"><small>If you don't save, your changes will be lost.</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
<div class="wrapper navigation">
<?php    dynamic_sidebar( 'homepage-sidebar' ); ?>
					<div class="left no-space ">

						<div class="logo left">

							<a href="<?php echo site_url(); ?>"><span>EASE</span></a>

						</div>

						<div class="slogan left">Emerald Air Starters & Equipment</div>

					</div>

					<div class="menu-container right">

						<div class="menu-vertical left">

							<?php
								$defaults = array(

											'menu'            => 'MenuTop',

											//s'container'       => 'div',

											'menu_class'      => '',

											'echo'            => true,

											'fallback_cb'     => 'wp_page_menu',

											'items_wrap'      => '<ul class="%2$s">%3$s</ul>',

										);

								wp_nav_menu( $defaults );
							?>
						</div>

						<div class="menu-horizontal left">

							<div class="menu-horizontal-container">

								<a href="#" class="open-menu"><img src="<?php bloginfo('template_directory');?>/images/3_rows.png" /></a>

								<div class="group">

									<input type="text" onkeypress="if((event.charCode || event.keyCode)==13){location.href='<?php echo site_url(); ?>/shop?woof_title='+event.target.value;}" class="search" name="q" placeholder="Search here" />

									<div class="menu">

										<script>

											( function( $ ) {
												
												$(document).ready(function(){
										
													$('.menu > .menu-menu_pro-container > ul > li > a').click(function() {

													  $('.menu li').removeClass('active');

													  $(this).closest('li').addClass('active');

													  

													  $('.menu > .menu-menu_pro-container > ul ul').slideUp('normal');

													  

													  var checkElement = $(this).next();

													  if((checkElement.is('ul')) && (checkElement.is(':visible'))) {

														$(this).closest('li').removeClass('active');

														checkElement.slideUp('normal');

													  }

													  if((checkElement.is('ul')) && (!checkElement.is(':visible'))) {

														$('.menu ul ul:visible').slideUp('normal');

														checkElement.slideDown('normal');

													  }

													  return $(this).closest('li').find('ul').children().length == 0;

													});

												});
											} )( jQuery );

										</script>

											<?php
												function md_nmi_custom_content_1( $content, $item_id, $original_content ) {
																	//get content get_post_field( 'post_content', $item_id )
														$content = $content.'<span class="page-title">' . $original_content . '</span>';
													return $content;
												}
												add_filter( 'nmi_menu_item_content', 'md_nmi_custom_content_1', 10, 3 );
												$defaults = array(

															'menu'            => 'Menu_Pro',

															//s'container'       => 'div',

															'menu_class'      => '',

															'echo'            => true,

															'fallback_cb'     => 'wp_page_menu',

															'items_wrap'      => '<ul class="%2$s">%3$s</ul>',

														);

												wp_nav_menu( $defaults );
											?>
									</div>

								</div>

							</div>

						</div>

						<div class="right cart-holder">
							<script>

/** Modified cart-fragments.js script to break HTML5 fragment caching. Useful with WPML when switching languages **/
	jQuery(document).ready(function($) {
		//add new function count only product in cart
		$(document).bind("mini-cart-update",function(e,data){
			var list = $(".product_list_widget",data.html);
			var l = 0;
			if(list.find("li.empty").length!=0){
				$(".cart-holder a.total_pro").find("span").html(l);
			}else{
				l = list.find("li").length;
				$(".cart-holder a.total_pro").find("span").html(l);
			}
			
			if(data.callback instanceof Function) data.callback(l);
		});
		$(document).bind("mini-cart",function(e,args){
			/** Cart Handling */
			    $supports_html5_storage = ( 'sessionStorage' in window && window['sessionStorage'] !== null );
			
			    $fragment_refresh = {
			        url: woocommerce_params.ajax_url,
			        type: 'POST',
			        data: { action: 'woocommerce_get_refreshed_fragments' },
			        success: function( data ) {
			            if ( data && data.fragments ) {
			
			                $.each( data.fragments, function( key, value ) {
			                    $(key).replaceWith(value);
			                });
			
			                if ( $supports_html5_storage ) {
			                    sessionStorage.setItem( "wc_fragments", JSON.stringify( data.fragments ) );
			                    sessionStorage.setItem( "wc_cart_hash", data.cart_hash );
			                }
			
			                $('body').trigger( 'wc_fragments_refreshed' );
							
							if(args.callback instanceof Function){
								args.callback(data);
							}
			            }
			        }
			    };
			
			    //Always perform fragment refresh
			    $.ajax( $fragment_refresh );
			
			    /* Cart hiding */
			    if ( $.cookie( "woocommerce_items_in_cart" ) > 0 )
			        $('.hide_cart_widget_if_empty').closest('.widget_shopping_cart').show();
			    else
			        $('.hide_cart_widget_if_empty').closest('.widget_shopping_cart').hide();
			
			    $('body').bind( 'adding_to_cart', function() {
			        $('.hide_cart_widget_if_empty').closest('.widget_shopping_cart').show();
			    } );
			});
    	$(document).trigger("mini-cart",{callback:function(/**/){
			var args = arguments[0];
			$(document).trigger("mini-cart-update",{html:args.fragments["div.widget_shopping_cart_content"]});
		}});
	});

							var WC_cart_hash = "<?php echo AhluStore()->getHash(); ?>";	
							//how to prevent add mutil time product in cart
							$(document).ready(function(){
							
								var id = setInterval(function(){
									if($( ".cart-holder .total_pro" ).find("span").html()!="0")
										shake($( ".cart-holder" ));
								},5000);
								if($( ".cart-holder .total_pro" ).find("span").html()!="0")
										shake($( ".cart-holder" ));

								//listen agian after cart mini updated
							$( document.body ).bind( 'added_to_cart', function( event, fragments, cart_hash ) {
								//trigger event	
								$(document).trigger("mini-cart-update",{html:fragments["div.widget_shopping_cart_content"]});
							});
							
								function shake($div,interval,distance,times){
									if(!interval)
										interval=100;
									if(!distance)
										distance=10
									if(!times)times=4

								    $div.css('position','relative');
								    for(var iter=0;iter<(times+1);iter++){
								        $div.animate({ left: ((iter%2==0 ? distance : distance*-1))}, interval);
								    }//for
								   $div.animate({ left: 0},interval);
								}//shake
								//detech all button listen to this, this excute before cart html updating
								$('.add_to_cart_button').on( 'click', function(e) {
									var $this = $(this);
									//get type of enquiry
									var type = $(this).hasClass("enquiry-service")?"Service":"Product";
									var ok = true;
									var id = $(this).attr("data-product_id");
									
	
									$(".well").remove();

									//check this product in cart
									if($(".widget_shopping_cart_content").find(".pro-"+$this.attr("data-product_id")).length!=0){
										//change this class notify, this product as read more or can not be bought
										$this.removeClass("product_type_simple");
										
										var msg = 'You Have Already Added This Item.';
										

										$(".product-bg .ahlu-body").prepend('<div class="well" style="position: absolute;top: -43px;width: 800px;">'+msg+'</div>');
										setTimeout(function(){
											var well= $(".well");
											well.hide("slow",function(){
												well.remove();
											});
											
										},5000);
										$(window).scrollTop( 0 );
									}else{
										$.ajax({
								            type: 'POST',
								            url: "<?php echo admin_url( 'admin-ajax.php'); ?>",
								            data: {"action": "add_service_enquiry",id_product:id,type:type,cart_hash:WC_cart_hash},
											async: false, //wait
								            success: function(data){
												if(data==1){
												}else{
													ok = false;
												}
												done = true;
											}
								        });
										//show message ok
										if(ok){
											var msg = 'Your	'+type+' Enquiry "'+$this.attr("data-title").toUpperCase()+'" Has	Been Added To Your Cart.';

											$(".product-bg .ahlu-body").prepend('<div class="well" style="position: absolute;top: -43px;width: 800px;">'+msg+'</div>');
											setTimeout(function(){
												var well= $(".well");
												well.hide("slow",function(){
													well.remove();
												});
										
											},5000);
										}
										$(window).scrollTop( 0 );
										
									}
									e.preventDefault();
									
								});
								
								
							});
							</script>
							<a class="total_pro" href="<?php echo site_url("checkout"); ?>"><img src="<?php bloginfo('template_directory');?>/images/holder.png" /><span><?php echo count(WC()->cart->get_cart()); //count the product exist ?></span></a>
							<div class="cart-content"  style="display:none;">
                                <div class="widget_shopping_cart_content">
									<?php woocommerce_mini_cart(); //reference mini-cart in theme woo ?>
								</div>												
							</div>
							
						</div>

					</div>

				</div>

			