<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Editor extends Infrastructure {

	private $path = "admin_views/editor/";
	/**
	 * 載入父類別建構方法
	 * 預先處理需執行的項目
	 */
	public function __construct() {
		parent::__construct();
		if(!$this->getLogin()){
			redirect(base_url("login"));
			exit();
		}
	}

	public function index(){
		$tokenID = uniqid();
		//載入從Controllers要傳入View的資料
		$transferData = [
			"token" => $this->getNewToken("Editor-".$tokenID),
			"tokenID" => $tokenID
		];
		//初始化view
		$this->initView("{$this->path}New_view",$transferData);
	}
	
	public function edit($activityID = ""){

		if($activityID == ""){
			redirect(base_url("admin"));
			exit();	
		}

		$set = [
            "cover" => [],
            "activity" => [],
            "form" => [],
            "fee" => [],
            "style" => []
        ];
		$member_id = $this->getIdDecode($this->getMemberId(),"member");
		if($result = $this->isRepeated("activity","id",$activityID,[
			["filed"=>"member_id","value"=>$member_id]
		])){
			$result = $result->row();
            $activityID = $result->id;
            $set["activity"] = [
                "title" => $result->title,
                "content" => $result->content,
                "start_date" => $result->start_date,
                "deadline" => $result->end_date,
				"cashFlow" => $result->is_cash == 1 ? true : false,
				"submitMax" => $result->submit_max
			];
			$transfer_start_time = $result->transfer_start_time;
			$transfer_end_time = $result->transfer_end_time;
            $set["style"] = $this->jsonDecodeFilter($result->style);
        }else{
			redirect(base_url("admin"));
			exit();	
		}

		if($transfer_start_time != "" || $transfer_end_time != ""){
			redirect(base_url("admin/account/activity/{$activityID}"));
		}

		//組合 cover
		if($result = $this->isRepeated("activity_cover","activity_id",$activityID)){
            $set["cover"] = $this->jsonDecodeFilter($result->row()->data);
        }else{
            redirect(base_url());
			exit();	
        }

        //組合 form
        $result = $this->isRepeated(
            "activity_form","activity_id",$activityID,[
            ["filed"=>"is_history","value"=>0]
        ]);
        if($result){
            $set["form"] = $this->jsonDecodeFilter($result->row()->data);
        }else{
            redirect(base_url());
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
                $temp["data"][] = $this->jsonDecodeFilter($value->data);
            }
            $set["fee"] = $temp;
        }else{
            redirect(base_url());
			exit();	
		}

		$tokenID = uniqid();
		$transferData = [
            "loadData"  => $set,
			"isEdit" => true,
			"activityID" => $activityID,
			"token" => $this->getNewToken("Editor-".$tokenID),
			"tokenID" => $tokenID
		];
		
		$this->initView("{$this->path}Edit_view",$transferData);
	}
      
    public function finish($activityID = ""){
		if($activityID == ""){
			redirect(base_url("admin"));
			exit();	
		}

		if($activity = $this->isRepeated("activity","id",$activityID)){
			$title = $activity->row()->title;
            $transferData["url"] = base_url("hum/{$title}");
        }else{
			redirect(base_url("admin"));
			exit();	
		}
		
		//初始化view
		$this->initView("{$this->path}Finish_view",$transferData);
	}

	public function preview($sessionName = ""){
		$name = $this->security->xss_clean($sessionName);
		if($name == ""){
			echo "error";
			exit();
		}
		if(!isset($_SESSION[$name])){
			echo "error";
			exit();
		}
		if(!$this->getLogin()){
			redirect(base_url("login"));
		}

		$data = json_decode($_SESSION[$name],true);
		$data["activity"]["content"] = nl2br($data["activity"]["content"]);
		//初始化view
		$this->initView("{$this->path}Preview_view",$data);
	}
	
}
