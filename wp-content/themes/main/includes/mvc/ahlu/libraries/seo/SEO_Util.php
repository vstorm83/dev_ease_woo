<?php
/******************************************************************
Projectname:   on page optimization class (SEO)
Version:       1.0
Author:        Radovan Janjic <rade@it-radionica.com>
Last modified: 16 May 2011
Copyright (C): 2011 IT-radionica.com, All Rights Reserved

* GNU General Public License (Version 2, June 1991)
*
* This program is free software; you can redistribute
* it and/or modify it under the terms of the GNU
* General Public License as published by the Free
* Software Foundation; either version 2 of the License,
* or (at your option) any later version.
*
* This program is distributed in the hope that it will
* be useful, but WITHOUT ANY WARRANTY; without even the
* implied warranty of MERCHANTABILITY or FITNESS FOR A
* PARTICULAR PURPOSE. See the GNU General Public License
* for more details.

Description:

On-Page SEO

This class can generates dynamically 
- Title 
- Meta description
- canonical tag
- Link (href ank title)
- Img (src, alt, title)
for your web pages based on the contents of your data.

******************************************************************/

class SEO_Util {
	
	// domaine name
	public $root = "www.example.com";
	
	// vars wich will be used in text
	public $vars = array();
	
	// page title max characters
	public $maxTitle = 64;
	
	// meta description max characters
	public $maxDescription = 156;
	
	// max word's used in alt of an img tag
	public $maxWordAlt = 5;
	
	// max word's used in title of an img or a tag
	public $maxWordTitle = 10;
	
    public function __construct(){}	
	
    public function load( $root ){
		$this->root = $this->addhttp($root);
	}
	
	public function addVars($vars = array()){
		$this->vars = $vars;
	}
	
	public function dumpVars(){
		$this->vars =  array();
	}
	
	public function getPageTitle($str){
		return $this->strTruncate($this->varReplace($str), $this->maxTitle);	
	}
	
	public function getDescription($str){
		return $this->strTruncate($this->varReplace($str), $this->maxDescription);	
	}
	
	public function getAlt($str){
		$str = $this->varReplace($str);
		return implode(' ', array_slice(str_word_count($str,1), 0, $this->maxWordAlt));	
	}	
	
	public function getTitle($str){
		$str = $this->varReplace($str);
		return implode(' ', array_slice(str_word_count($str,1), 0, $this->maxWordTitle));	
	}
		
	public function getHref($str){
		if (preg_match_all ("/\[+[a-zA-Z0-9_]+\]/", $str, $regs)) {
			// replace	
			$var_search = $var_replace = array();																			
			while(list($key, $val) = each($regs[0])) {
				$var_search[] = $val;
				$var_replace[] = $this->cleanUri($this->vars[str_replace(array('[',']'), '', $val)]);
			}
			return $this->root . str_replace($var_search, $var_replace, $str);
		}
	}
	
