<?php
    /**
    *   Class BaseDAO
    */
   abstract class BaseDAO{
       protected $path =null;
       
       protected $prefix_name = "ahlu";
       /**
       * Create file 
       *  
       * @param mixed $path
       * @param mixed $content
       */
       protected function createFile($path,$content="Coming Soon."){
           if(!file_exists($path)){
              if(!file_put_contents($path,$content)){
                 trigger_error("Can not create file '{$path}'.",E_USER_NOTICE); 
              }
           }
       }
       
       protected function getFile($file,$data=array())
       {
          if(!file_exists($file)){
              trigger_error("Can not open file '{$file}'.",E_USER_WARNING);      
          }
          extract($data);
           ob_start();
            include_once $file;
            $content = ob_get_clean();
            return $content;
       }
    }
?>