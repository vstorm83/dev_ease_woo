
	
<div class="motopress-wrapper content-holder clearfix">
	<div class="container">
		<div class="row">
			<div class="span12" data-motopress-wrapper-file="search.php" data-motopress-wrapper-type="content">
				<div class="row">
					<div class="span12" data-motopress-type="static" data-motopress-static-file="static/static-title.php">
						<section class="title-section">
	<h1 class="title-header">
					Search for: "<?php echo $query; ?>"
		
			</h1>
				<!-- BEGIN BREADCRUMBS-->
			<ul class="breadcrumb breadcrumb__t"><li><a href="<?php echo site_url(); ?>">Home</a></li><li class="divider"></li><li class="active">Search for: "<?php echo $query; ?>"</li></ul>			<!-- END BREADCRUMBS -->
	</section><!-- .title-section -->					</div>
				</div>
				<div class="row">
					<div class="span9 right right" id="content" data-motopress-type="loop" data-motopress-loop-file="loop/loop-blog.php">
						<!-- displays the tag's description from the Wordpress admin -->

 <?php
	
	
	if($data==null)
	{
	?>
	<h4 style="font-weight: bold;">No results for: '<?php echo $query; ?>'</h4>
	<?php 
	}
	else{
	
	foreach($data->data as $k=>$item){
		

	?>						
						
<div class="post_wrapper"><article id="post-<?php echo $item->ID; ?>" class="post-<?php echo $item->ID; ?> post type-post status-publish format-standard has-post-thumbnail hentry category-uncategorized tag-elit tag-ipsum-dolor tag-lorem post__holder cat-1-id">
				<header class="post-header">
						<h2 class="post-title"><a href="<?php if($item->post_type=="blog"){echo $item->post_name;}else {echo 'product/'.$item->post_name;}?>" title="<?php echo $item->post_title; ?>"><?php echo $item->post_title; ?></a></h2>
		</header>
				<figure class="featured-thumbnail thumbnail large" ><a href="<?php if($item->post_type=="blog"){echo $item->post_name;}else {echo 'product/'.$item->post_name;}?>" title="<?php echo $item->post_title; ?>" ><img src="//" data-src="<?php echo $item->thumbnail; ?>" alt="<?php echo $item->post_title; ?>" ></a></figure>
				<!-- Post Content -->
		<div class="post_content">
								<div class="excerpt">
					<?php echo $item->post_excerpt; ?>		</div>
						<a href="<?php if($item->post_type=="blog"){echo $item->post_name;}else {echo 'product/'.$item->post_name;}?>" class="btn btn-primary">Read more</a>
			<div class="clear"></div>
		</div>

		
		<!-- Post Meta -->
<!--// Post Meta -->
</article></div>


	<?php
				}
				if(!empty ($data->link)){
				echo $data->link;
				}
			}
			?>


<!-- Posts navigation -->					</div>
					<div class="span3 sidebar" id="sidebar" data-motopress-type="static-sidebar"  data-motopress-sidebar-file="sidebar.php">
						
<div id="categories-4" class="widget">
<h3>Categories</h3>		
<ul>
<?php
			
	$categories = $category->menuTop();

	foreach($categories as $item){

		echo '<li class="cat-item cat-item-'.$item->term_id.'"><a href="'.site_url("{$item->slug}.html").'">'.$item->name.'</a> <span class="count">('.$item->count.')</span></li>';

	}
?>
</ul>


</div>
<div id="woocommerce_product_categories-2" class="widget">
<style>
#woocommerce_product_categories-2{list-style: none;} </style>
<h3>Product Categories</h3>

<?php  echo do_shortcode('[do_widget "WooCommerce Product Categories"]'); ?>
</div>

<div id="woocommerce_top_rated_products-2" class="widget">
<style>
#woocommerce_top_rated_products-2{list-style: none;} </style>
<h3>Top Rated Products</h3>
<?php  echo do_shortcode('[do_widget "WooCommerce Top Rated Products"]'); ?>
</div>			
	
	</div>
				</div>
			</div>
		</div>
	</div>
</div>
