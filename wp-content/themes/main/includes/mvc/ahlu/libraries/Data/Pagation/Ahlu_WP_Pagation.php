<?php
/**
*  Class Ahlu_WP_Pagation
*/
class Ahlu_WP_Pagation extends Pagation implements IPagation{
     private $_rows =0;
     private  $paging = null;
     
     public function __construct($db=null){
	 
         return $this->load($db);
     }
     
     public function load($db){
         $this->db = $db;
         if($db!=null){
             $this->db = $db;
             $this->paging = Ahlu::Library("Paging"); 
         }
		 
		 return $this;
     }
    //////////////////////////// 
    public function PageLinks($echo=false,$isFile =false){
         // template

		 $this->paging->currentPage  = $this->page;
		 $this->paging->showRecord  = $this->limit;

		 
         $page_links = trim($this->paging->navigation(false));     
         
            if($isFile){
                 include_once($isFile);
            }else{
			
                 if ( $echo ) {
                    return $page_links ;
                }else{
                    echo  $page_links ;
                } 
            }   
    }
    public function PageData($default=true,$template=false){
        
        if(!$template){
            //print_r($this->dataObject);
            return ($default) ? $this->dataObject : (array)$this->dataObject; 
        }
             
        //parse template
        //{ID} => {$ID}
    } 
    //////////////////////////// 
    
    public function setTotalRows($rows){
		$this->paging->total = $rows ; 
        $this->_rows = $rows;
    }  
 }
 
/*
*       global $wpdb;
               $sl = new Ahlu_WP_Slideshow($wpdb);
                
               $pagation = new Ahlu_WP_Pagation($wpdb);
               $pagation->setPage($_REQUEST["page"]);
               $pagation->setLimit(12);
                $sl->setID(intval($id));
                   
                   $pagation->excute($sl->getQueryString());     
            $data = $pagation->PageData();
* 
*/
?>