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
require_once "akb/ipfTemplate.php";
require_once "akb/ipfDB.php";
error_reporting(E_ERROR | E_WARNING | E_PARSE);
/***********************
 * セッション格納処理
***********************/

require_once "akb/startingClass.php";
$ins_startingClass = new startingClass;
$sysinfo = $ins_startingClass->getStartingData(9);	//9はログインチェックしない


/***********************
 * コンストラクタ
***********************/
$ins_ipfTemplate = new ipfTemplate();
$ins_ipfDB = new ipfDB;
$ins_ipfDB->ini("akbdb");

require_once "define.php";
/***********************
 * 画面表示処理
***********************/
//自動ログイン


require_once "common/user_main.php";




$template_file = "natsuki.template";
//テンプレートファイルの読込
$templateData = $ins_ipfTemplate->loadTemplate($template_file);
$templateData = $ins_ipfTemplate->makeTemplateData($templateData, $PAGE_VALUE, $valuesForLoop);
$ins_ipfTemplate->putMemory($templateData);
$ins_ipfTemplate->view();

?>