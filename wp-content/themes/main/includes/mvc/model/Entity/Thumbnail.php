<?php

 class Thumbnail{

	protected $id=-1;
	protected $link=null;
	protected $title=null;
	protected $content=null;
	protected $slug=null;
	protected $date=null;
	protected $img=null;
	protected $file=null;
	protected $thumbs=null; //array
	
	
	public function setThumbs($thumbs){
		$this->thumbs=$thumbs;
		return $this;
	}
	
	public function setFile($file){
		$this->file=$file;
		return $this;
	}
	public function getFile(){
		return $this->file;
	}
	public function setID($id){
		$this->id=$id;
		return $this;
	}
	public function getID(){
		return $this->id;
	}
	public function setLink($link){
		$this->link=$link;
		return $this;
	}
	public function getLink(){
		return $this->link;
	}
	public function setTitle($title){
		$this->title=$title;
		return $this;
	}
	public function getTitle(){
		return $this->title;
	}
	public function setContent($content){
		$this->content=$content;
		return $this;
	}
	public function getContent(){
		return $this->content;
	}
	public function setSlug($slug){
		$this->slug=$slug;
		return $this;
	}
	public function getSlug(){
		return $this->slug;
	}
	public function setDate($date){
	$this->date=$date;
		return $this;
	}
	public function getDate(){
		return $this->date;
	}
	public function setImage($img){
		$this->img=$img;
		return $this;
	}
	public function getImage($size=null){
		if($size!=null && is_string($size)){
			if(isset($this->thumbs["sizes"][$size])){
				$url = explode("/",$this->img);
				array_pop($url);
				return rtrim(implode("/",$url),"/")."/".$this->thumbs["sizes"][$size]["file"];
			}
			
			return $this->img;
		}
		return $this->img;
	}

 }
?>