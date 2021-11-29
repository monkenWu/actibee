<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends Infrastructure {

	private $path = "index_views/home/";
	/**
	 * 建構載入需要預先執行的項目
	 */
	public function __construct() {
		parent::__construct();
	}

	public function index(){
		$transferData = [
			"isLogin"  => $this->getLogin(),
			"userName" => $this->getMemberName(),
			"userImg"  => base_url("admin/api/resource/photo/userPhoto.jpg")
		];
		$this->initView("{$this->path}Home_view",$transferData);
	}


}
