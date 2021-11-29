<?php

class Infrastructure_model extends DataBaseError{

    public function __construct(){
        parent::__construct();
    }

	//DB操作手冊
	//https://codeigniter.org.tw/user_guide/database/active_record.html
    public function __destruct() {
        $this->db->close();
    }
    
    function getIdDecode($encode,$tableName){
        $this->db
            ->select('`id`')
            ->from($tableName)
            ->where("SHA1(CONCAT(N'actibee' , id)) =",$encode);
        if($result = $this->dbGetCatch()){
            return $result;
        }else{
            return false;
        } 
    }

    function isRepeated($table,$failed,$value,$where = [],$join = []){
        $this->db->select("*");
        $this->db->from($table);
        $this->db->where("`{$failed}`",$value);
        foreach ($where as $key => $value) {
            $this->db->where($value['filed'],$value['value']);
        }
        foreach ($join as $key => $value) {
            $this->db->join($value['table'],$value['on']);
        }
        if($result = $this->dbGetCatch()){
            return $result;
        }else{
            return false;
        } 
    }

    function deleteDBData($form,$where){
        foreach ($where as $key => $value) {
            $this->db->where($value['filed'],$value['value']);
        }
        if($result = $this->dbDeleteCatch($form)){
            return true;
        }else{
            return false;
        }
    }

    function getMemberData($memebr_id){
        $this->db
            ->select("
                SHA1(CONCAT(N'actibee' , member.id)) as `memebr_id`,
                SHA1(school_id) as `school_id`,
                member.`name` as `member_name`,
                member.`img`  as `member_img`,
                school.`name` as `school_name`")
            ->from('member')
            ->join('school','school.id = member.school_id')
            ->where('member.id = ',$memebr_id);
        if($result = $this->dbGetCatch()){
            return $result->row();
        }else{
            return false;
        }
    }

}
