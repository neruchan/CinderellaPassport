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

 require_once "cinderella/user_class/startingClass.php";
 $ins_startingClass = new startingClass;
 $sysinfo = $ins_startingClass->getStartingData();	//9はログインチェックしない


/***********************
 * コンストラクタ
***********************/
$ins_ipfTemplate = new ipfTemplate();
$ins_ipfDB = new ipfDB;
$ins_ipfDB->ini("cinderella");

 require_once "define.php";
/***********************
 * 画面表示処理
***********************/
//自動ログイン

$coupon_id = $_GET['id'];

$PAGE_VALUE['coupon_id'] = $coupon_id;

if(!$coupon_id){
	header('Location: index.php');
}

$PAGE_VALUE['header_title'] = "";

$PAGE_VALUE["day_1"] = setOptions($open_days,$_POST['day_1']);
$PAGE_VALUE["month_1"] = setOptions($open_months,$_POST['month_1']);
$PAGE_VALUE["hour_1"] = setOptions($open_hours,$_POST['hour_1']);
$PAGE_VALUE["minute_1"] = setOptions($open_minutes,$_POST['minute_1']);

$PAGE_VALUE["day_2"] = setOptions($open_days,$_POST['day_2']);
$PAGE_VALUE["month_2"] = setOptions($open_months,$_POST['month_2']);
$PAGE_VALUE["hour_2"] = setOptions($open_hours,$_POST['hour_2']);
$PAGE_VALUE["minute_2"] = setOptions($open_minutes,$_POST['minute_2']);

$PAGE_VALUE["day_3"] = setOptions($open_days,$_POST['day_3']);
$PAGE_VALUE["month_3"] = setOptions($open_months,$_POST['month_3']);
$PAGE_VALUE["hour_3"] = setOptions($open_hours,$_POST['hour_3']);
$PAGE_VALUE["minute_3"] = setOptions($open_minutes,$_POST['minute_3']);


$couponData = $cinderella->selectCouponByID($coupon_id);

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
}


$template_file = "book-form.template";
//テンプレートファイルの読込
$templateData = $ins_ipfTemplate->loadTemplate($template_file);
$templateData = $ins_ipfTemplate->makeTemplateData($templateData, $PAGE_VALUE, $valuesForLoop);
$ins_ipfTemplate->putMemory($templateData);
$ins_ipfTemplate->view();

?>