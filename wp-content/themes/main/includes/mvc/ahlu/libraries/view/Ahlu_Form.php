<?php
    class Ahlu_Form extends View{
        protected $fields = array();
        protected $rules = array();
        
        protected $form = array();
        protected $buttonForm = null;
        protected static $countForm =0;
        
        public function __construct(){
          parent::__construct();
        }
        
        /**
        * Repare format for form
        * 
        * @param mixed $array
        */
        public function load($array=null){
            if($this->form==null)
              self::$countForm++;
            
            
            if($array!=null && is_string($array)){
                $array = array("field"=>$array);
            } 
                  
            $def =array(
                "id"=>"form_button".self::$countForm,
                "name"=>"form_button".self::$countForm,
                "action"=>$_SERVER["REQUEST_URI"],
                "method"=>"post",
                "enctype"=>"multipart/form-data"
            );
            $this->form = $array!=null ? array_merge($def,$array) : $def;
            
        }
        //override
        /**
        * Assign input to form
        * 
        * @param mixed $key
        * @param mixed $array
        */
        public function assign($key,$array){
            $key = str_replace(" ","_",$key);
            $def = array(
                "type"=>"text",
                "value"=>"",
                "label"=> "input",
                "title"=>"Please enter the field",
                "class"=>"error required"
            );
            
            $args = func_get_args();
            $info = is_string($args[0]) && is_string($args[1]) ? array_merge($def,array("value"=>$args[1])) : array_merge($def,(!is_array($array)?array("value"=>$array):$array));
           unset($args);
           //set  rules
           if(isset($info["error"]) && !empty($info["error"])){
               $this->rules[$key] = $info["error"];
               unset($info["error"]);
           }

           $this->fields[$key] = $info; 
        }
         /**
         * Set all data to fields
         * Ex:
         *  ["a"=>array()]
         * @param mixed $array
         */
         public function assignRange($array){
            foreach($array as $k=>$v){
                $this->assign($k,$v);
            } 
         } 
        /**
        * Get value field
        * 
        * @param mixed $label
        * @param mixed $echo
        */
        public function item($label,$echo =true){
            if(isset($this->fields[$label])){
                if($echo)
                   echo $this->fields[$label];
                else{
                    return $this->fields[$label];
                }
            }
        }
        /**
        * Build form
        *  
        * @param mixed $echo
        */
        public function Build($theme=null,$echo =true){
            //store sesstion about fields as validation,...
            
            //build form
            if($this->buttonForm==null)
                $this->load();
                
            $attr = StringUtil::BuildAttribute($this->form); 
            $s="<form {$attr}>\n";
            //now build field
            //print_r($this->rules);
            
            foreach($this->fields as $k=>$f){
               $s.= "<div><span>$k</span>:<span>".$this->buildField($k,$f)."</span></div>\n"; 
            }
            //add button
            if($this->buttonForm==null)
                $this->publishButton();
            
            $s.="<div>".$this->buildField("",$this->buttonForm)."<div>\n";    
            $s.="</form>\n";
            
            if($echo){
                echo $s;
            }else{
                return $s;
            }
            
        }
        /**
        * Set rule validation
        * 
        * @param mixed $item
        * @param mixed $error
        */
        public function setRuleValidation($item,$error=null){
            if($error!=null && is_string($error)){
                   $this->rules[$item] = $error;
            }else if((is_array($error) ||is_object($error))){
                $this->rules[$item] = StringUtil::BuildStringWith($error,"|");
            }
        }
       /**
       * Set Button input
       *  
       * @param mixed $btn
       */
        public function publishButton($btn=array()){
            $def =array(
                "type"=>"submit",
                "value"=>"Submit",
                "field"=>"form_button".self::$countForm
            );
            if(is_string($btn)){
              $btn=  array("value"=>$btn);
            }
            $this->buttonForm = array_merge($def,$btn);
        }
        /**
        * Build field input by one
        * 
        * @param string $field
        * @param array $item
        */
        protected function buildField($field,$item){
           if(!is_string($item)){
              trigger_error("Cannot create the input. in ".__LINE__); 
           }
           
           $label = $this->lang->itemForm("username");
           $type = strtolower($item["type"]);
           $field = strtolower((isset($item["field"])? $item["field"]:$field));  

           //sprint_r($item);
           //now remove type
           if(isset($item["field"]))
                unset($item["field"]);
           // remove unneccessary fields
              unset($item["key"]); 
              unset($item["length"]); 
              unset($item["tableRef"]); 
              unset($item["IDRef"]); 
              
              
           switch($type){
               case "radio":
               case "checkbox":
                 if($item["value"]==1 || $item["value"]=="1" || $item["value"]==true){
                    $item["checked"] = "checked"; 
                 }else if(!empty($item["value"])){  // >=1 , no empty
                     if(is_numeric($item["value"]) && intval($item["value"])>=1){
                        
                     }else if(is_string($item["value"])){
                         $item["checked"] = "checked";
                     }
                 }
                 $attr = StringUtil::BuildAttribute($item);   
                 return '<input id="'.$field.'" name="'.$field.'" '.$attr.' />';
               
               case "text":
               case "hidden":
               case "file":
               case "button":
               case "password":
               case "submit":
               $def =array(
                "size"=>20
               );

               $attr = StringUtil::BuildAttribute(array_merge($def,$item));   
               return '<input id="'.$field.'" name="'.$field.'" '.$attr.' />';
               
               case "textarea":
               $value = $item["value"];
               //now remove type
               unset($item["value"]);
               unset($item["type"]); 
               $def =array(
                "rows"=>10,
                "cols"=>10
               ); 
               $attr = StringUtil::BuildAttribute(array_merge($def,$item));
               return '<textarea id="'.$field.'" name="'.$field.'" '.$attr.'> '.$value.' <textarea>';
               
               case "select":
               $values = $item["value"];
               $selected = $item["selected"];
               
               //print_r($values);
               unset($item["value"]);
               unset($item["type"]); 
               unset($item["selected"]); 
               
               $def =array(
                "demo"=>"",
                "selected"=>array("key"=>null)               
                ); 
                
               $attr = StringUtil::BuildAttribute($item);  
               $item = array_merge($def,$item);  
               
               $s= '<select id="'.$field.'" name="'.$field.'" '.$attr.'><option value="-1">-- All --</option>';
               //add default option if exist
                   if(!empty($item["demo"])){
                       if(is_string($item["demo"])){
                           $s.="<option value=\"-1\">{$item["demo"]}</option>";
                       }else if(is_array($item["demo"])){
                            $s.="<option value=\"{$item["demo"]["key"]}\">{$item["demo"]["value"]}</option>"; 
                       }
                   }
                   if(is_array($values)){
                       $a="";
                       foreach($values as $v=>$arr){
                         $a.="<option value=\"{$v}\" ".($v==$selected? 'selected="selected"':'').">{$arr["name"]}</option>\n";  
                       }
                       $s.=$a;
                       unset($a);
                   }else{ //get demo
                       $s.="<option value=\"{$values}\">{$values}</option>\n";  
                   }
               
               $s.='</select>'; 
               return $s ;

           }
        }
        
        
       //////////////////////
       public function input($item){
       } 
    }            
?>