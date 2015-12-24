<?php
/*
* ファイル名 : main.php
* 機能名   : クーポン管理一覧ページ
* 作成者   : ネルソン
* 作成日   : 2015/10/19
*/

/***********************
 * 定義
***********************/
require_once "cinderella/ipfTemplate.php";
require_once "tiary/ipfDB.php";
require_once "define_admin.php";
/***********************
 * コンストラクタ
***********************/
$ins_ipfTemplate = new ipfTemplate();
$ins_ipfDB = new ipfDB;
$ins_ipfDB->ini("admin");
/***********************
 * 画面表示処理
***********************/
session_start();

if(!$_SESSION["admin"]){
	header('Location: index.php');
}

$PAGE_VALUE["search_shopid"] = $_POST["search_shopid"];
$PAGE_VALUE["search_shopname"] = $_POST["search_shopname"];

if($_POST["search_flag"]){
	$page = 0;
	unset($_SESSION["sess_shopid"]);
	unset($_SESSION["sess_shopname"]);
    unset($_SESSION["sess_s_couponyn"]);
}

//店舗ID検索
if($_POST["search_shopid"]!=""){
	$_SESSION["sess_shopid"] = $_POST["search_shopid"];
}
if($_SESSION["sess_shopid"]!=""){
	$PAGE_VALUE["search_shopid"] = $_SESSION["sess_shopid"];
}
//店舗名検索
if($_POST["search_shopname"]!=""){
	$_SESSION["sess_shopname"] = $_POST["search_shopname"];
}
if($_SESSION["sess_shopname"]!=""){
	$PAGE_VALUE["search_shopname"] = $_SESSION["sess_shopname"];
}


//ユーザー削除
if($_POST["search_flag1"]=="del"){
	if($_POST["delete_id"]){
		$cinderella_admin->deleteShop(implode(',', $_POST["delete_id"]));
		header("Location: shop.php");
	}
}

if($_POST["search_a_addtime"]!=""){
	$_SESSION["sess_a_addtime"] = $_POST["search_a_addtime"];
}

if($_POST["search_pref"]!=""){
	$_SESSION["sess_pref"] = $_POST["search_pref"];
}

if($_POST["shop_coupon_yn"]!=""){
	$_SESSION["sess_s_couponyn"] = $_POST["shop_coupon_yn"];
}

$PAGE_VALUE["shop_pref_pulldown"] = setOptions($todoufukens,$_SESSION["sess_pref"]);
$PAGE_VALUE["shop_addtime_pulldown"] = setOptions($addtime_sorts,$_SESSION["sess_a_addtime"]);
$PAGE_VALUE["shop_coupon_yn_pulldown"] = setOptions($shop_coupon_yn,$_SESSION["sess_s_couponyn"]);

$template_file = "shop.template";
$valuesForLoop['dataAll'] = array();
$valuesForLoop['pages'] = array();
$PAGE_VALUE['str_prev_page'] = "";
$PAGE_VALUE['str_next_page'] = "";
session_start();
//ページデータのセット
$page = $_REQUEST['p'];
if(!$page)
$page = 0;
$npage = 20;

$PAGE_VALUE["all_num"] = $admin->selectShopAllNum();
$dataCnt = $admin->selectShopCntAllCin($_SESSION["sess_shopid"],$_SESSION["sess_shopname"],$_SESSION["sess_pref"],"");

$PAGE_VALUE["search_num"] = $dataCnt;
$valuesForLoop['dataAll'] = $admin->selectShopAllCin($_SESSION["sess_shopid"],$_SESSION["sess_shopname"],$_SESSION["sess_pref"],"",$_SESSION["sess_a_addtime"],$npage,$page);

foreach($valuesForLoop['dataAll'] as $key => $val) {

	$valuesForLoop['dataAll'][$key]["no"] = ($key+1)+($page*$npage);
	$valuesForLoop['dataAll'][$key]["id"] = $val["id"];//mb_substr($val["sc_title"],0,20,"UTF-8");
						
	$valuesForLoop['dataAll'][$key]["name"] = $val["shop_name"];
	$valuesForLoop['dataAll'][$key]["addtime"] = date("Y/m/d H:i",strtotime($val["shop_addtime"]));
	
	$valuesForLoop['dataAll'][$key]["pref"] = $val["shop_pref"];
	
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
							$valuesForLoop['pages'][$i]['ipage_link_str'] = '<a href="shop.php?p='.$i.'">';
							$valuesForLoop['pages'][$i]['ipage_link_a'] = '</a>';
						}else{
							continue;
						}
					}elseif($i<10){
						$valuesForLoop['pages'][$i]['ipage_link_str'] = '<a href="shop.php?p='.$i.'">';
						$valuesForLoop['pages'][$i]['ipage_link_a'] = '</a>';

					}else{
						break;
					}
				}else{
					$valuesForLoop['pages'][$i]['ipage_link_str'] = '<a href="shop.php?p='.$i.'">';
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