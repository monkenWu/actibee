<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hum extends Infrastructure {

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
        $this->load->model("index/api/Hum_model","model",TRUE);
	}

	public function index(){
		header("HTTP/1.1 403 Forbidden");
        echo json_encode(["status" => 403]);
    }

    private function isSubmitMax($activityID){
        $submitMax = 0;
        //組合 activity 與 style
        if($result = $this->isRepeated("activity","id",$activityID)){
            $result = $result->row();
            $submitMax = $result->submit_max;
        }else{
			return false;
        }

        //判斷是否上限
        if($submitMax != 0){
            $nowSubmit = $this->isRepeated("activity_form","activity_id",$activityID,[],[
                ["table" => "order_activity_form","on" => "activity_form.id = activity_form_id"]
            ]);
            if($nowSubmit){
                if($nowSubmit->num_rows() >= $submitMax){
                    return true;
                }
            }
        }

        return false;
    }

    public function verifyPayForm(){
        $data = $this->xss(json_decode($_POST['data'],true));
        $verifyArray = ["activityID","formDataID"];
        if(!$this->verifyIndex($data,$verifyArray)){
            echo json_encode(["status"=>0,"data"=>[
                "msg"=>"驗證欄位內容失敗，這並非有效的請求。"]
            ]);
            exit();
        }
        
        if($this->isSubmitMax($data["activityID"])){
            echo json_encode(["status"=>3,"data"=>[
                "msg"=>"活動報名人數已達上限！"]
            ]);
            exit();
        }

        $formTrueID = $this->getIdDecode($data["formDataID"],"activity_form");

        if(!$this->isRepeated("activity_form","id",$formTrueID,[
            ["filed"=>"activity_id","value"=>$data["activityID"]]
        ])){
            echo json_encode(["status"=>0,"data"=>[
                "msg"=>"活動表單驗證失敗，這並非有效的請求，請重新整理後再試。"]
            ]);
            exit();
        }

        $tokenID = uniqid();
        echo json_encode(["status" => 1,"data"=>[
            "payToken" => $this->getNewToken("HumPay-".$tokenID),
            "payTokenID" => $tokenID
        ]]);
    }

    public function sendForm(){
        $data = $this->xss(json_decode($_POST['data'],true));
        $verifyArray = ["form","activityID","formDataID","token","tokenID"];
        if(!$this->verifyIndex($data,$verifyArray)){
            echo json_encode(["status"=>0,"data"=>[
                "msg"=>"驗證欄位內容失敗，這並非有效的請求。"]
            ]);
            exit();
        }

        if($this->isSubmitMax($data["activityID"])){
            echo json_encode(["status"=>3,"data"=>[
                "msg"=>"活動報名人數已達上限！"]
            ]);
            exit();
        }

        $formTrueID = $this->getIdDecode($data["formDataID"],"activity_form");

        if(!$this->isRepeated("activity_form","id",$formTrueID,[
            ["filed"=>"activity_id","value"=>$data["activityID"]]
        ])){
            echo json_encode(["status"=>0,"data"=>[
                "msg"=>"活動表單驗證失敗，這並非有效的請求，請重新整理後再試。"]
            ]);
            exit();
        }

        if(!$this->verifyToken("Hum-".$data['tokenID'],$data["token"])){
            echo json_encode(["status" => 0,"data"=>[
                "msg"=>"驗證 token 內容，這並非有效的請求。"
            ]]);
            exit();
        }

        //產生20字元唯一訂單號
        $orderID = "bee".uniqid();
        while($this->isRepeated("order","id",$orderID)){
            $orderID = "bee".uniqid();
        }
        
        $data["order_id"] = $orderID;
        $data["activity_form_id"] = $formTrueID;
        $data["trade_date"] = date("Y-m-d H:i:s");

        $result = $this->model->setOrderData($data);
        if($result){
            echo json_encode(["status" => 1,"data"=>[
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

    public function sendPayForm(){
        $data = $this->xss(json_decode($_POST['data'],true));
        $verifyArray = ["form","activityID","formDataID","token","tokenID","payData","feeID"];
        if(!$this->verifyIndex($data,$verifyArray)){
            echo json_encode(["status"=>0,"data"=>[
                "msg"=>"驗證欄位內容失敗，這並非有效的請求。"]
            ]);
            exit();
        }

        if($this->isSubmitMax($data["activityID"])){
            echo json_encode(["status"=>3,"data"=>[
                "msg"=>"活動報名人數已達上限！"]
            ]);
            exit();
        }

        $formTrueID = $this->getIdDecode($data["formDataID"],"activity_form");

        if(!$this->isRepeated("activity_form","id",$formTrueID,[
            ["filed"=>"activity_id","value"=>$data["activityID"]]
        ])){
            echo json_encode(["status"=>0,"data"=>[
                "msg"=>"活動表單驗證失敗，這並非有效的請求，請重新整理後再試。"]
            ]);
            exit();
        }

        $feeTrueID = $this->getIdDecode($data["feeID"],"activity_fee");

        //組合 activity 與 style
        $actiTitle = "";
        if($result = $this->isRepeated("activity","id",$data["activityID"])){
            $result = $result->row();
            $actiTitle = $result->title;
        }else{
			redirect(base_url("beedie"));
			exit();	
        }

        $payData = $data["payData"];
        if(isset($_SESSION[$payData["MerchantTradeNo"]])){
            $sessionOrder = $_SESSION[$payData["MerchantTradeNo"]];
            $totalPrice = $sessionOrder["price"]+$sessionOrder["handling"];
            if($totalPrice != $payData["TradeAmt"]){
                echo json_encode(["status"=>2,"data"=>[
                    "msg"=>"回傳售價驗證失敗，價格比對失敗，本次交易無效，請重新再試。"]
                ]);
                exit();
            }
        }else{
            echo json_encode(["status"=>2,"data"=>[
                "msg"=>"回傳售價驗證失敗，伺服器出現錯誤，本次交易無效，請重新再試。"]
            ]);
            exit();
        }

        if(!$this->verifyToken("Hum-".$data['tokenID'],$data["token"])){
            echo json_encode(["status" => 0,"data"=>[
                "msg"=>"驗證 token 內容，這並非有效的請求。"
            ]]);
            exit();
        }

        if(!isset($payData["MerchantTradeNo"]) && $payData["RtnCode"] != 1){
            $tokenID = uniqid();
            echo json_encode(["status" => 2,"data"=>[
                "msg"=>"{$payData["RtnMsg"]}",
                "token" =>$this->getNewToken("Hum-".$tokenID),
                "tokenID" => $tokenID
            ]]);
            exit();
        }

        $data["activity_form_id"] = $formTrueID;
        $data["activity_fee_id"] = $feeTrueID;
        $data["order_id"] = $payData["MerchantTradeNo"];
        $data["trade_no"] = $payData["TradeNo"];
        $data["trade_amt"] = $payData["TradeAmt"];
        $data["handling"] = $sessionOrder["handling"];
        $data["payment_type"] = $payData["PaymentType"];
        $data["expire_date"] = $payData["ExpireDate"] ?? null;
        $data["trade_date"] = $payData["TradeDate"];
        $data["payment_date"] = $payData["PaymentDate"] ?? null;
        if($payData["RtnCode"] == 1){
            $data["rtn_code"] = 1;
        }else{
            $data["rtn_code"] = null;
        }
        $data["rtn_msg"] = $payData["RtnMsg"];

        $result = $this->model->setPayOrderData($data);
        if(!$result){
            if(ENVIRONMENT == "development"){
                echo $this->model->getDataBaseMsg();
                exit();
            }else{
                //資料庫錯誤 重新簽發token
                $tokenID = uniqid();
                echo json_encode(["status" => 2,"data"=>[
                    "msg"=>"伺服器處理出現錯誤，資料並沒有被保存。請重新再試一次，若錯誤重複出現，請洽系統管理員。",
                    "token" =>$this->getNewToken("Hum-".$tokenID),
                    "tokenID" => $tokenID
                ]]);
                exit();
            }
        }
        
        if(strpos($data["payment_type"],"CVS") !== false){
            $sendContent = "您好，您填寫了《{$actiTitle}》，這是一個需要付費的表單。<br>您選擇的交易方式是<b>超商代碼繳費</b>。<br>您的超商繳費序號為:「{$payData["PaymentNo"]}」<br>請在{$payData["ExpireDate"]}前就近至的便利超商繳納 NT.<b>{$payData["TradeAmt"]}</b> 元！";
        }else if (strpos($data["payment_type"],"ATM") !== false){
            $sendContent = "您好，您填寫了《{$actiTitle}》，這是一個需要付費的表單。<br>您選擇的交易方式是<b>ATM代碼繳費</b>。<br>銀行代碼與帳號為:({$payData["BankCode"]}){$payData["vAccount"]}<br>請在{$payData["ExpireDate"]}前繳納 NT.<b>{$payData["TradeAmt"]}</b> 元！";
        }else{
            $sendContent = "您好，您填寫了《{$actiTitle}》。<br>您選擇的交易方式是<b>信用卡線上繳費</b>。<br>我們已經收到了 NT.<b>{$payData["TradeAmt"]}</b> 的款項，您的報名已經生效！";
        }

        $result = $this->sendEmail([
            "to"       => $data["form"][0]["ans"],
            "subject"  => "《Actibee - 活動蜂》交易及活動報名通知信",
            "template" => "template/email/paymentSuccess",
            "body"     => [
                "title"      => "訂單號：{$data["order_id"]}",
                "content"    =>  $sendContent
            ]
        ]);

        echo json_encode(["status" => 1,"data"=>[]]);
    }

}