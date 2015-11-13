<?php
/*! @brief ipfUtilityクラス

	ipfUtilityクラスは共通機能クラスです。

	@package	ipf.ipfUtility.class
	@access	public
	@author Reiun Ni <reiun@auris-dev.jp>
	@date 2009-12-14, 新規
*/

class ipfUtility {

	//! 暗号化キー
	var $keyEncrypt = "";

	//! 入力文字コード
	var $strEncode = "";

	/*!
		@brief コンストラクタ
		@param なし
		@return なし
	*/
	function __construct() {
		$iniData = parse_ini_file("tapp/ipf.ini");
		$this->keyEncrypt = $iniData['encrypt_key'];
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
		@brief 文字列を暗号化する。
		@param[in]	$string 暗号化する文字列
		@return		暗号化した文字列を返します。
	*/
	function pwdEncrypt($string) {
		$result = '';
		for($i=0; $i<strlen($string); $i++) {
			$char = substr($string, $i, 1);
			$keychar = substr($this->keyEncrypt, ($i % strlen($this->keyEncrypt))-1, 1);
			$char = chr(ord($char)+ord($keychar));
			$result.=$char;
		}

		return urlencode(base64_encode($result));
	}

	/*!
		@brief 複号化を行う。
		@param[in]	$string 複号化する文字列
		@return		複号かされた文字列を返します。
	*/
	function pwdDecrypt($string) {
		$result = '';
		//$string = base64_decode(urldecode($string));
		$string = base64_decode($string);

		for($i=0; $i<strlen($string); $i++) {
			$char = substr($string, $i, 1);
			$keychar = substr($this->keyEncrypt, ($i % strlen($this->keyEncrypt))-1, 1);
			$char = chr(ord($char)-ord($keychar));
			$result.=$char;
		}

		return $result;
	}

	/*!
		@brief パスワードにマスクを付ける。
		@param[in]	$str パスワード文字列
		@return		パスワード文字列桁数の*文字を返します。
	*/
	function pwdMask($string)
	{
		for($i = 0; $i < strlen($string); $i++)
			echo "*";
	}

	/*!
		@brief メールを送信する。
		@param[in]	$mailto 送信先メールアドレス
		@param[in]	$subject 送信タイトル
		@param[in]	$message 送信本文
		@param[in]	$mailfrom 送信元メールアドレス
		@param[in]	$mailbcc BCC先メールアドレス
		@return		成功した場合に TRUE を、失敗した場合に FALSE を返します。
	*/
	function sendMail($mailto, $subject, $message, $mailfrom,  $mailbcc="")
	{

		$mailheader = "From: " . $mailfrom;
		if($mailbcc){
			if($mailto != $mailbcc){
				$mailheader .= "\nBCC: " . $mailbcc;
			}
		}
		mb_language("ja");
		mb_internal_encoding($this->strEncode);
		return mb_send_mail($mailto, $subject, $message, $mailheader);
	}

	/*!
		@brief 月の末日を取得する。
		@param[in]	$month 月
		@return		指定月の末日を返します。
	*/
	function getLastDate($month, $year = "")
	{
		if(!$year)
			$year = date("Y");
		$dt = mktime(0, 0, 0, $month + 1, 0, $year);
		return date("d", $dt);
	}

        /*!
                @brief 携帯ﾒｰﾙｱﾄﾞをﾁｪｯｸ
                @param[in]      $email 携帯ﾒｰﾙｱﾄﾞ
                @return         携帯ﾒｰﾙｱﾄﾞの場合true,それ以外false
        */
        function chkMobileAddr($email)
        {
                if(!$email)
                    return false;

                //携帯ドメイン
                $aryMobileDoman = array("docomo.ne.jp",
                        "ezweb.ne.jp",
                        "softbank.ne.jp",
                        "t.vodafone.ne.jp",
                        "d.vodafone.ne.jp",
                        "h.vodafone.ne.jp",
                        "c.vodafone.ne.jp",
                        "k.vodafone.ne.jp",
                        "r.vodafone.ne.jp",
                        "n.vodafone.ne.jp",
                        "s.vodafone.ne.jp",
                        "q.vodafone.ne.jp",
                        "disney.ne.jp"
                );

                $str = split("@", $email);
                if(in_array(strtolower($str[1]), $aryMobileDoman))
                    return true;
                else if(strstr(strtolower($email),"biz.ezweb.ne.jp"))
	            return true;
	        else
                    return false;

       }

}
?>
