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

// require_once "define.php";
/***********************
 * 画面表示処理
***********************/
//自動ログイン

$coupon_id = $_GET['id'];

$PAGE_VALUE['coupon_id'] = $coupon_id;

if(!$coupon_id){
	header('Location: index.php');
}





$template_file = "book-form.template";
//テンプレートファイルの読込
$templateData = $ins_ipfTemplate->loadTemplate($template_file);
$templateData = $ins_ipfTemplate->makeTemplateData($templateData, $PAGE_VALUE, $valuesForLoop);
$ins_ipfTemplate->putMemory($templateData);
$ins_ipfTemplate->view();

?>