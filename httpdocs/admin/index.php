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
$ins_ipfDB->ini("cinderella_admin");
/***********************
 * 画面表示処理
 ***********************/
//共通処理
session_start();
$template_file = "index.template";
$PAGE_VALUE[iderror] = "";
$PAGE_VALUE[userid] = $_REQUEST['userid'];
$PAGE_VALUE[pass] = $_REQUEST['pass'];
$aryCookie = unserialize($_COOKIE['ci_session']);
// print_r($_POST);
// print_r($_COOKIE);

//  echo $me['email'];
if($_REQUEST["login_flag"]!=""){
	//普通の会員ログイン関数
	if(!$_REQUEST["userid"])
		$PAGE_VALUE[iderror] = "ログインIDが未入力。";
	if(!$_REQUEST["pass"])
		$PAGE_VALUE[iderror] = "パスワードIDが未入力。";

	if($_REQUEST["userid"] && $_REQUEST["pass"]){
// 		var_dump($_POST);
		$passValidation = ($_REQUEST["userid"] == "cin-admin" && $_REQUEST["pass"] == "1nJYOdLRSa");
		if($passValidation>0){
			$_SESSION["admin"] = $_REQUEST["userid"];
			header('Location: main.php');
			exit;
		}else{
			$PAGE_VALUE[iderror] = "※ID、またはパスワードが間違っています。";
		}
	}

}


//テンプレートファイルの読込
$templateData = $ins_ipfTemplate->loadTemplate($template_file);
$templateData = $ins_ipfTemplate->makeTemplateData($templateData, $PAGE_VALUE, $valuesForLoop);
$ins_ipfTemplate->putMemory($templateData);
$ins_ipfTemplate->view();


?>