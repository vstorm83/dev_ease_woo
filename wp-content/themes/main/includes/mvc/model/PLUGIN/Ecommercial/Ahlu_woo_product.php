<?php
	class Ahlu_woo_product extends WC_Product {
		
		public function __construct($id){
			parent::__construct($id);
		}

		public function get_price_html_from_to( $from, $to ) {
			return '<span>' . ( ( is_numeric( $from ) ) ? wc_price( $from ) : $from ) . '</span> <div>' . ( ( is_numeric( $to ) ) ? wc_price( $to ) : $to ) . '</div>';
		}
		
		
	}
?>