<?php

class Signup_model extends DataBaseError{

	//DB操作手冊
	//https://codeigniter.org.tw/user_guide/database/active_record.html
    public function __destruct() {  
        $this->db->close();  
    }

    public function getSchoolInfo(){
        $this->db
            ->select("CONCAT(N'sc' , id) AS `serial` , name, mail")
            ->from("school");
        if($result = $this->dbGetCatch()){
            return $result->result();
        }else{
            return false;
        }
    }

    public function getEmail($dataArray){
        $this->db
            ->select("mail,id")
            ->from("school")
            ->where("CONCAT(N'sc' , id) =",$dataArray["school"]);
        if($result = $this->dbGetCatch()){
            if($result->num_rows() > 0){
                return $result->row();
            }
            return false;
        }else{
            return false;
        }
    }

    public function doSignup($dataArray){
        $this->db
            ->set("school_id", $dataArray["schoolID"])
            ->set("account", $dataArray["stuNum"])
            ->set("password", MD5($dataArray["password"]))
            ->set("name", $dataArray["nickname"]);
        if($result = $this->dbInsertCatch("member")){
            return $result;
        }else{
            return false;
        }
    }

    public function setVerifyToken($newMemberID){
        $nowTime = time();
        $token = md5("{$newMemberID},{$nowTime}");
        $this->db
            ->set("member_id",$newMemberID)
            ->set("token",$token);
        if($result = $this->dbInsertCatch("member_verify")){
            return $token;
        }else{
            return false;
        }
    }

    public function verifyToken($token){
        $this->db
            ->select("*")
            ->from("member_verify")
            ->where("token",$token)
            ->where("time = ",null);
        if($result = $this->dbGetCatch()){
            return $result;
        }else{
            return false;
        } 
    }

    public function setVerifyTime($token){
        $this->db
            ->where('token',$token);
        $setData = array(
            "time" => date("Y-m-d H:i:s")
        );
        if($result = $this->dbUpdateCatch('member_verify',$setData)){
            return true;
        }else{
            return false;
        }
    }

}
