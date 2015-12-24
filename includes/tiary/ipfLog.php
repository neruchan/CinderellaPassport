<?PHP
/*! @brief ipfLogクラス

	ipfLogクラスは共通クラスです。

	@package	ipf.ipfLog.class
	@access	public
*/
class ipfLog{

	var $INI_DATA;	///初期設定データ

	/**
	* コンストラクタ
	*
	* @access	public
	* @param	なし
	*/
	function __construct() {
		$this->INI_DATA = parse_ini_file("startyfree/ipf.ini");
	}
	/**
	* デストラクタ
	*
	* @access	public
	* @param	なし
	*/
	function __destruct() {
	}

	/**
	* アプリログを出力。
	*
	* @access	public
	* @param	$message ログ本文
	*/
	function appLog($message) {
		file_put_contents($this->INI_DATA[app_log_file], "$message\n", FILE_APPEND);
	}


	/**
	* 例外エラーログを出力。
	*
	* @access	public
	* @param	$viewid 画面ＩＤ
	* @param	$msg    メッセージ
	*/
	function write_trace_log($viewid = NULL,$uid = NULL, $msg = NULL) {

	    $now = date("Y/m/d H:i:s (D)", time());
	    //$server_name = $_SERVER['SERVER_NAME'];            //$_SERVER["SERVER_NAME"]
	    $ip_addr = $_SERVER["REMOTE_ADDR"];
	    $script_name = $_SERVER['SCRIPT_NAME'];

	    $log_data = $now.'	'.$script_name.'	'.$ip_addr.'	'.$viewid.'	'.$uid.'	'.$msg."\n";

	    //日付単位ログ出力
	    $today = date("Y/m/d");
	    $tdy = "applog_".substr( $today, 0, 4 );
	    $tdy .= substr( $today, 5, 2 );
	    $tdy .= substr( $today, 8, 2 ).".log";
	    $today_app_log_file = $this->INI_DATA[today_log_path];
	    $today_app_log_file .= $tdy;
	    file_put_contents("$today_app_log_file", "$log_data", FILE_APPEND);

	}

	/**
	* カードエラーログを出力。
	*
	* @access	public
	* @param	$gold_id  GMO会員ID
	* @param	$error_cd エラーコードまたは、登録、更新
	*/
	function write_card_trace_log($gold_id = NULL,$error_cd = NULL) {

	    $now = date("Y/m/d H:i:s (D)", time());
	    //$server_name = $_SERVER['SERVER_NAME'];            //$_SERVER["SERVER_NAME"]
	    $ip_addr = $_SERVER["REMOTE_ADDR"];
	    $script_name = $_SERVER['SCRIPT_NAME'];
	    $pid = getmypid();

		//edited by reiun on 2010/3/9
		$uid = $_SESSION['system']['uid'];

	    $log_data = $now.'	'.$script_name.'	'.$ip_addr.'	'.$pid.'	'.$uid.'	'.$gold_id.'	'.$error_cd."\n";

	    $today = date("Y/m/d");
	    $tdy = "authorilog_".substr( $today, 0, 4 );
	    $tdy .= substr( $today, 5, 2 );
	    $tdy .= substr( $today, 8, 2 ).".log";
	    $today_auth_log_file = $this->INI_DATA[today_log_path];
	    $today_auth_log_file .= $tdy;
	    file_put_contents("$today_auth_log_file", "$log_data", FILE_APPEND);
	}

	/**
	* エラーログを出力。
	*
	* @access	public
	* @param	$message ログ本文
	*/
	function errLog() {
		file_put_contents($this->INI_DATA[err_log_file], "$message\n", FILE_APPEND);
	}


	/**
	* 例外エラーログを出力。
	*
	* @access	public
	* @param	$message ログ本文
	*/
	function local_error_log($msg) {
	    $script_name = $_SERVER['SCRIPT_NAME'];
	    $msg = var_export($msg,TRUE);
	    $backtrace = var_export(debug_backtrace(),TRUE);
	    $now = date("Y/m/d H:i:s (D)", time());

	    $log_data = <<<EOF

--------Error report Start---------------------------------
>>> script
$script_name

>>> time
$now

>>> message
$msg

>>> backtrace
$backtrace

>>> end
--------Error report End---------------------------------

EOF;

	//    $log_file_path = 'sys_err.log';
	//    error_log("$log_data" , 3 , $log_file_path);
	file_put_contents($this->INI_DATA[err_log_file], "$log_data\n", FILE_APPEND);
	}

	/**
	* SQL実行ログを出力。
	*
	* @access	public
	* @param	$message ログ本文
	*/
	function sqlLog() {
	    $today = date("Y/m/d");
	    $tdy = "sqllog_".substr( $today, 0, 4 );
	    $tdy .= substr( $today, 5, 2 );
	    $tdy .= substr( $today, 8, 2 ).".log";
	    $today_sql_log_file = $this->INI_DATA[today_log_path];
	    $today_sql_log_file .= $tdy;
	    file_put_contents("$today_sql_log_file", "$log_data", FILE_APPEND);
	}
}
?>