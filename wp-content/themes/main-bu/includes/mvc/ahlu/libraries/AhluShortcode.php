<?php
class AhluShortcode{
	
	public function xml_entities($string) {
		return strtr(
			$string, 
			array(
				"<" => "&lt;",
				">" => "&gt;",
				'"' => "&quot;",
				"'" => "&apos;",
				"&" => "&amp;",
			)
		);
	}
	
	public function register($name,$f){
		$me = $this;
		add_shortcode($name, function ($args, $content ) use($me,$f){
			$xml = null;
			if(!empty($content)){
				$content =  $me->xml_entities($content);
				$content = str_replace(array("]","["),array(">","<"),$content);
		
				$str = "<?xml version=\"1.0\" standalone=\"yes\"?> \n <ahlu>".$content."</ahlu>";

				$xml = new SimpleXMLElement($str);
			}

			return $f($args,$xml);
		});
	}
	public function run($content,$bool=false){
		return do_shortcode($content,$bool);
	}
}
?>