<?php
/*
*	Column info
*/
    class Column_Post_Info
    {                                
        protected static $isloaded = false;
        //stored columns defined
        protected $columns = array();
        protected $defaultCols = array("title"=>"Title","date"=>"Date","id"=>"ID");
        private $post_type_name;
        
		public $name_type = null;
		
        public function __construct($post_type_name)
        {
              $this->post_type_name = $post_type_name;
        }
        
        /**
        * Process columns
        * 
        */
        public function load(){
            if(!self::$isloaded){  
                //we need to define this filter for aciton  and call once in first.
                Ahlu::Call("WP_Event")->Filter()->Add_filter("main_me",array(&$this,"settings_colunm"),10,1);
                Ahlu::Call("WP_Event")->Filter()->Add_filter("main_sort",array(&$this,"settings_colunm_sort"),10,2); 
                 self::$isloaded =true;
                 Ahlu::Call("WP_Event")->Action()->Add_action('manage_posts_custom_column', array(&$this,'populate_columns'));      
            }
            //call hook   
            add_filter( "manage_edit-{$this->post_type_name}_columns", array(&$this,'define_columns'));
            add_filter( "manage_edit-{$this->post_type_name}_sortable_columns", array(&$this,'sort_columns')); 
            //sort 
            //add_filter();
           // add_filter( 'request', array(&$this,'column_orderby') );   

        }   
        public function setDefaultCols($columns){
			$cols=array("content","description");
			if(is_array($cols)){
				foreach($columns as $k=> $col){
					if(in_array(strtolower($k),$cols)){
						$this->defaultCols[strtolower($k)] = $col;
					}
				}
			}
		}
        /**
        * Setting some colunms for this table
        * 
        * @param mixed $columns
        */
        public function settings_colunm($columns){
            $data = array();
              $data['cb'] = '<input type="checkbox" />';
              
              
              foreach($columns as $key=>$col) {
                  if(!empty($col["taxonomy"])){
                       $data[$col["taxonomy"]] = $col["label"]; 
                  }else if(!empty($col["meta_key"])){
                        $data[$col["meta_key"]] = $col["label"]; 
                  }else{
                       $data[$col["field"]] = $col["label"];
                  }
              }
     
            unset( $data['comments'] );
			
			foreach($this->defaultCols as $k=>$v){
				$data[$k] = $v;
			}
            
           return $data;
        }
        
        /**
        * publish content for each colunm
        * 
        * @param mixed $column
        */
        public  function populate_columns( $column ) 
        { 
            global $post;   
           $columns = $this->columns;
           //print_r($column);

		   //check column must be field name in form  
			if ( 'title' == $column ) {
						  echo  the_title();  
			 }else if ( 'date' == $column ) {
						  echo  the_date();  
			 }else if ( 'content' == $column ) {
				  ob_start();
				  echo the_content();
				  $a = ob_get_clean();
				  
				  echo  substr(strip_tags($a),0,300);  
			 }else if ( 'description' == $column ) {
				echo 1;
 
			 }else if ( 'id' == $column ) {
						  echo  the_ID();  
			 }else{
				$col = strtolower($column);
				 
				if(isset($columns[$col]))
				{
					
					$this->do_column($post->ID,$columns[$col],$column); 
				}
			 }        
                            
        }
        /**
        * Prepare colunms
        *                
        * @param mixed $columns
        */
         public  function define_columns( $columns ) {        
              return apply_filters("main_me",$this->columns ); 
         }
        
        /**
        * Add column
        *                
        * @return void
        */
        private function _add_column($key,$args){
             $def = array(
                'label'    => 'column label', 
                'type'     => 'native', //'native','post_meta','custom_tax',text  
                'size'     => array('80','80'),
                'taxonomy' => '',
                'meta_key' => '',
                'field'=>'', // if field is not empty, meta_key or taxonomy
                'sortable' => false,
                'text'     => '',
                'orderby'  => 'meta_value',
                'prefix'   => '',
                'suffix'   => ''
            );

              $key = strtolower( str_replace( ' ', '_', $key) )."_{$this->name_type}";
               if(!isset($this->columns[$key])){
                   $this->columns[$key] = array_merge($def,!is_array($args)?array():$args);
                   //check only tatoxomy
                   if(!empty($this->columns[$key]["taxonomy"])){
                       $this->columns[$key]["taxonomy"] = strtolower( str_replace( ' ', '_',$this->columns[$key]["taxonomy"]) ) ."_{$this->name_type}";  
                   } 
               }
             //print_r($this->columns);  
        }
		 /**
        * Add  native column
        *                
        * @return void
        */
        public function addNativeColumn($key,$args=null){
             $def = array(
                'label'    => 'Excerpt', 
				'text'     => '',
                'type'     => 'native', //'native','post_meta','custom_tax',text  
                'field'=>'', // if field is not empty, meta_key or taxonomy
                'sortable' => false
            );

              $this->_add_column($key,$def);
        }
		
		 /**
        * Add  meta_field column
        *                
        * @return void
        */
        public function addFieldColumn($label,$key,$format=null,$sortable= false){
            $def = array(
                'label'    => ucwords($label), 
                'type'     => 'post_meta',
                'meta_key' => $key,
                'field'=>'', // if field is not empty, meta_key or taxonomy
                'sortable' => $sortable,
                'orderby'  => 'meta_value',
                'prefix'   => '',
                'suffix'   => '',
				'format'=>$format
            );

            $key = strtolower( str_replace( ' ', '_', $key) );
		    if(!isset($this->columns[$key])){
			   $this->columns[$key] = $def;
			   //check only tatoxomy
			   if(!empty($this->columns[$key]["taxonomy"])){
				   $this->columns[$key]["taxonomy"] = strtolower( str_replace( ' ', '_',$this->columns[$key]["taxonomy"]) ) ."_{$this->name_type}";  
			   } 
		    }
            
        }
        /**
        * get value from defined column
        * 
        * @param mixed $post_id
        * @param mixed $column
        * @param mixed $column_name
        */
        function do_column($post_id,$column,$column_name){
               
            //if (in_array($column['type'],array('text','thumb','post_meta','custom_tax') ) )
                //echo $column['prefix'];
			$columns = $this->columns;
			
            switch ($column['type']) {
                case 'text':
                    echo apply_filters( 'cpt_columns_text_'.$column_name, $column['text'],$post_id,$column, $column_name);
                    break;
                case 'thumb':
                    if (has_post_thumbnail( $post_id )){
                        the_post_thumbnail(  $column['size'] );
                    }else{
                        echo 'N/A';
                    }
                    break;
                case 'post_meta':
					$data = get_post_meta($post_id,$column['meta_key'],true);
					$format = $this->columns[strtolower($column_name)]["format"];
					
					if(empty($format)){
						echo $data;
					}else if($format instanceof Closure){
						echo call_user_func_array($format,array($data));
					}else{
					
					}
                    break;
                case 'custom_tax':
                    $post_type = get_post_type($post_id);
                   // print_r($column['taxonomy']); 
                    $terms = get_the_terms($post_id, $column['taxonomy']);
                    if ( !empty($terms) ) {
                        foreach ( $terms as $term ){
                           // $href = "edit.php?post_type={$post_type}&{$column['taxonomy']}={$term->slug}";
                           $href = "edit-tags.php?action=edit&taxonomy={$column['taxonomy']}&tag_ID={$term->term_id}&post_type={$post_type}&{$column['taxonomy']}={$term->slug}"; 
                            $name = esc_html(sanitize_term_field('name', $term->name, $term->term_id, $column['taxonomy'], 'edit'));
                            $post_terms[] = "<a href='{$href}'>{$name}</a>";
                        }                            
                        echo join( ', ', $post_terms );
                    }
                       else echo '';
                    break;
            }//end switch
           // if (in_array($column['type'],array('text','thumb','post_meta','custom_tax') ) )
             //   echo $column['suffix'];
        }//end do_column
        
       ///////////////////Sort
       public function settings_colunm_sort($colunms){
         //print_r($colunms);
          $data = array(); 
          $data['desciption'] = "desciption";
          $data['date'] = "date";              
          foreach($colunms as $k=>$v){
              //print_r($v);
              if($v["sortable"])
                  $data[$v["meta_key"]] = $v["meta_key"];
          }

            return $data;
       } 
               
       public function sort_columns ($vars ) {
           return apply_filters("main_sort",$this->columns); 
        }
        
       ///////////////////Request
       
       
      
    }
?>