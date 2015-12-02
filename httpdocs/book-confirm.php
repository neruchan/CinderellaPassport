<?php

/*
* ファイル名 : index.php
* 機能名   : トップページ
* 作成者   : tou
* 作成日   : 2012/9/27
*/

/***********************
 * 定義
***********************/
require_once "cinderella/ipfTemplate.php";
require_once "cinderella/ipfDB.php";
/***********************
 * セッション格納処理
***********************/

// require_once "akb/startingClass.php";
// $ins_startingClass = new startingClass;
// $sysinfo = $ins_startingClass->getStartingData(9);	//9はログインチェックしない


/***********************
 * コンストラクタ
***********************/
$ins_ipfTemplate = new ipfTemplate();
$ins_ipfDB = new ipfDB;
$ins_ipfDB->ini("cinderella");

// require_once "define.php";
/***********************
 * 画面表示処理
***********************/
//自動ログイン


// require_once "common/user_main.php";


$couponData = $cinderella->selectCouponByID($_POST['coupon_id']);

if(count($couponData)>0){
    
    $PAGE_VALUE['header_title'] = $couponData["shop_name"];
    
    $PAGE_VALUE['shop_id'] = $couponData["shop_id"];
	$PAGE_VALUE['shop_name'] = $couponData["shop_name"];
	$PAGE_VALUE['shop_address'] = $couponData["address"];
	$PAGE_VALUE['shop_email'] = $couponData["email"];
	$PAGE_VALUE['shop_phone'] = $couponData["phone"];
	$PAGE_VALUE['shop_station'] = $couponData["station"];
	$PAGE_VALUE['shop_pref'] = $couponData["pref"];
	$PAGE_VALUE['shop_zip'] = $couponData["zip"];
	$PAGE_VALUE['shop_pic1'] = $couponData["shop_pic1"];
	$PAGE_VALUE['shop_pic2'] = $couponData["shop_pic2"];
	$PAGE_VALUE['shop_pic3'] = $couponData["shop_pic3"];
    $PAGE_VALUE['shop_access'] = $couponData["access"];
    $PAGE_VALUE['shop_website'] = $couponData["website"];
    $PAGE_VALUE['shop_detail'] = $couponData["detail"];
    $PAGE_VALUE['shop_jikan'] = $couponData["eigyo_jikan"];
    $PAGE_VALUE['shop_holiday'] = $couponData["holiday"];
    $PAGE_VALUE['shop_average'] = $couponData["average_price"];
	
	$PAGE_VALUE['title'] = $couponData["title"];
	$PAGE_VALUE['category'] = $coupon_category[$couponData['category']];
	$PAGE_VALUE['description'] = $couponData["description"];
	$PAGE_VALUE['coupon_pic'] = $couponData["pic_url"];
    $PAGE_VALUE['addtime'] = $couponData["addtime"];
    $PAGE_VALUE['exp_date_from'] = $couponData["exp_date_from"];
	$PAGE_VALUE['exp_date_until'] = $couponData["exp_date_until"];
    $PAGE_VALUE['warning'] = $couponData["warning"];
	
	$PAGE_VALUE['before_price'] = $couponData["before_price"];
	$PAGE_VALUE['after_price'] = $couponData["after_price"];
    
    $userid=$sysinfo['user_id'];
    if(!$userid){
        header('Location: login.php');
    }
    
    $userDirect = json_decode(file_get_contents('http://direct.tiary.jp/eccube/api/get_user_status.php?i='.$userid));

    $status = $userDirect->{'status'};
    
    if($status == "OK"){
        $tempUserAddressData = $userDirect->{'result'};
        $userAddressData = get_object_vars($tempUserAddressData);
        
        $PAGE_VALUE['name01'] = $userAddressData['name01'];
	    $PAGE_VALUE['name02'] = $userAddressData['name02'];
        $PAGE_VALUE['email'] = $userAddressData['email'];
        $PAGE_VALUE['telephone'] = $userAddressData['tel01']."-".$userAddressData['tel02']."-".$userAddressData['tel03'];
    }    
}



$PAGE_VALUE['first_kibou'] = date_japan($_POST['month_1'], $_POST['day_1']);
$PAGE_VALUE['first_time'] = $_POST['hour_1'].":".$_POST['minute_1'];
$PAGE_VALUE['second_kibou'] = date_japan($_POST['month_2'], $_POST['day_2']);
$PAGE_VALUE['second_time'] = $_POST['hour_2'].":".$_POST['minute_2'];
$PAGE_VALUE['third_kibou'] = date_japan($_POST['month_3'], $_POST['day_3']);
$PAGE_VALUE['third_time'] = $_POST['hour_3'].":".$_POST['minute_3'];

function date_japan($month, $date) {
    $year = date("Y");
    
    $fullYMD = $year."-".$month."-".$date;
    
    $dy  = date("w", strtotime($fullYMD));

    $dys = array("日","月","火","水","木","金","土");
    $dyj = $dys[$dy];
    
    $completeString = $year . '年 ' . $month . '月 ' . $date . '日' . '(' . $dyj . ')';
    
    return $completeString;
}


$template_file = "book-confirm.template";
//テンプレートファイルの読込
$templateData = $ins_ipfTemplate->loadTemplate($template_file);
$templateData = $ins_ipfTemplate->makeTemplateData($templateData, $PAGE_VALUE, $valuesForLoop);
$ins_ipfTemplate->putMemory($templateData);
$ins_ipfTemplate->view();

?>