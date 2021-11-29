<?php

class Hum_model extends DataBaseError{

	//DB操作手冊
	//https://codeigniter.org.tw/user_guide/database/active_record.html
    public function __destruct() {  
        $this->db->close();  
    }

    public function setOrderData($dataArray){
        return $this->startTransaction([
            $this->getOrderQuery($dataArray),
            $this->getOrderFormQuery($dataArray)
        ]);
    }
    
    public function getOrderQuery($dataArray){

        $orderFiled = "(id, activity_id,trade_date)";
        $orderValue = "(?, ?, ?)";
        $orderData = [
            $dataArray["order_id"],
            $dataArray["activityID"],
            $dataArray["trade_date"]
        ];

        return[
            "sql"  => "INSERT INTO `order` {$orderFiled} VALUES {$orderValue};",
            "data" => $orderData
        ];
    }

    private function getOrderFormQuery($dataArray){
        $jsonData = json_encode($dataArray["form"],JSON_UNESCAPED_UNICODE);

        $formFiled = "(`order_id`, `activity_form_id`, `data`)";
        $formValue = "(?,?,'{$jsonData}')";
        $formData = [
            $dataArray["order_id"],
            $dataArray["activity_form_id"]
        ];

        return[
            "sql"  => "INSERT INTO `order_activity_form` {$formFiled} VALUES {$formValue};",
            "data" => $formData
        ];
    }

    public function setPayOrderData($dataArray){
        return $this->startTransaction([
            $this->getPayOrderQuery($dataArray),
            $this->getOrderFormQuery($dataArray)
        ]);
    }
    
    public function getPayOrderQuery($dataArray){
        
        $orderFiled = "(`id`, `activity_id`, `activity_fee_id`, `trade_no`, `trade_amt`, `payment_type`, `expire_date`,     `trade_date` ,`payment_date`, `rtn_code`, `rtn_msg` ,`handling`)";
        $orderValue = "(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $orderData = [
            $dataArray["order_id"],
            $dataArray["activityID"],
            $dataArray["activity_fee_id"],
            $dataArray["trade_no"],
            $dataArray["trade_amt"],
            $dataArray["payment_type"],
            $dataArray["expire_date"],
            $dataArray["trade_date"],
            $dataArray["payment_date"],
            $dataArray["rtn_code"],
            $dataArray["rtn_msg"],
            $dataArray["handling"]
        ];

        return[
            "sql"  => "INSERT INTO `order` {$orderFiled} VALUES {$orderValue};",
            "data" => $orderData
        ];
    }

}
