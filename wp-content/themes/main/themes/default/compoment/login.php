<?php global $lang; ?><div class="main-container col1-layout">
				<div class="container">
					<div class="row">
						<div class="span12">
							<div class="main">
																<div class="col-main">
									<div class="padding-s">
												                                <div class="account-login">
    <div class="page-title">
        <h1>Login or Create an Account</h1>
    </div>
        <form action="" method="post" id="login-form">
        <div class="col2-set">
			<div class="wrapper">
				<div class="registered-users-wrapper">
					<div class="col-2 registered-users">
						<div class="content">
							<h2><?php echo $lang[qtrans_getLanguage()]["user"]["info"]["login"];?></h2>
							<p><?php echo $lang[qtrans_getLanguage()]["user"]["info"]["login_exist"];?></p>
							<ul class="form-list">
								<li>
									<label for="email" class="required"><em>*</em><?php echo $lang[qtrans_getLanguage()]["checkout"]["info"]["email"];?></label>
									<div class="input-box">
										<input type="text" name="email" value="" id="email" class="required email" title="<?php echo $lang[qtrans_getLanguage()]["checkout"]["info"]["email"];?>">
										
									</div>
								</li>
								<li>
									<label for="pass" class="required"><em>*</em><?php echo $lang[qtrans_getLanguage()]["user"]["info"]["password"];?></label>
									<div class="input-box">
										<input type="password" name="password" class="required" id="pass" title="<?php echo $lang[qtrans_getLanguage()]["user"]["info"]["password"];?>">
									</div>
								</li>
																							</ul>
							

<script type="text/javascript">
//<![CDATA[
    function toggleRememberMepopup(event){
        if($('remember-me-popup')){
            var viewportHeight = document.viewport.getHeight(),
                docHeight      = $$('body')[0].getHeight(),
                height         = docHeight > viewportHeight ? docHeight : viewportHeight;
            $('remember-me-popup').toggle();
            $('window-overlay').setStyle({ height: height + 'px' }).toggle();
        }
        Event.stop(event);
    }

    document.observe("dom:loaded", function() {
        new Insertion.Bottom($$('body')[0], $('window-overlay'));
        new Insertion.Bottom($$('body')[0], $('remember-me-popup'));

        $$('.remember-me-popup-close').each(function(element){
            Event.observe(element, 'click', toggleRememberMepopup);
        })
        $$('#remember-me-box a').each(function(element) {
            Event.observe(element, 'click', toggleRememberMepopup);
        });
    });
//]]>
</script>
							<p class="required">* <?php echo $lang[qtrans_getLanguage()]["required"];?></p>	           
							<div class="buttons-set">
								<a href="<?php echo site_url('user/forgotPassword'); ?>" class="f-left"><?php echo $lang[qtrans_getLanguage()]["user"]["info"]["forgot_password"];?>?</a>
								<button type="submit" class="button" title="Login" name="send" id="send2"><span><span><?php echo $lang[qtrans_getLanguage()]["user"]["info"]["login"];?></span></span></button>
							</div>
													</div>
					</div>
				</div>
				<div class="new-users-wrapper">
					<div class="col-1 new-users">
						<div class="content">
							<h2><?php echo $lang[qtrans_getLanguage()]["user"]["info"]["create_account"];?></h2>
							<p><?php echo $lang[qtrans_getLanguage()]["user"]["info"]["create_account_message"];?></p>	          
							<div class="buttons-set">
								<button type="button" title="<?php echo $lang[qtrans_getLanguage()]["user"]["info"]["create_account"];?>" class="button"><span><span><?php echo $lang[qtrans_getLanguage()]["user"]["info"]["create_account"];?></span></span></button>
							</div>
						</div>
					</div>
				</div>
			</div>
        </div>
    </form>
    <script type="text/javascript">
    //<![CDATA[
        var dataForm = new VarienForm('login-form', true);
    //]]>
    </script>
</div>
		                             </div>
								</div>
							</div>
						</div>
					</div>
			    </div>
			</div>