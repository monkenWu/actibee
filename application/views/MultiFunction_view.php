<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class MultiFunction_view extends ViewEntryPoint{

    private $viewData;
    
    public function __construct($transfer = array()){
        parent::__construct();
        //存放由Controller傳遞過來的資訊
        $this->viewData = $transfer;
    }

    public function index(){
        //設定組件
        $components = $this->loadComponent('example',[
            "jsOnclick","modal"
        ]);
        //設定頁面小功能
        $multiFunctions = $this->loadMutiFunction(
            $this->getFunctionData([
                "memberName" => $this->viewData['memberName']
            ])
        );
        //載入模板
        $this->loadTemplate('systemTemplate',[
            //註冊組件
            "components" => $components,
            //註冊頁面小功能
            "multiFunctions" => $multiFunctions,
            //設定頁面內容
            "pageTitle" => "標籤標題",
            "bodyTitle" => "內頁標題",
        ]);
    }

    private function getFunctionData($dataArray=array()){
        return $this->multiFunctions = [
            [
                "title" => "呼叫組件的JS",
                "href"  => "javascript:jsOnclick.run();",
                "target" => "_self",
                "icon"  => "fa-plus-square"
            ],
            [
                "title" => "打開組件中的Modal",
                "href"  => "javascript:openModal('modal','{$dataArray['memberName']}');",
                "target" => "_self",
                "icon"  => "fa-plus-square"
            ],
            [
                "title" => "單一頁面功能範例頁",
                "href"  => base_url("example/oneFunction"),
                "target" => "_blank",
                "icon"  => "fa-paper-plane-o"
            ],
            [
                "title" => "多重頁面功能範例頁",
                "href"  => base_url("example/multiFunction"),
                "target" => "_self",
                "icon"  => "fa-paper-plane-o"
            ]
        ];
    }
}
?>