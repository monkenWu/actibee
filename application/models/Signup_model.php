<?php

class Signup_model extends DataBaseError{

	//DB操作手冊
	//https://codeigniter.org.tw/user_guide/database/active_record.html
    public function __destruct() {  
        $this->db->close();  
    }


}
