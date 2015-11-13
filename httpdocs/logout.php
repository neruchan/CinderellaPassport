<?php
/*
 * ファイル名 : logout.php
 * 機能名   : ログアウト処理
 * 作成者   : tou
 * 作成日   : 2012/10/11
 * 更新日   : 2012/10/11
 */

/***********************
 * セッション格納処理
 ***********************/
$viewid = basename(__FILE__, '.php');
/***********************
 * 定義
 ***********************/
require_once "cinderella/ipfDB.php";
/***********************
 * コンストラクタ
 ***********************/
$ins_ipfDB = new ipfDB;
$ins_ipfDB->ini("login");
$ins_ipfDB->ini("myautologin");
/***********************
 * 画面表示処理
 ***********************/
//共通処理
session_start();

$aryCookie = unserialize($_COOKIE['ci_session']);
//COOKIE情報削除
$login->deleteCookie($aryCookie['session_id']);
$myautologin->delete_autologin();
$login->clearsession($aryCookie['session_id']);
//setcookie('ci_session','');
setcookie('ci_session','',0,'/');

// $facebook = new Facebook(array(
// 	'appId' => 	'288711291241062',
// 	'secret' => '8c52b7dfa54ab48901f4425603e4deab',
// 	'cookie' => true,
// ));
// $user = $facebook->getUser();
// // echo $user;
// if($user!=""){
// 	setcookie('fbs_'.$facebook->getAppId(),'', time()-100, '/', 'http://www.startyfree.jp/');
// 	$facebook->destroySession();
//  	unset($_COOKIE[$cookie_name]);
// }


header('Location: index.php');
exit;

?>