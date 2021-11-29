<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Account extends Infrastructure {
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
            if(!file_exists($_FILES['file']['tmp_name']) || !is_uploaded_file($_FILES['file']['tmp_name'])){
                echo json_encode(["status" => 0]);
                exit();    
            }
        }
        $this->load->model("admin/api/Account_model","model",TRUE);
	}

	public function index(){
		echo json_encode(["status" => 0]);
    }

    public function getAccountData(){
        $member_id = $this->getIdDecode($this->getMemberId(),"member");
        $data = [
            "view" => [],
            "info" => [
                "count" => 0,
                "payment" => 0,
                "success" => 0
            ]
        ];

        $result = $this->model->getActivityData($member_id);
        if($result){
            $data["info"]["count"] = $result->num_rows();
            foreach ($result->result() as $key => $row) {
                $imgJsonData = $this->jsonDecodeFilter($row->img);
                if(count($imgJsonData) == 0){
                    $img = "";
                }else{
                    $img = $imgJsonData[0]; 
                }
                $data["view"][] = [
                    "img" => $img,
                    "name" => $row->title,
                    "id" => $row->id
                ];
            }
        }else{
            echo $this->model->getDataBaseMsg();
            exit();
        }

        $result = $this->isRepeated("activity","member_id",$member_id,[
            ["filed"=>"transfer_start_time IS NOT ","value"=> null],
            ["filed"=>"transfer_end_time","value"=>null]
        ]);
        if($result){
            $data["info"]["payment"] = $result->num_rows();
        }

        $result = $this->isRepeated("activity","member_id",$member_id,[
            ["filed"=>"transfer_start_time IS NOT ","value"=>null],
            ["filed"=>"transfer_end_time IS NOT ","value"=>null]
        ]);
        if($result){
            $data["info"]["success"] = $result->num_rows();
        }

        
        echo json_encode(["status"=>1,"data"=>$data]);
    }

    public function editProfile(){
        $data = json_decode($_POST["data"],true);

        $verifyArray = ["nickName","img","token"];
        if(!$this->verifyIndex($data,$verifyArray)){
            echo json_encode(["status"=>0,"data"=>[
                "msg"=>"驗證欄位內容，這並非有效的請求。"]
            ]);
            exit();
        }

        if(!$this->verifyToken("account-info",$data["token"])){
            echo json_encode(["status" => 0,"data"=>[
                "msg"=>"驗證 token 內容，這並非有效的請求。"
            ]]);
            exit();
        }

        if($data["nickName"] == ""){
            echo json_encode(["status" => 2,"data"=>[
                "msg"=>"暱稱不可為空。"
            ]]);
            exit();
        }
        
        $data["nickName"] = $this->security->xss_clean($data["nickName"]);

        if($data["img"] != ""){
            $img = str_replace('data:image/png;base64,', '', $data["img"]);
            $img = str_replace(' ', '+', $img);
            $imgCode = base64_decode($img);
            $fileName = md5(uniqid()) . ".jpg";
            $filePath = $this->getFilePath("userfile","account/profile",$fileName);

            $result = file_put_contents($filePath, $imgCode);
            if($result){
                $data["img"] = $fileName;
            }else{
                echo json_encode(["status" => 2,"data"=>[
                    "msg"=>"檔案上傳失敗。"
                ]]);
                exit();
            }
        }else{
            $data["img"] = $this->getMemberImg();
        }

        $member_id = $this->getIdDecode($this->getMemberId(),"member");
        $data["member_id"] = $member_id;
        $result = $this->model->editProfile($data);
        if($result){
            $this->reloadMemberInfo();
            echo json_encode(["status" => 1,"data"=>[
                "msg"=>"更新成功！"
            ]]);
        }else{
            echo $this->model->getDataBaseMsg();
        }
    }

    public function editRealInfoFirst(){
        if ( 0 < $_FILES['file']['error'] ) {
            echo json_encode(["status"=>0,"data"=>[
                "msg"=> $_FILES['file']['error'] 
            ]]);
            exit();
        }

        //取得附檔名
        $filename = pathinfo($_FILES['file']['name']);
        //print_r($filename);
        if(!isset($filename["extension"])){
            echo json_encode(["status"=>0,"data"=>[
                "msg"=> "請上傳具有副檔名的合理檔案。"
            ]]);
            exit();
        }

        $newFileName = md5(uniqid()).".".$filename["extension"];
        $path = $this->getFilePath("userfile","account/realinfo",$newFileName);
        
        if(move_uploaded_file($_FILES['file']['tmp_name'], $path)){
            echo json_encode(["status"=>1,"data"=>[
                "fileName"=> $newFileName
            ]]);
        }else{
            echo json_encode(["status"=>0,"data"=>[
                "msg"=> "檔案上傳失敗。"
            ]]);
        }

    }

    public function editRealInfoSecond(){
        $data = json_decode($_POST["data"],true);

        $verifyArray = ["realName","stuNum","club","job","cellphone","fileName","img","token"];
        if(!$this->verifyIndex($data,$verifyArray)){
            echo json_encode(["status"=>0,"data"=>[
                "msg"=>"驗證欄位內容，這並非有效的請求。"]
            ]);
            exit();
        }

        if(!$this->verifyToken("account-info",$data["token"])){
            echo json_encode(["status" => 0,"data"=>[
                "msg"=>"驗證 token 內容，這並非有效的請求。"
            ]]);
            exit();
        }

        $data = $this->xss($data,["img"]);
        $member_id = $this->getIdDecode($this->getMemberId(),"member");
        $data["member_id"] = $member_id;

        if($repeated = $this->isRepeated("member_sensitive","member_id",$data["member_id"])){
            if($repeated->row()->verify != 2){
                echo json_encode(["status" => 0,"data"=>[
                    "msg"=>"狀態為審核中或審核通過，不可修改！"
                ]]);
                exit();
            }else{
                $this->deleteDBData("member_sensitive",[
                    ["filed"=>"member_id","value"=>$data["member_id"]]
                ]);
            }
        }

        if($data["img"] != ""){
            $img = str_replace('data:image/png;base64,', '', $data["img"]);
            $img = str_replace(' ', '+', $img);
            $imgCode = base64_decode($img);
            $fileName = md5(uniqid()) . ".jpg";
            $filePath = $this->getFilePath("userfile","account/realinfo",$fileName);

            $result = file_put_contents($filePath, $imgCode);
            if($result){
                $data["img"] = $fileName;
            }else{
                echo json_encode(["status" => 2,"data"=>[
                    "msg"=>"檔案上傳失敗。"
                ]]);
                exit();
            }
        }else{
            $data["img"] = $this->getMemberImg();
        }

        $result = $this->model->editRealinfo($data);
        if($result){
            $this->reloadMemberInfo();
            echo json_encode(["status" => 1,"data"=>[
                "msg"=>"更新成功！"
            ]]);
        }else{
            echo $this->model->getDataBaseMsg();
        }

    }

    public function actibeeTable(){
        $activityID = $this->security->xss_clean($_POST["data"]);
        $member_id = $this->getIdDecode($this->getMemberId(),"member");
		if($activity = $this->isRepeated("activity","id",$activityID,[
			["filed"=>"member_id","value"=>$member_id]
		])){
			$data = $activity->row();
			$is_cash = $data->is_cash == 1 ? true : false;
        }else{
			redirect(base_url("beedie"));
			exit();	
        }

        $this->load->library('light_datatables',null,"table");

        $order=array(null,'`id`','trade_date','payment_type','trade_amt','rtn_msg','payment_date');
        $like=array('`id`','`trade_date`','`trade_amt`');

        $buttton = "<div id='personID' class='btn btn-bee-one float-right' onclick='personDetail.open(\"[extra]\",\"[extra]\")'><i class='fas fa-align-left'></i>　回覆內容</div>";
        $payment_type = "[extra]";
        $payment_date = "[extra]";
        $output=array($buttton,'id','trade_date',$payment_type,'[extra]','rtn_msg',$payment_date);
        $extra=array('id','activity_fee_id','payment_type','trade_amt','payment_date');
        $extraFunction = function($value,$case,$thisRow){
            if($case == 2){
                return sha1("actibee".$value);
            }else if($case == 3){
                if(strpos($value,"CVS") !== false){
                    return "超商代碼繳費";
                }else if (strpos($value,"ATM") !== false){
                    return "ATM代碼繳費";
                }else if ($value == ""){
                    return "";
                }else{
                    return "信用卡繳費";
                }
            }else if($case == 4){
                return $value - $thisRow("handling");
            }else if($case == 5){
                if($value == ""){
                    return "待付款或無須付款";
                }else{
                    return $value;
                }
            }else{
                return $value;
            }
        };

        $this->table->ci->db
            ->from('order')
            ->select('*')
            ->where('activity_id',$activityID);
        
        //輸出設定
        echo $this->table
            ->set_querycolumn($order,$like)
            ->order_by('trade_date','DESC')
            ->set_output($output,$extra,$extraFunction,true)
            ->get_datatable();

    }

    public function getOrderData(){
        $data = $this->xss(json_decode($_POST["data"],true));

        $verifyArray = ["orderID","feeID"];
        if(!$this->verifyIndex($data,$verifyArray)){
            echo json_encode(["status"=>0,"data"=>[
                "msg"=>"驗證欄位內容，這並非有效的請求。"]
            ]);
            exit();
        }
        
        if($data["feeID"] != sha1("actibee")){
            $data["feeID"] = $this->getIdDecode($data["feeID"],"activity_fee");
        }
        
        $set = [];
        $result = $this->isRepeated(
            "order_activity_form","order_id",$data["orderID"]);
        if($result){
            $set["form"] = $this->jsonDecodeFilter($result->row()->data);
        }else{
            echo json_encode(["status"=>0,"data"=>[
                "msg" => "驗證表單內容失敗，請重新再試"
            ]]);
			exit();	
        }

        $returnData = [];
        $tempTitle = [];
        foreach ($set["form"] as $key => $value) {
            if($result = array_search($value["title"],$tempTitle)){
                $returnData[$result]["ans"][] = nl2br($value["ans"]);
            }else{
                $returnData[] = [
                    "title" => $value["title"],
                    "ans" => [nl2br($value["ans"])]
                ];
                $tempTitle[] = $value["title"];
            }
        }

        echo json_encode(["status"=>1,"data"=>$returnData]);

    }

    public function walletTable(){
        $this->load->library('light_datatables',null,"table");
        $member_id = $this->getIdDecode($this->getMemberId(),"member");
        
        $allCount = "SELECT  SUM(`order`.trade_amt - `order`.handling)
		             FROM `order`
		             WHERE `order`.activity_id = activity.id
                     AND `order`.rtn_code = 1 ";
        
        $order=array(null,'activity.title','activity.end_date','activity.transfer_start_time','activity.transfer_end_time',"all_count");
        $like=array('activity.title','activity.end_date','activity.transfer_start_time','activity.transfer_end_time');

        $output=array("[extra]",'title','end_date','[extra]','[extra]','all_count');
        $extra=array('transfer_start_time','transfer_start_time','transfer_end_time');

        $extraFunction = function($value,$case,$thisRow){
            if($case == 1){
                if($value != ""){
                    return "";
                }else{
                    $id = $thisRow("id");
                    $endDate = $thisRow("end_date");
                    return "<div class='btn btn-bee-one float-right' onclick='cashModal.open(\"{$id}\",\"{$endDate}\")'><i class='fas fa-align-left'></i></div>";
                }
            }else if($case == 2){
                return $value == "" ? "尚未申請" : $value;
            }else if($case == 3){
                if($value == "" && $thisRow("transfer_start_time") != ""){
                    return "處理中";
                }else if($value != ""){
                    return $value;
                }else{
                    return "尚未申請";
                }
            }else{
                return $value;
            }
        };

        $this->table->ci->db
        ->from('activity')
        ->select("activity.id, activity.title , activity.end_date, activity.transfer_start_time, activity.transfer_end_time,({$allCount}) as all_count")
        ->where("({$allCount}) > ",0)
        ->where("activity.member_id",$member_id);
    
        //輸出設定
        echo $this->table
            ->set_querycolumn($order,$like)
            ->order_by('end_date','DESC')
            ->set_output($output,$extra,$extraFunction,true)
            ->get_datatable();
    }

    function getBankCode(){
        $this->load->library('TaiwanBankCode',null,"bank");
        // $this->bank->updateXmlFromFisc();
        // $this->bank->convertJsonFromXml();
        if($result = $this->bank->listBankCodeATM()){
            echo json_encode(["status"=>1,"data"=>$result]);
        }else{
            echo json_encode(["status"=>0,"data"=>[
                "msg" => "取得銀行代碼失敗，請再試一次。"
            ]]);
        }
    }

    function setWallet(){
        $data = $this->xss(json_decode($_POST["data"],true));

        $verifyArray = ["bank","branch","name","address","code","activityID"];
        if(!$this->verifyIndex($data,$verifyArray)){
            echo json_encode(["status"=>0,"data"=>[
                "msg"=>"驗證欄位內容失敗，這並非有效的請求。"]
            ]);
            exit();
        }

        $member_id = $this->getIdDecode($this->getMemberId(),"member");
        $endTime = "";
		if($activity = $this->isRepeated("activity","id",$data["activityID"],[
			["filed"=>"member_id","value"=>$member_id]
		])){
            $activity = $activity->row();
            $endTime = $activity->end_date;
        }else{
            echo json_encode(["status"=>0,"data"=>[
                "msg"=>"驗證活動資料失敗，這並非有效的請求。"]
            ]);
            exit();	
        }
        
        $today = date('Y-m-d');
        if(strtotime($endTime) > strtotime($today)){
            echo json_encode(["status"=>0,"data"=>[
                "msg"=>"活動結束時間未到，這並非有效的請求。"]
            ]);
            exit();	
        }
        $data["memberID"] = $member_id;
        $data["transfer_start_time"] = date('Y-m-d h:i:s');
        $result = $this->model->setWallet($data);
        if($result){
            echo json_encode(["status" => 1,"data"=>[
                "msg"=>"更新成功！"
            ]]);
        }else{
            echo $this->model->getDataBaseMsg();
        }

    }

}