<?php
class Infrastructure extends CI_Controller {

	//false=未登入 true=已登入
	private $login;
	//private $menu;
	//private $permissionKey;
	private $memberName;
	private $memberId;
	private $memberImg;
	private $schoolName;
	private $schoolId;
	private $backReCAPTCHA = "6LfyJMIUAAAAACET5iFHAY98h32mm-TsOhUsMVRh";
	private $viewReCAPTCHA = "6LfyJMIUAAAAAJmkQ_bxAE4KW5kjFdeOdz-aSayW";
	private $userfile_path = USERFILE_PATH;

	public function __construct(){
		parent::__construct();
		$this->startSession();
		session_write_close();
		header("X-Frame-Options: DENY");
		$this->login =  isset($_SESSION['memberId']);
		if($this->login){
			$this->schoolId = $_SESSION['schoolId'];
			$this->memberId = $_SESSION['memberId'];
			$this->schoolName = $_SESSION['schoolName'];
			$this->memberName = $_SESSION['memberName'];
			$this->memberImg = $_SESSION['memberImg'];
		}
		$this->load->model('Infrastructure_model');
	}

	public function startSession(){
		if(ENVIRONMENT == "development"){
			$expire = 8*3600;
		}else{
			$expire = 8*3600;
		}
		//如果$expire設定為0的話以PHP.INI裡設定的時間為主
		if ($expire == 0){
			$expire = ini_get('session.gc_maxlifetime');
		}else{
			ini_set('session.gc_maxlifetime', $expire);
		}
		//如果$_COOKIE['PHPSESSID']不存在,在指定時間內SESSION過期
		if (empty($_COOKIE['PHPSESSID'])) {
			session_set_cookie_params($expire);
			session_start();
		} else {
			//如果$_COOKIE['PHPSESSID']存在
			session_start();
			setcookie('PHPSESSID', session_id(), time() + $expire);
		}
	}
		
	/**
	 * 回傳是否擁有登入狀態
	 * @return Boolean
	 */
	public function getLogin(){
		return $this->login;
	}

	/**
	 * 回傳用戶主鍵
	 * @return Int
	 */
	public function getMemberId(){
		return $this->memberId;
	}

	/**
	 * 回傳用戶名稱
	 * @return String
	 */
	public function getMemberName(){
		return $this->memberName;
	}

	/**
	 * 回傳校園主鍵
	 * @return String
	 */
	public function getSchoolId(){
		return $this->schoolId;
	}

	/**
	 * 回傳校園名稱
	 * @return String
	 */
	public function getSchoolName(){
		return $this->schoolName;
	}

	/**
	 * 取得使用者大頭貼連結
	 * @return String
	 */
	public function getMemberImgHerf(){
		return base_url("admin/api/account/photo/{$this->memberImg}");
	}

	/**
	 * 取得使用者大頭貼檔案名稱
	 * @return String
	 */
	public function getMemberImg(){
		return $this->memberImg;
	}

	/**
	 * 主鍵解密
	 * @param  String $encode 原始key
	 * @param  String $keyName key原table名稱
	 * @return Atring key原始碼
	 */
	public function getIdDecode($encode,$tableName){
		if($result = $this->Infrastructure_model->getIdDecode($encode,$tableName)){
			if($result->num_rows() == 0 ){
				echo json_encode(["status"=>0,"data"=>[
					"msg"=>"KEY_ID驗證失敗這並非有效的請求。"]
				]);
				exit();
			}else{
				return $result->row()->id;
			}
		}else{
			if(ENVIRONMENT == "development"){
				echo $this->Infrastructure_model->getDataBaseMsg();
			}else{
				echo json_encode(["status"=>0,"data"=>[
					"msg"=>"伺服器處理出現錯誤，請重新整理或再試一次，若重複出現此錯誤請洽系統管理員。"]
				]);
			}
			exit();
			return false;
		}
	}

	/**
	 * 取得Google驗證前端token
	 * @return String
	 */
	public function getViewReCAPTCHA(){
		return $this->viewReCAPTCHA;
	}

