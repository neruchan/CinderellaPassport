<?
/**
 * ipfDBクラス
 *
 * @package	itquest.scorpion.database.class
 * @access	public
 * @author	Reiun Ni <reiun@nipponwide.com>
 * @create	2008/02/08
 * @version	$Id: ipfDB.php, v 1.0 2009/12/11 Reiun Ni Exp $
 **/
class ipfDB{
	var $conn;		///接続ID
	var $INI_DATA;	///初期設定データ
	//set_include_path($_SERVER['SERVER_NAME'].'/fbv/startyfree');

	/**
	* コンストラクタ
	*
	* @access	public
	* @param	なし
	*/
	function __construct() {
		$this->INI_DATA = parse_ini_file("tiary/ipf.ini");
		$this->connect();
	}
	/**
	* デストラクタ
	*
	* @access	public
	* @param	なし
	*/
	function __destruct() {
		$this->disconnect();
	}

	/**
	* 初期設定
	*
	* @access	public
	* @param	String	$tableList	テーブル名のカンマ区切り
	*/
	function ini($tableList){
		$tableList = str_replace(" ", "", $tableList);
		$TABLELIST = explode(",", $tableList);
		foreach($TABLELIST as $tableClass){
			$file_path = $this->INI_DATA['project_name'] . "/table_class/" . $tableClass . ".php";
			require_once "$file_path";
			require_once "$file_path";
			global $$tableClass;
			$$tableClass = new $tableClass;
		}
		$this->connect();
	}

	/**
	* DB接続
	*
	* @access	public
	* @return	object	$db	接続ID
	*/
	function connect(){
		$dateTime = date("Y-m-d H:i:s");
		$this->conn = mysql_pconnect($this->INI_DATA['database_host'], $this->INI_DATA['database_user'], $this->INI_DATA['database_password']);
		mysql_query("SET NAMES 'utf8'");
		mysql_query("SET CHARACTER_SET_CLIENT=utf8");
		mysql_query("SET CHARACTER_SET_RESULTS=utf8");
		if(!$this->conn){
			$this->dbError("$dateTime <connect error> $this->conn");
		}
		else{
			$db_selected = mysql_select_db($this->INI_DATA['database_name'], $this->conn);
		}
	}

	/**
	* DB切断
	*
	* @access	public
	* @return	boolean	$dbm	成功(true)、失敗(false)
	*/
	function disconnect(){
		$dbm = mysql_close($this->conn);
		return $dbm;
	}

	/**
	* インサート
	*
	* @access	public
	* @param	String	$tableName	テーブル名
	* @return	int	$idNum	auto_incrementの値
	*/
	function insert($tableName){
		$dateTime = date("Y-m-d H:i:s");
		global $_DATA;
		$sql_fields = array();
		$sql_values = array();
		foreach($_DATA[$tableName] as $fieldName => $value){
			$escapedstring = mysql_real_escape_string($value);
			array_push($sql_fields, $fieldName);
			array_push($sql_values, "'$escapedstring'");
		}
		$sql_fields_str = join(", ", $sql_fields);
		$sql_values_str = join(", ", $sql_values);
		$sql = "
			INSERT INTO $tableName (
				$sql_fields_str
			)
			VALUES (
				$sql_values_str
			)
		";
		$result = mysql_query($sql);
		$idNum = mysql_insert_id();
		if(!$result){
			$this->dbError("$dateTime <insert error> $sql");
		}
		else{
			return $idNum;
		}
	}

	/**
	* アップデート
	*
	* @access	public
	* @param	String	$tableName	テーブル名
	* @param	String	$where	WHERE条件の文字列
	* @return	boolean	$result	成功(true)、失敗(false)
	*/
	function update($tableName, $where){
		$dateTime = date("Y-m-d H:i:s");
		global $_DATA;
		$sql = array();
		foreach($_DATA[$tableName] as $fieldName => $value){
			$escapedstring = mysql_real_escape_string($value);
			array_push($sql,"$fieldName = '$escapedstring'");
		}
		$sql2 = join(", ",$sql);
		$sql = "";
		if(is_array($where)){
			foreach($where as $tbl => $whr){
				if($tableName==$tbl){
					$sql = "
						UPDATE $tableName SET
							$sql2
						WHERE
							$whr
					";
					break;
				}
			}
		}
		else{
			$sql = "
				UPDATE $tableName SET
					$sql2
				WHERE
					$where
			";
		}
		$result = mysql_query($sql);
		$affected_rows = mysql_affected_rows();
		if(!$result){
			$this->dbError("$dateTime <update error> $sql");
		}
		else{
			$result = $affected_rows;
		}
		return $result;
	}

