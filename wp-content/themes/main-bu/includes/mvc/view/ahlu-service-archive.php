<?php global $lang; ?>
<?php if(isset($WP_enable) && $WP_enable) get_header(); ?> 

<style type="text/css">
	header{
	margin-top:46px!important;
	}
</style>

<section id="content" class="top-content"> 
  <div class="main-tabs">
    <div class="inner">
        <div class="tabs">
              <div class="div-nav">
	              <ul class="nav">
	                   <li class="selected"><a href="#tab-1">Latest</a></li>
	                   <li><a href="#tab-2">Popular</a></li>
	                 
	              </ul>
                  <div class="all"></div>
                  <div class="clear"></div>
              </div>
               <div  id="tab-1" class="tab-content">
			  <?php
				  $myblog=new Blog_model();
					$blogdata=$myblog->listDynamic(4,1,array('latest'=>1));
					if ($blogdata!=null){
						if(is_array($blogdata->data) && count($blogdata->data)>0){
							foreach($blogdata->data as $i=>$item)
							{
							
						?>	
						  <div class="grid_3 <?php echo $i==0 ?"marg_left-0":($i==3 ? "marg_right-0":"") ?>">
								 <a href="#" class="inner-1">
									<img src="<?php echo $item->thumbnail; ?>" alt=""><strong></strong>
								 </a>
								 <span class="bold col dis-block">
								 <time pubdate datetime="<?php echo date("M d,Y",strtotime($item->post_date)); ?>"><?php echo date("M d,Y",strtotime($item->post_date)); ?>
								 </span>
								 <strong class="dis-block col-1 bot-4"><?php echo $item->post_title; ?> </strong>
								 <a href="<?php echo site_url($item->post_name) ?>" class="link-a"><?php echo substr($item->post_content,0,250); ?> <em class="link"></em></a>
						  </div> 
                  <?php }} }?>
                  <div class="clear"></div>
              </div>
              <div  id="tab-2" class="tab-content">
                <?php
					$blogdata=$myblog->listDynamic(4,1,array('popular'=>1));
					if(is_array($blogdata->data) && count($blogdata->data)>0){
					foreach($blogdata->data as $i=>$item)
					{
					
				?>	
                   <div class="grid_3 <?php echo $i==0 ?"marg_left-0":($i==3 ? "marg_right-0":"") ?>">
                         <a href="#" class="inner-1">
                            <img src="<?php echo $item->thumbnail; ?>" alt=""><strong></strong>
                         </a>
                         <span class="bold col dis-block">
						 <time pubdate datetime="<?php echo date("M d,Y",strtotime($item->post_date)); ?>"><?php echo date("M d,Y",strtotime($item->post_date)); ?>
						 </span>
                         <strong class="dis-block col-1 bot-4"><?php echo $item->post_title; ?> </strong>
                         <a href="<?php echo site_url($item->post_name) ?>" class="link-a"><?php echo substr($item->post_content,0,250); ?> <em class="link"></em></a>
                  </div> 
                  <?php } }?>
                  <div class="clear"></div>
              </div>
              <div  id="tab-3" class="tab-content">
                 
              </div>
           </div><!--the end of tabs-->
    </div>
  </div>
         
