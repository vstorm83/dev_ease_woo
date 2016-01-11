<?php
    class Ahlu{
        //store some classes 
        protected static $class = array();
        //        
        protected static $users= array();  
        protected static $views= array();  
        
        protected static $db = array();
        //store core class
        protected static $class_core = array();
        protected static $is_autoload = false; 
        
        public static function Core(){
            $a = func_get_args();
			$cls = array_shift($a);
            $name = strtolower($cls);
			
            if(!isset(self::$class_core[$name])){
                if(class_exists($cls)){
                  self::$class_core[$name] = new $cls();     
                }else{
                    trigger_error("Cannot not locate the class {$cls}. please try again.");
                }
                
            }
            return self::$class_core[$name];
        }
        
         public static function getObject($cls){  

              if(isset(self::$class[$cls])){
                  return  self::$class[$cls];
              }
			  return Ahlu::Call($cls);
         }
        
         public static function Library(){
            $a = func_get_args();
			$cls = array_shift($a);
            $name = strtolower($cls);
            if(!isset(self::$class_core[$name])){
                if(class_exists($cls)){
                  self::$class_core[$name] = new $cls();  			  
                }else{
                    trigger_error("Cannot not locate the class {$cls}. please try again.");
                }
                
            }	
            if(method_exists(self::$class_core[$name],"load")){
                call_user_func_array(array(&self::$class_core[$name], "load"),count($a)==0?array(null):$a);
            }
            
            return self::$class_core[$name];
        }
        
        public static function isLoaded(){
             return self::$class_core;
        }
        
        public static function setDB($db){
             if(self::$db==null){
                 self::$db = $db;
             }
             return self::$db;
        }
         public static function DB(){
             return self::$db;
        }
        
        public static function Call(){
			$a = func_get_args();
			$cls = array_shift($a);
			//print_r($a);
			$name = strtolower($cls);
            if(!isset(self::$class[$name])){
                if(class_exists($cls)){
                  self::$class[$name] = new $cls();     
                }else{
                    trigger_error("Cannot not locate the class {$cls}. please try again.");
                }
                
            }
			
			
			if(method_exists(self::$class[$name],"load")){
					//print_r($a);
					call_user_func_array(array(self::$class[$name], "load"),count($a)==0?array(null):$a);
			} 
            return self::$class[$name];
        }
        
        /**
        * Autoload all class from specific path
        * 
        * @param mixed $path
        */
        public static function Autoload($path=null){
            if(!class_exists("autoloader")){
               require_once (TEMPLATEPATH."/includes/mvc/ahlu/libraries/autoloader.php"); 
               self::$is_autoload = true;
            }

            $ahlu = null; 
            if($path==null){
               $path = TEMPLATEPATH."/includes/mvc"; 
            }
            
            $ahlu = new autoloader();
            $ahlu->load($path);
        }
        
        //model
        public static function User(){ //$name,$args=null
            $args = func_get_args();
            $name = ucfirst(array_shift($args))."_model";
            //print_r($args[0]);
            if(!isset(self::$views[$name])){
                
                if(class_exists($name)){
                  self::$views[$name] = new $name((count($args)>0? array_shift($args) : null));   
                  return  self::$views[$name];   
                }else{
                    trigger_error("Cannot not locate the class {$cls}. please try again.");
                }
                
            }
            
            return self::$users[$name]->load((count($args)>0? array_shift($args) : null));
        }
        //view
        public static function view(){ //$name,$args=null
            $args = func_get_args();
            $name = ucfirst(array_shift($args));
            if(!isset(self::$users[$name])){
                
                if(class_exists($name)){
                  self::$users[$name] = new $name($args); 
                  return  self::$users[$name];   
                }else{
                    trigger_error("Cannot not locate the class {$cls}. please try again.");
                }
                
            }
            
            return self::$users[$name]->load((count($args)>0? array_shift($args) : null));
        }
			/**
	 * @param string $func - method name
	 * @param object $obj - object to call method on
	 * @param boolean|array $params - array of parameters
	 */	
	public function call_object_method_array($func, $obj, $params=false){
		if (!method_exists($obj,$func)){        
			// object doesn't have function, return null
			return (null);
		}
		// no params so just return function
		if (!$params){
			return ($obj->$func());
		}        
		// build eval string to execute function with parameters        
		$pstr=array();
		foreach ($params as  $key =>$param){
			$pstr[]='$params["'.$key.'"]';
		}
		$evalstr='$retval=$obj->'.$func.'('.implode(",", $pstr).');';
		$evalok=eval($evalstr);
		print_r($retval);
		// if eval worked ok, return value returned by function
		return $retval;   
	}
    }
?>