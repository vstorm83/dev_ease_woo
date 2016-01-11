<?php
    /**
    * Create MVC template
    */
    class DAOMVC extends BaseDAO{
        public $controller;
        public function __construct($c=null){
             $this->controller_name = $c;
             return $this->init();
        }
        public function create(){			return $this->makeView()->makeController()->makeModel();		}
		
        public function makeView(){
            $c = strtolower($this->controller);
            //create post
            $content = $this->getFile("{$this->path}/view-post.php");
            $this->createFile(APPBASEVIEW."/{$this->prefix_name}-{$c}-post.php",$content);
            //create category
            $content = $this->getFile("{$this->path}/view-category.php");
            $this->createFile(APPBASEVIEW."/{$this->prefix_name}-{$c}-category.php",$content);			
			
			//create archive
            $content = $this->getFile("{$this->path}/view-archive.php");
            $this->createFile(APPBASEVIEW."/{$this->prefix_name}-{$c}-archive.php",$content);			
			
			return $this;
        }	
        public function makeController($extend="Ahlu_post"){
            $data["name"] = ucfirst($this->controller);
            $data["extend"] = $extend; 
            
            $content = $this->getFile("{$this->path}/view-controller.php",$data); 
            $this->createFile(APPBASE."/controller/{$data["name"]}.php",$content);			
	
			$content = $this->getFile("{$this->path}/view-controller.php",$data); 
            $this->createFile(APPBASE."/controller/{$data["name"]}.config.ahlu","autoload=true\n");	
			
			return $this;
        }
        public function makeModel($extend="Model"){
            $data["name"] = ucfirst($this->controller);
            $data["extend"] = $extend;

            $content = $this->getFile("{$this->path}/view-model.php",$data); 
            $this->createFile(APPBASE."/model/{$data["name"]}_model.php",$content);			
			
			//item
			$content = $this->getFile("{$this->path}/view-model-item.php",$data); 
            $this->createFile(APPBASE."/model/{$data["name"]}_item_model.php",$content);
			
			return $this;
        }
        private function init(){
          $this->path =  APPBASE."/ahlu/libraries/Data/DAO/includes";  		  return $this;	
        }
    }
?>