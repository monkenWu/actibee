<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Retpay extends Infrastructure {

    private $set = PAYMENT;

    /**
	 * 載入父類別建構方法
	 * 預先處理需執行的項目
	 */
	public function __construct() {
        parent::__construct();
        // echo "1|OK";
        // exit();
        if(!isset($_POST["MerchantTradeNo"])){
            echo "err";
            exit();
        }
        $this->load->model("index/api/Retpay_model","model",TRUE);
        $this->load->library("ECPay_AllInOne",NULL,"ecpay");
        $CheckMacValue = ECPay_CheckMacValue::generate($_POST, $this->set["HashKey"], $this->set["HashIV"],$this->set["EncryptType"]);
        if($CheckMacValue != $_POST['CheckMacValue']){
            echo "err";
            exit();
        }
	}

	public function index(){
        
        if($result = $this->isRepeated("order","id",$_POST["MerchantTradeNo"])){
            $result = $result->row();
        }else{
            echo "err";
            exit();
        }

        if($result->payment_type != $_POST["PaymentType"]){
            echo "err";
            exit();
        }  

        $data = ["rtn_code" => $_POST["RtnCode"]];
        if($data["rtn_code"] == 1){
            $data["payment_date"] = $_POST["PaymentDate"];
        }else{
            $data["payment_date"] = "";
        }
        $data["rtn_msg"] = $_POST["RtnMsg"];

        $data["id"] = $_POST["MerchantTradeNo"];

        $trade_amt = $result->trade_amt;

        if($result = $this->isRepeated("order_activity_form","order_id",$_POST["MerchantTradeNo"])){
            $sendEmail = $this->jsonDecodeFilter($result->row()->data)[0]["ans"];
        }else{
            echo "err";
            exit();
        }
        
        if(!$result = $this->model->updateOrder($data)){
            echo "dbErr";
            exit();
        }

        $sendContent = "我們已經收到了新台幣 <b>{$trade_amt}</b> 元的款項，您的報名已經生效！";

        $result = $this->sendEmail([
            "to"       => $sendEmail,
            "subject"  => "《Actibee - 活動蜂》付款成功通知信！",
            "template" => "template/email/paymentSuccess",
            "body"     => [
                "title"      => "訂單號：{$_POST["MerchantTradeNo"]}",
                "content"    =>  $sendContent
            ]
        ]);

        echo "1|OK";

    }

}