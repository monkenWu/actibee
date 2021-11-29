<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends Infrastructure {

	/**
	 * 載入父類別建構方法
	 * 預先處理需執行的項目
	 */
	public function __construct() {
		parent::__construct();
	}

	public function index(){
		if($this->getLogin()){
			redirect(base_url("admin/account"));
		}else{
			redirect(base_url("login"));
		}
	}
	
}
