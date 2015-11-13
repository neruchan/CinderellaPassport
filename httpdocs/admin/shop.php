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

$PAGE_VALUE["article_pref_pulldown"] = setOptions($todoufukens,$_SESSION["sess_pref"]);
$PAGE_VALUE["article_addtime_pulldown"] = setOptions($addtime_sorts,$_SESSION["sess_a_addtime"]);




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

$PAGE_VALUE["all_num"] = $cinderella_admin->selectShopAllNum();
$dataCnt = $cinderella_admin->selectShopCnt($_SESSION["sess_pref"],$_SESSION["sess_a_addtime"]);
$PAGE_VALUE["search_num"] = $dataCnt;
$valuesForLoop['dataAll'] = $cinderella_admin->selectShopAll($_SESSION["sess_pref"],$_SESSION["sess_a_addtime"],$npage,$page);

foreach($valuesForLoop['dataAll'] as $key => $val) {

	$valuesForLoop['dataAll'][$key]["no"] = ($key+1)+($page*$npage);
	$valuesForLoop['dataAll'][$key]["id"] = $val["id"];//mb_substr($val["sc_title"],0,20,"UTF-8");
						
	$valuesForLoop['dataAll'][$key]["name"] = $val["name"];
	$valuesForLoop['dataAll'][$key]["addtime"] = date("Y/m/d H:i",strtotime($val["addtime"]));
	
	$valuesForLoop['dataAll'][$key]["pref"] = $todoufukens[$val["pref"]];
	
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