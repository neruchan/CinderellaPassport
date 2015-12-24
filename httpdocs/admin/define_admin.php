<?php

$shop_coupon_yn = array(
    "0" => "クーポン有無",
    "1" => "あり",
	"2" => "なし"
);

//記事カテゴリ
$coupon_categorys = array(
    '1'=>'Pickup',
    '2'=>'ビューティー',
	'3'=>'ボディ',
	'4'=>'スクール',
	'5'=>'エンタメ'
);

//記事カテゴリ
$coupon_categorys_dropdown = array(
	"0" => "カテゴリ",
    '1'=>'Pickup',
    '2'=>'ビューティー',
	'3'=>'ボディ',
	'4'=>'スクール',
	'5'=>'エンタメ'
);

$open_years = array(
    "2013" => "2013",
    "2014" => "2014",
	"2015" => "2015"
);

$open_months = array(
    "1" => "1",
    "2" => "2",
	"3" => "3",
    "4" => "4",
	"5" => "5",
    "6" => "6",
	"7" => "7",
    "8" => "8",
	"9" => "9",
	"10" => "10",
	"11" => "11",
	"12" => "12"
);

//誕生日日プルダウン
$open_days = array(
    "1" => "1",
    "2" => "2",
	"3" => "3",
    "4" => "4",
	"5" => "5",
    "6" => "6",
	"7" => "7",
    "8" => "8",
	"9" => "9",
    "10" => "10",
	"11" => "11",
    "12" => "12",
	"13" => "13",
    "14" => "14",
	"15" => "15",
    "16" => "16",
	"17" => "17",
    "18" => "18",
	"19" => "19",
    "20" => "20",
	"21" => "21",
    "22" => "22",
	"23" => "23",
    "24" => "24",
	"25" => "25",
    "26" => "26",
	"27" => "27",
    "28" => "28",
	"29" => "29",
    "30" => "30",
	"31" => "31"
);


//都道府県プルダウン
$todoufukens = array(
    "北海道" => "北海道",
    "青森県" => "青森県",
	"岩手県" => "岩手県",
    "宮城県" => "宮城県",
	"秋田県" => "秋田県",
    "山形県" => "山形県",
	"福島県" => "福島県",
    "茨城県" => "茨城県",
	"栃木県" => "栃木県",
    "群馬県" => "群馬県",
	"埼玉県" => "埼玉県",
    "千葉県" => "千葉県",
	"東京都" => "東京都",
    "神奈川県" => "神奈川県",
	"新潟県" => "新潟県",
    "富山県" => "富山県",
	"石川県" => "石川県",
    "福井県" => "福井県",
	"山梨県" => "山梨県",
    "長野県" => "長野県",
	"岐阜県" => "岐阜県",
    "静岡県" => "静岡県",
	"愛知県" => "愛知県",
    "三重県" => "三重県",
	"滋賀県" => "滋賀県",
    "京都府" => "京都府",
	"大阪府" => "大阪府",
    "兵庫県" => "兵庫県",
	"奈良県" => "奈良県",
    "和歌山県" => "和歌山県",
	"鳥取県" => "鳥取県",
	"島根県" => "島根県",
    "岡山県" => "岡山県",
	"広島県" => "広島県",
    "山口県" => "山口県",
	"徳島県" => "徳島県",
    "香川県" => "香川県",
	"愛媛県" => "愛媛県",
    "高知県" => "高知県",
	"福岡県" => "福岡県",
    "佐賀県" => "佐賀県",
	"長崎県" => "長崎県",
    "熊本県" => "熊本県",
	"大分県" => "大分県",
    "宮崎県" => "宮崎県",
	"鹿児島県" => "鹿児島県",
    "沖縄県" => "沖縄県"
);


//店舗カテゴリ
$shop_categorys = array(
	"0" => " カテゴリ",
    "1" => " ヘア",
    "2" => " ネイル",
	"3" => " まつ毛エクステ",
    "4" => " エステ",
	"5" => " リラク",
	"6" => " カフェ",
	"7" => "ウェディング",
	"8" => " その他"
);


//店舗カテゴリ
$new_shop_categorys = array(
    "1" => " ヘア",
    "2" => " ネイル",
	"3" => " まつ毛エクステ",
    "4" => " エステ",
	"5" => " リラク",
	"6" => " カフェ",
	"7" => "ウェディング",
	"8" => " その他"
);

//クーポンの種類
$sc_coupontypesarr = array(
    "1" => "ホットペッパー",
    "2" => "クーポンランド",
	"3" => "OZmall",
	"4" => "ポンパレ",
	"5" => "ホームページ",
    "6" => "その他"
);


//順番
$sorts = array(
	"0" => "全部",
	"1" => "多い順",
    "2" => "少ない順"
);


//投稿日時
$addtime_sorts = array(
	"0" => "投稿日時",
	"1" => "新しい投稿順",
    "2" => "古い投稿順"
);

//投稿日時
$pv_sorts = array(
	"0" => "PV数",
	"1" => "多い順",
    "2" => "少ない順"
);

//投稿記事数
$article_num_sorts = array(
	"0" => "投稿記事数",
	"1" => "多い順",
    "2" => "少ない順"
);

function setOptions($list, $default = "", $format = "%s") {
	$ret = "";
	if(is_array($list)) {
		foreach($list as $key => $val) {
			$ret .= '<option value="'. $key . '"' . ($default == $key ? ' selected' : '') . '>' . sprintf($format, $val) . '</option>';
		}
	}
	return $ret;
}

function generate_password($length = 8) {
	$chars = 'abcdefghijklmnopqrstuvwxyz0123456789';
	$password = '';
	for ( $i = 0; $i < $length; $i++ )
	{
		$password .= $chars[ mt_rand(0, strlen($chars) - 1) ];
	}
	return $password;
}

function setCheckbox($list,$default = ""){
	$ret = "";
	if(is_array($list)){
		$cbarr = array();
		if($default!=""){
			$cbarr = explode(",",$default);
		}
		foreach($list as $key => $val){
			if(count($cbarr)>0){
				$checked = '';
				for($i=0;$i<count($cbarr);$i++){
					if($cbarr[$i] == $key){
						$checked = 'checked="checked"';
						break;
					}
				}
			}
			$ret .= '<input type="checkbox" name="shop_category[]" value="'.$key.'" '.$checked.'/><label for="shop_category" >'.$val.'</label>';
		}
	}
	return $ret;
}

function setCheckboxArticle($list,$default = ""){
	$ret = "";
	if(is_array($list)){
		$cbarr = array();
		if($default!=""){
			$cbarr = explode(",",$default);
		}
		foreach($list as $key => $val){
			if(count($cbarr)>0){
				$checked = '';
				for($i=0;$i<count($cbarr);$i++){
					if($cbarr[$i] == $key){
						$checked = 'checked="checked"';
						break;
					}
				}
			}
			$ret .= '<input type="checkbox" style="vertical-align:middle;" name="category[]" value="'.$key.'" '.$checked.'/><label for="'.$val.'" >'.$val.'</label>&nbsp;&nbsp;';
		}
	}
	return $ret;
}

?>