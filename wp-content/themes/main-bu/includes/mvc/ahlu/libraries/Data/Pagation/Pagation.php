<?php
/**
* Abstract Pagation
*/
abstract class Pagation{
    protected $db = null; 
    protected $query = null;
    protected $page = 1;
    protected $limit = 10;
    
    protected $dataObject = null;
    public $hasData = false; //check if having data
    public $total = 0; //check if having data
    ///////
    public function excute($query){  
	  $this->hasData = false;

      if($query==null && $this->query==null){
          trigger_error("Can not find querys string.",E_USER_WARNING);
      }else{
          $this->setQuery($query); 
      }  
      $offset = ( $this->page - 1 ) * $this->limit;
      
        if(preg_match('/(select)/i',$query,$m)){
			$query = str_replace(array("Select","SELECT"),"select",$query);
		}
		$find = stripos($query,"select");
		if($find!==FALSE){
			$str = substr($query,$find+strlen("select"));
			$query = "select SQL_CALC_FOUND_ROWS ".$str;
		}else{
			$query = str_replace(array("select","Select","SELECT"),"select SQL_CALC_FOUND_ROWS ",$query);
		}
		
		//set limit 
		if($this->limit!=0){
			$query .=" Limit $offset,{$this->limit}";
		}
		
		//echo $query;
		$this->query = $query;
        $sqlTotal = "SELECT FOUND_ROWS()";
        $sql = $this->db->get_results($query);
        
        if(count($sql)>0){
            $this->dataObject = $sql;
            //get total rows
            $this->total = $this->db->get_var($sqlTotal);
            $this->hasData =true;
        } 

    }
    ///////
    abstract public function setTotalRows($rows);
    
    public function setQuery($query){
        
        if(is_string($query)){
           $this->query = $query; 
        }  
    }
    public function setPage($page){
        if(is_numeric($page))
           $this->page = $page;
    }
    public function setLimit($limit){
        if(is_numeric($limit)){
           $this->limit = $limit;
		}
    }
}
?>