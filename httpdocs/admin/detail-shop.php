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
$INI_DATA = parse_ini_file("cinderella/ipf.ini");
$ins_ipfTemplate = new ipfTemplate();
$ins_ipfDB = new ipfDB;
$ins_ipfDB->ini("admin");
/***********************
 * 画面表示処理
***********************/

$template_file = "detail-shop.template";

session_start();
if(!$_SESSION["admin"]){
	header('Location: index.php');
}

if($_GET["no"]=="" || $_GET["sid"]==""){
	header('Location: shop.php');
}



$shopData = $admin->selectShopDataByID($_GET["sid"]);

if(count($shopData)>0){
	
	$PAGE_VALUE["no"] = $_GET["no"];
	$PAGE_VALUE["id"] =$_GET["sid"];
	$PAGE_VALUE["shop_name"] = $shopData["shop_name"];
    $PAGE_VALUE["shop_name_kana"] = $shopData["shop_name_kana"];
    $PAGE_VALUE["shop_pref"] = $shopData["shop_pref"];
    $PAGE_VALUE["shop_city"] = $shopData["shop_city"];
    $PAGE_VALUE["shop_zip"] = $shopData["shop_zip"];
    $PAGE_VALUE["shop_address"] = $shopData["shop_address"];
    $PAGE_VALUE["shop_phone"] = $shopData["shop_phone"];
    
    $catestr = "";
    if($shopData["shop_category"]!=""){
        $catearr = explode(',', $shopData["shop_category"]);
        foreach ($catearr as $key=>$val) {
            $kigou ="";
            if($key!=0)
            $kigou = ",";
            $catestr .= $kigou.$coupon_categorys[$val];
        }
    }
    $PAGE_VALUE["category"] = $catestr;
    
    if($shopData["shop_img"]){
        if (strpos($shopData["shop_img"],$INI_DATA['domain_url']) !== false) {
            $shopImg1 = $shopData["shop_img"];
        }
        else{
            $shopImg1 = "//tiary.jp/s/pjpic/".$shopData["shop_img"];
        }

        $PAGE_VALUE["shop_img"] = $shopImg1;
    }
    else{
        $PAGE_VALUE["shop_img"] = "//cinderella.tiary.jp/img/no-img2.jpg";
    }
    
    
    if($shopData["shop_img2"]){
        if (strpos($shopData["shop_img2"],$INI_DATA['domain_url']) !== false) {
            $shopImg2 = $shopData["shop_img2"];
        }
        else{
            $shopImg2 = "//tiary.jp/s/pjpic/".$shopData["shop_img2"];
        }
        $PAGE_VALUE["shop_img2"] = $shopImg2;
    }
    else{
        $PAGE_VALUE["shop_img2"] = "//cinderella.tiary.jp/img/no-img2.jpg";
    }
    
    if($shopData["shop_img3"]){
        if (strpos($shopData["shop_img3"],$INI_DATA['domain_url']) !== false) {
            $shopImg3 = $shopData["shop_img3"];
        }
        else{
            $shopImg3 = "//tiary.jp/s/pjpic/".$shopData["shop_img3"];
        }
        $PAGE_VALUE["shop_img3"] = $shopImg3;
    }
    else{
        $PAGE_VALUE["shop_img3"] = "//cinderella.tiary.jp/img/no-img2.jpg";
    }
    
    
    $PAGE_VALUE["shop_opentime"] = $shopData["shop_opentime"];
    $PAGE_VALUE["shop_holiday"] = $shopData["shop_holiday"];
    $PAGE_VALUE["shop_homepage"] = $shopData["shop_homepage"];
    $PAGE_VALUE["shop_eki"] = $shopData["shop_eki"];
    $PAGE_VALUE["shop_access"] = $shopData["shop_access"];
    $PAGE_VALUE["shop_notes"] = $shopData["shop_notes"];
    $PAGE_VALUE["shop_average_price"] = $shopData["shop_average_price"];
    $PAGE_VALUE["shop_addtime"] = $shopData["shop_addtime"];
    $PAGE_VALUE["shop_updatetime"] = $shopData["shop_updatetime"];    
    $PAGE_VALUE["shop_email"] = $shopData["shop_email"];    
    $PAGE_VALUE["shop_aff_tag"] = $shopData["shop_aff_tag"]; 
}else{
	header('Location: shop.php');
}

//テンプレートファイルの読込
$templateData = $ins_ipfTemplate->loadTemplate($template_file);
$templateData = $ins_ipfTemplate->makeTemplateData($templateData, $PAGE_VALUE, $valuesForLoop);
$ins_ipfTemplate->putMemory($templateData);
$ins_ipfTemplate->view();
?>