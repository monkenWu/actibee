<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	/**
	* Light_Datatables
	*
	* 基於CodeIgniter3的jQuery Datatables資料串接library。
	* 可以滿足基本的Datatables與PHP串接之需求，並且保持輕量簡單的程式碼。
	* @package    CodeIgniter
	* @subpackage libraries
	* @category   library
	* @version    1.2
	* @author    mohaiWU <stp92088@gmail.com>
	* @link       https://github.com/mohaiWu/Light_Datatables
	*        
	*/
	class Light_datatables{
		public 	$ci;
		private $order_column = array();
		private $like_column = array();
		private $preset_order = array();
		private $output_column = array();
		private $output_extra = array();
		private $output_function = NULL;
		private $output_case = false;
	    public function __construct(){
	    	//使$ci參考CodeIgniter物件，並載入database函數
	    	$this->ci =& get_instance();
	    	$this->ci->load->database();
	    	$this->ci->db->start_cache();
	    }
	    /**
	     * 初始化變數內容
	     */
	    private function initialization(){
	    	$this->order_column = array();
			$this->like_column = array();
			$this->preset_order = array();
			$this->output_column = array();
			$this->output_extra = array();
			$this->output_function = NULL;
			$this->output_case = false;
	    }
	    /**
	     * 設定所要排序、比對的項目
	     *
	     * @param array $columns
	     * @return mixed
	     */
	    public function set_querycolumn(array $order , array $like){
	    	$this->order_column = $order;
	    	$this->like_column = $like;
	    	return $this;
	    }
	    /**
	     * 設定預設排序項目
	     * 
	     * @param string $item
	     * @param string $type
	     * @return mixed
	     */
	    public function order_by($item,$type){
	    	$this->preset_order = array($item, $type);
	    	return $this;
	    }
	    /**
	     * 設定實際輸出的序列與額外合成項目
	     *
	     * @param array $columns
	     * @param array $extra
	     * @param anonymous function $functions
	     * @param boolean $case
	     * @return mixed
	     */
	    public function set_output(array $column , array $extra = [] , $functions = null ,$case = false){
	    	$this->output_column = $column;
	    	$this->output_extra = $extra;
	    	if(!is_null($functions)){
	    		$this->output_function = $functions;
	    		if($case){
		    		$this->output_case = true;
		    	}
	    	}
	    	return $this;
	    }
	    /**
	     * 創建or_like語句
	     *
	     * @param array $like_column
	     * @param string $value
	     * @return string
	     */
	    private function make_or_like(array $like_column,$value){
	        $where = "(";
	        for($k=0;$k<count($like_column);$k++){
	            if($k==0){
	                $where .= $like_column[$k];
	                $where .= " LIKE '%".$value."%'";
	            }else{
	                $where .= " OR ";
	                $where .= $like_column[$k];
	                $where .= " LIKE '%".$value."%'";
	            }
	            if($k == count($like_column)-1){
	                $where .= ")";
	            }
	        }
	        return $where;
	    }
	    /**
	     * 設定like語句
	     *
	     * @param string $value
	     */
	    private function set_like($value){
	    	$likeCount = count($this->like_column);
	    	if($likeCount>0){
	            $where = $this->make_or_like($this->like_column,$value);
	            $this->ci->db->where($where, NULL, FALSE);
	    	}
	    }
	    /**
	     * 查詢是否有有排序或搜索的要求
	     */
	    private function extra_config(){
	        if(!empty($_POST["search"]["value"])){  
	            $this->set_like($_POST["search"]["value"]);
	        }
	        if(isset($_POST["order"])){  
	            $this->ci->db->order_by($this->order_column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);  
	        }else{  
	            $this->ci->db->order_by($this->preset_order[0], $this->preset_order[1]);  
	        }
	    }
	    /**
	     * 執行查詢
	     * 在此停止ci->db類別紀錄規則
	     * @return  array
	     */
	    private function get_result(){
	    	$this->ci->db->stop_cache();
	    	$this->extra_config();
	    	if(isset($_POST["length"])){
	    		if($_POST["length"] != -1) {  
					$this->ci->db->limit($_POST['length'], $_POST['start']);  
				}
	    	}
			$query = $this->ci->db->get();
			return $query;
	    }
	    /**
	     * 搜索總筆數
	     */
	    private function get_filtered(){
	    	$this->extra_config();
	        $query = $this->ci->db->get();  
	        return $query->num_rows(); 
	    }
	    /**
	     * 總筆數
	     */
	    private function get_total(){
	    	$this->extra_config();
	        return $this->ci->db->count_all_results();  
	    }
	    /**
	     * 合成每列資料的內容，包含[extra]之判斷與串接
	     * 
	 	 * @param array $row
	     * @return array     
	     */
	    private function get_output_data($row){
	    	$sub_array = array();
	    	$column_count = count($this->output_column);
			$extraNum = 0;
			$getThisRow = function ($name) use ($row) {
				return $row[$name];
			};
	    	for($i=0;$i<$column_count;$i++){
	    		if(preg_match("/\[extra\]/i", $this->output_column[$i])){
	    			$str_sec = explode("[extra]",$this->output_column[$i]);
	    			$str_out = "";
	    			for($j=0;$j<count($str_sec);$j++){
			            if($j+1 != count($str_sec)){
			            	if(!is_null($this->output_function)){
			            		$this_on_function = $this->output_function;
			            		if($this->output_case){
				            		$callback = $this_on_function($row[$this->output_extra[$extraNum]],($extraNum+1),$getThisRow);
			            		}else{
				            		$callback = $this_on_function($row[$this->output_extra[$extraNum]]);
			            		}
			            		$str_out .= $str_sec[$j].$callback;
					    	}else{
					    		$str_out .= $str_sec[$j].$row[$this->output_extra[$extraNum]];
					    	}
			                $extraNum++;
			            }else{
			                $str_out .= $str_sec[$j];
			            }
			        }
			        $sub_array[] = $str_out;
	    		}else{
	    			$sub_array[] = $row[$this->output_column[$i]];
	    		}
	    	}
	    	return $sub_array;
		}
		
		public function getDataBaseError(){
			$db_error = $this->ci->db->error();
			$data['status'] = "dbErr";
			$data['msg'] = $db_error['message'];
			$data['code'] = $db_error['code'];
			$data['sql'] = $this->ci->db->last_query();
			return json_encode($data);			
		}

	    /**
	     * 取得完整的Datatable Json字串
	     * @return string
	     */
	    public function get_datatable(){
			if($result = $this->get_result()){
				$data = array();
				foreach ($result->result_array() as $row){
					$data[] = $this->get_output_data($row);
				}
				$output = array(
					"recordsTotal" => $this->get_total(),
					"recordsFiltered" => $this->get_filtered(),
					"data" => $data
				);
				$this->initialization();
				return json_encode($output);
			}else{
				return $this->getDataBaseError();
			}
	    }
	}
?>