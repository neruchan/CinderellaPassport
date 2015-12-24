<?php
/*! @brief ipfConvertクラス

	ipfConvertクラスは斁E���EめE"全角かな"、E半角かな"等に)変換するクラスです、E

	@package	ipf.ipfConvert.class
	@access	public
	@author Reiun Ni <reiun@auris-dev.jp>
	@date 2009-12-14, 新要E
*/

class ipfConvert {

	//! 入力文字コーチE
	var $strEncode = "";

	/*!
		@brief コンストラクタ
		@param なぁE
		@return なぁE
	*/
	function __construct() {
		$iniData = parse_ini_file("mrhns-net/ipf.ini");
		$this->strEncode = $iniData['site_string_code'];
	}

	/*!
		@brief チE��トラクタ
		@param なぁE
		@return なぁE
	*/
	function __destruct() {

	}

	/*!
		@brief 半角カナ、英数字文字�Eを�E角カナ、英数字文字�Eに変換する、E
		@param[in]	$string 変換する斁E���E
		@return		変換した斁E���Eを返します、E
	*/
	function toZenkaku($string) {
		return mb_convert_kana($string, "KVAS", $this->strEncode);
	}

	/*!
		@brief 全角カナ、英数字文字�Eを半角カナ、英数字文字�Eに変換する、E
		@param[in]	$string 変換する斁E���E
		@return		変換した斁E���Eを返します、E
	*/
	function toHankaku($string) {
		return mb_convert_kana($string, "kas", $this->strEncode);
	}

	/*!
		@brief カタカナ文字�Eを�E角�Eらがな斁E���Eに変換する、E
		@param[in]	$string 変換する斁E���E
		@return		変換した斁E���Eを返します、E
	*/
	function toHiragana($string) {
		return mb_convert_kana($string, "Hc", $this->strEncode);
	}

	/*!
		@brief ひらがな斁E���Eをカタカナ文字�Eに変換する、E
		@param[in]	$string 変換する斁E���E
		@param[in]	$flag カタカナ半角フラグ
		@return		フラグがfalseの場合�E角カタカナ、trueの場合半角カタカナを返します、E
	*/
	function toKatakana($string, $flag = false) {
		if($flag)
			return mb_convert_kana($string, "kh", $this->strEncode);
		else
			return mb_convert_kana($string, "KVC", $this->strEncode);
	}
	
	//20100303 追加�E�古賀
	/*!
		@brief スペ�Eスを半角に変換する、E
			   半角英数孁E0-9,A-Z)、半角カナ、半角数字、半角英斁E��、半角スペ�Eスを�E角に変換する、E
		@param[in]	$string 変換する斁E���E
		@return		変換した斁E���Eを返します、E
	*/
	function zenkaku($string) {
		//return mb_convert_kana($string, "AKVNRS", $this->strEncode);
		return mb_convert_kana($string, "AsKVN", $this->strEncode);
	}
	
}
?>
