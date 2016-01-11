<?php
class AutoloadException extends Exception { }

    # nullify any existing autoloads
    spl_autoload_register(null, false);
    /**
     * autoload classes 
     *
     *@var $directory_name
     *
     *@param string $directory_name
     *
     *@func __construct
     *@func autoload
     *
     *@return string
    */
    class autoloader
    {
        private $directory_name;
        public $folder = array();
        
        public function __construct($directory_name=null)
        {
            if($directory_name!=null){
                 $this->directory_name = $directory_name;
                $this->load();
                
            }
           
            
        }
    
      public function load($directory_name=null){
                
                if($directory_name!=null)
                    $this->directory_name = $directory_name; 
                    
               $this->listFolder($this->directory_name);
               // print_r($this->folder);
                spl_autoload_extensions('.php');
                spl_autoload_register(array($this, 'autoload'));
            }
            
    private function loaderExample($className) {
            echo 'Trying to load ', $className, ' via ', __METHOD__, "()\n";
            include $className . '.php';
        }
        
        
        public function autoload($class_name) 
        { 
            $found =false;
            $file_name = $class_name.'.php';

           // $file = $this->directory_name.'/'.$file_name;
            
           
            if(count($this->folder)>0){
                foreach($this->folder as $k=>$v){
                   $file = $v.'/'.$file_name;
                   
                   if (file_exists($file))
                    {
                       $found =true;
                      // echo $file;  
                       include ($file);
                    }
                }

                if(!$found){
                /*
                // no, create a new one!
                    eval("class $class_name {
                        function __construct() {
                            throw new AutoloadException('Class $class_name not found');
                        }
            
                        static function __callstatic(\$m, \$args) {
                            throw new AutoloadException('Class $class_name not found');
                        }
                    }");
            */     
                    return false;
                }
        }
        
        }
        
        public function abc(){
            return $this->folder;
        }
        /////
        private function listFolder($path,$file1=true){
        $directory = "{$path}/";
        
        //get all files in specified directory
        $files = glob($directory . "*");
        $l=""; 
        //print each file name
        //print_r($files);
        if(is_array($files) && count($files)>0){
            foreach($files as $file)
            {
             //check to see if the file is a folder/directory
             
             if(is_dir($file))
             {
             
                 $l = "{$file}";
                // echo "$l<br>";
                 $this->folder[] = $l.$this->listFolder($l);
             }
            }
    }
    //   
    }
    }

?>