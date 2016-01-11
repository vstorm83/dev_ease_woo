<?php
   if(!function_exists("Ahlu_insert_js")){
         function Ahlu_insert_js($file){
          $path= Ahlu::Library("URL")->LocationFile($file.".js");
          $embed = &Ahlu::Call("LoadEmbed");
          $embed->assignJS($path);
        }
   }

?>