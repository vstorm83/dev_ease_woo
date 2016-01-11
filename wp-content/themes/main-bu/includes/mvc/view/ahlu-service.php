<?php global $lang; ?>
<?php if(isset($WP_enable) && $WP_enable) get_header(); ?> 
<script src="<?php bloginfo('template_directory');?>/templates/theme3156/js/jquery.mixitup.min.js"></script>

<div id="content">
          <div class="row-container">
            <div class="container-fluid">
              <div class="content-inner row-fluid">   
                        
                <div id="component" class="span12">
                  <main role="main">
                           
                       
                    <div id="system-message-container">
	</div>
     
                    <div class="note"></div>
<section class="page-gallery page-gallery__">
    <header class="page_header">
    <h2><span class="item_title_part_0 item_title_part_odd item_title_part_first_half item_title_part_first item_title_part_last">Services</span></h2>
  </header>
    <!-- Filter -->
    <div id="isotopeContainer" class="gallery items-row row-fluid cols-3 hover_false">
	<?php  
	$data = $category->listPostType(10,URI::getInstance()->page);
	if($data!=null){
	foreach($data->data as $k=>$item) {
		$url = site_url($item->post_name);
		$time = strtotime($item->post_date);
echo <<<AHLU
        <div class="gallery-item mix mix_all gallery-grid span12  portfolio" data-date="{$time}" data-name="{$item->post_title}" data-popularity="218">
        <!-- Image  -->
  <figure class="item_img img-intro img-intro__none">
  	<img src="{$item->thumbnail}" alt="{$item->post_title}">
      </figure>    
          <!--  title/author -->
  			<div class="item_header">
  				<h6 class="item_title"><a href="{$url}">{$item->post_title}</a></h6>  			</div>
  			  		  <!-- Introtext -->
  			<div class="item_introtext">{$item->post_excerpt}</div>
         
  		  <!-- info BOTTOM -->
  			      <div class="clearfix"></div>
    </div>

AHLU;
	}
	if(empty ($data->link)){
	echo $data->link;
	}
}
?>      
    <li class="gap span12"></li>
    <li class="gap span12"></li>
    <li class="gap span12"></li>
  </div>
  </section>

<script>
  jQuery(document).ready(function($) {
    $(window).load(function(){

      var $cols = 3;
      var $container = $('#isotopeContainer');

      $item = $('.gallery-item')

      $container.mixitup({
        targetSelector: '.gallery-item',
        filterSelector: '.filter',
        sortSelector: '.sort',
        buttonEvent: 'click',
        effects: [],
        listEffects: null,
        easing: 'smooth',
        layoutMode: 'grid',
        targetDisplayGrid: 'inline-block',
        targetDisplayList: 'block',
        gridClass: 'grid',
        listClass: 'list',
        transitionSpeed: 600,
        showOnLoad: 'all',
        sortOnLoad: false,
        multiFilter: false,
        filterLogic: 'or',
        resizeContainer: true,
        minHeight: 0,
        failClass: 'fail',
        perspectiveDistance: '3000',
        perspectiveOrigin: '50% 50%',
        animateGridList: true,
        onMixLoad: function(){
          $container.addClass('loaded');
        },
        onMixStart: function(config){
          if(config.layoutMode == 'list'){
            config.effects = ['fade','scale']
          }
          else{
            config.effects = []
            $container.find('.mix').removeClass('gallery-list').addClass('gallery-grid');
          }
        },
        onMixEnd: function(config){
          if(config.layoutMode == 'list'){
            $container.find('.mix').addClass('gallery-list').removeClass('gallery-grid');
          }
        }
      });

      $('.toGrid').click(function(){
        $('.layout-mode a').removeClass('active');
        $(this).addClass('active');
        $container.mixitup('toGrid')
      })

      $('.toList').click(function(){
        $('.layout-mode a').removeClass('active');
        $(this).addClass('active');
        $container.mixitup('toList');

      })

      $('ul#isotopeContainer a[data-fancybox="fancybox"]').fancybox({
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
            width: 80,
            source  : function(current) {
                return $(current.element).data('thumbnail');
            }
          },
          overlay : {
            css : {
              'background' : '#191919'
            }
          }
        }
      });
      $('#sort .sort').click(function(){
        $('#sort .sort').removeClass('selected').removeClass('active');
        $(this).addClass('selected');
        if($(this).attr('data-order')=='desc'){
          $(this).attr('data-order', 'asc')
        }
        else{
          $(this).attr('data-order', 'desc')
        }
      })
   });
}); 
</script>   
                                      </main>
                </div>        
                              </div>
            </div>
          </div>
        </div>