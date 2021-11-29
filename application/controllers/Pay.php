<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pay extends Infrastructure {
    
    private $set = PAYMENT;
    private $feeid;
    private $payType;
    private $feeTitle;
    private $feePrice;
    private $feeHandling;
    private $totalPrice;
    private $feeContent;
    private $orderID;
    private $tradeDate;

	/**
	 * 建構載入需要預先執行的項目
	 */
	public function __construct() {
        parent::__construct();

        if(!isset($_POST["data"])){
            header("HTTP/1.1 403 Forbidden");
            echo json_encode(["status" => 403]);
            exit();
        }

        $data = $this->xss(json_decode($_POST['data'],true));
        $verifyArray = ["payToken","payTokenID","feeID","type"];
        if(!$this->verifyIndex($data,$verifyArray)){
            echo json_encode(["status"=>0,"data"=>[
                "msg"=>"驗證欄位內容失敗，這並非有效的請求。"]
            ]);
            exit();
        }
        $this->payType = $data["type"];

        if(!$this->verifyToken("HumPay-".$data['payTokenID'],$data["payToken"])){
            echo json_encode(["status" => 0,"data"=>[
                "msg"=>"驗證 token 內容，這並非有效的請求。"
            ]]);
            exit();
        }

        $feeID = $this->getIdDecode($data["feeID"],"activity_fee");

        if($result = $this->isRepeated("activity_fee","id",$feeID)){
            $feeData = $this->jsonDecodeFilter($result->row()->data);
            $feeType = $result->row()->type;
            if((int)$feeData["price"] == 0){
                echo json_encode(["status"=>0,"data"=>[
                    "msg"=>"這是免費的付款方案，並非有效的請求。"]
                ]);
                exit();
            }
            $this->feeid = $feeID;
            $this->feeTitle = $feeData["title"];
            if($feeType == "free"){
                if($feeData["price"] <= $data["price"] ){
                    $this->feeHandling = $this->getHandling((int)$data["price"],$this->payType);
                    $this->feePrice = $data["price"];
                }else{
                    echo json_encode(["status"=>0,"data"=>[
                        "msg"=>"付款方案驗證失敗，填寫的金額不小於最低金額 {$feeData["price"]} 元。"]
                    ]);
                    exit();
                }
            }else{
                $this->feeHandling = $this->getHandling((int)$feeData["price"],$this->payType);
                $this->feePrice = $feeData["price"];
            }
            $this->totalPrice = $this->feeHandling+$this->feePrice;
            $this->feeContent = $feeData["content"];
        }else{
            echo json_encode(["status"=>0,"data"=>[
                "msg"=>"付款方案驗證失敗，這並非有效的請求。"]
            ]);
            exit();
        }

        $orderID = "bee".uniqid();
        while($this->isRepeated("order","id",$orderID)){
            $orderID = "bee".uniqid();
        }
        $this->orderID = $orderID;
        $this->tradeDate = date('Y/m/d H:i:s');
        
        //載入庫與伺服器連線設定
        $this->load->library("ECPay_AllInOne",NULL,"ecpay");
        $this->ecpay->ServiceURL = $this->set["ServiceURL"];
        $this->ecpay->HashKey = $this->set["HashKey"];
        $this->ecpay->HashIV = $this->set["HashIV"];
        $this->ecpay->MerchantID = $this->set["MerchantID"];
        $this->ecpay->EncryptType = $this->set["EncryptType"];
        //交易方式設定
        $this->ecpay->Send['ChoosePayment'] = ECPay_PaymentMethod::ALL ;
        $this->ecpay->Send['NeedExtraPaidInfo'] = 'Y' ;
	}

	public function index(){
        echo json_encode(["status" => 0]);
    }
    

    public function ecPay(){
        
        //付款完成後回傳的API
        $this->ecpay->Send['ReturnURL'] = base_url("api/retpay");

        //訂單相關設定
        $this->ecpay->Send['MerchantTradeNo'] = $this->orderID;
        $this->ecpay->Send['MerchantTradeDate'] = $this->tradeDate;
                
        $this->ecpay->Send['TotalAmount'] = (int)$this->totalPrice;
        $this->ecpay->Send['TradeDesc'] = "actibee 活動蜂 - 電子支付交易 :".$this->feeContent; 

        //echo $newPrice;
        //訂單的商品資料
        array_push($this->ecpay->Send['Items'], array(
            'Name' =>$this->feeTitle,
            'Price' => $this->totalPrice,
            'Currency' => "元",
            'Quantity' => (int) "1"
        ));

        //print_r($this->ecpay->Send['Items']);
        //設定過期日期
        if($this->payType == "ATM"){
            $this->ecpay->SendExtend['ExpireDate'] = 1 ;
            //$this->ecpay->SendExtend['PaymentInfoURL'] = base_url("pay/paymentReturnATM");
        }
        //設定過期分鐘
        if($this->payType == "CVS"){
            $this->ecpay->SendExtend['StoreExpireDate'] = "720" ;
        }

        try {
            //產生本次訂單資訊
            $aSdk_Return = $this->ecpay->CreateTrade();

            $aSdk_Return['SPCheckOut']  = $this->set["sSPCheckOut_Url"];

            $aSdk_Return['PaymentType'] = $this->payType;
            
            $this->startSession();
            $_SESSION[$this->orderID] = [
                "price" =>  $this->feePrice,
                "handling" => $this->feeHandling
            ];
           
            echo json_encode(["status"=>1,"data"=>$aSdk_Return]);

        } catch (Exception $e) {
            echo json_encode(["status"=>0,"data"=>[
                "msg"=>$e->getMessage()]
            ]);
        }

    }

    public function returnOrder(){
        
    }


}
