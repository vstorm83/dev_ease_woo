<?php
abstract class BaseService{
	protected $ahlu=null;
	protected $rendered = false; //data returned is string not json , default json
	
	//need to override
	public function setAhlu($ahlu){
		$this->ahlu = (object)$ahlu;
		return $this;
	}
	public function index(){
		return "default index.";
	}
	public function hasRendered(){
		return $this->rendered;
	}
}
?>