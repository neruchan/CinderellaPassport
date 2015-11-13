<?PHP
/*! @brief ipfTemplateクラス

	ipfTemplateクラス(IF無しバージョン)は共通クラスです。

	@package	ipf.ipfTemplate.class
	@access	public
	@author	Naoto Imai <imai@auris.ne.jp>
	@create	2009/12/11
	@version	$Id: ipfDB.php, v 1.0 2009/12/11 Naoto Imai Exp $
*/
/*
require_once "myvision/ipfKeitai.php";
*/
class ipfTemplate{

	//! 初期設定データ
	private $INI_DATA;

	private $cacheFile = "";

	public $MEMORY = array();

	/**
	* コンストラクタ
	*
	* @access	public
	* @param	なし
	*/
	function __construct() {
		$this->INI_DATA = parse_ini_file("modeltiary/ipf.ini");
	}
	/**
	* デストラクタ
	*
	* @access	public
	* @param	なし
	*/
	function __destruct() {
		unset($this->INI_DATA);
	}

	/**
	* キャッシュファイルのチェック・作成・表示
	*
	* @access	public
	* @param	string $FileName	キャッシュファイル名
	* @return	bool
	*/
	public function useCacheFile($cacheFileName = ""){
		if(file_exists("/tmp/ipfTemplate/$cacheFileName") && $cacheFileName != ""){
			$this->loadCacheFile($cacheFileName);
		}
		elseif($cacheFileName != ""){
			$this->cacheFile = "NO";
		}
		else{
			return false;
		}
	}

	private function loadCacheFile($cacheFileName){
		print file_get_contents("/tmp/ipfTemplate/$cacheFileName");
		exit;
	}

	private function makeCacheFile($cacheFileName, $printData){
		file_put_contents("/tmp/ipfTemplate/$cacheFileName", $printData);
	}

	/**
	* テンプレートファイルをロード
	*
	* @access	public
	* @param	string $templateFileName	テンプレートファイル
	* @return	string テンプレートデータ
	*/
	public function loadTemplate($templateFileName = ""){
		if($templateFileName != ""){
			$TEMPLATE_DATA = $this->getTemplateData($templateFileName);
		}
		else{
			$a = $_SERVER["PHP_SELF"];
			$a = basename($a, ".php");
			$a = basename($a, ".html");
			$templateFile = $a . ".template";
			$TEMPLATE_DATA = $this->getTemplateData($templateFile);
		}
		return $TEMPLATE_DATA;
	}

	/**
	* テンプレートデータを書き換え
	*
	* @access	public
	* @param	string $TEMPLATE_DATA	テンプレートデータ
	* @param	string $PRINT_VALUE		タグ#PRINTのデータ
	* @param	string $LOOP_VALUE		タグ#LOOPのデータ
	* @return	string 書き換えたテンプレートデータ
	*/
	public function makeTemplateData($TEMPLATE_DATA, $PRINT_VALUE = "", $LOOP_VALUE = ""){
		//INCLUDE
		while(preg_match_all('/<#INCLUDE [^#>]*?#>/si', $TEMPLATE_DATA, $MATCH)){
			$TEMPLATE_DATA = $this->includeReplace($TEMPLATE_DATA, $MATCH[0]);
		}
		//LOOP
		if($LOOP_VALUE != ""){
			foreach($LOOP_VALUE as $LOOP_NAME => $LOOP_VALUE2){
				if(preg_match("/<#LOOP_START $LOOP_NAME#>(.+)<#LOOP_END $LOOP_NAME#>/si", $TEMPLATE_DATA, $MATCH)){
					$tmp = "";
					foreach($LOOP_VALUE2 as $num => $value){
						$default = $MATCH[1];
						foreach($value as $key => $value2){
							$MATCH[1] = str_replace("<#LOOP_PRINT $key#>", $value2, $MATCH[1]);
						}
						$tmp .= $MATCH[1];
						$MATCH[1] = $default;
					}
					$TEMPLATE_DATA = preg_replace("/<#LOOP_START $LOOP_NAME#>(.+)<#LOOP_END $LOOP_NAME#>/si", $tmp, $TEMPLATE_DATA);
				}
			}
		}
		//PRINT
		if($PRINT_VALUE != ""){
			foreach($PRINT_VALUE as $key => $value){
				$TEMPLATE_DATA = str_replace("<#PRINT $key#>", $value, $TEMPLATE_DATA);
			}
		}
	/*
		//全角カナを半角ｶﾅ、全角英数字を半角英数字に変換
		$tmpData = mb_split("ヱ", $TEMPLATE_DATA);
		$TEMPLATE_DATA = "";
		foreach($tmpData as $tmpValue) {
			if($TEMPLATE_DATA)
				$TEMPLATE_DATA .= "ヱ";
			$TEMPLATE_DATA .= mb_convert_kana($tmpValue, "ak", "sjis");
		}
	*/
		//$TEMPLATE_DATA = mb_convert_kana($TEMPLATE_DATA, "ak", "sjis");

		//EMOJI
	/*
		if(preg_match_all('/<#EMOJI [^#>]*?#>/si', $TEMPLATE_DATA, $MATCH)){
			$TEMPLATE_DATA = $this->emojiReplace($TEMPLATE_DATA, $MATCH[0]);
		}
	*/
		return $TEMPLATE_DATA;
	}

