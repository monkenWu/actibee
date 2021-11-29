<?php

class Resource_xlsx_model extends DataBaseError{

    private $alpha = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];

    public function __construct(){
        parent::__construct();
    }

	//DB操作手冊
	//https://codeigniter.org.tw/user_guide/database/active_record.html
    public function __destruct() {
        $this->db->close();
    }

    public function getColName($index){
        $index--;
        $nAlphabets = 26;
        $f = floor($index/pow($nAlphabets,0)) % $nAlphabets;
        $s = (floor($index/pow($nAlphabets,1)) % $nAlphabets)-1;
        $t = (floor($index/pow($nAlphabets,2)) % $nAlphabets)-1;
        
        $f = $f < 0 ? '' : $this->alpha[$f];
        $s = $s < 0 ? '' : $this->alpha[$s];
        $t = $t < 0 ? '' : $this->alpha[$t];
        
        return trim("{$t}{$s}{$f}");
    }
    
}