<div class="main-block"> 
	 <div class="container_12">
	    <div class="wrapper">
		    <div class="grid_9">
                <h2 class="bg-h2-1 bot">Blog</h2>
				<?php  
					$data = $category->listPostType(10,URI::getInstance()->page);
					if($data!=null){
					foreach($data->data as $k=>$item) {
					
					?>
					
					
                <div class="wrapper-extra bg-bot">
			
	                <a href="#" class="fleft dis-inblock link-1 right top"><strong></strong><img src="<?php echo $item->thumbnail; ?>" alt=""></a>
	                <div class="extra-wrap">
                        <span class="bold col"><time pubdate datetime="<?php echo date("M d,Y",strtotime($item->post_date)); ?>"><?php echo date("M d,Y",strtotime($item->post_date)); ?> </span>
                        <h3 class="top-1-1 bot-0-1"><?php echo  $item->post_title; ?></h3>
                        <p><?php echo substr($item->post_content,0,360); ?>
						</p>
                        
						<a href="<?php echo site_url($item->post_name) ?>"  class="button top-2">read more</a>
                    </div>
                </div>
          
            
			<?php
				}
				if(empty ($data->link)){
				echo $data->link;
				}
			}
			?>
				
            </div>
              <div class="grid_3">
					<!-----Facebook--->
			  <div class="wrapper-extra bg-bot" style="margin-bottom:10px">		
						<div id="fb-root"></div>
								<script>(function(d, s, id) {
								  var js, fjs = d.getElementsByTagName(s)[0];
								  if (d.getElementById(id)) return;
								  js = d.createElement(s); js.id = id;
								  js.src = "http://connect.facebook.net/en_EN/sdk.js#xfbml=1&version=v2.0";
								  fjs.parentNode.insertBefore(js, fjs);
								}(document, 'script', 'facebook-jssdk'));</script>
					
					<div class="fb-like-box" data-href="https://www.facebook.com/abtec38" data-width="220" data-colorscheme="light" data-show-faces="true" data-header="true" data-stream="true" data-show-border="true"></div>
				</div>
				<!-----End Facebook--->
			
			
<div>		
<section id="block-blog-recent" class="wrapper-extra bg-bot">

        <h3>Recent blogs</h3>

    

  <div class="content">

    <div class="item-list">

	<ul>

	<?php

	$data = $category->listDynamic(5);

	

	if($data!=null){

		foreach($data->data as $i=> $item){

			if($i==0){

				echo '<li class="first"><a href="'.site_url($item->post_name.".html").'">'.$item->post_title.'</a></li>';

			}else if($i==count($data->data)-1){

				echo ' <li><a href="'.site_url($item->post_name.".html").'">'.$item->post_title.'</a></li>';

			}else{

				echo ' <li class="last"><a href="'.site_url($item->post_name.".html").'">'.$item->post_title.'</a></li>';

			}	

		}

		

	}else{

		echo '<li class="first">No Posts.</li>';

	}

	?>

	</ul>

</div>

 </div><!-- /.content -->



</section>

<!-- /.block --><section id="block-comment-recent" class="block block-comment block-even">

<style type="text/css">

ul.archives{}

ul.archives li {position: relative;}

ul.archives li ul{position: relative;left: 25px;}

ul.archives li {color:#ed1a1c;font-family: Helvetica, Arial, san-serif; margin-bottom:0px !important;}

ul.archives li a{display: initial !important;}



</style>

        <h3>Archives</h3>

    

  <div class="content">

    <?php $archives =$category->archive(1000);

		if($archives->data!=null){

			$limit = 15;

			echo "<ul class='archives'>";

			foreach($archives->data as $year=>$months){

				$total = 0;

				$sb="";

				foreach($months as $month=>$list){

					$total += $list["c"];

						

					

					$sb.='<li><a href="'.site_url("archive-{$class}-{$year}-{$month}.html").'"><span>'.date("m", mktime(0, 0, 0, $month, 1, $year)).'</span></a><span>('.count($list).')</span> <ul class="archive_post">';

					/*foreach($list["items"] as $i=> $post){

						if($i>$limit)break;	

						$sb.='<li ><a href="'.site_url("{$post["url"]}.html").'">'.$post["title"].'</a></li>';	

					}

					*/

					$sb.='</ul>';

				}

				//

				echo "<li><a href='".site_url("archive-{$class}-{$year}.html")."'>{$year}</a><span>({$total})</span>

				 <ul class='archive_month'>

					{$sb}

				 </ul>

				</li>";

			}

			echo "</ul>";

		}



	?>

 </div>
</section>
</div>

			
			
			
			
			</div>
			
	</div>
				
            </div>
	        <div class="clear"></div> 
	     </div><!--the end of wrapper-->
	  </div>
</div>
</section>