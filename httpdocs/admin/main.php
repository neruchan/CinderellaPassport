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
session_start();

if(!$_SESSION["admin"]){
	header('Location: index.php');
}

//ユーザー削除
if($_POST["search_flag1"]=="del"){
	if($_POST["delete_id"]){
		$cinderella_admin->deleteCoupon(implode(',', $_POST["delete_id"]));
		header("Location: main.php");
	}
}


unset($_SESSION);

//投稿日付検索
if($_POST["search_a_addtime"]!=""){
	$_SESSION["sess_a_addtime"] = $_POST["search_a_addtime"];
}

//カテゴリ検索
if($_POST["search_a_category"]!=""){
	$_SESSION["sess_a_category"] = $_POST["search_a_category"];
}

//投稿日付検索
if($_POST["search_a_pv"]!=""){
	$_SESSION["sess_a_pv"] = $_POST["search_a_pv"];
}

$PAGE_VALUE["article_category_pulldown"] = setOptions($coupon_categorys_dropdown,$_SESSION["sess_a_category"]);
$PAGE_VALUE["article_addtime_pulldown"] = setOptions($addtime_sorts,$_SESSION["sess_a_addtime"]);
$PAGE_VALUE["article_pv_pulldown"] = setOptions($pv_sorts,$_SESSION["sess_a_pv"]);

$template_file = "main.template";
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

$PAGE_VALUE["all_num"] = $cinderella_admin->selectCouponAllNum();
$dataCnt = $cinderella_admin->selectCouponCnt($_SESSION["sess_a_category"],$_SESSION["sess_a_addtime"]);
$PAGE_VALUE["search_num"] = $dataCnt;
$valuesForLoop['dataAll'] = $cinderella_admin->selectCouponAll($_SESSION["sess_a_category"],$_SESSION["sess_a_addtime"],$npage,$page);

foreach($valuesForLoop['dataAll'] as $key => $val) {

	$valuesForLoop['dataAll'][$key]["no"] = ($key+1)+($page*$npage);
	$valuesForLoop['dataAll'][$key]["id"] = $val["id"];//mb_substr($val["sc_title"],0,20,"UTF-8");

	if($val["category"]!="" && $val["category"]!="0"){
		$category = "";
		$categoryarr = explode(',', $val["category"]);
		for ($i = 0; $i < count($categoryarr); $i++) {
			$kigo = "";
			if($i!=0)
			$kigo = ",";
			$category .= $kigo.$coupon_categorys[$categoryarr[$i]];
		}
		$valuesForLoop['dataAll'][$key]["category"] = $category;
	}else{
		$valuesForLoop['dataAll'][$key]["category"] = "";
	}
						
	$valuesForLoop['dataAll'][$key]["title"] = $val["title"];
	$valuesForLoop['dataAll'][$key]["addtime"] = date("Y/m/d H:i",strtotime($val["addtime"]));
	$valuesForLoop['dataAll'][$key]["exp_date_until"] = date("Y/m/d H:i",strtotime($val["exp_date_until"]));
	$valuesForLoop['dataAll'][$key]["shop_id"] = $val["shop_id"];
	$valuesForLoop['dataAll'][$key]["shop_name"] = $val["shop_name"];
	$valuesForLoop['dataAll'][$key]["access_num"] = $val["access_num"];
	
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
							$valuesForLoop['pages'][$i]['ipage_link_str'] = '<a href="main.php?p='.$i.'">';
							$valuesForLoop['pages'][$i]['ipage_link_a'] = '</a>';
						}else{
							continue;
						}
					}elseif($i<10){
						$valuesForLoop['pages'][$i]['ipage_link_str'] = '<a href="main.php?p='.$i.'">';
						$valuesForLoop['pages'][$i]['ipage_link_a'] = '</a>';

					}else{
						break;
					}
				}else{
					$valuesForLoop['pages'][$i]['ipage_link_str'] = '<a href="main.php?p='.$i.'">';
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