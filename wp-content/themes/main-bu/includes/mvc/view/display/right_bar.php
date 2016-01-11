<div class="block block-cart">

        <div class="block-title">

        <strong><span><?php echo $lang[qtrans_getLanguage()]["cart"][0];?></span></strong>

    <span class="toggle"></span></div>

    <div class="block-content mycart_short">

        <div class="summary">

                            <p class="amount"><?php echo $lang[qtrans_getLanguage()]["cart"]["info"]["total_in_cart"];?></p>

                        <p class="subtotal">

                            <span class="label"><?php echo $lang[qtrans_getLanguage()]["cart"]["info"]["sub_total"];?>:</span> <span class="price">{money}</span>                                                </p>

        </div>

		<div class="actions">

                <button type="button" title="Checkout" class="button" onclick="setLocation('<?php echo site_url("/product/checkout");?>')"><span><span><?php echo $lang[qtrans_getLanguage()]["btn_checkout"];?></span></span></button>

		</div>	

	</div>

</div>

<div class="block block-subscribe last_block">

    <div class="block-title">

        <strong><span><?php echo $lang[qtrans_getLanguage()]["user"]["info"]["newsletter"];?></span></strong>

    <span class="toggle"></span></div>

    <form action="#" method="post" id="newsletter-validate-detail">

        <div class="block-content">

            <div class="form-subscribe-header">

                <label for="newsletter"><?php echo $lang[qtrans_getLanguage()]["user"]["info"]["sign_up_newsletter"];?>:</label>

            </div>

            <div class="input-box">

               <input type="text" name="email" id="newsletter" title="Sign up for our newsletter" class="input-text required-entry validate-email">

            </div>

            <div class="actions">

                <button type="submit" title="Subscribe" class="button"><span><span><?php echo $lang[qtrans_getLanguage()]["btn_subscribe"];?></span></span></button>

            </div>

        </div>

    </form>

</div>
