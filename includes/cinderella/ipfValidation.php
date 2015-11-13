<?PHP
/*! @brief ipfValidationクラス

	ipfValidationクラスは入力フォームの項目値に対して、以下のチェックを行います。

		・桁数チェック
		・数値チェック
		・存在日付チェック
		・機種依存文字
		・メールアドレスチェック
		・カタカナチェック
		・ひらがなチェック

	@package	ipf.validation.class
	@access	public
	@author Reiun Ni <reiun@auris-dev.jp>
	@date 2009-12-11, 新規
*/
class ipfValidation{

	//! 入力文字コード
	var $strEncode = "";

	/*!
		@brief コンストラクタ
		@param なし
		@return なし
	*/
	function __construct() {
		$iniData = parse_ini_file("mrhns-net/ipf.ini");
		$this->strEncode = $iniData['site_string_code'];
	}

	/*!
		@brief デストラクタ
		@param なし
		@return なし
	*/
	function __destruct() {

	}

	/*!
		@brief 数値チェック
		@param[in]	$data 入力データ
		@return		数値または数値形式の文字列である場合に TRUE、それ以外の場合に FALSE を返します。
	*/
	function isNumber($data) {
		return is_numeric($data);
	}

	/*!
		@brief 桁数チェック
		@param[in]	$data 入力データ
		@param[in]	桁数制限
		@return		指定桁数を超えてる場合に FALSE、それ以外の場合に TRUE を返します。
	*/
	function chkLen($data, $len, $flag = false) {
		if($flag)
			return strlen($data) == $len;
		else
			return strlen($data) > $len;
	}

	/*!
		@brief 存在日付チェック
		@param[in]	$year  年
		@param[in]	$month 月
		@param[in]	$day   日
		@return		指定した日付が有効な場合に TRUE、そうでない場合に FALSE を返します。
	*/
	function isDate($year, $month, $day) {
		return checkdate($month, $day, $year);
	}

	/*!
		@brief 存在日付チェック
		@param[in]	$str  YYYYMMDD式年月日
		@return		指定した日付が有効な場合に TRUE、そうでない場合に FALSE を返します。
	*/
	function isDateString($str) {
		$year = substr($str, 0, 4);
		$month = intval(substr($str, 4, 2));
		$day = intval(substr($str, 6, 2));
		return checkdate($month, $day, $year);
	}

	/*!
		@brief 存在日付チェック
		@param[in]	$ymd  年月日
		@return		指定した日付が有効な場合に TRUE、そうでない場合に FALSE を返します。
	*/
	function isDateStr($ymd) {
		list($year, $month, $day) = preg_split("/[-\/]/", $ymd);
		return checkdate($month, $day, $year);
	}

	/*!
		@brief メールアドレスチェック
		@param[in]	$email  入力されたメールアドレス
		@return		メールアドレスが正しい場合に TRUE、そうでない場合に FALSE を返します。
	*/
	function isMailAddr($email) {
		return preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+([\.][a-z0-9-]+)+$/i", $email);
	}

	/*!
		@brief ひらがな、全角カタカナ、半角ｶﾀｶﾅのチェック
		@param[in]	$str  入力された文字列
		@param[in]	$flg  チェックフラグ（H:ひらがな; K:全角カタカナ; k:半角ｶﾀｶﾅ）
		@return		すべて一致した場合に TRUE、そうでない場合に FALSE を返します。
	*/
	function isKana($str, $flg = "H"){
		mb_regex_encoding('utf-8');
		$val = mb_convert_encoding($str, "utf-8", $this->strEncode);
		if ( $flg == "H" ){			//!< ひらがなの場合
			return preg_match('/^[ぁ-ん]+$/u', $val);
		}else if ( $flg == "K" ){	//!< 全角カタカナの場合
			return preg_match("/^[ァ-ヾ]+$/u",$val);
		}else if ( $flg == "k" ){	//!< 半角ｶﾀｶﾅの場合
			return preg_match('/^[ｦ-ﾝﾞﾟ]+$/u', $val);
		}else {
			return false;
		}
		return false;
	}
	
	//20100302追加：古賀
	/*!
		@brief 漢字チェック
		@param[in]	$str  入力された文字列
		@param[in]	$flg  チェックフラグ（C:漢字）
		@return		漢字を含む場合に TRUE、そうでない場合に FALSE を返します。
	*/
	function isKanji($str, $flg = "W"){
		mb_regex_encoding('utf-8');
		$val = mb_convert_encoding($str, "utf-8", $this->strEncode);
		if ( $flg == "C" ){		//!< 漢字の場合
			return preg_match('/[一-龠]+/u', $val);
		}elseif ( $flg == "W" ){	//!< ひらがなとカタカナの場合
			return preg_match('/^[ぁ-ん]+|[ァ-ヾ]+$/u', $val);
		}else {
			return false;
		}
		return false;
	}

