<?php global $lang; ?>
<?php if(isset($WP_enable) && $WP_enable) get_header(); ?> 

<div id="content">
          <div class="row-container">
            <div class="container-fluid">
              <div class="content-inner row-fluid">   
                        
                <div id="component" class="span9">
                  <main role="main">
                           
                       
                    <div id="system-message-container">
	</div>
     
                    <section class="page-category page-category__testimonials">
		<header class="page_header">
    <h2>Testimonials</h2>
	</header>
	<?php  
	$data = $category->listPostType(10,URI::getInstance()->page);
	if($data!=null){
	foreach($data->data as $k=>$item) {
		$time = strtotime($item->post_date);
echo <<<AHLU
			<div class="items-row cols-1 row-0 row-fluid">
			<div class="span12">
			<article class="item column-1" id="item_50">
				<!--  title/author -->
<header class="item_header">
	<h6 class="item_title"><a href="#">{$item->post_title}</a></h6></header>
<!-- Introtext -->
<div class="item_introtext">
	<blockquote>
{$item->post_content}
</blockquote></div>
<!-- info BOTTOM -->
		<div class="content-links">
			<ul>
								<li class="content-links-a">
				<a href="{$url}" target="_blank"  rel="nofollow">{$item->post_excerpt}</a>				</li>
							</ul>
		</div>
				</article><!-- end item -->
					</div><!-- end spann -->
					
	</div><!-- end row -->
	
AHLU;
}
	if(empty ($data->link)){
	echo $data->link;
	}
}
?>
	</section>   
                                      </main>
                </div>        
                                <!-- Right sidebar -->
                <div id="aside-right" class="span3">
                  <aside role="complementary">
                    <div class="moduletable aside"><div class="module_container"><header><h3 class="moduleTitle "><span class="item_title_part_0 item_title_part_odd item_title_part_first_half item_title_part_first item_title_part_last">Search</span></h3></header><div role="search" class="mod-search mod-search__aside">
  <form action="testimonials" method="post" class="navbar-form">
  	<label for="searchword-102" class="element-invisible">Search ...</label> <input id="searchword-102" name="searchword" maxlength="200"  class="inputbox mod-search_searchword" type="text" size="20" placeholder="Search ..." required><br /> <button class="button btn btn-primary" onclick="this.form.searchword.focus();">Search</button>  	<input type="hidden" name="task" value="search">
  	<input type="hidden" name="option" value="com_search">
  	<input type="hidden" name="Itemid" value="137">
  </form>
</div></div></div><div class="moduletable "><div class="module_container"><header><h3 class="moduleTitle "><span class="item_title_part_0 item_title_part_odd item_title_part_first_half item_title_part_first">Login</span> <span class="item_title_part_1 item_title_part_even item_title_part_second_half item_title_part_last">form</span></h3></header>

<div class="lr_social_login_basic_150">
		<div class="lr_providers">
		<div class="lr_icons_box"><div>
			<a class="lr_providericons lr_facebook" href="javascript:void(0);" onclick="javascript:window.open('http://www.facebook.com/dialog/oauth?client_id=296188807244109&amp;redirect_uri=http://livedemo00.template-help.com/joomla_55058/?provider=facebook&amp;display=popup&amp;scope=email,user_photos,user_about_me,user_hometown,user_photos','Facebook','toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width=400px,height=400px');" rel="nofollow" title="Login with Facebook">Login with Facebook</a>
			</div><div>
			<a class="lr_providericons lr_google" href="javascript:void(0);" onclick="javascript:window.open('https://accounts.google.com/o/oauth2/auth?response_type=code&amp;redirect_uri=http://livedemo00.template-help.com/joomla_55058/?provider=google&amp;client_id=4ea43331a8b16c6ddb33685fc03635a8&amp;scope=https%3A%2F%2Fwww.googleapis.com%2Fauth%2Fuserinfo.profile+https%3A%2F%2Fwww.googleapis.com%2Fauth%2Fuserinfo.email','Google','toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width=400px,height=400px');" rel="nofollow" title="Login with Google">Login with Google</a>
			</div></div>
		</div>
		</div></div></div><div class="moduletable aside"><div class="module_container"><div class="mod-login mod-login__aside">
	<form action="/joomla_55058/index.php/about-us/team/testimonials" method="post" id="login-form-101" class="form-inline">
				<div class="mod-login_userdata">
		<div id="form-login-username-101" class="control-group">
			<div class="controls">
								<input id="mod-login_username-101" class="inputbox mod-login_username" type="text" name="username" tabindex="1" size="18" placeholder="User name" required>
							</div>
		</div>
		<div id="form-login-password-101" class="control-group">
			<div class="controls">
								<input id="mod-login_passwd-101" class="inputbox mod-login_passwd" type="password" name="password" tabindex="2" size="18" placeholder="Password"  required>
							</div>
		</div>		
								<label for="mod-login_remember-101" class="checkbox">
				<input id="mod-login_remember-101" class="mod-login_remember" type="checkbox" name="remember" value="yes">
				Remember me			</label> 
						<div class="mod-login_submit">
				<button type="submit" tabindex="3" name="Submit" class="btn btn-primary">Log in</button>
			</div>
								
			<ul class="unstyled">
				<li><a href="/joomla_55058/index.php/username-reminder-request" class="" title="Forgot your username?">Forgot your username?</a></li>
				<li><a href="/joomla_55058/index.php/password-reset" class="" title="Forgot your password?">Forgot your password?</a></li>
								<li><a href="/joomla_55058/index.php/user-registration">Create an account</a></li>
							</ul>
			<input type="hidden" name="option" value="com_users">
			<input type="hidden" name="task" value="user.login">
			<input type="hidden" name="return" value="aW5kZXgucGhwP0l0ZW1pZD0xMzcmb3B0aW9uPWNvbV9jb250ZW50">
			<input type="hidden" name="bf5dc0cea741aa9d8f7efc84e73c6229" value="1" />		</div>
			</form>
</div></div></div>
                  </aside>
                </div>
                              </div>
            </div>
          </div>
        </div>
                                <div id="push"></div>