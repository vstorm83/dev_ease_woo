<?php
/**
* Class MenuTree
* id   |  title    |  link  |  parent_id  
*/
  class MenuTree{
      protected $table;
      protected $callback;
      protected $categories = array();
      protected $rootCategories = array();
      public $selectedId = 0;
      
      public function __construct($table=null){
          
          $this->load($table);
      }
      
      public function load($table=null){
          if($table!=null){
              $this->table = $table;
              $this->init();
       
	   }
      }

      public function setCallback(&$f){
         $this->callback = $f;
      }
/*	  function display_children($parent, $level) {
    $result = mysql_query("SELECT a.id, a.label, a.link, Deriv1.Count FROM `menu` a  LEFT OUTER JOIN (SELECT parent, COUNT(*) AS Count FROM `menu` GROUP BY parent) Deriv1 ON a.id = Deriv1.parent WHERE a.parent=" . $parent);
    echo "<ul>";
    while ($row = mysql_fetch_assoc($result)) {
        if ($row['Count'] > 0) {
            echo "<li><a href='" . $row['link'] . "'>" . $row['label'] . "</a>";
            display_children($row['id'], $level + 1);
            echo "</li>";
        } elseif ($row['Count']==0) {
            echo "<li><a href='" . $row['link'] . "'>" . $row['label'] . "</a></li>";
        } else;
    }
    echo "</ul>";
}
*/
	  /**
       * Tracking root tree
       */		
	  public function trackingTree($col="id"){
        foreach($this->rootCategories as $items){
			$tracking = array();
			$find = $this->_trackingTree($items,$col,$tracking);
			if($find){
				return $tracking;
			}
		}
		return null;
      }  
	  public function _trackingTree($a=null,$find=null,&$tracking=array()){
		
		$tracking[] = $a->{$find};
		if($a->id==$this->selectedId){
			return true;
		}
		if(count($a->children)>0){
			foreach($a->children as $item){
				return $this->_trackingTree($item,$find,$tracking);
			}
		}
		return false;
      }
	  
      public function toSelect($selectedId=0){
          
      }      
	  public function toLIFrom($selectedId=0){
		  $record = null;
          foreach($this->categories as $item){
			if($item->id==$selectedId){
				$record = $item;
				break;
			}
		  }
		  $records="";
		  if($record != null){
			 $records= "<ul>";
			 foreach($record->children as $item){
				if(count($item->children)==0){
					$records.= '<li class="'.($this->selectedId==$item->id ? 'item_selected':'' ).'"'."><a href='{$item->link}'>".$item->title."</a></li>";
				}else{
					$records.= "<li><a href='{$item->link}'>".$item->title."</a>".$this->toLI($item->id)."</li>";
				}
				//print_r($this->toLI($item->id));
			 }
			$recordss.= "</ul>";
		  }
		  return $records;
      }
	  /////////////////////////////////////
      /*
	  * To List
	  * Note: Reset id selected if param is set
	  */
      public function toLI($selectedId=0,$collection=null){
			
			
          if($selectedId==0 && $collection == null){
              $collection  = $this->categories;
          }else if(intval($selectedId)!=0 && $collection == null){
              // find root from selected id
              $collection  =  $this->_find($this->selectedId);
          }

          //check callback
          if($this->callback instanceof Closure || is_array($this->callback)){
            //print_r($collection);
            $level = 0;
            return call_user_func_array($this->callback, array("ul",$collection,$this->toLiCallBack($selectedId!=0?$selectedId:$this->selectedId,$collection,"",$level,0),$level,false,0));
          }

		
          echo '<ul>';
            foreach($collection->children as $record) {
                //print_r($record);
                    if(count($record->children)>0) {
                            echo '<li class="parent-item'.($this->selectedId==$record->id ? ' item_selected ':'' ).' item-'.$record->id.'"><a href="'.$record->link.'">'.$record->title."</a>";
                                    $this->toLI($selectedId,$record);
                            echo '</li>';
                    } else {
                            echo '<li class="'.($this->selectedId==$record->id ? 'item_selected ':'' ).'item-'.$record->id.'"><a href="'.$record->link.'">'.$record->title.'</a></li>';
                    }
            }
          echo '</ul>';
      }
      private function toLiCallBack($selectedId,$root,$s="",$level=0,$isFirst=0){
         
          if(is_array($root->children) || count($root->children)>0){
              foreach($root->children as $record) {
                //print_r($record);
				
                if(count($record->children)>0) {
                      //refresh first child
                        $f = $this->toLiCallBack($selectedId,$record,"",$level++,0);
                       $s .= call_user_func_array($this->callback, array("li",$record,$f,$level,$selectedId==$record->id,0));
                } else {

                      $s .= call_user_func_array($this->callback, array("li",$record,"",$level,$selectedId==$record->id,$isFirst));
                      $isFirst++;
                }
              }
          }
          
          return $s;
      }
////////////////////////////////////////////////////////
	 private function init(){
          
          if(!is_array($this->table)){
              trigger_error("Can not process.",E_NOTICE);
              return ;
          }
          foreach ($this->table as $row ) {
                $row->children = array();
                $this->categories[$row->id] = $row;
                if(empty($row->parent_id)) {
                  //store the root
                    $this->rootCategories[$row->id] = $this->categories[$row->id];
                }
                ///////////////
                $this->categories[$row->id]->children = array();
                $this->_findTreeDown($this->categories[$row->id]->children,$row->id);
                
          }
           
              
      }

      private function _findTreeDown(&$rows,$id){
         foreach ($this->table as $row ) {
                
                if($id==$row->parent_id){

                    $rows[$row->id] = $row;
                    $rows[$row->id]->children = array();
                    $this->_findTreeDown($rows[$row->id]->children,$row->id);
                    
                }
          }
      }

      /**
      * Find item from collection
      * 
      * @param mixed $id
      * @param mixed $co
      */
     private function _find($id,$co=null){
          if($co==null){
              $co=  $this->categories;
          }
         
         foreach($co as $key=>$record) {
                if($key== $id) {
                       return $record;
                } else if(count($record->children)>0){
                     $this->_find($id,$record->children);
                }
          }
     }
     private function getPath($id) {
        $path = array();
        $current=$id;
        $path[] = 1;
        //print_r($this->categories); 
        while(!is_null($this->categories[$current]->parent_id)) {
            
            $current=$this->categories[$current]->parent_id;
            $path[] = $current;
        }
        return $path;
 }
  }
?>