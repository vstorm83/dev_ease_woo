<?php
    class View{
        protected $folder_template = "default";
        protected $folder ="themes";
        protected $data = array();
        public $load = null;
        public $pathTheme = null;
        
        public function __construct(){
          $this->load = Ahlu::Core("Loader"); 
          $this->embed = Ahlu::Call("LoadEmbed"); 
          $this->lang = Ahlu::Call("Language");
        }
        
        public function assign($key,$v){
            if(is_string($key))
                $this->data[$key] =$v;
        }
		
		
		public function getPath(){
		
			return  TEMPLATEPATH."/{$this->folder}/{$this->folder_template}"; 
		}
    }
?>