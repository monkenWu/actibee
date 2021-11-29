<?php

class Account_model extends DataBaseError{

    public function __construct(){
        parent::__construct();
    }

	//DB操作手冊
	//https://codeigniter.org.tw/user_guide/database/active_record.html
    public function __destruct() {
        $this->db->close();
    }
    
    public function editProfile($dataArray){
        $this->db
            ->where("id",$dataArray["member_id"]);
        $setData = array(
            "name" => $dataArray['nickName'],
            "img" => $dataArray['img']
        );
        if($result = $this->dbUpdateCatch('member',$setData)){
            return true;
        }else{
            return false;
        }
    }

    public function editRealinfo($dataArray){
        $this->db
            ->set("member_id",$dataArray["member_id"])
            ->set("real_name",$dataArray["realName"])
            ->set("student_id",$dataArray["stuNum"])
            ->set("id_card_img",$dataArray["img"])
            ->set("file",$dataArray["fileName"])
            ->set("cellphone",$dataArray["cellphone"])
            ->set("group",$dataArray["club"])
            ->set("postion",$dataArray["job"])
            ->set("update_time",date("Y-m-d H:i:s"))
            ->set("verify",1);
        if($result = $this->dbInsertCatch("member_sensitive")){
            return true;
        }else{
            return false;
        }
    }

    public function getActivityData($member_id){
        $this->db
            ->from('activity')
            ->select("activity.id , activity.title , activity_cover.data as img")
            ->join('activity_cover','activity.id = activity_cover.activity_id')
            ->where("activity.member_id",$member_id);
        if($result = $this->dbGetCatch()){
            return $result;
        }else{
            return false;
        }
    }

    public function setWallet($dataArray){
        return $this->startTransaction([
            $this->getSetWalletQuery($dataArray),
            $this->updateActivityQuery($dataArray)
        ]);
    }

    private function getSetWalletQuery($data){

        $walletFiled = "(member_id, activity_id, code, bank, branch, address, name)";
        $walletValue = "(?, ?, ?, ?, ?, ?, ?)";
        $walletData = [
            $data["memberID"],
            $data["activityID"],
            $data["code"],
            $data["bank"],
            $data["branch"],
            $data["address"],
            $data["name"]
        ];

        return[
            "sql"  => "INSERT INTO member_wallet {$walletFiled} VALUES {$walletValue};",
            "data" => $walletData
        ];
    }

    private function updateActivityQuery($data){
        return[
            "sql" => "UPDATE `activity` SET `transfer_start_time` = '{$data["transfer_start_time"]}' WHERE `id` = '{$data["activityID"]}' ;",
            "manually" => true
        ];
    }

}
