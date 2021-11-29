<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class ViewEntryPoint{

	private $load;
	private $menu;

    public function __construct(){
		//參考loader類別
		$this->load =& load_class("Loader");
		$this->menu = [
			[
				"name" => "個人首頁",
				"controller" => "account",
				"icon" => "fa-book"
			],
			[
				"name" => "編輯器",
				"controller" => "editor",
				"icon" => "fa-cog"
			]
		];
    }

    /**
	 * 回傳Component的畫面內容
	 * @param  String,Array
	 * @return Array
	 */
	public function loadComponent($folder,$component,$data=[]){
		$allComponent = "";
		
		if($folder){
			foreach ($component as $key => $name) {
				$allComponent .= $this->load->view("{$folder}/{$name}",$this->loadChildComponent($folder,$data),true);
			}
		}else{
			foreach ($component as $key => $path) {
				$allComponent .= $this->load->view("{$path}",'',true);
			}
		}
		return $allComponent;
	}

	private function loadChildComponent($nowFolder,$data){

		$childArray = [
			"childComponent" => function($folder,$component)use($nowFolder,$data){
				foreach ($component as $key => $name) {
					echo $this->load->view("{$nowFolder}/{$folder}/{$name}",$this->loadChildComponent("{$nowFolder}/{$folder}",$data),true);
				}
			},
		];

		foreach ($data as $key => $value) {
			$childArray[$key] = $value;
		}

		return $childArray;
	}
	
	/**
	 * 回傳Menu的設定內容
	 * @return String
	 */
	private function loadMenu(){
		$navbar="";
		foreach ($this->menu as $key => $button) {
			$url = base_url("admin/".$button['controller']);
		    $navbar .= "<li>";
		    $navbar .=     "<a href='{$url}'><i class='fa {$button['icon']}'></i> {$button['name']} </a>";
		    $navbar .= "</li>";
		}
		return $navbar;
	}


	 /**
	 * 回傳MutiFunction的設定內容
	 * @param  Array
	 * @return String
	 */
	public function loadMutiFunction($functions){
		$funHtml = "";
		$funHtml .= '<div class="container_header">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="navigation scroll_auto">
                                    <ul id="dc_accordion" class="sidebar-menu tree">';
                                        for($i = 0;$i < count($functions);$i++){
                                            $funHtml .= 
                                            "<li>
                                                <a href=\"{$functions[$i]['href']}\" target=\"{$functions[$i]['target']}\">
                                                    <i class=\"fa {$functions[$i]['icon']}\"></i>
                                                    <span>{$functions[$i]['title']}</span>
                                                </a>
                                            </li>"; 
                                        }
        $funHtml .= '               </ul>
                                </div>
                            </div>
                        </div>
					</div>';
		return $funHtml;
	}

    /**
	 * 使用所設定好的模板載入頁面內容
	 * @param  String,Array
	 * @return void
	 */
	public function loadTemplate($templateName,$setData = array()){
		//統一規格頁面標題
		$setData["pageTitle"] =  "Actibee 活動蜂 - {$setData["pageTitle"]}";
		//載入功能清單
		$setData["navBar"] = $this->loadMenu();
		//載入模板，從這裡開始串接出頁面內容
		$this->load->view("template/{$templateName}",$setData);
    }
    
}
?>