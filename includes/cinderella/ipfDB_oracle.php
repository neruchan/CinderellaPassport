<?PHP
/*! @brief ipfDBクラス

	ipfDBクラスは共通機能クラスです。

	@package	ipf.database.class
	@access	public
	@author Naoto Imai <imai@auris.ne.jp>
	@date $Id: ipfDB.php, v 1.0 2009/12/11 Naoto Imai Exp $
*/
class ipfDB{

	var $conn;		///接続ID
	var $INI_DATA;	///初期設定データ

	/** 
	* コンストラクタ
	* 
	* @access	public
	* @param	なし
	*/
	function __construct() {
		$this->INI_DATA = parse_ini_file("mrhns-net/ipf.ini");
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
			global $$tableClass;
			$$tableClass = new $tableClass;
		}
	}
	
	/** 
	* DB接続
	* 
	* @access	public
	* @return	object	$db	接続ID
	*/
	private function connect(){
		$dateTime = date("Y-m-d H:i:s");
		$this->conn = oci_pconnect($this->INI_DATA['database_user'], $this->INI_DATA['database_password'], ($this->INI_DATA['database_host'] ? $this->INI_DATA['database_host']."/" : "").$this->INI_DATA['database_name']);
		if(!$this->conn){
			$this->dbError("$dateTime <connect error> $this->conn");
		}
	}

	/** 
	* DB切断
	* 
	* @access	public
	* @return	boolean	$dbm	成功(true)、失敗(false)
	*/
	private function disconnect(){
		$dbm = oci_close($this->conn);
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
			array_push($sql_fields, $fieldName);
			array_push($sql_values, "'$value'");
		}
		$sql_fields_str = join(", ", $sql_fields);
		$sql_values_str = join(", ", $sql_values);

		if($this->INI_DATA['database_schema'])
			$schemaTable = $this->INI_DATA['database_schema'] . "." . $tableName;
		else
			$schemaTable = $tableName;

		$sql = "
			INSERT INTO $schemaTable (
				$sql_fields_str
			)
			VALUES (
				$sql_values_str
			)
		";
        $parse = oci_parse($this->conn, $sql);
        if (@oci_execute($parse, OCI_DEFAULT)) {
            oci_commit($this->conn);
        }
        else {
			$this->dbError("$dateTime <insert error> $sql");
            oci_rollback($this->conn);
        }
        oci_free_statement($parse);
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
			if($value == "null")
				array_push($sql,"$fieldName = $value");
			else
				array_push($sql,"$fieldName = '$value'");
		}
		$sql2 = join(", ",$sql);
		$sql = "";
		if(is_array($where)){			
			foreach($where as $tbl => $whr){
				array_push($sql,"$fieldName = '$value'");
				if($tableName==$tbl){

					if($this->INI_DATA['database_schema'])
						$schemaTable = $this->INI_DATA['database_schema'] . "." . $tableName;
					else
						$schemaTable = $tableName;

					$sql = "
						UPDATE $schemaTable SET
							$sql2
						WHERE
							$whr
					";
					break;
				}
			}
			/*
			//修正 start-----------------------------------------
			$whr = array();
			foreach($where[$tableName] as $wfieldName => $wvalue){
				array_push($whr,"$wfieldName = '$wvalue'");
			}
			$whr2 = join(" AND ",$whr);

			if($this->INI_DATA['database_schema'])
				$schemaTable = $this->INI_DATA['database_schema'] . "." . $tableName;
			else
				$schemaTable = $tableName;

			$sql = "
				UPDATE $schemaTable SET
					$sql2
				WHERE
					$whr2
			";
			//修正 end-----------------------------------------
			*/
		}
		else{

			if($this->INI_DATA['database_schema'])
				$schemaTable = $this->INI_DATA['database_schema'] . "." . $tableName;
			else
				$schemaTable = $tableName;

			$sql = "
				UPDATE $schemaTable SET
					$sql2
				WHERE
					$where
			";
		}

        $parse = oci_parse($this->conn, $sql);
        if (@oci_execute($parse, OCI_DEFAULT)) {
            oci_commit($this->conn);
        }
        else {
			$this->dbError("$dateTime <update error> $sql");
            oci_rollback($this->conn);
        }
        oci_free_statement($parse);
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

		if($this->INI_DATA['database_schema'])
			$schemaTable = $this->INI_DATA['database_schema'] . "." . $tableName;
		else
			$schemaTable = $tableName;

		//$sql = "UPDATE $schemaTable SET $tableName\_del_flag = 1, $tableName\_del_date = \'$dateTime\' WHERE $tableName\_id = $delId";
		$sql = "UPDATE $schemaTable SET ${tableName}_del_flg = 1, ${tableName}_up_date = '$dateTime' WHERE ${tableName}_id = $delId";
        $parse = oci_parse($this->conn, $sql);
        if (@oci_execute($parse, OCI_DEFAULT)) {
            oci_commit($this->conn);
        }
        else {
			$this->dbError("$dateTime <delete error> $sql");
            oci_rollback($this->conn);
        }
        oci_free_statement($parse);
	}

	/** 
	* 抽出クエリ実行（select用）
	* 
	* @access	public
	* @param	String	$tableName	テーブル名
	* @param	String	$where	WHERE条件の文字列
	* @return	Array	$data	データベースからの値を配列で返す
	*/
	function query($sql){
		$dateTime = date("Y-m-d H:i:s");
		$stmt = oci_parse($this->conn, $sql);
        $result = oci_execute($stmt);

		if(!$result){
			$this->dbError("$dateTime <unknown sql error> $sql");
		}
		else{
			$data = array();
			while($one = oci_fetch_array($stmt, OCI_ASSOC)){
				array_push($data, $one);
			}
		}
		oci_free_statement($stmt);
		return $data;
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
				$idNum = $$key->insert($key, $param);
				return $idNum;
			}
			elseif($type == "update"){
				$affected_rows = $$key->update($key, $param);
				return $affected_rows;
			}
			elseif($type == "delete"){
				$affected_rows = $$key->delete($key, $param);
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