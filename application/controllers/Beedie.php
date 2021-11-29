<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Beedie extends Infrastructure {

	private $path = "index_views/beedie/";
	/**
	 * 建構載入需要預先執行的項目
	 */
	public function __construct() {
		parent::__construct();
	}

	public function index(){
		$this->initView("{$this->path}Beedie_view");
	}


}
