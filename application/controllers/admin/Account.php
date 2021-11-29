<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Account extends Infrastructure {

	private $path = "admin_views/account/";
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
		$transferData = [
			"userName" => $this->getMemberName(),
			"userImg"  => base_url("admin/api/resource/photo/userPhoto.jpg")
		];
		//初始化view
		$this->initView("{$this->path}Account_view",$transferData);
    }
        
    public function activity($activityID=""){
		if($activityID == ""){
			redirect(base_url("admin"));
			exit();	
		}
		$member_id = $this->getIdDecode($this->getMemberId(),"member");
		if($activity = $this->isRepeated("activity","id",$activityID,[
			["filed"=>"member_id","value"=>$member_id]
		])){
			$data = $activity->row();
			$transferData = [
				"activityID" => $data->id,
				"title" => $data->title,
				"url" =>base_url("hum/{$data->title}"),
				"content" => $data->content,
				"start_date" => $data->start_date,
				"end_date" => $data->end_date,
				"is_cash" => $data->is_cash == 1 ? "已開啟" : "未開啟",
				"transfer_start_time" => $data->transfer_start_time,
				"transfer_end_time" => $data->transfer_end_time
			];
        }else{
			redirect(base_url("admin"));
			exit();	
		}
		
		//初始化view
		$this->initView("{$this->path}Activity_view",$transferData);
	}

	public function activityEdit(){
		//載入從Controllers要傳入View的資料
		$transferData = $this->getTransferData();
		//初始化view
		$this->initView("{$this->path}activityEdit_view",$transferData);
    }


	public function info(){
		$member_id = $this->getIdDecode($this->getMemberId(),"member");

        if($repeated = $this->isRepeated("member_sensitive","member_id",$member_id)){
			$data = $repeated->row();
			$realinfo = [
				"verify" => $data->verify,
				"realName" => $data->real_name,
				"stuNum" => $data->student_id,
				"img" => base_url("admin/api/resource/card/studentCard.jpg"),
				"cellphone" => $data->cellphone,
				"club" => $data->group,
				"job" => $data->postion,
				"updateTime" => $data->update_time,
				"verifyTime" => $data->verify_time,
				"reason" => $data->reason
			];
        }else{
			//verify = 3 : 尚未初始化任何資料
			$realinfo = ["verify" => 3];
		}

		$transferData = [
			"profile" => [
				"userName" => $this->getMemberName(),
				"userImg"  => base_url("admin/api/resource/photo/userPhoto.jpg")
			],
			"realinfo" =>$realinfo,
			"token" => $this->getNewToken("account-info")
		];
		//初始化view
		$this->initView("{$this->path}Info_view",$transferData);
	}
	
}
