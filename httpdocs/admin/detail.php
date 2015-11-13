<?php
/*
 *ファイル名 : detail.php
 * 機能名    : クーポン詳細ページ
 * 作成者    : ネルソン
 * 作成日    : 15/10/19
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

$template_file = "detail.template";

$PAGE_VALUE["title"] = "";
$PAGE_VALUE["image"] = "";
$PAGE_VALUE["contents"] = "";
$PAGE_VALUE["links"] = "";
$PAGE_VALUE["addtime"] = "";
$PAGE_VALUE["categroy"] = "";
$PAGE_VALUE["writer_name"] ="";
$PAGE_VALUE["no"] ="";
$PAGE_VALUE["aid"] ="";
$PAGE_VALUE["nickname_field"] = '';
$PAGE_VALUE["edit_btn"] = '';
$PAGE_VALUE["video_part"] = '';
$PAGE_VALUE["tag"] = "";
$PAGE_VALUE["click_info"] = "";
session_start();
if(!$_SESSION["admin"]){
	header('Location: index.php');
}

if($_GET["no"]=="" || $_GET["aid"]==""){
	header('Location: main.php');
}

$couponData = $cinderella_admin->selectCouponByID($_GET["aid"]);
if(count($couponData)>0){
	
	$PAGE_VALUE["no"] = $_GET["no"];
	$PAGE_VALUE["aid"] =$_GET["aid"];
	$PAGE_VALUE["title"] = $couponData["title"];
	$PAGE_VALUE["description"] = nl2br($couponData["description"]);
    $PAGE_VALUE["warning"] = nl2br($couponData["warning"]);
    $PAGE_VALUE["addtime"] = date('Y/m/d H:s',strtotime($couponData["addtime"]));
    $PAGE_VALUE["pic_url"] = $couponData["pic_url"];
    $PAGE_VALUE["exp_date_from"] = $couponData["exp_date_from"];
    $PAGE_VALUE["exp_date_until"] = $couponData["exp_date_until"];
    $PAGE_VALUE["before_price"] = $couponData["before_price"];
    $PAGE_VALUE["after_price"] = $couponData["after_price"];
	$PAGE_VALUE["access_num"] = $couponData["access_num"];
    
	$PAGE_VALUE["shop_id"] = $couponData["shop_id"];
    $PAGE_VALUE["shop_name"] = $couponData["shop_name"];
    
    $PAGE_VALUE["osusume"] = ($couponData['osusume_flag']=="1"?" checked":"");
    
	$catestr = "";
    if($couponData["category"]!=""){
        $catearr = explode(',', $couponData["category"]);
        foreach ($catearr as $key=>$val) {
            $kigou ="";
            if($key!=0)
            $kigou = ",";
            $catestr .= $kigou.$coupon_categorys[$val];
        }
    }
    $PAGE_VALUE["category"] = $catestr;
    
    
    $listTags = $cinderella_admin->selectTagsByCouponId($_GET["aid"]);
	if($listTags){
		$printsTags  ="";
		foreach($listTags as $key => $val) {
			$comma = "";
			if($key != 0){
				$comma = ",";
			}
			$printsTags .= $comma.$val['tag_name'];
		}
		$PAGE_VALUE["tag"] = $printsTags;
	}
    
    
}else{
	header('Location: main.php');
}

//テンプレートファイルの読込
$templateData = $ins_ipfTemplate->loadTemplate($template_file);
$templateData = $ins_ipfTemplate->makeTemplateData($templateData, $PAGE_VALUE, $valuesForLoop);
$ins_ipfTemplate->putMemory($templateData);
$ins_ipfTemplate->view();
?>