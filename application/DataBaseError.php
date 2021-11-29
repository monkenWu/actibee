<?php

class DataBaseError extends CI_Model{

    private $error = "";
    private $code = 0;
    private $queryCode = "";

    public function __construct(){
        parent::__construct();
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
            $this->error = 0;
        }
        $this->queryCode = $queryCode;
    }

    public function getDBErrorMSG(){
        return $this->error;
    }
    
    public function getDBErrorCode(){
        return $this->code;
    }

    public function getQueryCode(){
        return $this->queryCode;
    }

    public function getDBErrJson(){
        $data = array();
        $data['status'] = "dbErr";
        $data['msg'] = $this->getDBErrorMSG();
        $data['code'] = $this->getDBErrorCode();
        $data['sql'] = $this->getQueryCode();
        return json_encode($data);
    }

}

?>