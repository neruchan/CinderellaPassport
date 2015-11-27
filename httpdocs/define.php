<?php

$open_years = array(
    "2013" => "2013",
    "2014" => "2014",
	"2015" => "2015",
    "2016" => "2016",
    "2017" => "2017",
    "2018" => "2018"
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

$open_hours = array(
    "00" => "00",
    "01" => "01",
	"02" => "02",
    "03" => "03",
	"04" => "04",
    "05" => "05",
	"06" => "06",
    "07" => "07",
	"08" => "08",
	"09" => "09",
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
    "23" => "23"
);

$open_minutes = array(
    "10" => "10",
    "20" => "20",
	"30" => "30",
    "40" => "40",
	"50" => "50",
    "60" => "60"
);

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

$todoufukens = array(
	"" => "--------",
    "1" => "北海道",
    "2" => "青森県",
	"3" => "岩手県",
    "4" => "宮城県",
	"5" => "秋田県",
    "6" => "山形県",
	"7" => "福島県",
    "8" => "茨城県",
	"9" => "栃木県",
    "10" => "群馬県",
	"11" => "埼玉県",
    "12" => "千葉県",
	"13" => "東京都",
    "14" => "神奈川県",
	"15" => "新潟県",
    "16" => "富山県",
	"17" => "石川県",
    "18" => "福井県",
	"19" => "山梨県",
    "20" => "長野県",
	"21" => "岐阜県",
    "22" => "静岡県",
	"23" => "愛知県",
    "24" => "三重県",
	"25" => "滋賀県",
    "26" => "京都府",
	"27" => "大阪府",
    "28" => "兵庫県",
	"29" => "奈良県",
    "30" => "和歌山県",
	"31" => "鳥取県",
	"32" => "島根県",
    "33" => "岡山県",
	"34" => "広島県",
    "35" => "山口県",
	"36" => "徳島県",
    "37" => "香川県",
	"38" => "愛媛県",
    "39" => "高知県",
	"40" => "福岡県",
    "41" => "佐賀県",
	"42" => "長崎県",
    "43" => "熊本県",
	"44" => "大分県",
    "45" => "宮崎県",
	"46" => "鹿児島県",
    "47" => "沖縄県"
);

$coupon_category = array(
	'1'=>'ライフスタイル',
	'2'=>'ビューティ',
	'3'=>'エンタメ',
	'4'=>'ピックアップ',
    "5" => "エンタメ"
);

//setAscNumList関数
function setAscNumList($start, $end, $add = 1) {
	$ret = array();
	for($i = $start; $i <= $end; $i = $i + $add) {
		$ret[$i] = $i;
	}
	return $ret;
}

//setPulldown関数
function setPulldown($list, $default = "", $format = "%s") {
	$ret = "";
	if(is_array($list)) {
		foreach($list as $key => $val) {
			$ret .= '<option value="'. $val . '"' . ($default == $val ? ' selected' : '') . '>' . sprintf($format, $val) . '</option>';
		}
	}
	return $ret;
}

//setPulldown関数
function setPulldown2($list, $default = "", $format = "%s") {
	$ret = "";
	if(is_array($list)) {
		foreach($list as $key => $val) {
			$ret .= '<option value="'. $key . '"' . ($default == $key ? ' selected' : '') . '>' . sprintf($format, $val) . '</option>';
		}
	}
	return $ret;
}

//setRadio
function setRadio($list,$default = ""){
	$ret = "";
	if(is_array($list)){
		foreach($list as $key => $val){
			if($default=="" && $key=="0"){
				$check = 'checked="checked"';
			}else{
				$check = "";
			}
			$tab = "";
			if($key>0)
			$tab = "&nbsp;&nbsp;&nbsp;";

			$ret .= $tab.'<input type="radio" '.($default == $val[1] ?'checked="checked"':'').' '.$check.'name="'.$val[0].'" id="'.$val[0].($key+1).'" value="'.$val[1].'" />'.$val[1];
		}
	}
	return $ret;
}

//setCheckbox
function setCheckbox($list,$name="",$default = array(),$linefeed =""){
	$ret = "";
	if(is_array($list)){
		$cbarr = array();
		foreach($list as $key => $val){
			if(count($default)>0){
				$checked = '';
				for($i=0;$i<count($default);$i++){
					if($default[$i] == $val){
						$checked = 'checked="checked"';
						break;
					}
				}
			}
			$ret .= '<input  type="checkbox"  '.$checked.' name="'.$name.'[]" value="'.$val.'">'.$val.$linefeed;
		}
	}
	return $ret;
}

function setCheckbox2($list,$default = ""){
	$ret = "";
	if(is_array($list)){
		$cbarr = array();
		if($default!=""){
			$cbarr = explode(",",$default);
		}
		foreach($list as $key => $val){
			$space = "";
			$space1 = " ";
			if(count($cbarr)>0){
				
				$checked = '';
				for($i=0;$i<count($cbarr);$i++){
					if($cbarr[$i] == $key){
						$checked = 'checked="checked"';
						break;
					}
				}
			}
			// if($key==1){
// 				$space = "&nbsp;&nbsp;&nbsp; ";
// 			}
// 			if($key==3 || $key==6){
// 				$space1 = "<br />";
// 			}
			
			$ret .= '<div class="cat"><label for="cat01"><input type="checkbox" name="category[]" value="'.$key.'" '.$checked.'/><label for="shop_category" >'.$val.$space.$space1.'</label></div>';
			
		}
	}
	return $ret;
}


// Function to get the client IP address
function get_client_ip() {
    $ipaddress = '';
    if ($_SERVER['HTTP_CLIENT_IP'])
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if($_SERVER['HTTP_X_FORWARDED_FOR'])
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if($_SERVER['HTTP_X_FORWARDED'])
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if($_SERVER['HTTP_FORWARDED_FOR'])
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if($_SERVER['HTTP_FORWARDED'])
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if($_SERVER['REMOTE_ADDR'])
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}


function checkfortimepv($time, $flag){
	//1 >> １日ごと
	//2 >> １時間ごと
	//3 >> 30分ごと
	//4 >> 10分ごと
	//5 >> 5分ごと
	//6 >> １分ごと　
	//7 >> １週間ごと
	//8 >> １ヶ月ごと
	if($flag == 1){
		return (strtotime('-1 days') > strtotime($time));
	}
	else if($flag == 2){
		return (strtotime('-1 hours') > strtotime($time));
	}
	else if($flag == 3){
		return (strtotime('-30 minutes') > strtotime($time));
	}
	else if($flag == 4){
		return (strtotime('-10 minutes') > strtotime($time));
	}
	else if($flag == 5){
		return (strtotime('-5 minutes') > strtotime($time));
	}
	else if($flag == 6){
		return (strtotime('-1 minutes') > strtotime($time));
	}
	else if($flag == 7){
		return (strtotime('-1 week') > strtotime($time));
	}
	else if($flag == 8){
		return (strtotime('-1 month') > strtotime($time));
	}
	return false;
}

function timeOpen($time,$otime){
	$timestr = "";
	if($time == 0){
		$timestr = "0分前";
	}
	if($time!="" && $time != 0){
		$daystr = explode(".", $time);
		if($daystr[0]!="0"){
			$timestr = date('m月d日',strtotime($otime));
		}else{
			$hourarr =  explode(".", (("0.".$daystr[1])*24));
			if($hourarr[0]>'0'){
				$timestr = $hourarr[0]."時間前";
			}else{
				$minutearr = explode(".", (("0.".$hourarr[1])*60));
				$timestr = $minutearr[0]."分前";
			}

		}
	}
	return $timestr;
}


function resetBlockSession(){
	unset($_SESSION['current_block']);
	unset($_SESSION['entry_img1']);
	unset($_SESSION['entry_img2']);
	unset($_SESSION['entry_img3']);
	unset($_SESSION['entry_img4']);
	unset($_SESSION['entry_img5']);
	unset($_SESSION['entry_img6']);
	unset($_SESSION['entry_img7']);
	unset($_SESSION['entry_img8']);
}

?>
