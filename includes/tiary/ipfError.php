<?PHP
/*! @brief ipfErrorクラス

	ipfErrorクラスは共通クラスです。

	@package	ipf.ipfError.class
	@access	public
	@author	Naoto Imai <imai@auris.ne.jp>
	@create	2009/12/11
*/
class ipfError{

	var $INI_DATA;	///初期設定データ

	/**
	* コンストラクタ
	*
	* @access	public
	* @param	なし
	*/
	function __construct() {
		$this->INI_DATA = parse_ini_file("tiary/ipf.ini");
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
	* 一般的なエラー処理をする。
	*
	* @access	public
	* @param	array	$level	エラー処理レベル(配列で複数指定できる)
								1 : print
								2 : log
								3 : email
								4 : location
	* @param	String	$message	エラーメッセージ
	*/
	function error($levelArray, $message){
		foreach($levelArray as $level){
			if($level == 1){
				print $message;
			}
			elseif($level == 2){
				file_put_contents($this->INI_DATA[sqlerr_log_file], "$message\n", FILE_APPEND);
			}
			elseif($level == 3){
				mb_send_mail($this->INI_DATA[admin_email], "ipf error message (startyfree)", $message, "From: " . $this->INI_DATA[admin_email]);
			}
			elseif($level == 4){
				header("Location: " . $this->INI_DATA[destination]);
			}
		}
		exit;
	}

	/**
	* エラーページを出力。
	*
	* @access	public
	* @param	なし
	*/
	function toErrorPage() {
		header("Location: " . $this->INI_DATA[destination]);
	}
}
?>