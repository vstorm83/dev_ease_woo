<?php
    class Loader{
      private  $ob_level; 
      protected static $instance = null;
	  //recieve data from controller to view
      public $dateController = null;

      public function __construct(){
          $this->ob_level = ob_get_level();
		  $this->uri = Ahlu::Core("URI");
      }
      
       public function getInstance(){
          if(self::$instance==null){
              self::$instance = new self();
          }
         return  self::$instance; 
      }
      
       /**
       * Loader cache for any file excuting php tags
       *  
       * @param mixed $file
       * @param mixed $vars
       * @param mixed $return
       */
       public function part_template_string($file,$vars=array(),$return = FALSE){
           //remove from document
           $rootTheme = str_replace(array('\\','/'),DIRECTORY_SEPARATOR,get_template_directory());
                  
          $root = str_replace(array('\\','/'),DIRECTORY_SEPARATOR,$_SERVER["DOCUMENT_ROOT"]);
          $filetmp = str_replace(array('\\','/'),DIRECTORY_SEPARATOR,$file);
           
           
               if(strpos($filetmp,$root)!==FALSE){
                          $fileRemove = str_replace($rootTheme.DIRECTORY_SEPARATOR,"",$filetmp);
               }else{

                   //we remove path web root
                    $fileRemove = str_replace(get_template_directory_uri()."/","",$file);
                    //remove defind root
                    if(preg_match('#^(\/)+#',$fileRemove,$m)){
                       $fileRemove = substr($fileRemove,strlen($m[0]));
                       //get system
                    }
                    
               }
           //get filename in relative to system drive
           $fileRemove = str_replace(array('\\','/'),DIRECTORY_SEPARATOR,$fileRemove);
            unset($filetmp);

            $path = $rootTheme.DIRECTORY_SEPARATOR."$fileRemove";
             //echo $path;
             if(!file_exists($path)){
                   //  echo $file;
                     return;
             }
             //echo $fileRemove;
             
           //begin  
             $string = file_get_contents($path); 
             $string = str_replace('&lt;','<',$string);
             $string = str_replace('&gt;','>',$string);
             
			 $vars = is_array($vars) ? $vars : array();
             extract($vars);

            ob_start();

            if ((bool) @ini_get('short_open_tag') === FALSE )
            {
                echo eval('?>'.preg_replace("/;*\s*\?>/", "; ?>", str_replace('<?=', '<?php echo ', $string)));
            }
            else
            {
               echo eval('?>'.$string);
            }
            //end

            $path = $rootTheme.DIRECTORY_SEPARATOR."cache".DIRECTORY_SEPARATOR.$fileRemove;
            CreateDir($path);
            //repare file web for this string
            $file = get_template_directory_uri()."/cache/".str_replace(DIRECTORY_SEPARATOR,"/",$fileRemove)."?".rand(0,1000000);
            
             // Return the file data if requested
            if ($return === TRUE)
            {
                $buffer = ob_get_contents();
                @ob_end_clean();
                file_put_contents($path,$buffer);
            }else{
                $a = ob_get_contents();
                @ob_end_clean();
                file_put_contents($path,$a);
                
            }
            
         echo $file;
            
       }
      
      public function model($file){
          $file = ucfirst($file);  
          if(file_exists(TEMPLATEPATH."/includes/mvc/model/$file.php")){
            $name = strtolower($file);
            $cls = ucfirst($name);
            Ahlu_MVC::getInstance()->{$name}=new $cls(); 
         }else if(file_exists(TEMPLATEPATH."/includes/mvc/ahlu/core/model/$file.php")){
            $name = strtolower($file);
            $cls = ucfirst($name);
           Ahlu_MVC::getInstance()->{$name}=new $cls();  
         }else if(!file_exists($file)){
            trigger_error('Unable to load the requested file: '.$file,E_USER_WARNING);
            return;  
         }
         
      }
        
      public function view($file,$vars=array(),$return = FALSE){ 
		 
         if(file_exists(TEMPLATEPATH."/includes/mvc/view/$file.php")){ //from view folder
            $file = TEMPLATEPATH."/includes/mvc/view/$file.php";
         }else if(!file_exists($file)){ //from any view, note with ".php" extension
		    if(file_exists($file.".php")){
				$file = $file.".php";
			}
         }
		 $vars = is_array($vars) ? $vars : array();
		 //add more data
		 if(is_array($this->dateController)){
			$vars =  array_merge($this->dateController,$vars);
		 }
         //print_r($vars);
         extract($vars);

        ob_start();

            // If the PHP installation does not support short tags we'll
        // do a little string replacement, changing the short tags
        // to standard PHP echo statements.

        if ((bool) @ini_get('short_open_tag') === FALSE)
        {       
            echo eval('?>'.preg_replace("/;*\s*\?>/", "; ?>", str_replace('<?=', '<?php echo ', file_get_contents($file))));
        }
        else
        {
            include($file); // include() vs include_once() allows for multiple views with the same name
        }

        // Return the file data if requested
        if ($return === TRUE)
        {
            
            $buffer = ob_get_contents();
            @ob_end_clean();
            return $buffer;
        }
         
        /*
         * Flush the buffer... or buff the flusher?
         *
         * In order to permit views to be nested within
         * other views, we need to flush the content back out whenever
         * we are beyond the first level of output buffering so that
         * it can be seen and included properly by the first included
         * template and any subsequent ones. Oy!
         *
         */
        if (ob_get_level() > $this->ob_level+ 1)
        {  
            ob_end_flush();
        }
        else
        {
            $a= ob_get_contents();
            ob_end_clean();
            echo $a;  
        }  
       } 
    }
?>