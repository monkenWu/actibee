<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Home_view extends ViewEntryPoint{

    private $viewData;
    private $path = "index_views/home/components/";
    
    public function __construct($transfer = array()){
        parent::__construct();
        //存放由Controller傳遞過來的資訊
        $this->viewData = $transfer;
        //print_r( $this->viewData );
    }

    public function index(){
        //設定組件
        $components = $this->loadComponent("{$this->path}home",[
            // "homeMain"
            "homeNav","section1","intro","shortIntro","intro2","ecPay","bonus","photo","footer"
        ],[
            "isLogin"  => $this->viewData['isLogin'],
            "userName"  => $this->viewData['userName'],
            "userPhoto" => $this->viewData['userImg']
        ]);
        //設定需要在Head載入的內容
        $headComponents = $this->loadComponent("{$this->path}home",[
            "homeStyle"
        ]);
        //載入模板
        $this->loadTemplate('homePage',[
            //註冊組件
            "components" => $components,
            //註冊需要在head載入的檔案
            "headComponents" => $headComponents,
            //設定頁面內容
            "pageTitle" => "首頁",
        ]);
    }
}
?>