	public function getCanonical(){
		return $this->root . parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH);
	}
	
	function addhttp($url) {
		if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
			$url = "http://" . $url;
		}
		return $url;
	}
	
	function strTruncate($string, $length = 80, $etc = '...', $break_words = false, $middle = false){
		if ($length == 0) return '';
	
		if (strlen($string) > $length){
			$length -= min($length, strlen($etc));
			if (!$break_words && !$middle) {
				$string = preg_replace('/\s+?(\S+)?$/', '', substr($string, 0, $length+1));
			}
			if(!$middle) {
				return substr($string, 0, $length) . $etc;
			} else {
				return substr($string, 0, $length/2) . $etc . substr($string, -$length/2);
			}
		} else {
			return $string;
		}
	}
	
	function varReplace($str){
		if (preg_match_all("/\[+[a-zA-Z0-9_]+\]/", $str, $regs)) {
			// replace	
			$var_search = $var_replace = array();																			
			while(list($key, $val) = each($regs[0])) {
				$var_search[] = $val;
				$var_replace[] = $this->stripWordHtml($this->vars[str_replace(array('[',']'), '', $val)]);
			}
			return str_replace($var_search, $var_replace, $str);
		}else{ return $str; }

	}
	
	function cleanUri($str, $delimiter='-', $replace=array(), $charset='UTF-8') {
		$str = trim($str);
		$lat_search = array('š','đ','č','ć','ž','Š','Đ','Č','Ć','Ž');
		$lat_replace = array('s','dj','c','c','z','s','dj','c','c','z');
		$str = str_replace($lat_search, $lat_replace, $str);
		$str = iconv($charset, 'UTF-8', $str);
		
		if ( !empty($replace) ) $str = str_replace((array)$replace, ' ', $str);
		
		$clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
		$clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
		$clean = strtolower(trim($clean, '-'));
		$clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);
			
		return $clean;
	}
		
		function stripWordHtml($text){
			return htmlspecialchars(strip_tags($text));
		}
		
		public function __destruct(){ }
		
		public function __set($var, $val){ $this->$var = $val; }
		
		public function __get($var){ if (isset($this->$var)) return $this->$var; else return false; }
		
		public function __toString(){ return (string) (var_export($this, true)); }
	
    
    
    /**

     * Extract Keywords

     * Returns a lowercase string with keywords ordered by occurance in content seperated with comma's

     * @var $string

     * @var $min_word_char

     * @var $keyword_amount

     * @var $exclude_words

     */

    public function getKeywords($min_word_char = 3, $keyword_amount = 15,  $exclude_words = ''){
        return self::calculateKeywords(empty($this->vars["keyword"])?"":$this->vars["keyword"], $min_word_char, $keyword_amount,  $exclude_words);
    }

    

    private function calculateKeywords($string = '', $min_word_char = 3, $keyword_amount = 15,  $exclude_words = '' ) {

        

        $exclude_words = explode(", ", $exclude_words);

        //add space before br tags so words aren't concatenated when tags are stripped

        $string = preg_replace('/\<br(\s*)?\/?\>/i', " <br />", $string); 

        // get rid off the htmltags

        $string = html_entity_decode(strip_tags($string), ENT_NOQUOTES , 'UTF-8');

        

        // count all words with str_word_count_utf8

        $initial_words_array  = self::str_word_count_utf8($string, 1);

        $total_words = sizeof($initial_words_array);

        

        $new_string = $string;

        

        //convert to lower case

        $new_string = mb_convert_case($new_string, MB_CASE_LOWER, "UTF-8");

        

        // strip excluded words

        foreach($exclude_words as $filter_word)    {

            $new_string = preg_replace("/\b".$filter_word."\b/i", "", $new_string); 

        }

        

        // calculate words again without the excluded words using str_word_count_utf8

        $words_array = self::str_word_count_utf8($new_string, 1);

        $words_array = array_filter($words_array, create_function('$var', 'return (strlen($var) >= '.$min_word_char.');'));

        

        $popularity = array();

        $unique_words_array = array_unique($words_array);

        

        // create density array

        foreach($unique_words_array as  $key => $word)    {

            preg_match_all('/\b'.$word.'\b/i', $string, $out);

            $count = count($out[0]);    

            $popularity[$key]['count'] = $count;

            $popularity[$key]['word'] = $word;

            

        }

        

        usort($popularity, array('SEO','cmp'));

        

        // sort array form higher to lower

        krsort($popularity);

        

        // create keyword array with only words

        $keywords = array();

        foreach($popularity as $value){

            $keywords[] = $value['word']; 

        }

                    

        // glue keywords to string seperated by comma, maximum 15 words

        $keystring =  implode(', ', array_slice($keywords, 0, $keyword_amount));

        

        // return the keywords

        return $keystring;

    }

    

    /**

     * Sort array by count value

     */

    private static function cmp($a, $b) {

        return ($a['count'] > $b['count']) ? +1 : -1;

    }



    /** Word count for UTF8

    /* Found in: http://www.php.net/%20str_word_count#85592

    /* The original mask contained the apostrophe, not good for Meta keywords:

    /* "/\p{L}[\p{L}\p{Mn}\p{Pd}'\x{2019}..."

    */

    private static function str_word_count_utf8($string, $format = 0) {

        switch ($format) {

        case 1:

            preg_match_all("/\p{L}[\p{L}\p{Mn}\p{Pd}]*/u", $string, $matches);

            return $matches[0];

        case 2:

            preg_match_all("/\p{L}[\p{L}\p{Mn}\p{Pd}]*/u", $string, $matches, PREG_OFFSET_CAPTURE);

            $result = array();

            foreach ($matches[0] as $match) {

                $result[$match[1]] = $match[0];

            }
            return $result;

        }
        return preg_match_all("/\p{L}[\p{L}\p{Mn}\p{Pd}]*/u", $string, $matches);

    }        

}