	/**
	 * 與Google確定使用者是否為機器人
	 * @param  String,String
	 * @return Array
	 */
	public function runCaptcha($token){
		$url = "https://www.google.com/recaptcha/api/siteverify";

        $context = stream_context_create(['http' =>
            [
                'method'  => 'POST',
                'header'  => 'Content-Type: application/x-www-form-urlencoded',
                'content' => http_build_query([
                    'secret' => $this->backReCAPTCHA,
                    'response' => $token
                ])
            ]
        ]);

        $response = file_get_contents($url,false,$context);
		$responseKeys = json_decode($response, true);
		$returnData = [];
		if($responseKeys['success']){
            if($responseKeys['score'] >= 0.5) { 
				$returnData["status"] = 1;
				$returnData["data"]["msg"] = "認證成功！";
            }else{
				$returnData["status"] = 0;
				$returnData["data"]["msg"] = "reCaptcha驗證失敗請重新再試。";
            }
        }else{
			if($responseKeys['error-codes'][0] == "timeout-or-duplicate"){
				$returnData["status"] = 2;
				$returnData["data"]["msg"] = "reCaptcha驗證認證已失效。";
			}else{
				$returnData["status"] = 3;
				$returnData["data"]["msg"] = "reCaptcha驗證認證伺服器發生問題，請重試一次，若重複出現錯誤，請聯絡網站管理員。";
			}
		}
		return $returnData;
	}

	/**
	 * 回傳隨機字串，並將字串存入Session之中
	 * @return String
	 */
	public function getNewToken($name){
		$this->startSession();
		if(isset($_SESSION["{$name}Token"])){
			unset($_SESSION["{$name}Token"]);
		}
        $key = '';
        $word = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $len = strlen($word);
        for ($i = 0; $i < 20; $i++) {
            $key .= $word[rand() % $len];
		}
		$_SESSION["{$name}Token"] = $key;
        return $key;
	}
	
	/**
	 * 判斷從view傳遞來的token是否是系統所發給的tonken
	 * @param  String $name 傳入Controller名稱
	 * @param  String $token 傳入由View傳遞來的token
	 * @return Boolean 驗證成功為true反之為false
	 */
	public function verifyToken($name,$token) {
		if(!isset($_SESSION["{$name}Token"])){
			return false;
		}else if($_SESSION["{$name}Token"] != $token ){
			return false;
		}else{
			$this->startSession();
			unset($_SESSION["{$name}Token"]);
			session_write_close();
       		return true;
		}
	}

	/**
	 * 判斷傳入字串是否含有特殊字元
	 * @param String $str 傳入待驗證字串
	 * @return Boolean 包含回傳true不包含為false
	 */
	public function isSpecialChar($str){
		if(!preg_match('/^['.PERMITTED_URL_CHARS.']+$/i'.(UTF8_ENABLED ? 'u' : ''), $str)){
			return true;
		}else{
			if($str == "success"){
				return true;
			}
			return false;
		}
	}
	
	/**
	 * 判斷傳入陣列的元素是否為校驗的元素
	 * @param  Array $postArray 待驗證的陣列
	 * @param  Array $verifyArray 驗證集
	 * @return Boolean
	 */
	public function verifyIndex($postArray,$verifyArray){
		foreach ($verifyArray as $key => $value) {
			if(!isset($postArray[$value])) return false;
		}
		return true;
    }

	/**
	 * 替傳入陣列消毒，回傳消毒完成的結果
	 * @param  Array $array 傳入待消毒的陣列，陣列的元素必須為字串
	 * @param  Array $exception 傳入不需消毒的索引名稱
	 * @return Array
	 */
	public function xss(array $array,$exception = []){
		foreach ($array as $key => &$value){
			if(in_array($key, $exception)){
				continue;
			}
			if(is_array($value)){
				$value = $this->xss($value);
			}else{
				if(!is_bool($value)){
					$value = $this->security->xss_clean($value);
				}
			}
		}
		return $array;
	}

	/**
	 * 將傳入的json進行字串處理後，轉換為陣列回傳。
	 * @param  String $jsonText 傳入的Json字串
	 * @return Array
	 */
	public function jsonDecodeFilter($jsonText){
		return json_decode(filter_var($jsonText, FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW),true);
	}

	/**
	 * 確認這筆資料是否重複
	 * @param  String $table 資料庫中的表名稱
	 * @param  String $failed 待檢查的欄位
	 * @param  String $value 待檢查的值
	 * @param  Array  $where [可選]檢查更多條件 [["filed"=>"","value"=>""],[]...]
	 * @param  Array  $join  [可選]檢查更多條件 [["table"=>"","on"=>""],[]...]
	 * @return mix 若存在回傳撈取的資料，若不存在則回傳false
	 */
	public function isRepeated($table,$failed,$value,$where=[],$join=[]){
		$result = $this->Infrastructure_model->isRepeated($table,$failed,$value,$where,$join);
		if($result){
			return $result->num_rows() > 0 ? $result : false;
		}else{
			if(ENVIRONMENT == "development"){
				echo $this->Infrastructure_model->getDataBaseMsg();
			}else{
				echo json_encode(["status"=>0,"data"=>[
					"msg"=>"伺服器處理出現錯誤，請重新整理或再試一次，若重複出現此錯誤請洽系統管理員。"]
				]);
			}
			exit();
			return false;
		}
	}

