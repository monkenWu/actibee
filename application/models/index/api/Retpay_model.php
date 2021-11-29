<?php

class Retpay_model extends DataBaseError{

	//DB操作手冊
	//https://codeigniter.org.tw/user_guide/database/active_record.html
    public function __destruct() {  
        $this->db->close();  
    }

    public function updateOrder($data){
        return $this->startTransaction([
            $this->getUpdateOrder($data)
        ]);
    }

    public function getUpdateOrder($data){

        $orderFiled = "payment_date = ? , rtn_code = ? , rtn_msg = ?";
        $orderData = [
            $data["payment_date"],
            $data["rtn_code"],
            $data["rtn_msg"]
        ];

        return[
            "sql" => "UPDATE `order` SET {$orderFiled} WHERE `id` = '{$data["id"]}' ;",
            "data" => $orderData
        ];
    }

}
