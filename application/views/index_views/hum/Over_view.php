<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Over_view extends ViewEntryPoint{

    private $viewData;
    private $path = "index_views/hum/components/";
    
    public function __construct($transfer = array()){
        parent::__construct();
        //存放由Controller傳遞過來的資訊
        $this->viewData = $transfer;
        //print_r( $this->viewData["loadData"] );
    }

    public function index(){
        //載入模板
        $this->loadTemplate('formOverStyle',[
            //註冊組件
            // "headComponents" => $headComponents,
            //設定頁面內容
            "pageTitle" => "完成",
            "form" => $this->viewData["loadData"]
        ]);
    }
}
?>