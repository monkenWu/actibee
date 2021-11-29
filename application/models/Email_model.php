<?php

class Email_model extends DataBaseError{

    public function __construct(){
        parent::__construct();
    }

	//DB操作手冊
	//https://codeigniter.org.tw/user_guide/database/active_record.html
    public function __destruct() {
        $this->db->close();
    }
    

}
