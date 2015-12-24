<?php
/*
* ファイル名 : main.php
* 機能名   : トップページ
* 作成者   : tou
* 作成日   : 2013/06/12
*/

/***********************
 * 定義
***********************/
require_once "startyfree/ipfTemplate.php";
require_once "startyfree/ipfDB.php";
require_once "define_admin.php";
/***********************
 * コンストラクタ
***********************/
$ins_ipfTemplate = new ipfTemplate();
$ins_ipfDB = new ipfDB;
$ins_ipfDB->ini("users");
$ins_ipfDB->ini("cupoun");
/***********************
 * 画面表示処理
***********************/
session_start();
$PAGE_VALUE["search_userid"] = "";
$PAGE_VALUE["search_nickname"] = "";


if(!$_SESSION["admin"] && !$_SESSION["shop_id"] ){
	header('Location: index.php');
}

$template_file = "main.template";
$valuesForLoop['dataAll'] = array();
$valuesForLoop['pages'] = array();
$PAGE_VALUE[str_prev_page] = "";
$PAGE_VALUE[str_next_page] = "";
session_start();
//ページデータのセット
$page = $_REQUEST['p'];
if(!$page)
$page = 0;
$npage = 10;
$tg = "";
if($_REQUEST['c'] != ""){
	$tags = $_REQUEST['c'];
	$tg = "&c=".$_REQUEST['c'];
	$PAGE_VALUE["tg"] =$tg;
	$PAGE_VALUE["c"] = $tg;
}

//ユーザー削除
print_r($_POST);
if($_POST["delete_id"]){
	
	$cupoun->deleteCupoun(implode(',', $_POST["delete_id"]));
	header("Location: main.php");
}

if($_POST["search_flag"]){
	$page = 0;
	unset($_SESSION["sess_userid"]);
	unset($_SESSION["sess_nickname"]);
}
if($_POST["search_userid"]!=""){
	$_SESSION["sess_userid"] = $_POST["search_userid"];
}

if($_POST["search_nickname"]!=""){
	$_SESSION["sess_nickname"] = $_POST["search_nickname"];
}

if($_SESSION["sess_userid"]!=""){
	$PAGE_VALUE["search_userid"] = $_SESSION["sess_userid"];
}

if($_SESSION["sess_nickname"]!=""){
	$PAGE_VALUE["search_nickname"] = $_SESSION["sess_nickname"];
}

//sessionリセット
unset($_SESSION["sess_entryid"]);
unset($_SESSION["sess_entrytitle"]);
unset($_SESSION["sess_shopid"]);
unset($_SESSION["sess_shopname"]);

$shop_id = $_SESSION["shop_id"];

//ユーザー情報取得

$dataCnt = $cupoun->getAllNumberOfCupoun($shop_id);
$valuesForLoop['dataAll'] = $cupoun->getAllCupoun($npage,$page, $shop_id);
foreach($valuesForLoop['dataAll'] as $key => $val) {
	 $valuesForLoop['dataAll'][$key]["no"] = ($key+1)+($page*10);
	 $valuesForLoop['dataAll'][$key]["id"] = $val["id"];//mb_substr($val["sc_title"],0,20,"UTF-8");
	 $valuesForLoop['dataAll'][$key]["effective_date"] = date("Y/m/d",strtotime($val["effective_date"]));
	 $valuesForLoop['dataAll'][$key]["category"] = $shop_categorys[$val["category"]];
	 $valuesForLoop['dataAll'][$key]["title"] = $val["title"];
	 $valuesForLoop['dataAll'][$key]["use_period"] = (date("Y/m/d",strtotime($val["use_period"])));
	 $valuesForLoop['dataAll'][$key]["number_of_use"] = $val["number_of_use"];
	// $valuesForLoop['dataAll'][$key]["cmflag"] = ($val["cmflag"]==0?"ティアリィ":"ティアリィダイレクト");

	// //ティアリィ投稿数
	// $valuesForLoop['dataAll'][$key]["entrynum"] = $admin->selectEntryCount($val["id"]);
	// //カワイイ獲得数
	// $valuesForLoop['dataAll'][$key]["charmnum"] = $admin->selectCharmCount($val["id"]);

	$pageCnt = intval($dataCnt / $npage);
	if($dataCnt > $pageCnt * $npage)
	$pageCnt++;
	//ページング
	if($dataCnt > $npage * ($page + 1)) {
		$PAGE_VALUE[str_next_page] = "次へ &raquo;";
		$PAGE_VALUE[next_page] = $page + 1;
	}

	if($pageCnt > 1) {
		for($i = 0; $i < $pageCnt; $i++) {

			if($i == $page) {
				$valuesForLoop['pages'][$i]['ipage_link_str'] = "";
				$valuesForLoop['pages'][$i]['ipage_link_a'] = "";
			}else {
				if($pageCnt>10){
					if($page>5){
						if(($page-$i)<6 && ($i-$page)<5){
							$valuesForLoop['pages'][$i]['ipage_link_str'] = '<a href="main.php?p='.$i.$tg.'">';
							$valuesForLoop['pages'][$i]['ipage_link_a'] = '</a>';
						}else{
							continue;
						}
					}elseif($i<10){
						$valuesForLoop['pages'][$i]['ipage_link_str'] = '<a href="main.php?p='.$i.$tg.'">';
						$valuesForLoop['pages'][$i]['ipage_link_a'] = '</a>';

					}else{
						break;
					}
				}else{
					$valuesForLoop['pages'][$i]['ipage_link_str'] = '<a href="main.php?p='.$i.$tg.'">';
					$valuesForLoop['pages'][$i]['ipage_link_a'] = '</a>';
				}
			}
			$valuesForLoop['pages'][$i]['ipage'] = $i+1;
		}
	}

	if($page > 0) {
		$PAGE_VALUE[str_prev_page] = "&laquo; 前へ";
		$PAGE_VALUE[prev_page] = $page - 1;
	}
}



//テンプレートファイルの読込
$templateData = $ins_ipfTemplate->loadTemplate($template_file);
$templateData = $ins_ipfTemplate->makeTemplateData($templateData, $PAGE_VALUE, $valuesForLoop);
$ins_ipfTemplate->putMemory($templateData);
$ins_ipfTemplate->view();

?>