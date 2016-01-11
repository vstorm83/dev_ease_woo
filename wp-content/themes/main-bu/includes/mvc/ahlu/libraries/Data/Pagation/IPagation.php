<?php
/**
* Interface  IPagation
*/
interface IPagation{
    
    function PageLinks($template=null,$isFile =false);
    function PageData($default=true,$template=false);
}
?>