	/**
	 * 刪除任意資料庫資料
	 * @param  String $table 資料庫中的表名稱
	 * @param  Array $where 待檢查的欄位 [["filed"=>"","value"=>""],[]...]
	 * @return mix 若存在回傳撈取的資料，若不存在則回傳false
	 */
	public function deleteDBData($table,$where){
		$result = $this->Infrastructure_model->deleteDBData($table,$where);
		return $result ? true : false;
	}

	/**
	 * sendEmail
	 * @param  Array $sendSet 設定值陣列["to","subject","template","body"]
	 * @return Boolean 成功為true
	 */
	public function sendEmail(array $sendSet){
		if(ENVIRONMENT == "development"){
			$sendSet["subject"] = "【開發環境】{$sendSet["subject"]}";
		}else if(ENVIRONMENT == "testing"){
			$sendSet["subject"] = "【測試環境】{$sendSet["subject"]}";
		}
		$sendSet["token"] = EMAIL_KEY;
		$this->post_async(base_url("email"),["data"=>json_encode($sendSet,JSON_UNESCAPED_UNICODE)]);
		return true;
	}

	/**
	 * 異步POST發送(隨發及棄用請求)
	 * @param  string $url 發送網址
	 * @param  Array $params POST內容
	 * @return Boolean 成功為true
	 */
	public function post_async($url, $params, $type="POST"){
		// Build POST string
		foreach ($params as $key => &$val) {
			if (is_array($val)) $val = implode(',', $val);
			$post_params[] = $key.'='.urlencode($val);
		}

		$post_string = implode('&', $post_params);
	
		// Connect to server
		$parts=parse_url($url);
		if(ENVIRONMENT == "development"){}
		$fp = fsockopen('127.0.0.1', 80, $errno, $errstr, 30);

		// Build HTTP query             
		$out = "$type ".$parts['path']." HTTP/1.1\r\n";
		$out.= "Host: 127.0.0.1\r\n";
		$out.= "Content-Type: application/x-www-form-urlencoded\r\n";
		$out.= "Content-Length: ".strlen($post_string)."\r\n";
		$out.= "Connection: Close\r\n\r\n";
		$out.= $post_string;

		// Send data and close the connection
		fwrite($fp, $out);
		fclose($fp);
	}

	/**
	 * 初始化view
	 * @param  String,Array
	 * @return Array
	 */
	public function initView($entryName,$data = array()){
		$this->load->view($entryName);
		$exp = explode("/",$entryName);
		if(count($exp)>1){
			eval("\$entryName = new {$exp[count($exp)-1]}(\$data);");
		}else{
			eval("\$entryName = new {$entryName}(\$data);");
		}
		$entryName->index();
		return true;
	}

	/**
	 * 回傳View初始化內容
	 * @return Array
	 */
	public function getTransferData(){
		$data = array();
		$data["memberName"] = $this->getMemberName();
		return $data;
	}

	/**
	 * 傳入相對位置與檔案名稱後，將會與伺服器設定檔中的絕對位置進行拼接後回傳。
	 * @param  String 專案資料夾下的子路徑名
	 * @param  String 檔案儲存的名稱
	 * @return String 實際路徑
	 */
	public function getFilePath($floder,$childPath,$fileName){
        //return $this->uploadAddr.$type.'\\'.$item.'\\';
        return "{$this->userfile_path}/$floder/{$childPath}/{$fileName}";
	}

	/**
	 * 傳入售價與付費方案，回傳總共需要的手續費。
	 * @param  Int 售價
	 * @param  String 手續費
	 * @return Int 手續費
	 */
	public function getHandling($fee,$type){
		if(strpos($type,"CVS") !== false){
			return 30;
        }else if (strpos($type,"ATM") !== false){
			if($fee<1000) return 10;
			return ceil($fee*0.01);
		}else {
			if(ceil($fee*0.0275)<=5) return 5;
			return ceil($fee*0.0275);
		}
		return false;
	}
	
	/**
	 * 重新載入會員的資料進Session中。
	 */
	public function reloadMemberInfo(){
		$member_id = $this->getIdDecode($this->memberId,"member");
		$result = $this->Infrastructure_model->getMemberData($member_id);
		if($result){
			$this->startSession();
			$_SESSION['memberId'] = $result->memebr_id;
			$_SESSION['schoolId'] = $result->school_id;
			$_SESSION['schoolName'] = $result->school_name;
			$_SESSION['memberName'] = $result->member_name;
			$_SESSION['memberImg'] = $result->member_img;
		}else{
			echo $this->Infrastructure_model->getDataBaseMsg();
			exit();
		}
    }

}

?>