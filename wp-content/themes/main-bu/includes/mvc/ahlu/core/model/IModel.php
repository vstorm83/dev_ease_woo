<?php
    interface IModel {
        function loadInfo($id=null);
        function addInfo();
        
        //data from form or any to object Dao
        function cloneObject($obj);
        function clear();
        
        // view Object format
        function view($format);
    }
?>