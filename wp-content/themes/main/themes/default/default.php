<!DOCTYPE html>
<html>
<head lang="en">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
	
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
    <link href="<?php bloginfo('template_directory');?>/css/bootstrap.css" rel="stylesheet" type="text/css" />
    <link href="<?php bloginfo('template_directory');?>/css/bootstrap-table.min.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="<?php bloginfo('template_directory');?>/js/jquery-1.8.3.js"></script>
	<script src="<?php bloginfo('template_directory');?>/js/jquery-2.0.3.min.js" type="text/javascript"></script>
	<script src="<?php bloginfo('template_directory');?>/js/jquery-migrate.min.js" type="text/javascript"></script>
    <script src="<?php bloginfo('template_directory');?>/js/jquery-ui-1.10.3.custom.min.js" type="text/javascript"></script>
	 <script src="http://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <script src="<?php bloginfo('template_directory');?>/js/jquery.metadata.js" type="text/javascript"></script>
    <script src="<?php bloginfo('template_directory');?>/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="<?php bloginfo('template_directory');?>/js/bootstrap-table.min.js" type="text/javascript"></script>
	
	<link rel="stylesheet" href="<?php bloginfo('template_directory');?>/css/back-to-top.css">
    <script src="<?php bloginfo('template_directory');?>/js/back-to-top.js" type="text/javascript"></script>
	
	<script type="text/javascript">
		var WC_url = "<?php echo site_url("cart"); ?>";
	</script>
	<script type="text/javascript" src="<?php bloginfo('template_directory');?>/js/jquery.validate.js"></script>
	<script type="text/javascript" src="<?php bloginfo('template_directory');?>/js/ahluForm.js"></script>
	<script type="text/javascript" src="<?php bloginfo('template_directory');?>/js/jquery.serialize-object.min.js"></script>
	<script type="text/javascript" src="<?php bloginfo('template_directory');?>/js/ahluScript.js"></script>

	<link rel="stylesheet" href="https://fortawesome.github.io/Font-Awesome/assets/font-awesome/css/font-awesome.css">

	
	<link rel="stylesheet" href="<?php bloginfo('template_directory');?>/ahlu.css">
	<script type="text/javascript">
			function paramReplace(name, string, value) {
					// Find the param with regex
					// Grab the first character in the returned string (should be ? or &)
					// Replace our href string with our new value, passing on the name and delimeter

					var re = new RegExp("[\?&]" + name + "=([^&#]*)");
					var matches = re.exec(string);
					var newString;

					if (matches === null) {
						// if there are no params, append the parameter
						newString = string + "?" + name + "=" + value;
					} else {
						var delimeter = matches[0].charAt(0);
						newString = string.replace(re, delimeter + name + "=" + value);
					}
					return newString;
				}

		jQuery(document).ready(function(){
			jQuery(".open-menu").click(function(e){
				var $this = jQuery(this);
				var group = jQuery(".group");
				
				if($this.hasClass("opened")){
					group.slideUp(300,function(){
						$this.removeClass("opened");
						$this.closest(".menu-horizontal-container").removeClass("menu-horizontal-open").removeClass("menu-horizontal-opened");
						jQuery(".menu-horizontal").removeClass("menu-horizontal-opened");
					});
				}else{
					group.slideDown();
					$this.addClass("opened");
					$this.closest(".menu-horizontal-container").addClass("menu-horizontal-open").addClass("menu-horizontal-opened");
					jQuery(".menu-horizontal").addClass("menu-horizontal-opened");
				}
			
			});
			
		});
	</script>
</head>	
<body class="<?php echo isset($cls)?$cls:""; ?>">
	<div class="wrapper">
		<div class="main_header">
			<div class="container-inner center-no-center">
				<?php
					echo $header;
				?>
			</div>
		</div>
		<div class="main_content">
			<div class="container-inner center-no-center">
				<?php
					echo $content;
				?>
			</div>
		</div>
		<div class="main_footer">
			<div class="wrapper container-inner center-no-center">
			<a href="#" id="back-to-top" title="Back to top"><img src="<?php bloginfo('template_directory');?>/images/icons_03.png" /><br />Back to top</a>

				<?php
					echo $footer;
				?>
			</div>
		</div>
		
		<?php wp_footer(); ?>
</body>
</html>