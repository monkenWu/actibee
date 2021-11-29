<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends Infrastructure {

	private $path = "index_views/login/";
	/**
	 * 建構載入需要預先執行的項目
	 */
	public function __construct() {
		parent::__construct();
		$this->load->model("Login_model","model",TRUE);
	}

	public function index(){
		if($this->getLogin()){
			redirect(base_url("admin/home"));
		}else{
			$this->initView("{$this->path}Login_view");
			if(isset($_SESSION["verify"])){
				$this->startSession();
				unset($_SESSION["verify"]);
			}
		}
	}

	/**
	 * status = 1 登入成功,0登入失敗,2尚未認證
	 */
	public function checkLogin(){
		if(isset($_POST['data'])){
			//將json解析為陣列後進行消毒
			$data = $this->xss(json_decode($_POST['data'],true));
			$data['password'] = md5($data['password']);

			$login = $this->model->checkLogin($data);
			if($login){
				if($login->num_rows() == 0){
					$data = array('status'=>0,'data'=>array());
					echo json_encode($data);
					exit();
                }
			}else{
				$this->model->getDataBaseMsg();	
				exit();
			}

			$checkEmailVerify = $this->model->checkEmailVerify($this->getIdDecode($login->row()->memebr_id,"member"));
			if($checkEmailVerify){
				if($checkEmailVerify->row()->time == ""){
					$data = array('status'=>2,'data'=>array());
					echo json_encode($data);
					exit();
				}
			}else{
				echo $this->model->getDataBaseMsg();
				exit();
			}

			$data = array('status'=>1,'data'=>array());
			$this->startSession();
			$this->model->setSession($login->row());
			echo json_encode($data);
		}
	}

	/**
	 * status = 1 寄出成功,0寄出失敗,2帳號或密碼錯誤,3已認證過
	 */
	public function reMail(){
		if(isset($_POST['data'])){
			//將json解析為陣列後進行消毒
			$data = $this->xss(json_decode($_POST['data'],true));
			$data['password'] = md5($data['password']);

			$login = $this->model->checkLogin($data);
			if($login){
				if($login->num_rows() == 0){
					$data = array('status'=>2,'data'=>array());
					echo json_encode($data);
					exit();
                }
			}else{
				$this->model->getDataBaseMsg();	
				exit();
			}

			$checkEmailVerify = $this->model->checkEmailVerify($this->getIdDecode($login->row()->memebr_id,"member"));
			if($checkEmailVerify){
				if($checkEmailVerify->row()->time != ""){
					$data = array('status'=>3,'data'=>array());
					echo json_encode($data);
					exit();
				}
			}else{
				echo $this->model->getDataBaseMsg();
				exit();
			}

			$toMail = $login->row()->account;
			$toName = $login->row()->member_name;
			$token = $checkEmailVerify->row()->token;

			$result = $this->sendEmail([
				"to"       => $toMail,
				"subject"  => "《Actibee - 活動蜂》會員認證信補發",
				"template" => "template/email/accountLike",
				"body"     => [
					"title"      => "歡迎加入我們",
					"content"    => "哈囉，{$toName}。只差最後一步就可以完成會員註冊了，立即點擊按鈕享受活動蜂的所有功能！",
					"buttonName" => "驗證會員",
					"href"       => base_url("signup/verify/{$token}")
				]
			]);
			if($result){
				echo json_encode(["status"=>1,"data"=>[
					"msg"=>"Success"]
				]);
			}else{
				echo json_encode(["status"=>3,"data"=>[]]);
			}
		}
	}

	public function out(){
		$this->startSession();
		session_destroy();
		redirect(base_url());
	}

}
