<?php
/*   Class RecordMethod
*   Convert Fields from databse to method in object
* 
*/

/* usage 
$Record = new RecordMethod(
    array(
        'id' => 12,
        'title' => 'Greatest Hits',
        'description' => 'The greatest hits from the best band in the world!'
    )
);

echo 'The ID is:  '.$Record->getId(); // returns 12
echo 'The Title is:  '.$Record->getTitle(); // returns "Greatest Hits"
echo 'The Description is:  '.$Record->getDescription();   
*/
   
 abstract class RecordMethod {
    
    /* record information will be held in here */
    protected $infoME;
    /* store field and alias:  ["alias_field_to_call"]["field"] */
    protected $infoMEAlias = array();
    /* tracking type */
    protected $trackingType = array();
    /* Remove prefix */
    protected $removePrefix = array("me_","b_","post_","user_");
    /* Enable remove prefix */
    protected $isRemovePrefix = false;
    /* constructor */

    //Data from object
    protected $meData = array();
    protected $meDataInfo = array();
    
    /* dynamic function server and this magic __call just apply for fucntion*/
    public function __call($method,$arguments) {
         if(in_array($method,get_class_methods($this))){
            return call_user_method_array($method,$this,func_get_args());
         }

        $meth = $this->from_camel_case(substr($method,3,strlen($method)-3)); // get and set (this case is getter)
       //set data
        if(!array_key_exists($meth,$this->infoME) && substr($method,0,3)=="set"){
           //remove prefix
           if($this->isRemovePrefix && count($this->removePrefix)>0){
               foreach($this->removePrefix as $v){
                   $meth = str_replace($v,"",$meth);
               }
           } 
            $data = $arguments[0];
            if(is_object($data)){
              $meth = "{$meth}Obj";  
            }else if(is_array($data)){
              $meth = "{$meth}Array";    
            }
            
            $this->infoME[$meth] =  $data; 
            $this->infoMEAlias[strtolower($meth)][] = $data;
            $this->infoMEAlias[strtolower($meth)][strtolower($meth)] = $data;
            
            return false; 
        }

        // echo  $meth;     
        //tracking type
        $this->trackingType[$meth] = gettype($this->infoME[$meth]);

        //print_r($this->infoMEAlias);
        if(array_key_exists($meth,$this->infoMEAlias)){
           return $this->infoMEAlias[$meth][0]; 
        }else{
            //now we check alias name wheter it contains it or not
             foreach($this->infoMEAlias as $arr){
                 if(array_key_exists($meth,$arr)){
                     return  $arr[$meth][0];
                 }
             }
            trigger_error("Can not find '{$meth}', if defined variable is Object, we must add suffix '_Obj' in '(object->getNameObj())'",E_USER_WARNING);
            return false;
        }    
    }
    /* set Data to object by function */    
    public function __set($method,$value) {
        if(!is_string($method))
           trigger_error("Cannot set this property. Maybe the '$method' is a string.");
           
        $meth = $this->from_camel_case(substr($method,3,strlen($method)-3)); // 
		if(isset($this->infoME[$meth]))
			$this->infoME[$meth] =  $value; 
    }
    /* uncamelcaser: via http://www.paulferrett.com/2009/php-camel-case-functions/ */
    public function from_camel_case($str) {
        $str[0] = strtolower($str[0]);
        $func = create_function('$c', 'return strtolower($c[1]);');
        return preg_replace_callback('/([A-Z])/', $func, $str);
    }
    
    /**
    * Tracking type of value
    * 
    * @param mixed $name
    */
    public function TrackType($name){
        return $this->TrackType($name);
    }
    
    /**
    * Enable to remove prefix from column 
    * 
    * @param mixed $item
    */
    public function setRemovePrefix($item=null){
        $this->isRemovePrefix = true;
        if($item!=null && !is_array($item)){
            $this->removePrefix =  array_merge($this->removePrefix,array($item)); 
        }
        
       //remove prefix
       $this->removePrefix();
    }

    protected function removePrefix(){
        //print_r($this->infoME);
        $this->infoMEAlias = array();
           foreach($this->infoME as $k=> $v){
               $s=preg_replace('/^('.implode("|",$this->removePrefix).')/is',"",$k);
                   $this->infoMEAlias[$s][] = $v["value"]; 
                   $this->infoMEAlias[$s][strtolower($k)] = $v["value"];
               
           }
      print_r($this->infoMEAlias);
    }
}

?>