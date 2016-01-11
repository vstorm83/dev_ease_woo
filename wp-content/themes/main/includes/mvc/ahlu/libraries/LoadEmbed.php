<?php
    class LoadEmbed{
        private $_js =array();
        private $_css =array();
        private $_imgs =array();
        private $_embed =array();
        
        public function __construct(){
            
        }
        
        public function assignJS($v,$extra=null){
               $s ='<script type="text/javascript" src="'.$v.'"></script>'."\n" ;
               if(is_array($extra)){
                  $a = StringUtil::QueryStringFromArray($extra," "); 
                  $s='<script type="text/javascript" src="'.$v.'" '.$a.'></script>'."\n" ;
               }
            $this->_js[] = $s;
        }
        public function getJSs(){
            if(count($this->_js)==0) return "";
            $s="";
            foreach($this->_js as $k =>$v){
                $s.=$v."\n" ;
            }
            return $s;   
        }
        public function assignCSS($key,$v){
            if(is_string($key))
                $this->_css[$key] =$v;
        }
        public function getCSSs(){
            if(count($this->_ccs)==0) return "";
            
            $s="";
            foreach($this->_ccs as $k =>$v){
                $s.='<link rel="stylesheet" type="text/css" media="all" href="'.$v.'" />
'."\n" ;
            }
            return $s;   
        }
        public function assignIMG($key,$v){
            if(is_string($key)){
                 $this->_imgs[$key] =$v; 
                 if(is_array($extra));
                 $this->_imgs[$key]["attr"] = $extra; 
            }
                
        }
        public function assignEMBED($key,$v){
            if(is_string($key))
                $this->_embed[$key] =$v;
        }
    }
?>