	/**
	* 表示出力
	*
	* @access	public
	*/
	public function view($cacheFileName = ""){
	/*
		$clsKeitai = new ipfKeitai;
		$carrier = $clsKeitai->getCarrier();
	*/
		if($this->cacheFile == "NO" && $cacheFileName != ""){
			$this->makeCacheFile("$cacheFileName", $this->MEMORY);
		}
		foreach($this->MEMORY as $value){
			//print mb_convert_kana($value, "ak", "sjis");
		/*
			if($carrier == "s") {
				$value = str_replace(array("charset=Shift_JIS", ".jpg"), array("charset=UTF-8", ".jpz"), $value);
				$value = mb_convert_encoding($value, "UTF-8", "SJIS");
			}
		*/
			print $value;
		}
	}

	/**
	* 表示出力のため、一度メモリ上に保存
	*
	* @access	public
	* @param	string $MEM		保存データ
	* @return	int	メモリ番号
	*/
	public function putMemory($MEM){
		$temp = $this->MEMORY;
		if(is_array($MEM)){
			$i = 0;
			foreach($MEM as $value){
				array_push($temp, $value);
				$this->MEMORY = $temp;
				$i++;
			}
		}
		else{
			array_push($temp, $MEM);
			$this->MEMORY = $temp;
			$i = 1;
		}
		return $i;
	}

	/**
	* #INCLUDEファイルを読み込み、テンプレートデータを書き換え
	*
	* @access	public
	* @param	string $TEMPLATE_DATA	テンプレートデータ
	* @param	array  $MATCH	タグ#INCLUDEの配列
	* @return	string	書き換えたテンプレートデータ
	*/
	private function includeReplace($TEMPLATE_DATA, $MATCH){
		foreach($MATCH as $includeTag){
			$includeFile = preg_replace('/<#INCLUDE (.+)#>/s', '$1', $includeTag);
			$INCLUDE_DATA = $this->getTemplateData($includeFile);
			$TEMPLATE_DATA = str_replace("<#INCLUDE $includeFile#>", $INCLUDE_DATA, $TEMPLATE_DATA);
		}
		return $TEMPLATE_DATA;
	}

	/**
	* テンプレートファイルの読み込み
	*
	* @access	public
	* @param	string $templateFile	テンプレートファイル
	* @return	string テンプレートデータ
	*/
	private function getTemplateData($templateFile){
		$dir = dirname($_SERVER["SCRIPT_FILENAME"]);
		$TEMPLATE_DATA = file_get_contents("$dir/templates/$templateFile");
		return $TEMPLATE_DATA;
	}

	/**
	* 絵文字タグの書き換え
	*
	* @access	public
	* @param	string $TEMPLATE_DATA	テンプレートデータ
	* @param	array  $MATCH	タグ#EMOJIの配列
	* @return	string	書き換えたテンプレートデータ
	*/
	private function emojiReplace($TEMPLATE_DATA, $MATCH){
		require_once $this->INI_DATA['project_name'] . "/ipfKeitai.php";
		$a = new ipfKeitai;

		foreach($MATCH as $emojiTag){
			$no = preg_replace('/<#EMOJI (.+)#>/s', '$1', $emojiTag);
			$TEMPLATE_DATA = str_replace("<#EMOJI $no#>", $a->emoji(intval($no)), $TEMPLATE_DATA);
		}
		return $TEMPLATE_DATA;
	}
}
?>