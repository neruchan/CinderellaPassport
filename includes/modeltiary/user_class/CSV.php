<?PHP
/*! @brief CSVクラス


	@package	table_class.CSV
	@access	public
	@author Reiun Ni <reiun@nipponwide.com>
	@version	$Id: CSV.php, v 1.0 2013/04/11 Tou Exp $
*/
class CSV{

	//******************************
	//    コンストラクタ
	//******************************
	function __construct(){
		//セッションスタート
	}

	//******************************
	//    CSVアップロード
	//    param :
	//    param :
	//    return: array
	//******************************
	public function uploadCSV($filePath)
	{

		require_once "modeltiary/ipfDB.php";
		$ins_ipfDB = new ipfDB;

		require_once "modeltiary/table_class/entry.php";
		$ins_entry = new entry;
		//$ins_ipfDB->ini("entry");

		$cntOk = 0;
		$cntFalid = 0;
		if (($handle = fopen($filePath, "r")) !== FALSE) {
// 			setlocale(LC_ALL, 'ja_JP');
			setlocale(LC_ALL, 'ja_JP.UTF-8');
			//UTF-8の場合
			while (($data =$this->fgetcsv_reg($handle)) !== FALSE) {

				//項目数判断
				if(count($data) < 11){
					$cntFalid = "正しいのCSVファイル選択ください";
					continue;
				}
				$_enc_to=mb_internal_encoding();
				$_enc_from=mb_detect_order();

// 				mb_convert_variables($_enc_to,$_enc_from,$data);
				/*
				$checkphone = $ins_entry->check_shop_phone($data[2]);
				if($checkphone==0){
					unset($_DATA);
					$_DATA = array();
					$shop_category ="";
					for($i=10;$i<16;$i++){
						if($data[$i]=="1"){
							$shop_category .=($i-9).",";
						}
					}
					if($shop_category!="")
					$shop_category = substr($shop_category, 0, -1);

					$_DATA['shoptbl']['shop_category'] = $shop_category;//カテゴリ
					$_DATA['shoptbl']['shop_name'] = $data[0];//店舗名
					$_DATA['shoptbl']['shop_name_kana'] = $data[1];//店舗名（カタカナ）
					$_DATA['shoptbl']['shop_pref'] = $data[3];//都道府県
					$_DATA['shoptbl']['shop_city'] = $data[4];//地域
					$_DATA['shoptbl']['shop_address'] = $data[5];//住所
					$_DATA['shoptbl']['shop_phone'] = $data[2];//電話番号
// 					$_DATA['shoptbl']['shop_img'] = "";
					$_DATA['shoptbl']['shop_opentime'] = $data[7];//営業時間
					$_DATA['shoptbl']['shop_holiday'] = $data[8];//定休日
					$_DATA['shoptbl']['shop_homepage'] = $data[9];//ホームページ
					$_DATA['shoptbl']['shop_access'] = $data[6];//アクセス
// 					$_DATA['shoptbl']['shop_keyword'] = $data[10];//検索ワード
					$_DATA['shoptbl']['shop_addtime'] = date("Y-m-d H:i:s");
					$ins_entry->insert_shop_data($_DATA);

					$cntOk = $cntOk + 1;
				}
				*/
				$terminal = array("携帯"=>"0","PC"=>"1");
				$mailmgz = array("希望する"=>"0","希望しない"=>"1");
				unset($_DATA);
				$_DATA = array();
				$_DATA['users']['password'] = $data[2];//パスワード
				$_DATA['users']['email'] = $data[7];//メールアドレス
				$_DATA['users']['created'] = $data[11];//登録時間
				$_DATA['users']['cmflag'] = 1;//登録媒体
				$_DATA['users']['activated'] = 1;
				$_DATA['users']['terminal'] = $terminal[$data[8]];//登録端末
				$userid = $ins_ipfDB->dataControl("insert", $_DATA);
				unset($_DATA);
				$_DATA['users_info']['fullname'] = $data[0];//名前
				$_DATA['users_info']['fullname_kana'] = $data[1];//フリガナ
				$_DATA['users_info']['zipcode'] = $data[3];//郵便番号
				$_DATA['users_info']['todoufuken'] = $data[4];//都道府県
				$_DATA['users_info']['banti'] = $data[5];//住所
				$_DATA['users_info']['phonenumber'] = $data[6];//電話番号
				$_DATA['users_info']['mailmgz'] = $mailmgz[$data[9]];//ホームページ
				$_DATA['users_info']['birthday'] = $data[10];//生年月日
				$_DATA['users_info']['userid'] = $userid;//ID
				$ins_ipfDB->dataControl("insert", $_DATA);
				unset($_DATA);

				$cntOk = $cntOk + 1;
			}
			fclose($handle);
		}
		return array($cntOk,$cntFalid);
	}

	/**
	* ファイルポインタから行を取得し、CSVフィールドを処理する
	* @param resource handle
	* @param int length
	* @param string delimiter
	* @param string enclosure
	* @return ファイルの終端に達した場合を含み、エラー時にFALSEを返します。
	*/
	function fgetcsv_reg (&$handle, $length = null, $d = ',', $e = '"') {
		$d = preg_quote($d);
		$e = preg_quote($e);
		$_line = "";
		$eof = false;
		while (($eof != true) and !feof($handle)){
			$_line .= (empty($length) ? fgets($handle) : fgets($handle, $length));
			$itemcnt = preg_match_all('/'.$e.'/', $_line, $dummy);
			if ($itemcnt % 2 == 0) $eof = true;
		}
		$_csv_line = preg_replace('/(?:\r\n|[\r\n])?$/', $d, trim($_line));
		$_csv_pattern = '/('.$e.'[^'.$e.']*(?:'.$e.$e.'[^'.$e.']*)*'.$e.'|[^'.$d.']*)'.$d.'/';
		preg_match_all($_csv_pattern, $_csv_line, $_csv_matches);
		$_csv_data = $_csv_matches[1];
		for($_csv_i=0;$_csv_i<count($_csv_data);$_csv_i++){
			$_csv_data[$_csv_i]=preg_replace('/^'.$e.'(.*)'.$e.'$/s','$1',$_csv_data[$_csv_i]);
			$_csv_data[$_csv_i]=str_replace($e.$e, $e, $_csv_data[$_csv_i]);
		}
		return empty($_line) ? false : $_csv_data;
	}

	/**
	* ファイル内容のエンコードを判断する
	* @param $str
	* @param $encode
	* @return
	*/
	function check_encoding($str,$encode){
		if ($str === mb_convert_encoding(mb_convert_encoding($str, "UTF-32", $encode),$encode, "UTF-32")){
			return true;
		}else{
			return false;
		}
	}
}
?>
