<div class="motopress-wrapper content-holder clearfix">
<div class="container">
<div class="row">
<div class="span12" data-motopress-wrapper-file="page-faq.php" data-motopress-wrapper-type="content">
<div class="row">
<div class="span12" data-motopress-type="static" data-motopress-static-file="static/static-title.php">
<section class="title-section">
<h1 class="title-header">
FAQ </h1>
 
<ul class="breadcrumb breadcrumb__t"><li><a href="<?php echo site_url(); ?>">Home</a></li><li class="divider"></li><li class="active">FAQ</li></ul>  
</section>  </div>
</div>
<div class="row">
<div class="span12" id="content" data-motopress-type="loop" data-motopress-loop-file="loop/loop-faq.php">
<div id="post-12" class="post-12 page type-page status-publish hentry">
<div class="clear"></div>
</div> 
<dl class="faq-list">

<?php
$page = get_page_by_title( 'faq' );

echo $page->post_content;

 if(isset($WP_enable) && $WP_enable) get_footer(); 
 ?>

</dl>
</div>
</div>
</div>
</div>
</div>
</div>