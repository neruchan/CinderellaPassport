<?php
/*! @brief ipfKeitaiクラス

	ipfKeitaiクラスは携帯共通機能クラスです。

	@package	ipf.ipfKeitai.class
	@access	public
	@author Reiun Ni <reiun@auris-dev.jp>
	@date 2009-12-15, 新規
*/

class ipfKeitai {

	//*! 絵文字変換表 */
	var $emoji_data = "/home/httpd/vhosts/social-net.jp/includes/tokyo-girls/emojih.csv";
	
	/*! PC用絵文字格納フォルダ */
	var $img_dir = "/emoji_image/";
	
	/*! ドコモ→au絵文字自動変換利用（on:1 off:0） */
	var $ie = "1";
	
	/*! 携帯ﾕｰｻﾞｴｰｼﾞｪﾝﾄ */
	var $agent = "";

	/*! 絵文字配列 */
	var $emoji_array = array(); 

	/*!
		@brief コンストラクタ
		@param なし
		@return なし
	*/
	function __construct() {
		//携帯ﾕｰｻﾞｴｰｼﾞｪﾝﾄ取得
		$this->agent = $_SERVER["HTTP_USER_AGENT"];

		//変換表を配列に格納
		$this->emoji_array[] = "";
		$contents = @file($this->emoji_data); 
		foreach($contents as $line){ 
			$line = str_replace("\"", "", rtrim( $line )); 
			$this->emoji_array[] = explode(",", $line); 
		}

	}

	/*!
		@brief デストラクタ
		@param なし
		@return なし
	*/
	function __destruct() {

	}

	/*!
		@brief 携帯端末のﾕｰｻﾞｴｰｼﾞｪﾝﾄを取得
		@param なし
		@return 携帯端末のﾕｰｻﾞｴｰｼﾞｪﾝﾄ文字列
	*/
	function getUsrAgent() {
		return $this->agent;
	}

	/*!
		@brief 携帯端末のﾕｰｻﾞIDを取得
		@param なし
		@return 携帯端末のﾕｰｻﾞID文字列
	*/
	function getUID() {
		$uid = "";
		switch($this->getCarrier()){
			case "i";$uid = $_SERVER['HTTP_X_DCMGUID'];
				break;
			case "e";
				$uid = $_SERVER['HTTP_X_UP_SUBNO'];
				break;
			case "s";
				$uid = $_SERVER['HTTP_X_JPHONE_UID'];
				break;
		}
		return $uid;
	}

	/*!
		@brief ドコモ携帯端末の製造番号を取得
		@param なし
		@return MOVA:端末製造番号() FOMA:FOMAカード製造番号
	*/
	function getUTN() {
		$utn = "";
		if(ereg("DoCoMo/2.0", $this->agent)){
			//FOMAカード製造番号取得
			preg_match("/.+;(icc\w{20})\)$/",$this->agent, $matches);
			$utn = substr($matches[1], 3);

			if(!$utn) {
				//FOMA端末製造番号取得
				preg_match("/.+;(ser[0-9]+);icc.+/",$this->agent, $matches);
				$utn = substr($matches[1], 3);
			}

		}else if(ereg("DoCoMo/1.0", $this->agent)){
			//MOVA端末製造番号取得
			preg_match("/DoCoMo\/1\.0\/.+\/(ser.+)/",$this->agent, $matches);
			$utn = substr($matches[1], 3);
		}

		return $utn;
	}

	/*!
		@brief 携帯端末の機種名を取得
		@param なし
		@return 携帯端末の機種名文字列
	*/
	function getModel() {
		$device = "";
		switch($this->getCarrier()){
			case "i";
				if (strpos($this->agent, '/1.0') > 0 && strpos($this->agent, '/', 11) >= 0) {
					$device = substr($this->agent, 11, strpos($this->agent, '/', 11) - 11);
				}
				else if (strpos($this->agent, '/2.0') > 0 && strpos($this->agent, '(') >= 0) {
					$device = substr($this->agent, 11, strpos($this->agent, '(') - 11);
				}
				else {
					$device = substr($this->agent, 11);
				}
				break;
			case "e";
				$device = substr($this->agent, (strpos($this->agent, "-") + 1), (strpos($this->agent, " ") - strpos($this->agent, "-") - 1));
				break;
			case "s";
				$device = $_SERVER['HTTP_X_JPHONE_MSNAME'];
				break;
		}
		return $device;
	}

	/*! 
		SJIS文字コードに変換
		@param	data	文字列
		@return	SJIS文字コード文字列
	 */ 
	private function encode($data) {
		$data_encode = mb_detect_encoding($data, "SJIS, EUC-JP, UTF-8");
		if($data_encode != "SJIS") {
			$data = mb_convert_encoding($data, "SJIS", $data_encode);
		}
		return $data;
	}
		
	/*! 
		@brief 携帯端末キャリアを取得
		@param なし
		@return 携帯端末のキャリアコード
				i：i-mode
				s：softbank
				e：ezweb
				w：willcom
				l：l-mode
				p：pc
	 */ 
	//private function getCarrier(){
	function getCarrier(){
		if(preg_match("/^DoCoMo\/[12]\.0/i", $this->agent))
		{
    		return "i";// i-mode
		}
		elseif(preg_match("/^(J\-PHONE|Vodafone|MOT\-[CV]980|SoftBank)\//i", $this->agent))
		{
    		return "s";// softbank
		}
		elseif(preg_match("/^KDDI\-/i", $this->agent) || preg_match("/UP\.Browser/i", $this->agent))
		{
    		return "e";// ezweb
		}
		elseif(preg_match("/^PDXGW/i", $this->agent) || preg_match("/(DDIPOCKET|WILLCOM);/i", $this->agent))
		{
    		return "w";// willcom
		}
		elseif(preg_match("/^L\-mode/i", $this->agent))
		{
    		return "l";// l-mode
		}
		else {
    		return "p";// pc
		}
	}
		
	/*! 
		携帯キャリアに合わせて絵文字を出力
		@param	data	絵文字番号
		@return	絵文字コード;
				絵文字コードに対応しない端末には絵文字画像表示ソースコード
	 */ 
	function emoji($data) {
		$put = "";
		if(preg_match("/[0-9]{1,3}/", $data) && is_numeric($data) && 0 < $data && $data < 253) {
			switch($this->getCarrier()){
				case "i";
					$put = $this->emoji_array[$data][1];
					break;
				case "e";
					if (preg_match("/[^0-9]/", $this->emoji_array[$data][2])) {
						$put = $this->emoji_array[$data][2];
					} elseif ($this->ie > 0) {
						$put = $this->emoji_array[$data][1]; // Display such the icons that ezserver transformed as docomo i-emoji.
					} else {
						$put = "<img localsrc=\"".$this->emoji_array[$data][2]."\" />";
					}
					break;
				case "s";
					if (preg_match("/^[A-Z]{1}?/", $this->emoji_array[$data][3])) {
						$put = "\x1B\$".$this->encode($this->emoji_array[$data][3])."\x0F";
					} else {
						$put = $this->encode($this->emoji_array[$data][3]);
					}
					break;
				case "p";
					$put = "<img src=\"".$this->img_dir.$this->emoji_array[$data][0].".gif\" width=\"12\" height=\"12\" border=\"0\" alt=\"\" />";
					break;
			}
		}

		return $put;
	}
}
