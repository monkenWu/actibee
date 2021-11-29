<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Signup extends Infrastructure {

	private $path = "index_views/signup/";
	/**
	 * 建構載入需要預先執行的項目
	 */
	public function __construct() {
		parent::__construct();
	}

	public function index(){
		if($this->getLogin()){
			redirect(base_url("admin/Home"));
		}else{
			$this->initView("{$this->path}Signup_view",[
				"reCAPTCHA" => $this->getViewReCAPTCHA()
			]);
		}
	}

	public function verify($token = ""){
		$token = $this->security->xss_clean($token);
		if($token == ""){
			redirect(base_url(""));
			exit();
		}

		$this->startSession();
		$this->load->model("index/api/Signup_model","model",TRUE);
		$result = $this->model->verifyToken($token);
		if($result){
			if($result->num_rows()==0){
				$_SESSION["verify"] = "error";
				redirect(base_url("login"));
				exit();
			}
			//redirect(base_url(""));	
		}else{
			redirect(base_url());
			exit();
		}
		
		$result =  $this->model->setVerifyTime($token);
		if($result){
			$_SESSION["verify"] = "success";
			redirect(base_url("login"));
			exit();
		}else{
			$_SESSION["verify"] = "error";
			redirect(base_url("login"));
			exit();
		}
		
	}

}
