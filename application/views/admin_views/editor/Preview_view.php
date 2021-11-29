<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Preview_view extends ViewEntryPoint{

    private $viewData;
    private $path = "admin_views/editor/components/";
    
    public function __construct($transfer = array()){
        parent::__construct();
        //存放由Controller傳遞過來的資訊
        $this->viewData = $transfer;
        //print_r($this->viewData);
    }

    public function index(){
        //設定需要在Head載入的內容
        $headComponents = $this->loadComponent("{$this->path}new",[
            "submitForm",
        ],["isPreview" => true]);
        //載入模板
        $this->loadTemplate('formBasicStyle',[
            //註冊需要在head載入的檔案
            "headComponents" => $headComponents,
            //設定頁面內容
            "pageTitle" => $this->viewData["activity"]["title"],
            "form" => $this->viewData
        ]);
    }

}
?>