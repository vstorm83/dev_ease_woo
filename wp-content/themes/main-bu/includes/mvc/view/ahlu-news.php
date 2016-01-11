<?php global $lang;
 if(isset($WP_enable) && $WP_enable) get_header(); 
 
 
 ?> 
<script src="<?php bloginfo('template_directory');?>/<?php bloginfo('template_directory');?>/templates/theme3156/js/jquery.mixitup.min.js"></script>

<div id="content">
          <div class="row-container">
            <div class="container-fluid">
              <div class="content-inner row-fluid">   
                        
                <div id="component" class="span12">
                  <main role="main">
                           
                       
                    <div id="system-message-container">
	</div>
     
                    <section class="page-blog page-blog__" itemscope itemtype="http://schema.org/Blog">
		<header class="page_header">
    	<h2>Latest news</h2>
	</header>
	<?php  
	$data = $category->listPostType(10,URI::getInstance()->page);
	if($data!=null){
	foreach($data->data as $k=>$item) {
		$url = site_url($item->post_name);
		$time_format = date("Y-m-d g:i",strtotime($item->post_date));
		$time = date("F d,Y",strtotime($item->post_date));
		
echo <<<AHLU
		<div class="items-row cols-1 row-0 row-fluid">
			<div class="span12">
			<article class="item column-1" id="item-11">
				<!--  title/author -->
<header class="item_header">
	<h6 class="item_title"><a href="{$url}">{$item->post_title}</a></h6></header>
<!-- info TOP -->
<div class="item_info">
	<dl class="item_info_dl">
		<dt class="article-info-term"></dt>
				<dd>
			<address class="item_createdby">
				Posted by Super User			</address>
		</dd>
				<dd>
			<time datetime="{$time_format}" class="item_published">
				on {$time}		</time>
		</dd>
				<dd>
			<div class="komento">
				

	<div class="kmt-readon">
		
				<span class="kmt-comment aligned-left">
			<a href="{$url}">3</a>
		</span>
		
			</div>

	

			</div>
		</dd>
			</dl>
</div>
<!-- Intro image -->
<figure class="item_img img-intro img-intro__none">
		<a href="{$url}">
			<img src="{$item->thumbnail}" alt=""/>
				</a>
	</figure>
<!-- Introtext -->
<div class="item_introtext">
	{$item->post_excerpt}
</div>
<!-- info BOTTOM -->
	<!-- Tags -->
		<!-- More -->
<a class="btn btn-info" href="{$url}">
	<span>Read more</span>
</a>
<div class="kmt-readon">
		
				<span class="kmt-comment aligned-left">
			<a href="{$url}">3</a>
		</span>
			</div>			
		</article>
		</div>		
	</div>
AHLU;
	}
		if(empty ($data->link)){
		echo $data->link;
		}
	
}
?>
		<footer class="pagination">
		<ul><li class="pagination-start"><span class="pagenav">Start</span></li><li class="pagination-prev"><span class="pagenav">Prev</span></li><li><span class="pagenav">1</span></li><li><a href="index.php/latest-news?start=3" class="pagenav">2</a></li><li><a href="index.php/latest-news?start=6" class="pagenav">3</a></li><li class="pagination-next"><a title="Next" href="index.php/latest-news?start=3" class="hasTooltip pagenav">Next</a></li><li class="pagination-end"><a title="End" href="index.php/latest-news?start=6" class="hasTooltip pagenav">End</a></li></ul>	</footer>
	</section>   
                                      </main>
                </div>        
                              </div>
            </div>
          </div>
        </div>
                                <div id="push"></div>
      