	/*!
		@brief 機種依存文字をチェック
		@param[in]	$str  入力された文字列
		@return		機種依存文字を含む場合に TRUE、そうでない場合に FALSE を返します。
	*/
	function hasDependentCharacter($str)
    {
		mb_regex_encoding('utf-8');
		$val = mb_convert_encoding(mb_convert_encoding($str,"sjis-win",$this->strEncode),"UTF-8","sjis-win");

        $pattern = '[①②③④⑤⑥⑦⑧⑨⑩⑪⑫⑬⑭⑯⑰⑱⑲⑳ⅠⅡⅢⅣⅤⅥⅦⅧⅨⅩ㍉㌔㌢㍍㌘㌧㌃㌶㍑㍗㌍㌦㌣㌫㍊㌻㎜㎝㎞㎎㎏㏄㎡㍻〝〟№㏍℡㊤㊥㊦㊧㊨㈱㈲㈹㍾㍽㍼ ∮∟⊿纊褜鍈銈蓜俉炻昱棈鋹曻彅丨仡仼伀伃伹佖侒侊侚侔俍偀倢俿倞偆偰偂傔僴僘兊兤冝冾凬刕劜劦勀勛匀匇匤卲厓厲叝﨎咜咊咩哿喆坙坥垬埈埇﨏塚增墲夋奓奛奝奣妤妺孖寀甯寘寬尞岦岺峵崧嵓﨑嵂嵭嶸嶹巐弡弴彧德忞恝悅悊惞惕愠惲愑愷愰憘戓抦揵摠撝擎敎昀昕昻昉昮昞昤晥晗晙晴晳暙暠暲暿曺朎朗杦枻桒柀栁桄棏﨓楨﨔榘槢樰橫橆橳橾櫢櫤毖氿汜沆汯泚洄涇浯涖涬淏淸淲淼渹湜渧渼溿澈澵濵瀅瀇瀨炅炫焏焄煜煆煇凞燁燾犱犾猤猪獷玽珉珖珣珒琇珵琦琪琩琮瑢璉璟甁畯皂皜皞皛皦益睆劯砡硎硤礰礼神祥禔福禛竑竧靖竫箞精絈絜綷綠緖繒罇羡羽茁荢荿菇菶葈蒴蕓蕙蕫﨟薰蘒﨡蠇裵訒訷詹誧誾諟諸諶譓譿賰賴贒赶﨣軏﨤逸遧郞都鄕鄧釚釗釞釭釮釤釥鈆鈐鈊鈺鉀鈼鉎鉙鉑鈹鉧銧鉷鉸鋧鋗鋙鋐﨧鋕鋠鋓錥錡鋻﨨錞鋿錝錂鍰鍗鎤鏆鏞鏸鐱鑅鑈閒隆﨩隝隯霳霻靃靍靏靑靕顗顥飯飼餧館馞驎髙髜魵魲鮏鮱鮻鰀鵰鵫鶴鸙黑ⅰⅱⅲⅳⅴⅵⅶⅷⅸⅹ￢￤＇＂]';

        return mb_ereg($pattern, $val);
    }

	/*!
		@brief 禁止文字をﾁｪｯｸ
		@param[in]	$str  入力された文字列
		@return		禁止文字を含む場合に TRUE、そうでない場合に FALSE を返します。
	*/
	function hasProhibitionCharacter($str)
    {
		mb_regex_encoding('utf-8');
		$val = mb_convert_encoding($str, "utf-8", $this->strEncode);

        //$pattern = '[\'\[\]\\\\!"#$%&()*+,-./:;<=>?@^_`{|}｡｢｣､]';
        //$pattern = '[\'\[\]\\\\"<>]';
        $pattern = '[\'"=<>]';

        return mb_ereg($pattern, $val);
    }
    
    //20100305追記：古賀
	/*!
		@brief 記号文字をﾁｪｯｸ
		@param[in]	$str  入力された文字列
		@return		記号文字を含む場合に TRUE、そうでない場合に FALSE を返します。
	*/
	function isMark($str)
    {
		mb_regex_encoding('utf-8');
		$val = mb_convert_encoding($str, "utf-8", $this->strEncode);

        $pattern = '[\'\[\]\\\\!"#$%&()*+,-./:;<=>?@^_`{|}｡｢｣､]';
        //$pattern = '[\'\[\]\\\\"<>]';
        //$pattern = '[\'"=<>]';

        return mb_ereg($pattern, $val);
    }

}
