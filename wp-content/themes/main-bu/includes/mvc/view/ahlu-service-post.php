 <?php
 
 $me = $post->getMe();
 ?>
 <div id="content">
          <div class="row-container">
            <div class="container-fluid">
              <div class="content-inner row-fluid">   
                        
                <div id="component" class="span12">
                  <main role="main">
                           
                       
                    <div id="system-message-container">
	</div>
     
                    <article class="page-item page-item__gallery page-item__">
		<header class="item_header">
		<h6 class="item_title"><?php echo $me->post_title; ?></h6>	</header>
		<!-- Article Image -->	
		<div class="page-gallery_img">
		<figure class="item_img img-full img-full__left">
			<a class="fancybox-thumb zoom articleGalleryZoom" data-fancybox-group="portfolio" data-fancybox-type="image" data-fancybox="fancybox" src="<?php bloginfo('template_directory');?>/images/portfolio/project1.jpg">
				<img src="<?php echo $me->thumbnail; ?>" alt="">
							</a> 
		</figure>
	</div>
		<div class="item_fulltext">
		
<div class="portfolio-thumbs">
<ul>
<?php
 $pics = gallery_post($me->ID,$me->post_type);
 if(is_array($pics)){
	foreach($pics as $pic){
		if(stripos($pic->guid,"youtube.com")===false){
echo <<<AHLU
	<li><a class="fancybox-thumb" data-fancybox="fancybox" data-fancybox-type="image" data-fancybox-group="portfolio" href="{$pic->guid}"><img src="{$pic->guid}" alt="" /></a></li>
AHLU;
		}else{
echo <<<AHLU
	<li><a class="portfolio-video fancybox.iframe" data-fancybox="fancybox" data-fancybox-group="portfolio" href="{$pic->post_content}"><img src="{$pic->guid}" alt="" /></a></li>
AHLU;
		}
	}
 }
?>
</ul>
</div>
<ul class="portfolio-meta-list">
<li><strong class="portfolio-meta-key">Client:</strong> <?php echo $me->client; ?></li>
<li><strong class="portfolio-meta-key">Date:</strong> <?php echo date("m/d/Y",$me->post_date); ?></li>
<li><strong class="portfolio-meta-key">Info:</strong> <?php echo $me->info; ?></li>

</ul>
<?php echo $me->post_content; ?>

</div>
	<div class="addthis_sharing_toolbox"></div><script type="text/javascript">
    var addthis_config =
{
   pubid: "ra-5497f2254123130b"
}
    </script><script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js"></script></article>

<script>
	jQuery(function($){
		$('a[data-fancybox="fancybox"]').fancybox({
	        padding: 0,
	        margin: 0,
	        loop: true,
	        openSpeed:500,
	        closeSpeed:500,
	        nextSpeed:500,
	        prevSpeed:500,
	        afterLoad : function (){
	                    $('.fancybox-inner').click(function(){
	            if(click == true){
	              $('body').toggleClass('fancybox-full');
	            }
	          })
	        },
	        beforeShow: function() {
	          $('body').addClass('fancybox-lock');
	        },
	        afterClose : function() {
	          $('body').removeClass('fancybox-lock');
	                  },
	        tpl : {
	          image    : '<div class="fancybox-image" style="background-image: url(\'{href}\');"/>',
	          iframe: '<span class="iframe-before"/><iframe id="fancybox-frame{rnd}" width="60%" height="60%" name="fancybox-frame{rnd}" class="fancybox-iframe" frameborder="0" vspace="0" hspace="0"' + ($.browser.msie ? ' allowtransparency="true"' : '') + '/>'
	        },
	        helpers: {
	          title : null,
	          thumbs: {
	            height: 50,
	            width: 80
	          },
	          overlay : {
	            css : {
	              'background' : '#191919'
	            }
	          }
	        }
	    });
	})
</script>   
                                      </main>
                </div>        
                              </div>
            </div>
          </div>
        </div>
                                <div id="push"></div>
      