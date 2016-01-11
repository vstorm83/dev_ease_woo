<?php
if(isset($_REQUEST["ahluService"])){
	error_reporting(E_ALL);
	ini_set('display_errors', '1');

	ob_start();
	 //return output
	 header('content-type: application/json; charset=utf-8');
	 header("access-control-allow-origin: *");
	 

	//begin
	$ahlu = null;
	$ahlu = new stdClass();	
	$ahlu->rendered =false;

	try{
		$param = getMethod();
		
		if($param !=null)
		{ 
		  $obj = "Service_".$param["class"];

		  if(empty($param["class"]) || !class_exists($obj)){
			$ahlu->code =0;
			$ahlu->error = "Class {$param["class"]} not exist.";
		  }else{			
		    $post = parseInput(true);
			$obj = new $obj();
			$obj->setAhlu($post);
			$ahlu->error = "";			
			
			if (method_exists($obj, $param["method"])) {
			  $ahlu->code =1;
			  $ahlu->error = "";
			  $ahlu->data = Ahlu::call_object_method_array($param["method"], $obj,array_merge($post,$param["params"]));
			  //print_r($ahlu->data);
			  $ahlu->rendered = !method_exists($obj, "hasRendered") ? false: $obj->hasRendered();
			}else{
			   $ahlu->code =0;
			   $ahlu->error =  "Method {$param["method"]} is not exist in class '{$param["class"]}' in ".__LINE__;
			}
		  }      
		}else{
			$ahlu->code =0;
			$ahlu->error = "Class {$param["class"]} is not exsit. Please check agian with 'Class-Service - Method'.";
		}
	}catch(Exception $ex)
	{
		$ahlu->code =0;
		$ahlu->error = $ex->getMessage();
	}
	//end
	//output to browser	
	ob_clean();
	if($ahlu->rendered){
	   die($ahlu->data);
	}else{
		unset($ahlu->rendered);
		$object = new stdClass();
		$object->d = $ahlu;
		die(json_encode($object));
	}

}

/////////////////////////////////////////////////////////////////////////////////
	/* Get inout from url
	*   VietsingleService.php/GetUSer
	*/ 
	function parseInput($isArray =false){
		return (isset($_POST["ahlu"]) ? json_decode(stripslashes(urldecode($_POST["ahlu"])),$isArray) :array());
	}
	/* Get function from url
	*   VietsingleService.php/GetUSer
	*/ 
	function getMethod(){
		$uri = "/".ltrim($_REQUEST["ahluService"],"/");
		$arr = explode("/",$uri);
		
		$a = explode("-",$arr[count($arr)-1]);
		$len = count($a);
		if($len==1){
			$a[1]="index";
			$a[2]=array();
		}else if($len>=2){	
			$temp = $a;
			$a = array();
			$a[0] = array_shift($temp);
			$a[1] = array_shift($temp);
			$a[2] = $temp;
		}else{
			return array();
		}
		return array("class"=>$a[0],"method"=>$a[1],"params"=>empty($a[2])?(isset($url["query"])?$url["query"]:array()):$a[2]);
	}
?>
