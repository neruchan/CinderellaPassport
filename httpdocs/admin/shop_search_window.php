<?php
/*
* ファイル名 : index.php
* 機能名   : トップページ
* 作成者   : tou
* 作成日   : 2013/7/29
*/

/***********************
 * 定義
***********************/
require_once "cinderella/ipfTemplate.php";
require_once "cinderella/ipfDB.php";
/***********************
 * コンストラクタ
***********************/
$ins_ipfTemplate = new ipfTemplate();
$ins_ipfDB = new ipfDB;
$ins_ipfDB->ini("admin");
/***********************
 * 画面表示処理
 ***********************/
//共通処理
session_start();
$valuesForLoop['dataAll'] = array();
$template_file = "shop_search_window.template";
$PAGE_VALUE["tag_1"] = "<!--";
$PAGE_VALUE["tag_2"] = "-->";

if($_POST['submit']){
    $PAGE_VALUE["tag_1"] = "";
    $PAGE_VALUE["tag_2"] = "";
    $valuesForLoop['dataAll'] = $admin->selectShopAll("",$_POST['keyword'],"","","","",1000,0);
}


//テンプレートファイルの読込
$templateData = $ins_ipfTemplate->loadTemplate($template_file);
$templateData = $ins_ipfTemplate->makeTemplateData($templateData, $PAGE_VALUE, $valuesForLoop);
$ins_ipfTemplate->putMemory($templateData);
$ins_ipfTemplate->view();


?>