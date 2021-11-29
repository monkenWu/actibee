<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Signup_view extends ViewEntryPoint{

    private $viewData;
    private $path = "index_views/signup/components/";

    public function __construct($transfer = array()){
        parent::__construct();
        //存放由Controller傳遞過來的資訊
        $this->viewData = $transfer;
    }

    public function index(){
        //設定組件
        $components = $this->loadComponent("{$this->path}signup",[
            "form"
        ]);
        //設定需要在Head載入的內容
        $headComponents = $this->loadComponent("{$this->path}signup",[
            "css"
        ]);
        //載入模板
        $this->loadTemplate('siginTemplate',[
            //註冊組件
            "components" => $components,
            //註冊需要在head載入的檔案
            "headComponents" => $headComponents,
            //設定頁面內容
            "pageTitle" => "註冊會員",
            "bodyTitle" => "註冊會員",
            "reCAPTCHA" => $this->viewData["reCAPTCHA"],
            "reCAPTCHA_pageName" => "siginup"
        ]);
    }
}
?>