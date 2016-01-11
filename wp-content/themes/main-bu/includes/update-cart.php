<?php
/** WP E-COMMERCE UPDATE HEADER CART ITEM TOTAL */
function theme_cart_update() {
    $cart_count = wpsc_cart_item_count();
        echo '
        jQuery(".ge-count-hidden").html(parseInt(jQuery(".ge-count-hidden").html())+'.$cart_count.');
        jQuery(".ge-count").html("Now "+jQuery(".ge-count-hidden").html()+ (parseInt(jQuery(".ge-count-hidden").html())>1? " items" : " item")+" in your cart.");
 ';
}

add_action('wpsc_alternate_cart_html', 'theme_cart_update');
/** END WP E-COMMERCE UPDATE HEADER CART ITEM TOTAL */
?>