	/**
	* デリート(論理削除)
	*
	* @access	public
	* @param	String	$tableName	デリートするテーブル名
	* @param	String	$delId	デリートするレコードのID
	* @return	boolean	$result	成功(true)、失敗(false)
	*/
	function delete($tableName, $delId){
		$dateTime = date("Y-m-d H:i:s");
		$sql = "UPDATE $tableName SET $tableName"."_del_flag = 1, $tableName"."_del_date = '$dateTime' WHERE $tableName"."_id = $delId";
		$result = mysql_query($sql);
		$affected_rows = mysql_affected_rows();
		if(!$result){
			$this->dbError("$dateTime <delete error> $sql");
		}
		else{
			$result = $affected_rows;
		}
		return $result;
	}

	/**
	* 抽出クエリ実行（select用）
	*
	* @access	public
	* @param	String	$sql	SELECTのSQL
	* @param	String	$type	SELECT:0 その他SQL:1
	* @return	Array	$data	データベースからの値を配列で返す
	*/
	function query($sql, $type = 0){
		$dateTime = date("Y-m-d H:i:s");
		$result = mysql_query($sql);
		if(!$result){
			$this->dbError("$dateTime <unknown sql error> $sql");
		}
		else{
			if($type == 0){
				$data = array();
				while($one = mysql_fetch_array($result ,MYSQL_ASSOC)){
					array_push($data, $one);
				}
				return $data;
			}
			elseif($type == 1){
				$affected_rows = mysql_affected_rows();
				return $affected_rows;
			}
		}
	}

	/**
	* データベースエラー用
	*
	* @access	public
	* @param	String	$tableName	テーブル名
	* @param	String	$where	WHERE条件の文字列
	* @return	Array	$data	データベースからの値を配列で返す
	*/
	function dbError($message){
		$errorArray = explode(",", $this->INI_DATA['error_level']);
		require_once $this->INI_DATA['project_name'] . "/ipfError.php";
		$a = new ipfError;
		$a->error($errorArray, $message, $this->INI_DATA['destination']);
	}

	/**
	* $_DATAにPOSTまたはGETで来た値を、2次元配列でセットするためのメソッド
	* 例：<INPUT TYPE="text" NAME="hoge:hogehoge"> --> $_DATA[hoge][hogehoge]とする$_DATAはグローバル変数
	*
	* @access	public
	* @param	String	$type	"POST"又は"GET"又は"SESSION"
	* @global	array	$_DATA	POSTまたはGETまたはSESSIONで来た値の2次元配列
	*/
	function createData($type){
		global $_DATA;
		if($type == "POST"){
			$data = $_POST;
		}
		elseif($type == "GET"){
			$data = $_GET;
		}
		elseif($type == "SESSION"){
			$data = $_SESSION;
		}
		foreach($data as $key => $value){
			if(preg_match("/:/",$key)){
				list($tableName, $fieldName) = split(":",$key);
				$_DATA[$tableName][$fieldName] = $value;
			}
		}
	}

	/**
	* "INSERT"又は"UPDATE"又は"DELETE(論理削除のためipfDBではUPDATEを走らせている2008/02/12)"のクエリを実行する。
	*
	* @access	public
	* @param	String	$type	"INSERT"又は"UPDATE"又は"DELETE"
	* @param	String	$where	WHERE条件の文字列
	* @global	array	$_DATA	POSTまたはGETで来た値の2次元配列
	*/
	function dataControl($type, $param=""){
		global $_DATA;
		foreach($_DATA as $key => $value){
			global $$key;
			if($type == "insert"){
				$idNum = $this->insert($key, $param);
				return $idNum;
			}
			elseif($type == "update"){
				$affected_rows =$this->update($key, $param);
				return $affected_rows;
			}
			elseif($type == "delete"){
				$affected_rows = $this->delete($key, $param);
				return $affected_rows;
			}
		}
	}

	/**
	* SQL実行
	*
	* @access	public
	* @param	String	$sql	SQL分
	* @return	boolean	$result	成功(true)、失敗(false)
	*/
	function execute($sql){
		$dateTime = date("Y-m-d H:i:s");
        $parse = oci_parse($this->conn, $sql);
        if (@oci_execute($parse, OCI_DEFAULT)) {
            oci_commit($this->conn);
        }
        else {
			$this->dbError("$dateTime <execute error> $sql");
            oci_rollback($this->conn);
        }
        oci_free_statement($parse);
	}

}
?>