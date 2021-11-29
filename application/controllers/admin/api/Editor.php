<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Editor extends Infrastructure {
    /**
	 * 載入父類別建構方法
	 * 預先處理需執行的項目
	 */
	public function __construct() {
        parent::__construct();
        if(!$this->getLogin()){
            echo json_encode(["status" => 0]);
            exit();
		}else if(!isset($_POST["data"])){
            echo json_encode(["status" => 0]);
            exit();
        }
        $this->load->model("admin/api/Editor_model","model",TRUE);
	}

	public function index(){
		echo json_encode(["status" => 0]);
    }

    public function getPreview(){
        $data = $this->xss(json_decode($_POST['data'],true));
        $sessionName = sha1(rand(0,1000).$this->getMemberName());
        $this->startSession();
        $_SESSION[$sessionName] = json_encode($data);
        echo json_encode([
            "status"=>1,
            "data" => [
                "url" => base_url("admin/editor/preview/{$sessionName}")
            ]
        ]);
    }

    public function checkCashFlow(){
        $member_id = $this->getIdDecode($this->getMemberId(),"member");

        if($repeated = $this->isRepeated("member_sensitive","member_id",$member_id)){
            $verify = $repeated->row()->verify;
            if($verify == 1){
                echo json_encode(["status" => 1]);
                exit();
            }else if ($verify == 0){
                echo json_encode(["status" => 0]);
                exit();
            }else if ($verify == 2){
                echo json_encode(["status" => 2]);
                exit();
            }
        }else{
            echo json_encode(["status" => 3]);
            exit();
        }

    }

    public function sumitForm(){
        $data = $this->xss(json_decode($_POST['data'],true));
        $verifyArray = ["cover","activity","form","fee","style","token","tokenID"];
        if(!$this->verifyIndex($data,$verifyArray)){
            echo json_encode(["status"=>0,"data"=>[
                "msg"=>"驗證欄位內容，這並非有效的請求。"]
            ]);
            exit();
        }

        if($this->isSpecialChar($data["activity"]["title"])){
            echo json_encode(["status"=>0,"data"=>[
                "msg"=>"活動標題包含不合法的特殊字元請修正或者是改用全形標點符號。"]
            ]);
            exit();
        }
        $data["activity"]["submitMax"] = (int)$data["activity"]["submitMax"];
        if($data["activity"]["submitMax"] < 0){
            echo json_encode(["status"=>0,"data"=>[
                "msg"=>"人數上限必須大於等於 0 ！"]
            ]);
            exit();
        }
        
        if($this->isRepeated("activity","title",$data["activity"]["title"])){
            echo json_encode(["status"=>0,"data"=>[
                "msg"=>"這個活動標題已經被使用過囉！請修改一下吧？"]
            ]);
            exit();
        }
        
        if(!$this->verifyToken("Editor-".$data['tokenID'],$data["token"])){
            echo json_encode(["status" => 0,"data"=>[
                "msg"=>"驗證 token 內容，這並非有效的請求。"
            ]]);
            exit();
        }

        $member_id = $this->getIdDecode($this->getMemberId(),"member");
        $activity_id = md5(uniqid().$member_id);
        $data["id"] = [
            "member_id"   => $member_id,
            "activity_id" => $activity_id
        ];
        $data["add_time"] = date("Y-m-d H:i:s");

        $result = $this->model->setActivityData($data);
        if($result){
            echo json_encode(["status" => 1,"data"=>[
                "msg"    => "更新成功！",
                "actiID" => $activity_id
            ]]);
        }else{
            if(ENVIRONMENT == "development"){
                echo $this->model->getDataBaseMsg();
            }else{
                //資料庫錯誤 重新簽發token
                $tokenID = uniqid();
                echo json_encode(["status" => 2,"data"=>[
                    "msg"=>"伺服器處理出現錯誤，資料並沒有被保存。請重新再試一次，若錯誤重複出現，請洽系統管理員。",
                    "token" =>$this->getNewToken("Editor-".$tokenID),
                    "tokenID" => $tokenID
                ]]);
            }
        }
    }

    public function editForm(){
        $data = $this->xss(json_decode($_POST['data'],true));
        $verifyArray = ["cover","activity","form","fee","style","token","tokenID","activityID"];
        if(!$this->verifyIndex($data,$verifyArray)){
            echo json_encode(["status"=>0,"data"=>[
                "msg"=>"驗證欄位內容，這並非有效的請求。"]
            ]);
            exit();
        }

        if($this->isSpecialChar($data["activity"]["title"])){
            echo json_encode(["status"=>0,"data"=>[
                "msg"=>"活動標題包含不合法的特殊字元請修正或者是改用全形標點符號。"]
            ]);
            exit();
        }

        $data["activity"]["submitMax"] = (int)$data["activity"]["submitMax"];
        if($data["activity"]["submitMax"] < 0){
            echo json_encode(["status"=>0,"data"=>[
                "msg"=>"填寫人數上限必須大於等於 0 ！"]
            ]);
            exit();
        }

        $nowSubmit = $this->isRepeated("activity_form","activity_id",$data["activityID"],[],[
            ["table" => "order_activity_form","on" => "activity_form.id = activity_form_id"]
        ]);
        
        if($nowSubmit){
            if($nowSubmit->num_rows() > $data["activity"]["submitMax"] ){  
                if($data["activity"]["submitMax"] != 0){
                    echo json_encode(["status"=>0,"data"=>[
                        "msg"=>"填寫人數上限不能小於已填表的人數 「{$nowSubmit->num_rows}」 人！"]
                    ]);
                    exit();
                }
            }
        }

        if($result = $this->isRepeated("activity","title",$data["activity"]["title"])){
            if($result->row()->id != $data["activityID"]){
                echo json_encode(["status"=>0,"data"=>[
                    "msg"=>"這個活動標題已經被使用過囉！請修改一下吧？"]
                ]);
                exit();
            }
        }

        $member_id = $this->getIdDecode($this->getMemberId(),"member");
        if(!$repeated = $this->isRepeated("activity","id",$data["activityID"],[
            ["filed"=>"member_id","value"=>$member_id]
        ])){
            echo json_encode(["status"=>0,"data"=>[
                "msg"=>"活動序號不匹配，請重新整理或再試一次，若重複出現此錯誤請洽系統管理員。"]
            ]);
            exit();
        }

        if(!$this->verifyToken("Editor-".$data['tokenID'],$data["token"])){
            echo json_encode(["status" => 0,"data"=>[
                "msg"=>"驗證 token 內容，這並非有效的請求。"
            ]]);
            exit();
        }

        //取得目前的fee id集
        $feeID = "(";
        if($fee = $this->isRepeated("activity_fee","activity_id",$data["activityID"],[
            ["filed"=>"is_history","value"=>0]
        ])){
            $count = $fee->num_rows();
            foreach ($fee->result() as $key => $value) {
                if($key == ($count-1)){
                    $feeID .= $value->id.")";
                }else{
                    $feeID .= $value->id.",";
                }
            }
        }

        //取得目前的form id
        $formID = 0;
        if($form = $this->isRepeated("activity_form","activity_id",$data["activityID"],[
            ["filed"=>"is_history","value"=>0]
        ])){
            $formID = $form->row()->id;
        }
        
        $data["id"] = [
            "activity_id" => $data["activityID"],
            "formID" => $formID,
            "feeID" => $feeID
        ];
        $data["add_time"] = date("Y-m-d H:i:s");

        //print_r($data);

        $result = $this->model->editActivityData($data);
        if($result){
            echo json_encode(["status" => 1,"data"=>[
                "msg"    => "更新成功！",
                "actiID" => $data["activityID"]
            ]]);
        }else{
            if(ENVIRONMENT == "development"){
                echo $this->model->getDataBaseMsg();
            }else{
                //資料庫錯誤 重新簽發token
                $tokenID = uniqid();
                echo json_encode(["status" => 2,"data"=>[
                    "msg"=>"伺服器處理出現錯誤，資料並沒有被保存。請重新再試一次，若錯誤重複出現，請洽系統管理員。",
                    "token" =>$this->getNewToken("Editor-".$tokenID),
                    "tokenID" => $tokenID
                ]]);
            }
        }
    }

    
}