<?php

class DataBaseError extends CI_Model{

    private $error = "";
    private $code = 0;
    private $queryCode = "";
    //為true則顯示DB文字錯誤於JSON中，若為False則寫入DB文字錯誤於LOG檔案中
    private $textShow = ENVIRONMENT == "development" ? true : false;
    private $logPath = DATABASE_LOG;

    public function __construct(){
        parent::__construct();
    }
    public function __destruct() {
       $this->db->close();
    }
    private function getDBErrorMSG(){
        return $this->error;
    }

    private function getDBErrorCode(){
        return $this->code;
    }

    private function getQueryCode(){
        return $this->queryCode;
    }

    public function startTransaction($query=[]){
        $this->db->trans_start();
        foreach ($query as $key => $value) {
            if(isset($value["manually"])){
                $this->db->query($value["sql"]);
            }else{
                $this->db->query($value["sql"],$value["data"]);
            }
        }
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE){
            $this->setError($this->db->error(),$this->db->last_query());
            $this->db->flush_cache();
            return false;
        }
        $this->db->flush_cache();
        return true;
    }

    public function dbQueryCatch($query){
        if($result = $this->db->query($query)){
            $this->db->flush_cache();
            return $result;
        }else{
            $this->setError($this->db->error(),$this->db->last_query());
            $this->db->flush_cache();
            return false;
        }
    }

    public function dbGetCatch(){
        if($result = $this->db->get()){
            $this->db->flush_cache();
            return $result;
        }else{
            $this->setError($this->db->error(),$this->db->last_query());
            $this->db->flush_cache();
            return false;
        }
    }

    public function dbInsertCatch($tableName){
        if($result = $this->db->insert($tableName)){
            if($insertKey = $this->db->insert_id()){
                $this->db->flush_cache();
                return $insertKey;
            }else{
                $this->db->flush_cache();
                return true;
            }
        }else{
            $this->setError($this->db->error(),$this->db->last_query());
            $this->db->flush_cache();
            return false;
        }
    }

    public function dbDeleteCatch($tableName){
        if($result = $this->db->delete($tableName)){
            $this->db->flush_cache();
            return $result;
        }else{
            $this->setError($this->db->error(),$this->db->last_query());
            $this->db->flush_cache();
            return false;
        }
    }

    public function dbUpdateCatch($tableName,$data){
        if($result = $this->db->update($tableName, $data)){
            $this->db->flush_cache();
            return $result;
        }else{
            $this->setError($this->db->error(),$this->db->last_query());
            $this->db->flush_cache();
            return false;
        }
    }

    public function dbInsertBatchCatch($tableName,$data){
        if($result = $this->db->insert_batch($tableName, $data)){
            $this->db->flush_cache();
            return $result;
        }else{
            $this->setError($this->db->error(),$this->db->last_query());
            $this->db->flush_cache();
            return false;
        }
    }

    private function setError($db_error,$queryCode){
        if (!empty($db_error)) {
            $this->error = $db_error['message'];
            $this->code = $db_error['code'];
        }else{
            $this->error = "none";
            $this->code = 0;
        }
        $this->queryCode = $queryCode;
    }

    private function writeLog(){
        $date = date('Y-m-d');
        $writePath = $_SERVER['DOCUMENT_ROOT'].$this->logPath."[{$date}]dataBaseErrorLog.dblog";
        $fp = fopen($writePath,"a");
        $time = date('H:i:s');
        $code = $this->getDBErrorCode();
        $msg = $this->getDBErrorMSG();
        $sql = str_replace("\r","",$this->getQueryCode());
        $sql = str_replace("\n","",$sql);
        fwrite($fp,"[{$time}][$code]{$msg} : {$sql}\r\n");
        fclose($fp);
    }

    public function getDataBaseMsg(){
        $data = array();
        $data['code'] = $this->getDBErrorCode();
        if($this->textShow){
            $data['status'] = "dbErrDev";
            $data['msg'] = $this->getDBErrorMSG();
            $data['sql'] = $this->getQueryCode();
        }else{
            $data['status'] = "dbErr";
            $this->writeLog();
        }
        return json_encode($data);
    }
}

?>