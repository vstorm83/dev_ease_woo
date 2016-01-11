<?php
    class Template extends View{
        
        public function __construct(){
          parent::__construct();
        }
        
        public function load($template,$theme=null){
			
			if(empty($template)){
				$this->template = $this->getPath()."/{$this->folder_template}.php";
				return;
			}
			$this->folder_template = $template;
			
            if($theme!= null) 
				$this->folder =$theme;

            $path =TEMPLATEPATH."/{$this->folder}/{$template}.php";
			//echo $path;
            if(file_exists($path)){   
               $this->template = $path; 
               //echo $this->template; 
               //refresh data
               $this->data = array();
            }else{
                trigger_error("Cannot load template '{$template}' in '{$path}'.");
            }
            
        }
        
        public function assign($key,$v){
            if(is_string($key))
                $this->data[$key] =$v;
        }
        /**
        * Print output in screen
        *  
        * @param mixed $return
        */
        public function render($return=false){
            //get some file default
            $this->data["insert_js"]=$this->embed->getJSs();
            $this->data["insert_css"]=$this->embed->getCSSs();
            
			$data = $this->data;
			add_action('wp_head',function() use($data){
			    echo $data["meta"];
				echo $data["insert_js"];
				echo $data["insert_css"];
			});
			
            if($return)
              return $this->load->view($this->template,$this->data,$return);
            else
              $this->load->view($this->template,$this->data,$return); 
        }
        
        
    }
?>
