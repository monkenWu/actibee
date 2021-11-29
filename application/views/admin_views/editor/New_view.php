<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class New_view extends ViewEntryPoint{

    private $viewData;
    private $path = "admin_views/editor/components/";
    
    public function __construct($transfer = array()){
        parent::__construct();
        //存放由Controller傳遞過來的資訊
        $this->viewData = $transfer;
    }

    public function index(){
        //設定組件
        $components = $this->loadComponent("{$this->path}new",[
            "activityMain"
        ]);
        //設定需要在Head載入的內容
        $headComponents = $this->loadComponent("{$this->path}new",[
            "jquery-step-css","activityStyle","activityJs"/*引入套件管理*/
        ]);

        //載入模板
        $this->loadTemplate('systemTemplate',[
            //註冊組件
            "components" => $components,
            //註冊需要在head載入的檔案
            "headComponents" => $headComponents,
            //設定頁面內容
            "pageTitle" => "新建活動",
            "bodyTitle" => "新建活動",
            //TOKEN
            "ajaxToken" => $this->viewData["token"],
            "ajaxTokenID" => $this->viewData["tokenID"]
        ]);
    }

}
?>
