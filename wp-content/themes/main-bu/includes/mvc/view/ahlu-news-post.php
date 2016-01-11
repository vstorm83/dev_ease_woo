<?php
$me = $post->getMe();
?>
</script>
<script src="<?php bloginfo('template_directory');?>/modules/mod_tm_ajax_contact_form/js/jquery.validate.min.js" type="text/javascript"></script>
<script src="<?php bloginfo('template_directory');?>/modules/mod_tm_ajax_contact_form/js/ajaxsendmail.js" type="text/javascript"></script>
<script src="<?php bloginfo('template_directory');?>/modules/mod_tm_ajax_contact_form/js/additional-methods.min.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_directory');?>/modules/mod_tm_ajax_contact_form/css/style.css">
<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_directory');?>/templates/theme3156/html/com_komento/css/style.css">
  <div id="content">
          <div class="row-container">
            <div class="container-fluid">
              <div class="content-inner row-fluid">   
                        
                <div id="component" class="span12">
                  <main role="main">
                           
                       
                    <div id="system-message-container">
	</div>
     
                    <article class="page-item page-item__blog page-item__" itemscope itemtype="http://schema.org/Article">
		<header class="page-header">
    	<h2>Latest news</h2>
	</header>
		<header class="item_header">
		<h6 class="item_title"><?php echo $me->post_title; ?></h6>	</header>
		<div class="item_info">
		<dl class="item_info_dl">
			<dt class="article-info-term"></dt>
						<dd>
				<address class="item_createdby">
					Posted by Super User				</address>
			</dd>
									<dd>
				<time datetime="<?php echo date("Y-m-d g:i",strtotime($me->post_date)); ?>" class="item_published">
					on <?php echo date("F d,Y",strtotime($me->post_date)); ?>					</time>
			</dd>
					</dl>
	</div>
		<div class="item_img img-full img-full__none item-image">
		<img src="<?php echo $me->thumbnail; ?>" alt=""/>
	</div>
	
	<div class="item_fulltext"><?php echo $me->post_content; ?></div>

<div id="section-kmt" class="theme-kuro">



	<script>
		jQuery(function($){
			jQuery.validator.setDefaults({
			  debug: true,
			  wrapper: "mark"
			});
			var form = $('#comment_form');
			form.validate();
			$( ".submitButton_2" ).bind('click', function() {
			  if(form.valid()){
			  	$( ".submitButton" ).click()
			  }
			});
		})
					
		
	</script>
	<style>
		#section-kmt .kmt-form-author ul li.firstItem{padding-left:0!important;}
	</style>
	<div id="kmt-form" class="commentForm kmt-form clearfix">
				<div class="formArea kmt-form-area">
			<h6 class="komento_title"><span class="item_title_part_0 item_title_part_odd item_title_part_first_half item_title_part_first">Leave</span> <span class="item_title_part_1 item_title_part_even item_title_part_first_half">a</span> <span class="item_title_part_2 item_title_part_odd item_title_part_second_half item_title_part_last">comment</span></h6>			<a name="commentform" id="commentform"></a>

			
			<form id="comment_form" novalidate>
				<ul class="formAlert kmt-form-alert hidden"></ul>

				<div class="kmt-form-author clearfix formAuthor">
						<ul class="reset-ul float-li">
			<li class="col kmt-form-name">
		<div><input id="register-name" class="input text" name="name" type="text" tabindex="41"  placeholder="Name:" required></div>
	</li>
	<li class="col kmt-form-email">
		<div><input id="register-email" class="input text" name="email" type="email" tabindex="42" placeholder="E-mail:" required></div>
	</li>
	<li class="col kmt-form-website">
		<div><input id="register-website" class="input text" name="website" type="text" tabindex="43" placeholder="Website:"></div>
	</li>
	</ul>

				</div>

				<div class="kmt-form-content">
					<div class="kmt-form-editor">
	<div>
		<textarea name="commentInputArea" id="commentInput" class="commentInput input textarea" cols="50" rows="10" tabindex="44" required placeholder="Comment:"></textarea>
			</div>
</div>
				</div>

				

				
				<div class="kmt-form-submit clearfix float-wrapper">
					
