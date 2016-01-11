<?php
    class Paging {
        public $view="defaultME";
        public $class= array("selected"=>"current","id"=>"paging","class"=>"paging","callback"=>"");
        public $showRecord = 10;
        public $data = null;
        public $total = 0;
        public $currentPage=1;
        public $enableURL = false;
        public $uri = null;
 
        public function __construct(){
           
			
			$this->uri = preg_replace('/(\-page\-\d+)$/','',$_SERVER["REQUEST_URI"]);

            return $this;
        }

        
        public function navigation($echo=true,$view = null){
			
             if($view!=null){
                 $this->view = $view; 
             }
             $data =  $this->{$this->view}(); 
             
             if($echo){
                 echo $data;
             }else{
                 return $data; 
             }
        }
        
        private function defaultME(){
            $totalPage = $this->showRecord>0?ceil($this->total/$this->showRecord):0;
            $currentPage = $this->currentPage==0 ? 1 :$this->currentPage ;
            
			
			$style ='';       

            $p = 1;
            $pre = $currentPage - 1;
            $next = $currentPage + 1;
            $sPaging="";
            $sb="";
            if($totalPage>1){ //show if more than one page
            $sPaging.='<nav class="woocommerce-pagination"><ul class="page-numbers">';
                if ($currentPage != 1) {
                    //$sPaging .='<li><a class="prev page-numbers"  href="'.URI::getInstance()->createPage($pre).'">&larr; </a></li>';
                }else{
                    //$sPaging .='<li><a class="prev page-numbers '.$this->class["selected"].'">&larr;</li>';
                }
                $count = 0; //count cho biet user hien dang  o trang nao
                $fflag = false;
                while ($p <= $totalPage) {
                    $count++;
                    if ($p == $currentPage) {
                        $sPaging .= '<li><a class="page-numbers '.$this->class["selected"].'">'.$p.'</a></li>';
                    }
                    else {
                        $sPaging .= '<li><a class="page-numbers" href="'.URI::getInstance()->createPage($p).'">'.$p.'</a></li>';
                    }
                    //chan truoc khi vuot qua gioi han 10 va hien thi them 2 page tro ve truoc
                    if (!$fflag && $totalPage > 10 && $p < $currentPage && $currentPage - 3 > 1 && $count > 1) {
                        $sPaging .= '<li><a class="page-numbers">...</a></li>';
                        $p =  $currentPage - 1;
                        $fflag = true;
                        continue;
                    }
                    //
                    if ($totalPage > 10 && $p > $currentPage && $totalPage - $p > 2 && $count > 2) {
                        $sPaging .= '<li><a class="page-numbers">...</a></li>';
                        $p = $totalPage - 1;
                        continue;
                    }
                    $p++;
                }
                if ($currentPage != $totalPage) {
                    //$sPaging .= '<li><a class="next page-numbers"  href="'.URI::getInstance()->createPage($next).'">&rarr;</a></li>';
                }
               $sPaging.='</ul></nav>';    
             }
             
             $sb.="\n".$style."\n".$sPaging."\n";
	
            return $sb;
        }
		
		/*
		private function defaultME(){
            $totalPage = $this->showRecord>0?ceil($this->total/$this->showRecord):0;
            $currentPage = $this->currentPage==0 ? 1 :$this->currentPage ;
            
			
			$style ='
              <style type="text/css">
                #paging{
                      text-align: right;
                }
                #paging a{
                   display : inline-block;
                   padding : 5px 10px;
                }
				#paging a.current{
                    text-decoration :none;
                    color:#ccc;
                }
              </style>
            ';       

            $p = 1;
            $pre = $currentPage - 1;
            $next = $currentPage + 1;
            $sPaging="";
            $sb="";
            if($totalPage>1){ //show if more than one page
            $sPaging.='<div id="'.$this->class["id"].'" class="'.$this->class["class"].'">';
                if ($currentPage != 1) {
                    $sPaging .='<a  href="'.URI::getInstance()->createPage($pre).'">&laquo;&nbsp;Previous </a>';
                }else{
                    $sPaging .=' <a class="'.$this->class["selected"].'"><span>Previous </span>';
                }
                $count = 0; //count cho biet user hien dang  o trang nao
                $fflag = false;
                while ($p <= $totalPage) {
                    $count++;
                    if ($p == $currentPage) {
                        $sPaging .= '<a class="'.$this->class["selected"].'"><span>'.$p.'</span></a>';
                    }
                    else {
                        $sPaging .= '<a  href="'.URI::getInstance()->createPage($p).'">'.$p.'</a>';
                    }
                    //chan truoc khi vuot qua gioi han 10 va hien thi them 2 page tro ve truoc
                    if (!$fflag && $totalPage > 10 && $p < $currentPage && $currentPage - 4 > 1 && $count > 1) {
                        $sPaging .= '<span>...</span>';
                        $p =  $currentPage - 2;
                        $fflag = true;
                        continue;
                    }
                    //
                    if ($totalPage > 10 && $p > $currentPage && $totalPage - $p > 2 && $count > 6) {
                        $sPaging .= '<span>...</span>';
                        $p = $totalPage - 1;
                        continue;
                    }
                    $p++;
                }
                if ($currentPage != $totalPage) {
                    $sPaging .= '<a  href="'.URI::getInstance()->createPage($next).'">Next&nbsp;&raquo;</a>';
                }
               $sPaging.='</div>';    
             }
             
             $sb.="\n".$style."\n".$sPaging."\n";
	
            return $sb;
        }
		*/
		
    }
?>