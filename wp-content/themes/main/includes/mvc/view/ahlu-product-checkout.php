<!-- header wrapper end -->
<?php
global $woocommerce;
$cart_url = site_url("cart");
?>
  <script>
	var window.WC_url = "<?php echo $cart_url; ?>";
  </script>
<div class="wrapper product-bg">
	<div class="ahlu-box panel-1 col-md-10 center-no-center" style="clear:inherit">
		<div class="ahlu-body">
		<div class="check-none" style="display:none;">
		<?php include_once "ahlu-product-checkout-non.php" ?>
		</div>
		<form class="form_enquires" method="post">
			<div class="col-md-12 no-space">
				<div class="col-md-4 no-space">
					<h1 class="head-title">Your <span>ENQUIRIES<span></h1>
				</div>
				<div class="col-md-4 no-space">			
					<div class="pagation_slide" style="display:none;">
						<a class="prev left">&nbsp;</a>
							<div class="pager left"></div>
						<a class="next left" href="#">&nbsp;</a>
						
					</div>
				</div>
				<div class="col-md-4 no-space">
					<div class="pagation_select" style="display:none;">
						<select name="filter_enquire">
							<option value="service enquiry">Service Enquiry</option>
							<option value="product enquiry">Product Enquiry</option>
						</select>
					</div>
					
				</div>				
			</div>
			<div class="col-md-12 no-space product-items">
				<table>
				<tbody>
				<?php
					//get fake cart
					$cart_fake = AhluStore();
					$cart_fake->check();
					//read file
					$cart_fake = unserialize($cart_fake->read());

					if(isset($cart)){
						$c=0;
						foreach($cart as $id=> $item){
							$item = (object)$item;
							$icon = strtolower($cart_fake[$item->product_id]);
	
							$thumbnail = wp_get_attachment_url( get_post_thumbnail_id($item->product_id) );
							$icon  = get_template_directory_uri()."/images/{$icon}-enquiry.png";
							$display = get_post_meta($item->product_id, 'display', true );
						
echo <<<AHLU
						<tr data-id="{$item->product_id}">
							<input type="hidden" value="{$item->product_id}" name="item[][id_product]" />
							<input type="hidden" value="1" name="item[][quantity]" />
							<td class="icon"><input type="hidden" value="{$cart_fake[$item->product_id]} enquiry" name="item[][icon]" /><img src="{$icon}" /></td>
							<td class="img"><input type="hidden" value="{$thumbnail}" name="item[][img]" /><img src="{$thumbnail}" /></td>
							<td class="title"><input type="hidden" value="{$item->data->post->post_title}" name="item[][title]" />{$display}</td>
							<td class="note"><input type="text" name="item[][note]" class="txt" value="" placeholder="| Write your Note" style="height: 80px; width: 100%;border: none;background: transparent;" /></td>
							<td class="status"><a href="#"><span></span></a></td>
						</tr>
AHLU;

						}
					}
				?>
				</tbody>
				</table>
				<table>
					<tfoot>
						<tr>
							<td class="email" colspan="2"><input type="text" name="email" value="" class="required email text" title="Please Enter Email" placeholder="| Enter your Email" /></td>
							<td colspan="2" class="button"><input type="submit" class="submit" value="SEND Enquiries" /></td>
						</tr>
					</tfoot>
				</table>
			</div>
		</form>
		</div>
	</div>
</div>