<?php

class Login_model extends DataBaseError{

	//DB操作手冊
	//https://codeigniter.org.tw/user_guide/database/active_record.html
    public function __destruct() {  
        $this->db->close();  
    }

    function checkLogin($dataArray){
        $this->db->select("SHA1(CONCAT(N'actibee' , member.id)) as `memebr_id`,
                           SHA1(school_id) as `school_id`,
                           member.`name` as `member_name`,
                           member.`img`  as `member_img`,
                           school.`name` as `school_name`,
                           member.`account`");
        $this->db->from('member');
        $this->db->join('school','school.id = member.school_id');
        $this->db->where('account',$dataArray['account']);
        $this->db->where('password',$dataArray['password']);
        if($result = $this->dbGetCatch()){
            return $result;
        }else{
            return false;
        }
    }

    function checkEmailVerify($memebr_id){
        $this->db
            ->select('*')
            ->from('member_verify')
            ->where('member_id = ',$memebr_id);
        if($result = $this->dbGetCatch()){
            return $result;
        }else{
            return false;
        }
    }

    function setSession($dataObject){
        $_SESSION['memberId'] = $dataObject->memebr_id;
        $_SESSION['schoolId'] = $dataObject->school_id;
        $_SESSION['schoolName'] = $dataObject->school_name;
        $_SESSION['memberImg'] = $dataObject->member_img;
        $_SESSION['memberName'] = $dataObject->member_name;
    }

}
