<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Signup extends Infrastructure {

    private $controllerName = "apiSiginUp";

    /**
	 * 載入父類別建構方法
	 * 預先處理需執行的項目
	 */
	public function __construct() {
        parent::__construct();
        if(!isset($_POST["data"])){
            header("HTTP/1.1 403 Forbidden");
            echo json_encode(["status" => 403]);
            exit();
        }
        $this->load->model("index/api/Signup_model","model",TRUE);
	}

	public function index(){
		header("HTTP/1.1 403 Forbidden");
        echo json_encode(["status" => 403]);
    }

    public function getSchool(){
        $result = $this->model->getSchoolInfo();
        $returnData = ["status"=>1,"data"=>[
            "select" => [],
            "email" => []
        ]];
        if($result){
            foreach ($result as $key => $row) {
                $returnData['data']["select"][] = [
                    "value" => $row->serial,
                    "text" => $row->name
                ];

                $returnData['data']['email'][$row->serial] = $row->mail;
            }
            echo json_encode($returnData);
            //print_r($returnData);
        }else{
            echo $this->model->getDataBaseMsg();
        }
    }

    public function captcha(){
        $data = json_decode($_POST['data'],true);
        $result = $this->runCaptcha($data["recaptcha_response"]);
        if($result["status"] == 1){
            $result["data"]["token"] = $this->getNewToken($this->controllerName);
        }
        echo json_encode($result);
    }

    public function doSignup(){
        $data = $this->xss($data = json_decode($_POST['data'],true));
        
        $verifyArray = ["school","stuNum","nickname","password","checkPassword","token"];

        if(!$this->verifyIndex($data,$verifyArray)){
            echo json_encode(["status"=>0,"data"=>[
                "msg"=>"驗證欄位內容，這並非有效的請求。"]
            ]);
            exit();
        }

        if(!$this->verifyToken($this->controllerName,$data["token"])){
            echo json_encode(["status"=>0,"data"=>[
                "msg"=>"Token不匹配，這並非有效的請求。"]
            ]);
            exit();
        }

        $emailDomain = $this->model->getEmail($data);
        if($emailDomain){
            $data["stuNum"] = "{$data["stuNum"]}@{$emailDomain->mail}";
            $data["schoolID"] = $emailDomain->id;
        }else{
            echo json_encode(["status"=>0,"data"=>[
                "msg"=>"校園代碼不匹配，這並非有效的請求。"]
            ]);
            exit();
        }

        if($this->isRepeated("member","account",$data["stuNum"])){
            echo json_encode(["status"=>2,"data"=>[
                "msg"=>"Email已經使用過囉！請再次確認。"]
            ]);
            exit();
        }

        $newMemberID = $this->model->doSignup($data);
        if(!$newMemberID){
            echo $this->model->getDataBaseMsg();
            exit();
        }

        $token = $this->model->setVerifyToken($newMemberID);
        if(!$token){
            echo $this->model->getDataBaseMsg();
            exit();
        }

        $result = $this->sendEmail([
            "to"       => $data["stuNum"],
            "subject"  => "《Actibee - 活動蜂》會員認證信",
            "template" => "template/email/accountLike",
            "body"     => [
                "title"      => "歡迎加入我們",
                "content"    => "哈囉，{$data["nickname"]}。只差最後一步就可以完成會員註冊了，立即點擊按鈕享受活動蜂的所有功能！",
                "buttonName" => "驗證會員",
                "href"       => base_url("signup/verify/{$token}")
            ]
        ]);
        if($result){
            echo json_encode(["status"=>1,"data"=>[
                "msg"=>"Success"]
            ]);
        }else{
            echo json_encode(["status"=>3,"data"=>[
                "msg"=>"認證信傳送出現問題，請確認你的學號是否存在！"]
            ]);
        }

        //print_r($data);
    }
}