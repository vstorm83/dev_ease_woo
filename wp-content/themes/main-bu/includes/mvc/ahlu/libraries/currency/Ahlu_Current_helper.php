<?php
class Ahlu_Current_helper{
	
	/**
     * get the target currency either by a previously saved cookie or by the users home country
     * @global object $wpdb
     * @return string ISO currency code
     */
	public static function getTargetCurrency(){
        global $wpdb;
        $target_currency= isset($_COOKIE['target_currency']) ? $_COOKIE['target_currency'] : null;
        if( !$target_currency){
            include("currency/geoip/geoip.inc");

            $gi = geoip_open("currency/geoip/GeoIP.dat",GEOIP_STANDARD);

            $target_currency = geoip_country_code_by_addr($gi, $_SERVER["REMOTE_ADDR"]);
            if(!$target_currency){
                $target_currency="US";
			}
            geoip_close($gi);
            setcookie('target_currency', $target_currency , time()+3600*24*100);
        }
		//if(isset($_COOKIE['target_currency'])) {
		  //unset($_COOKIE['target_currency']);
		  //setcookie('target_currency', '', time() - 3600); // empty value and old timestamp
		//}
        return $target_currency;
    }
	
	public static function parseCurrency($sign){
		$s = strtolower($sign);
		switch($s){
			case "vnd":
			return (int) $s;
			
			case "usd":
			return (float) $s;
		}
		return $sign;
	}
	public static function formatCurrency($num,$sign,$full= true){
		$s = strtolower($sign);
		switch($s){
			case "vnd":
			$symbol_thousand =".";
			$decimal_place = 0;
			$price = number_format($num, $decimal_place, '', $symbol_thousand);
			return sprintf("%s {$sign}",$price);
			
			case "usd":
			//need to round
			if(!$full){
				return sprintf("$%s",number_format($num, 2,'.', ','));
			}
			$num = str_replace("$","",$num);
			return sprintf("%s",number_format($num, 5,'.', ','));
		}
		return $sign;
	}
}

?>