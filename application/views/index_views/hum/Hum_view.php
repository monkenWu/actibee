<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Hum_view extends ViewEntryPoint{

    private $viewData;
    private $path = "index_views/hum/components/";
    
    public function __construct($transfer = array()){
        parent::__construct();
        //存放由Controller傳遞過來的資訊
        $this->viewData = $transfer;
        //print_r( $this->viewData["loadData"] );
    }

    public function index(){
        //設定組件
        $headComponents = $this->loadComponent("admin_views/editor/components/new",[
            "submitForm"
        ],["isPreview" => false]);

        $headComponents .= $this->loadComponent("{$this->path}hum",[
            "doSubmit"
        ],[
            "activityID" => $this->viewData["activityID"],
            "isCash" => $this->viewData["isCash"],
            "formDataID" => $this->viewData["formDataID"],
            "isFreedom" => $this->viewData["isFreedom"]
        ]);

        //載入模板
        $this->loadTemplate('formBasicStyle',[
            //註冊組件
            "headComponents" => $headComponents,
            //設定頁面內容
            "pageTitle" => $this->viewData["pageTitle"],
            "form" => $this->viewData["loadData"],
            //TOKEN
            "ajaxToken" => $this->viewData["token"],
            "ajaxTokenID" => $this->viewData["tokenID"]
        ]);
    }
}
?>