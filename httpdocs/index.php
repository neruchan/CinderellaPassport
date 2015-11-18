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
//test
// require_once "cinderella/user_class/startingClass.php";
// $ins_startingClass = new startingClass;
// $sysinfo = $ins_startingClass->getStartingData(9);	//9はログインチェックしない


/***********************
 * コンストラクタ
***********************/
$ins_ipfTemplate = new ipfTemplate();
$ins_ipfDB = new ipfDB;
$ins_ipfDB->ini("cinderella");

require_once "define.php";
/***********************
 * 画面表示処理
***********************/
//自動ログイン
$PAGE_VALUE['mottomiru'] = "";

// require_once "common/user_main.php";

$categoryId = $_GET['cid'];

//ページデータのセット
$page = $_REQUEST['p'];
if(!$page)
	$page = 0;

$npage = 8;

//TOP
$dataCnt = $cinderella->selectCouponNumberCnt("",$categoryId);
$osusumeCoupon = $cinderella->selectCouponOsusume($categoryId);
if($osusumeCoupon){
    $PAGE_VALUE['osusume_id'] = $osusumeCoupon[0]['id'];
    $PAGE_VALUE['osusume_title'] = $osusumeCoupon[0]['title'];
    $PAGE_VALUE['osusume_pic'] = $osusumeCoupon[0]['pic_url'];
    $PAGE_VALUE['osusume_shopname'] = $osusumeCoupon[0]['shop_name'];
    $PAGE_VALUE['osusume_before'] = $osusumeCoupon[0]['before_price'];
    $PAGE_VALUE['osusume_after'] = $osusumeCoupon[0]['after_price'];
}
else{
    $osusumeCoupon = $cinderella->selectCouponNewest($categoryId,1);
    $PAGE_VALUE['osusume_id'] = $osusumeCoupon[0]['id'];
    $PAGE_VALUE['osusume_title'] = $osusumeCoupon[0]['title'];
    $PAGE_VALUE['osusume_pic'] = $osusumeCoupon[0]['pic_url'];
    $PAGE_VALUE['osusume_shopname'] = $osusumeCoupon[0]['shop_name'];
    $PAGE_VALUE['osusume_before'] = $osusumeCoupon[0]['before_price'];
    $PAGE_VALUE['osusume_after'] = $osusumeCoupon[0]['after_price'];
}


$valuesForLoop['dataAll'] = $cinderella->selectCoupon("",$categoryId,$npage,$page);
foreach ($valuesForLoop['dataAll'] as $key =>$val) {

	if($dataCnt > $npage){
		$PAGE_VALUE['mottomiru'] = '
			<div class="button-more">
                <p>もっと見る</p>
            </div>';
	}

	$classLR = "";
	$openTag = "";
	$closeTag = "";
	if($key % 2 == 0){
		$openTag = '<div class="tiled-content clearfix">';
		$classLR = "left";
	}
	else{
		$closeTag = "</div>";
		$classLR = "right";
	}
	
	$valuesForLoop['dataAll'][$key]['classLR'] = $classLR;
	$valuesForLoop['dataAll'][$key]['openTag'] = $openTag;
	$valuesForLoop['dataAll'][$key]['closeTag'] = $closeTag;

	$valuesForLoop['dataAll'][$key]['id'] = $val['id'];
	$valuesForLoop['dataAll'][$key]['category'] = $coupon_category[$val['category']];
	$valuesForLoop['dataAll'][$key]['exp_date'] = date('Y.m.d',strtotime($val['exp_date']));
	$valuesForLoop['dataAll'][$key]['title'] = mb_strimwidth($val['title'], 0, 120,'…',utf8);
	$valuesForLoop['dataAll'][$key]['shop_name'] = $val['shop_name'];
	$valuesForLoop['dataAll'][$key]['shop_station'] = $val['shop_station'];
	$valuesForLoop['dataAll'][$key]['description'] = mb_strimwidth($val['description'], 0, 120,'…',utf8);
	$valuesForLoop['dataAll'][$key]['pic_url'] = $val['pic_url'];
	$valuesForLoop['dataAll'][$key]['before_price'] = $val['before_price'];
	$valuesForLoop['dataAll'][$key]['after_price'] = $val['after_price'];
	$valuesForLoop['dataAll'][$key]['addtime'] = date('Y.m.d',strtotime($val['addtime']));
}


$template_file = "index.template";
//テンプレートファイルの読込
$templateData = $ins_ipfTemplate->loadTemplate($template_file);
$templateData = $ins_ipfTemplate->makeTemplateData($templateData, $PAGE_VALUE, $valuesForLoop);
$ins_ipfTemplate->putMemory($templateData);
$ins_ipfTemplate->view();

?>