<button type="button" class="submitButton_2 btn btn-primary">Submit comment</button>
<button type="button" class="submitButton btn btn-primary">Submit comment</button>
				</div>

				<input type="hidden" name="parent" value="0" />
				<input type="hidden" name="task" value="commentSave" />
				<input type="hidden" name="pageItemId" class="pageItemId" value="141" />
			</form>
		</div>
	</div>

<div class="commentTools kmt-comment-tools-wrap">
<!-- Comment Title -->
<h6 class="komento_title"><span class="item_title_part_0 item_title_part_odd item_title_part_first_half item_title_part_first item_title_part_last">Comments</span></h6><ul class="kmt-toolbar reset-ul float-li clearfix">
		
	</ul>
</div>

			<div class="commentList kmt-list-wrap commentList-11">
			
<ul class="kmt-list reset-child">
	

<li id="kmt-1" class="kmt-item kmt-comment-item-public kmt-comment-item-usergroup-9 kmt-1 kmt-child-0 kmt-published" parentid="kmt-0" depth="0" childs="0" published="1" itemscope itemtype="http://schema.org/Comment">

<div class="kmt-wrap" style="margin-left: 0px !important">

	<!-- Avatar div.kmt-avatar -->
	<div class="kmt-avatar" itemprop="creator" itemscope itemtype="http://schema.org/Person"	>
			<a href="/joomla_55058/index.php/component/komento/profile" itemprop="url">
		<img src="http://www.gravatar.com/avatar/bd5498e3f98e085fad64eb2fb092483c?s=100&amp;d=mm" class="avatar" itemprop="image" />
			</a>
	</div>

	<!-- User rank div.kmt-rank -->
	
	<div class="kmt-content">

		<div class="kmt-head">
			

			<!-- Name span.kmt-author -->
			
<span class="author-kmt" itemprop="creator" itemscope itemtype="http://schema.org/Person">
	
	<span itemprop="name">Guest - James Bernard</span>

	</span>

			<!-- User rank div.kmt-rank -->
			
			<!-- In reply to span.kmt-inreplyto -->
			
			<span class="kmt-option float-wrapper">
				<!-- Report Comment span.kmt-report-wrap -->
				
				<!-- Permalink span.kmt-permalink-wrap -->
				
				<!-- AdminTools span.kmt-admin-wrap -->
							</span>

			
		</div>

		<div class="kmt-body">
			<i></i>

			<!-- Comment div.kmt-text -->
			
<div class="commentText kmt-text" itemprop="text"><p>In neque arcu, vulputate vitae dignissim id, placerat adipiscing lorem. Nulla consectetur adipiscing metus vel pulvinar. Aenean molestie mauris non diam tincidunt faucibus. Integer odio dui, iaculis in congue eleifend, faucibus nec diam. Maecenas ac est odio, at dignissim dolor. Quisque gravida, purus vitae varius sagittis, odio erat venenatis nibh, eu interdum diam est eu sem. Vivamus luctus lectus sit amet lectus egestas cursus.</p></div>

			

			<!-- Info span.kmt-info -->
			<span class="commentInfo kmt-info hidden"></span>
		</div>

		<div class="kmt-control">

			<div class="kmt-meta">
				<!-- Time span.kmt-time -->
				
<span class="kmt-time">
		<i class="fa fa-calendar"></i>
	<time itemprop="dateCreated" datetime="2014-05-03T09:58:29+00:00">
			<a class="kmt-timepermalink" href="http://livedemo00.template-help.com/joomla_55058/index.php/latest-news/15-vivamus-luctus-lectus-sit/11-donec-eu-elit-in-nisi-placerat#kmt-1" alt="Permalink"itemprop="url">
	about 1 year ago		</a>
		</time>
	
	<!-- Extended data for schema purposes -->
		<time class="hidden" itemprop="datePublished" datetime="2015-02-25T13:41:36+00:00"></time>
	</span>

				<!-- Location span.kmt-location -->
							</div>

		</div>
	</div>
</div>

</li>
</ul>
		</div>
	
</div><!--/section-kmt-->

<div class="addthis_sharing_toolbox"></div><script type="text/javascript">
    var addthis_config =
{
   pubid: "ra-5497f2254123130b"
}
    </script><script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js"></script>    
	<!-- Pagination -->
	</article>   
                                      </main>
                </div>        
                              </div>
            </div>
          </div>
        </div>
                                <div id="push"></div>
      