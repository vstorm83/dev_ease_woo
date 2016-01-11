<?php
class Service_User extends BaseService implements IService{
	protected $user_model = null;
	protected $cart_model = null;
	
	public function __construct(){
		$this->user_model = Ahlu::Call("User_model");
		return $this;
	}
	///////////////implement
	public function index(){
	
		return get_class($this);
	}
	///////////////////////
	///////////////override
	public function setAhlu($ahlu){
		$this->ahlu = (object)$ahlu;

		return $this;
	}
	//////////////////////////
	//Login
	public function login($login=null,$pass=null,$isRemembered=false){
		$login = isset($this->ahlu->username) ? $this->ahlu->username: $this->ahlu->email;
		$user = $this->user_model->login($login,$this->ahlu->password,isset($this->ahlu->isRemembered));

		if(!empty($user)){
			//$this->rendered =true;
			return array("url"=>site_url("user/account"));
		}
		return is_object($user);
	}
	public function signup(/**/){
		$arr = array("display_name"=>trim($this->ahlu->firstname)." ".$this->ahlu->lastname,"user_login"=>$this->ahlu->email,"user_pass"=>md5(sha1($this->ahlu->password)),"user_email"=>$this->ahlu->email,"user_url"=>'');
		//check exisstem in database
		if($this->checkEmail($this->ahlu->email)){
			$ok = $this->user_model->insert($arr);
			
			if($ok){
				//register as 
				if($this->ahlu->is_subscribed==1){
					$this->newsletter($this->ahlu->email);
				}
				//return $this->login($user_login,md5($user_pass),true);
				return array("url"=>site_url("login"));
			}
		}else{
			throw new Exception("'{$this->ahlu->email}' has been registered.");
		}
		return false;
	}
		
	public function checkEmail($email){
		return $this->user_model->checkEmail($email);
	}
	public function newsletter($email){
		return true;
	}
	
}
?>