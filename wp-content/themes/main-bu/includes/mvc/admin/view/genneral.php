<?php
$settings = null;

	if(isset($_POST["_wpnonce"])){
		$settings = get_option( 'ahlu_config_options' );
		
		
		
		if(isset($_POST["image_logo"]) && !empty($_POST["image_logo"])){
			$settings['logo'] =  $_POST["image_logo"];
		}
		
		if(!isset($settings['background'])){
			$settings['background'] = array();
		}
		
		if(isset($_POST["image_background"]) && !empty($_POST["image_background"])){
			$settings['background']["image"] = $_POST["image_background"];
		}
		
		if(isset($_POST["color_background"]) && !empty($_POST["color_background"])){
			$settings['background']["color"] = $_POST["color_background"];
		}
		
		update_option( 'ahlu_config_options',$settings );
		//print_r($_POST);
	}else{
		$settings = get_option( 'ahlu_config_options' );
		if ( !$settings ) {
			update_option( 'ahlu_config_options',array());
		}     
	}
	
	//print_r($settings);
?>
<script type="text/javascript">
window.uploadCallback = function(info,who){
	//info: {id:fldID,url:imgurl,title:title}
	
	
	if(who=="image_logo"){
		jQuery("#image_logo").val(JSON.stringify(info));
		
		jQuery(".image_preview").attr("src",info.url);
		jQuery(".link_image_preview").html(info.url);
	}else if(who=="image_background"){
		jQuery("#image_background").val(JSON.stringify(info));
		
		console.log(jQuery(".image_background_preview"));
		jQuery(".image_background_preview").attr("src",info.url);
	}
};
jQuery(document).ready(function($) {

	jQuery('.image_logo').click(function() {
          tb_show('', 'media-upload.php?post_id=0&who=image_logo&type=image&TB_iframe=true');
    });
	jQuery('.image_background').click(function() {
          tb_show('', 'media-upload.php?post_id=0&who=image_background&type=image&TB_iframe=true');
    });
     
});
</script>
<div class="wrap">
<div id="icon-edit-pages" class="icon32 icon32-posts-page"><br></div>
<h2>Web Settings</h2>
<form name="post" action="<?php echo $_SERVER["REQUEST_URI"]; //echo site_url((isset($_REQUEST["page"])?$_REQUEST["page"]:"").".html"); ?>" method="post" id="post" enctype="multipart/form-data">
<input type="hidden" id="_wpnonce" name="_wpnonce" value="<?php echo time(); ?>">
<input type="hidden" name="_wp_http_referer" value="<?php echo $_SERVER["REQUEST_URI"];?>">
<input type="hidden" id="user-id" name="user_ID" value="1">
<div id="poststuff">

<div id="post-body" class="metabox-holder columns-2">
<!-- /post-body-content -->


<div id="postbox-container-2" class="postbox-container">
<div id="normal-sortables" class="meta-box-sortables ui-sortable">
	<div id="additional" class="postbox">
<div class="handlediv" title="Click to toggle"><br></div><h3 class="hndle"><span>Logo</span></h3>
<div class="inside">
	<?php 
	$logo = null;
	
	if(isset($settings["logo"])){
		$logo = json_decode(stripslashes($settings["logo"]));
	}
	?>
	<input type="hidden" name="image_logo" id="image_logo" value="">
	<table class="form-table">
			<tbody>
				<tr>
					<th style="width:20%"><label for="image">image upload</label></th>
					<td>
						<div class="new-files">
							<div class="file-input"><input class="image_logo" type="button" value="logo"></div>
						</div>
					</td>
				</tr>
				<tr>
					<th style="width:20%"><label for="image">image preview</label></th>
					<td>
						<img class="image_preview" src="<?php echo  $logo && isset($logo->url) ? $logo->url : "" ?>" style="border:1px solid #ccc;padding:2px 5px;min-height:100%;max-width:100%;" />
					</td>
				</tr>
				<tr>
					<th style="width:20%"><label for="image">link URL</label></th>
					<td>
						<strong class="link_image_preview"><?php echo $logo && isset($logo->url) ? $logo->url : "" ?></strong>
					</td>
				</tr>
			</tbody>
	</table>

</div>
</div>
</div>

<div id="normal-sortables" class="meta-box-sortables ui-sortable">
	<div id="additional" class="postbox">
<div class="handlediv" title="Click to toggle"><br></div><h3 class="hndle"><span>Background</span></h3>
	<div class="inside">
	
	<?php 
		$background = null;

		if(isset($settings["background"])){
			$background = json_decode(stripslashes($settings["background"]["image"]));
			
		}
	?>
	<input type="hidden" name="image_background" id="image_background" value="">
		<table class="form-table">
			<tbody>
				<tr>
					<th style="width:20%"><label for="image">image background</label></th>
					<td>
						<div class="new-files">
							<div class="file-input"><div class="file-input"><input class="image_background" type="button" value="upload"></div></div>
						</div>
						<div class="new-files">
							<img  class="image_background_preview" src="<?php echo $background && isset($background->url)? $background->url : ""; ?>" style="border:1px solid #ccc;padding:2px 5px;min-height:100%;max-width:100%;" />
						</div>
					</td>
				</tr>
				<tr>
					<th style="width:20%"><label for="image">color background</label></th>
					<td>
						<div class="new-files">
							<div class="file-input"><input value="<?php echo isset($settings["background"]) && isset($settings["background"]["color"])? $settings["background"]["color"]: ""; ?>" type="text" name="color_background"></div>
						</div>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
</div>
<!--
<div id="normal-sortables" class="meta-box-sortables ui-sortable">
	<div id="additional" class="postbox">
<div class="handlediv" title="Click to toggle"><br></div><h3 class="hndle"><span>Background</span></h3>
	<div class="inside">
	<input type="hidden" id="rw_meta_box_nonce" name="rw_meta_box_nonce" value="47bb4fd044">
		<table class="form-table">
			<tbody>
				<tr>
					<th style="width:20%"><label for="image">URL link</label></th>
					<td>Upload File<br>
						<div class="new-files">
							<div class="file-input"><input type="file" name="logo"></div>
							<a class="rw-add-file" href="javascript:void(0)">Add more image</a>
						</div>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
</div>
-->
<div id="advanced-sortables" class="meta-box-sortables ui-sortable"></div>
</div>
<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Update"></p>
</div><!-- /post-body -->
<br class="clear">
</div><!-- /poststuff -->
</form>
</div>