<?php
/*
 *ファイル名 : detail.php
 * 機能名    : クーポン詳細ページ
 * 作成者    : ネルソン
 * 作成日    : 15/10/19
 */

/***********************
 * 定義
***********************/
require_once "cinderella/ipfTemplate.php";
require_once "cinderella/ipfDB.php";
require_once "define_admin.php";
/***********************
 * コンストラクタ
***********************/
$ins_ipfTemplate = new ipfTemplate();
$ins_ipfDB = new ipfDB;
$ins_ipfDB->ini("cinderella_admin");
/***********************
 * 画面表示処理
***********************/

$template_file = "detail-shop.template";

session_start();
if(!$_SESSION["admin"]){
	header('Location: index.php');
}

if($_GET["no"]=="" || $_GET["sid"]==""){
	header('Location: shop.php');
}



$shopData = $cinderella_admin->selectShopByID($_GET["sid"]);
if(count($shopData)>0){
	
	$PAGE_VALUE["no"] = $_GET["no"];
	$PAGE_VALUE["id"] =$_GET["sid"];
	$PAGE_VALUE["name"] = $shopData["name"];
	$PAGE_VALUE["address"] = $shopData["address"];
    $PAGE_VALUE["email"] = $shopData["email"];
    $PAGE_VALUE["phone"] = $shopData["phone"];
    $PAGE_VALUE["station"] = $shopData["station"];
    $PAGE_VALUE["pref"] = $todoufukens[$shopData["pref"]];
    $PAGE_VALUE["zip"] = $shopData["zip"];
    $PAGE_VALUE["access"] = $shopData["access"];
    $PAGE_VALUE["website"] = $shopData["website"];
    $PAGE_VALUE["detail"] = $shopData["detail"];
    $PAGE_VALUE["eigyo_jikan"] = $shopData["eigyo_jikan"];
    $PAGE_VALUE["holiday"] = $shopData["holiday"];
    $PAGE_VALUE["average_price"] = $shopData["average_price"];
    $PAGE_VALUE["addtime"] = date('Y/m/d H:s',strtotime($shopData["addtime"]));
    $PAGE_VALUE["pic_url1"] = $shopData["pic_url1"];
    $PAGE_VALUE["pic_url2"] = $shopData["pic_url2"];
    $PAGE_VALUE["pic_url3"] = $shopData["pic_url3"];
    
    
}else{
	header('Location: shop.php');
}

//テンプレートファイルの読込
$templateData = $ins_ipfTemplate->loadTemplate($template_file);
$templateData = $ins_ipfTemplate->makeTemplateData($templateData, $PAGE_VALUE, $valuesForLoop);
$ins_ipfTemplate->putMemory($templateData);
$ins_ipfTemplate->view();
?>