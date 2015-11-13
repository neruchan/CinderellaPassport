<?
/**
 * ipfMemクラス
 *
 * @access	public
 * @author	Reiun Ni <reiun@social-net.jp>
 * @create	2010/12/01
 * @version	$Id: ipfMem.php, v 1.0 2010/12/01 Reiun Ni Exp $
 **/
class ipfMem{
	var $conn;		///接続ID

	/** 
	* コンストラクタ
	* 
	* @access	public
	* @param	なし
	*/
	function __construct() {
		$this->conn = new Memcache;
		$this->conn->connect('127.0.0.1', 11211);
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
	* メモリキャシューを取得
	* 
	* @access	public
	* @param	String	$mem_key	メモリキャシュー取得ｷｰﾜｰﾄﾞ
	*/
	function get($mem_key){
		return $this->conn->get($mem_key);
	}

	/** 
	* メモリキャシューに書き込み
	* 
	* @access	public
	* @param	String	$mem_key	メモリキャシュー取得ｷｰﾜｰﾄﾞ
	*/
	function set($mem_key, $value){
		$this->conn->set($mem_key, $value);
	}
}
?>