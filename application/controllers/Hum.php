<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hum extends Infrastructure {

	private $path = "index_views/hum/";
	/**
	 * 建構載入需要預先執行的項目
	 */
	public function __construct() {
		parent::__construct();
    }
    
    public function _remap($activityTitle,$value=[]) {
        if($activityTitle == "success"){
            $this->success($value[0]);
        }else{
            $this->index($activityTitle);
        }
    }

	public function index($activityTitle = ""){
        $title = $this->security->xss_clean($activityTitle);
        $activityID = "";
        $formDataID = "";
        $isCash = false;
        $set = [
            "cover" => [],
            "activity" => [],
            "form" => [],
            "fee" => [],
            "style" => []
        ];
        $submitMax = 0;
        //組合 activity 與 style
        if($result = $this->isRepeated("activity","title",$title)){
            $result = $result->row();
            $activityID = $result->id;
            $isCash = $result->is_cash == 1 ? true : false;
            $set["activity"] = [
                "title" => $result->title,
                "content" => nl2br($result->content),
                "start_date" => $result->start_date,
                "deadline" => $result->end_date,
                "cashFlow" => $result->is_cash == 1 ? true : false,
            ];
            $transfer_start_time = $result->transfer_start_time;
			$transfer_end_time = $result->transfer_end_time;
            $set["style"] = $this->jsonDecodeFilter($result->style);
            $submitMax = $result->submit_max;
        }else{
			redirect(base_url("beedie"));
			exit();	
        }

        //組合 cover
        if($result = $this->isRepeated("activity_cover","activity_id",$activityID)){
            $set["cover"] = $this->jsonDecodeFilter($result->row()->data);
        }else{
            redirect(base_url("beedie"));
			exit();	
        }

        //判斷是否提領
        if($transfer_start_time != "" || $transfer_end_time != ""){
			$transferData = [
                "loadData"  => $set
            ];
            $this->initView("{$this->path}Over_view",$transferData);
            return;
		}

        //判斷是否上限
        if($submitMax != 0){
            $nowSubmit = $this->isRepeated("activity_form","activity_id",$activityID,[],[
                ["table" => "order_activity_form","on" => "activity_form.id = activity_form_id"]
            ]);
            if($nowSubmit){
                if($nowSubmit->num_rows() >= $submitMax){
                    $transferData = [
                        "loadData"  => $set
                    ];
                    $this->initView("{$this->path}Full_view",$transferData);
                    return;
                }
            }
        }

        //組合 form
        $result = $this->isRepeated(
            "activity_form","activity_id",$activityID,[
            ["filed"=>"is_history","value"=>0]
        ]);
        if($result){
            $formData = $this->jsonDecodeFilter($result->row()->data);
            $formDataID = sha1("actibee".$result->row()->id);
            $set["form"] = $this->jsonDecodeFilter($result->row()->data);
        }else{
            redirect(base_url("beedie"));
			exit();	
        }

        //組合 fee
        $result = $this->isRepeated(
            "activity_fee","activity_id",$activityID,[
            ["filed"=>"is_history","value"=>0]
        ]);
        if($result){
            $temp = [
                "data" => [],
                "type" => $result->row()->type
            ];

            foreach ($result->result() as $key => $value) {
                $thisFee = $this->jsonDecodeFilter($value->data);
                $thisFee["id"] = sha1("actibee".$value->id);
                $temp["data"][] = $thisFee;
            }
            $set["fee"] = $temp;
            //print_r($set["fee"]);
        }else{
            redirect(base_url("beedie"));
			exit();	
        }
        
        $tokenID = uniqid();
		$transferData = [
            "loadData"  => $set,
            "pageTitle" => "{$set["activity"]["title"]}",
            "activityID" => $activityID,
            "formDataID" => $formDataID,
            "isCash" => $isCash,
            "isFreedom" => $set["fee"]["type"] == "free" ? true : false,
            "token" => $this->getNewToken("Hum-".$tokenID),
            "tokenID" => $tokenID
        ];
        //echo json_encode($set);
        //print_r($set);
		$this->initView("{$this->path}Hum_view",$transferData);
    }
    
    public function success($activityID = ""){
        $activityID = $this->security->xss_clean($activityID);
        $set = [];
        $isCash = false;
        //組合 activity 與 style
        if($result = $this->isRepeated("activity","id",$activityID)){
            $result = $result->row();
            $isCash = $result->is_cash == 1 ? true : false;
            $set["style"] = $this->jsonDecodeFilter($result->style);
        }else{
			redirect(base_url("beedie"));
			exit();	
        }

        //組合 cover
        if($result = $this->isRepeated("activity_cover","activity_id",$activityID)){
            $set["cover"] = $this->jsonDecodeFilter($result->row()->data);
        }else{
            redirect(base_url("beedie"));
			exit();	
        }

        $transferData = [
            "loadData"  => $set,
            "isCash" => $isCash
        ];
        $this->initView("{$this->path}Success_view",$transferData);
    }

}
