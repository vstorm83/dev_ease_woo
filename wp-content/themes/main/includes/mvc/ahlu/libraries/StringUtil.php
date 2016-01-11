<?php
    class StringUtil{
        /**
         * Check if a string is serialized
         * @param string $string
         */
        public static function is_serialized($string) {
            return (@unserialize($string) !== false || $string == 'b:0;');
        }
        
        public static function parseQueryString($query,$array=false)
    {
        $q = new stdClass();
        $query = str_replace("#038;","&",$query);
        
        if (0 !== strlen($query)) {
            if ($query[0] == '?') {
                $query = substr($query, 1);
            }
            foreach (explode('&', $query) as $kvp) {
                $parts = explode('=', $kvp, 2);
                $key = rawurldecode($parts[0]);

                $paramIsPhpStyleArray = substr($key, -2) == '[]';
                if ($paramIsPhpStyleArray) {
                    $key = substr($key, 0, -2);
                }

                if (array_key_exists(1, $parts)) {
                    $value = rawurldecode(str_replace('+', '%20', $parts[1]));
                    if ($paramIsPhpStyleArray && !property_exists($q,$key)) {
                        $value = array($value);
                    }
                    $q->$key = $value;
                } 
            }
        }

        return $array ? (array)$q : $q;
    }
    
        public static function QueryStringFromArray($arr,$char="&")
        {
             $post_url = ''; 
            foreach ($_POST AS $key=>$value) 
                $post_url .= $key.'='.$value.$char; 
            return rtrim($post_url, $char); 
        }
        
        public static function BuildAttribute($arr)
        {
             $post_url = ''; 
            foreach ($arr AS $key=>$value) 
                $post_url .= $key.'="'.$value.'" '; 
            return trim($post_url, $char); 
        }
        public static function BuildStringWith($arr,$char=",")
        {
           $arr = (array)$arr; 
             $post_url = ''; 
            foreach ($arr AS $key=>$value) 
                $post_url .= $key.$char.$value; 
            return trim($post_url, $char); 
        }
        
        
        public static function generateSlug($phrase, $maxLength)  
        {  
            $result = strtolower($phrase);  
          
            $result = preg_replace("/[^a-z0-9\s-]/", "", $result);  
            $result = trim(preg_replace("/[\s-]+/", " ", $result));  
            $result = trim(substr($result, 0, $maxLength));  
            $result = preg_replace("/\s/", "-", $result);  
          
            return $result;  
        }
        
        /**
        * Convert type from database to field input on form
        * 
        * @param string $type
        * @return mixed
        */
        public static function ConvertToInput($type)  
        {   
            $type = strtolower($type);
            switch($type){
                case "bigint":
                case "int":
                case "float":
                case "decimal":
                case "long":
                case "double":
                    return "text";
                    
                case "datetime":
                case "time":
                case "longtime":
                case "shorttime":
                    return "text";
                
                case "text":
                case "longtext":
                     return "textarea";
                     
                case "tinyint":
                case "bit":
                  return "checkbox";
                
                default:
                 return "text";
            }
        }
    }
?>