<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Email extends Infrastructure {

    private $mailData;
	/**
	 * 建構載入需要預先執行的項目
	 */
	public function __construct() {
        parent::__construct();
        if(!isset($_POST["data"])){
            header("HTTP/1.1 403 Forbidden");
            echo json_encode(["status" => 403]);
            exit();
        }
        $this->mailData = $this->jsonDecodeFilter($_POST["data"]);
        if($this->mailData){
            if($this->mailData["token"] != EMAIL_KEY){
                header("HTTP/1.1 403 Forbidden");
                echo json_encode(["status" => 403]);
                exit();
            }
        }else{
            header("HTTP/1.1 403 Forbidden");
            echo json_encode(["status" => 403]);
            exit();
        }
        $this->load->library('email');
        $this->email->initialize(EMAIL_SET);
	}

	public function index(){
		$this->email->from('application@monken.tw','Actibee-活動蜂系統信箱');
        $this->email->to($this->mailData["to"]);
        $this->email->subject($this->mailData["subject"]);
        $this->email->message($this->load->view($this->mailData["template"],$this->mailData["body"],TRUE));
        if($this->email->send(FALSE)){
            return true;
        }else{
			//echo $this->email->print_debugger();
			if(ENVIRONMENT == "development" || ENVIRONMENT == "testing"){
				echo $this->Infrastructure_model->getDataBaseMsg();
			}
            return false;
        }
	}

}
