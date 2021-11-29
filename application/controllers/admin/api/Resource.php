<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Resource extends Infrastructure {
    /**
	 * 載入父類別建構方法
	 * 預先處理需執行的項目
	 */
	public function __construct() {
        parent::__construct();
        if(!$this->getLogin()){
            echo json_encode(["status" => 0]);
            exit();
		}
	}

	public function index(){
		echo json_encode(["status" => 0]);
    }

    public function photo($photoname){
        if($photoname == "" || $photoname != "userPhoto.jpg"){
            header("HTTP/1.1 403 Forbidden");
            echo json_encode(["status" => "無法響應資源"]);
            exit();
        }
        if($this->getMemberImg() != ""){
            $path = $this->getFilePath("userfile","account/profile",$this->getMemberImg());
        }else{
            $path = $this->getFilePath("assets","beeImage","people.png");
        }
        
        
        header("Content-type: application/octet-stream");
        header('Content-Disposition: attachment; filename="userPhoto.jpg"');
        
        readfile($path);
        exit();
    }

    public function card($photoname){
        if($photoname == "" || $photoname != "studentCard.jpg"){
            header("HTTP/1.1 403 Forbidden");
            echo json_encode(["status" => "無法響應資源"]);
            exit();
        }

        $member_id = $this->getIdDecode($this->getMemberId(),"member");
        if($repeated = $this->isRepeated("member_sensitive","member_id",$member_id)){
			$realFileName = $repeated->row()->id_card_img;
		}else{
            header("HTTP/1.1 403 Forbidden");
            echo json_encode(["status" => "無法響應資源"]);
            exit();
        }

        $path = $this->getFilePath("userfile","account/realinfo",$realFileName);
        
        header("Content-type: application/octet-stream");
        header('Content-Disposition: attachment; filename="userPhoto.jpg"');
        
        readfile($path);
        exit();
    }

    public function report($activityID){
        if($activityID == ""){
            header("HTTP/1.1 403 Forbidden");
            echo json_encode(["status" => "無法響應資源"]);
            exit();
        }

        if(!isset($_POST["data"])){
            header("HTTP/1.1 403 Forbidden");
            echo json_encode(["status" => "無法響應資源"]);
            exit();
        }

        $member_id = $this->getIdDecode($this->getMemberId(),"member");
		if(!$result = $this->isRepeated("activity","id",$activityID,[
			["filed"=>"member_id","value"=>$member_id]
		])){
			header("HTTP/1.1 403 Forbidden");
            echo json_encode(["status" => "無法響應資源"]);
            exit();
        }

        $resultData = $this->isRepeated("activity_form","activity_id",$activityID,[],[
            ["table" => "order_activity_form","on" => "activity_form.id = activity_form_id"]
        ]);
        
        $formData = $this->isRepeated("activity_form","activity_id",$activityID);
        $formFiled = [];
        foreach ($formData->result() as $key => $value) {
            $filedTitle = [];
            $jsonData = $this->jsonDecodeFilter($value->data);
            foreach ($jsonData as $key => $filed) {
                $filedTitle[] = $filed["data"]["title"];
            }
            $formFiled["form{$value->id}"] = $filedTitle;
        }

        $this->load->model("admin/api/Resource_xlsx_model","model",TRUE);

        $spreadsheet = new Spreadsheet();
        $row = 1;
        $spreadsheet->setActiveSheetIndex(0);
        $sheet = $spreadsheet->getActiveSheet();

        if(!$resultData){
            $col = $this->model->getColName(1);
            $sheet->getColumnDimension($col)->setWidth(10);
            $sheet->setCellValue("{$col}{$row}","填表時間");
            $latest = end($formFiled);
            foreach ($latest as $key => $value) {
                $col = $this->model->getColName($key+2);
                $width = (int)(strlen($value)*0.8);
                $sheet->getColumnDimension($col)->setWidth($width);
                $sheet->setCellValue("{$col}{$row}",$value);
            }
            //輸出
            $writer = new Xlsx($spreadsheet);
            
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;');
            header('Cache-Control: max-age=0');
            
            //輸出文件
            $writer->save('php://output'); 
            exit;
        }

        //print_r($resultData->result());
        $nowFormKey = "";
        foreach ($resultData->result() as $key => $value) {

            $tempResultData = $this->jsonDecodeFilter($value->data);
            $thisResultData = [];
            foreach ($tempResultData as $key => $ans) {
                if(isset($thisResultData[$ans["title"]])){
                    $thisResultData[$ans["title"]] .= ", {$ans["ans"]}";
                }else{
                    $thisResultData[$ans["title"]] = $ans["ans"];
                }
            }
            $isCash = false;

            //判斷是否版本不同輸出不同標題
            if($nowFormKey == "" || $nowFormKey != $value->id){
                $row += $row == 1 ? 0 : 1;
                $nowFormKey = $value->id;
                $col = $this->model->getColName(1);
                $sheet->getColumnDimension($col)->setWidth(20);
                $sheet->setCellValue("{$col}{$row}","填表時間");
                $latest = $formFiled["form{$nowFormKey}"];
                //判斷是否擁有電子信箱的欄位
                if(count($thisResultData) != count($latest)){
                    // print_r($thisResultData);
                    // print_r($latest);
                    $isCash = true;
                    $col = $this->model->getColName(2);
                    $sheet->getColumnDimension($col)->setWidth(20);
                    $sheet->setCellValue("{$col}{$row}","電子信箱");
                }    
                foreach ($latest as $key => $titleValue) {
                    $col = $this->model->getColName($key+($isCash?3:2));
                    $width = (int)(strlen($titleValue)*0.8);
                    $sheet->getColumnDimension($col)->setWidth($width);
                    $sheet->setCellValue("{$col}{$row}",$titleValue);
                }
                $row++;
            }
            //print_r($value);

            //組合每列資料
            $col = $this->model->getColName(1);
            $sheet->setCellValue("{$col}{$row}",$value->add_time);
            $num = 0;
            foreach ($thisResultData as $key => $filed) {
                $col = $this->model->getColName($num+2);
                $sheet->setCellValue("{$col}{$row}",$filed);
                $num++;
            }

            $row++;
        }

        //輸出
        $writer = new Xlsx($spreadsheet);
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;');
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output'); 
        exit;
    
    }

}


