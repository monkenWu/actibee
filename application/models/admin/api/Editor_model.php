<?php

class Editor_model extends DataBaseError{

    public function __construct(){
        parent::__construct();
    }

	//DB操作手冊
	//https://codeigniter.org.tw/user_guide/database/active_record.html
    public function __destruct() {
        $this->db->close();
    }

    public function setActivityData($dataArray){
        return $this->startTransaction([
            $this->getActivityQuery($dataArray),
            $this->getCoverQuery($dataArray),
            $this->getFeeQuery($dataArray),
            $this->getFormQuery($dataArray)
        ]);
    }

    private function getActivityQuery($dataArray){
        $data = $dataArray["activity"];
        $id = $dataArray["id"];
        $style = json_encode($dataArray["style"],JSON_UNESCAPED_UNICODE);

        $activityFiled = "(id, member_id, title, content, start_date, end_date, is_cash, submit_max, style)";
        $activityValue = "(?, ?, ?, ?, ?, ?, ?, ?, '{$style}')";
        $activityData = [
            $id["activity_id"],
            $id["member_id"],
            $data["title"],
            $data["content"],
            $data["start_date"],
            $data["deadline"],
            $data["cashFlow"] ? 1 : 0,
            $data["submitMax"]
        ];

        return[
            "sql"  => "INSERT INTO activity {$activityFiled} VALUES {$activityValue};",
            "data" => $activityData
        ];
    }

    private function getCoverQuery($dataArray){
        $id = $dataArray["id"];
        $data = json_encode($dataArray["cover"],JSON_UNESCAPED_UNICODE);

        $coverFiled = "(activity_id, data)";
        $coverValue = "(?,'{$data}')";
        $coverData = [
            $id["activity_id"]
        ];

        return[
            "sql"  => "INSERT INTO activity_cover {$coverFiled} VALUES {$coverValue};",
            "data" => $coverData
        ];
    }

    private function getFeeQuery($dataArray){
        $id = $dataArray["id"];
        $time = $dataArray["add_time"];
        $type = $dataArray["fee"]["type"];
        $data = $dataArray["fee"]["data"];

        $coverFiled = "(activity_id, add_time, type, data, is_history)";
        $coverValue = "";
        foreach ($data as $key => $value) {
            $json = json_encode($value,JSON_UNESCAPED_UNICODE);
            if(count($data) == ($key+1)){
                $coverValue .= "('{$id["activity_id"]}','{$time}','{$type}','{$json}',0)";
            }else{
                $coverValue .= "('{$id["activity_id"]}','{$time}','{$type}','{$json}',0),";
            }
        }

        return[
            "sql"  => "INSERT INTO activity_fee {$coverFiled} VALUES {$coverValue};",
            "manually" => true
        ];
    }

    private function getFormQuery($dataArray){
        $id = $dataArray["id"];
        $data = json_encode($dataArray["form"],JSON_UNESCAPED_UNICODE);
        $time = $dataArray["add_time"];

        $formFiled = "(activity_id, data, is_history, add_time)";
        $formValue = "(?,'{$data}',0,?)";
        $formData = [
            $id["activity_id"],$time
        ];

        return[
            "sql"  => "INSERT INTO activity_form {$formFiled} VALUES {$formValue};",
            "data" => $formData
        ];
    }


    public function editActivityData($dataArray){
        return $this->startTransaction([
            $this->getChangeFeeQuery($dataArray),
            $this->getChangeFormQuery($dataArray),
            $this->getUpdateActivityQuery($dataArray),
            $this->getUpdateCoverQuery($dataArray),
            $this->getFeeQuery($dataArray),
            $this->getFormQuery($dataArray)
        ]);
    }

    private function getChangeFeeQuery($dataArray){
        return[
            "sql" => "UPDATE `activity_fee` SET `is_history` = 1 WHERE `id` IN {$dataArray["id"]["feeID"]} ;",
            "manually" => true
        ];
    }

    private function getChangeFormQuery($dataArray){
        return[
            "sql" => "UPDATE `activity_form` SET `is_history` = 1 WHERE `id` = {$dataArray["id"]["formID"]} ;",
            "manually" => true
        ];
    }

    private function getUpdateActivityQuery($dataArray){
        $data = $dataArray["activity"];
        $id = $dataArray["id"];
        $style = json_encode($dataArray["style"],JSON_UNESCAPED_UNICODE);

        $activityFiled = "title = ? , content = ? , start_date = ?, end_date = ?, is_cash = ?, submit_max = ?, style = '{$style}'";
        $activityData = [
            $data["title"],
            $data["content"],
            $data["start_date"],
            $data["deadline"],
            $data["cashFlow"] ? 1 : 0,
            $data["submitMax"]
        ];
        
        return[
            "sql" => "UPDATE `activity` SET {$activityFiled} WHERE `id` = '{$dataArray["id"]["activity_id"]}' ;",
            "data" => $activityData
        ];
    }

    private function getUpdateCoverQuery($dataArray){
        $data = json_encode($dataArray["cover"],JSON_UNESCAPED_UNICODE);
        return[
            "sql" => "UPDATE `activity_cover` SET `data` = '{$data}' WHERE `activity_id` = '{$dataArray["id"]["activity_id"]}' ;",
            "manually" => true
        ];
    }
    
    
}
