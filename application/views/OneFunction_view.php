<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class OneFunction_view extends ViewEntryPoint{

    private $viewData;
    
    public function __construct($transfer = array()){
        parent::__construct();
        //存放由Controller傳遞過來的資訊
        $this->viewData = $transfer;
    }

    public function index(){
        //設定組件
        $components = $this->loadComponent('example',[
            "div1","div2"
        ]);
        //設定需要在Head載入的內容
        $headComponents = $this->loadComponent('example',[
            "jsLoad"
        ]);
        //載入模板
        $this->loadTemplate('systemTemplate',[
            //註冊組件
            "components" => $components,
            //註冊需要在head載入的檔案
            "headComponents" => $headComponents,
            //設定頁面內容
            "pageTitle" => "標籤標題",
            "bodyTitle" => "內頁標題",
            //使用傳遞資訊
            "memberName" => $this->viewData['memberName']
        ]);
    }

}
?>