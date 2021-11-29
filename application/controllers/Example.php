<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Example extends Infrastructure {

    private $multiFunctions;

    public function __construct(){
        parent::__construct();
    }

	public function index(){
        echo "<a href='oneFunction'>單一頁面功能範例頁</a><br>";
        echo "<a href='multiFunction'>多重頁面功能範例頁</a>";
    }
    
    public function oneFunction(){
        if($this->getLogin()){
			//載入從Controllers要傳入View的資料
            $transferData = $this->getTransferData();
            //初始化view
            $this->initView('OneFunction_view',$transferData);
		}else{
			redirect(base_url("login"));
		}
    }

    public function multiFunction(){
        if($this->getLogin()){
            //載入從Controllers要傳入View的資料
            $transferData = $this->getTransferData();
            //初始化view
            $this->initView('MultiFunction_view',$transferData);
		}else{
			redirect(base_url("login"));
		}
    }
	
}
