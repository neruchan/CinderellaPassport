<?php
/*
 *ファイル名 : edit-shop.php
 * 機能名    : 店舗編集ページ
 * 作成者    : ネルソン
 * 作成日    : 15/10/26
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
$INI_DATA = parse_ini_file("cinderella/ipf.ini");
/***********************
 * 画面表示処理
***********************/

$template_file = "edit-shop.template";
$PAGE_VALUE['shop_name_err'] = "";
$PAGE_VALUE['shop_name_kana_err'] = "";
$PAGE_VALUE['shop_email_err'] = "";
$PAGE_VALUE['category_err'] ="";
$PAGE_VALUE['contents_err'] ="";
$PAGE_VALUE['shop_err'] ="";
$PAGE_VALUE['access_err'] ="";
$PAGE_VALUE['shop_details_err'] ="";
$PAGE_VALUE['average_price_err'] ="";
$PAGE_VALUE['eigyo_jikan_err'] ="";
$PAGE_VALUE['category_err'] ="";
$PAGE_VALUE['article_img1_err'] ="";
$PAGE_VALUE['article_img2_err'] ="";
$PAGE_VALUE['article_img3_err'] ="";

$PAGE_VALUE["up_img1"] = "";
$PAGE_VALUE["up_img2"] = "";
$PAGE_VALUE["up_img3"] = "";

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
    
    $PAGE_VALUE["category_checkbox"] = setCheckboxArticle($coupon_categorys,$shopData["shop_category"]);

    
	$PAGE_VALUE["shop_name"] = $shopData["shop_name"];
    $PAGE_VALUE["shop_name_kana"] = $shopData["shop_name_kana"];
    $PAGE_VALUE["shop_pref"] = setOptions($todoufukens,$shopData["shop_pref"]);
    $PAGE_VALUE["shop_city"] = $shopData["shop_city"];
    $zip = explode('-',$shopData["shop_zip"]);
    $PAGE_VALUE["shop_zip1"] = $zip[0];
    $PAGE_VALUE["shop_zip2"] = $zip[1];
    $PAGE_VALUE["shop_address"] = $shopData["shop_address"];
    
    $phone = explode('-',$shopData["shop_phone"]);
    
    $PAGE_VALUE["shop_phone1"] = $phone[0];
    $PAGE_VALUE["shop_phone2"] = $phone[1];
    $PAGE_VALUE["shop_phone3"] = $phone[2];
    
    if($shopData["shop_img"]){
        if (strpos($shopData["shop_img"],$INI_DATA['domain_url']) !== false) {
            $shopImg1 = $shopData["shop_img"];
        }
        else{
            $shopImg1 = "//tiary.jp/s/pjpic/".$shopData["shop_img"];
        }

        $PAGE_VALUE["shop_img"] = $shopImg1;
        $PAGE_VALUE["up_img1"] = $shopImg1;
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
        $PAGE_VALUE["up_img2"] = $shopImg2;
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
        $PAGE_VALUE["up_img3"] = $shopImg3;
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
}
else{
	header('Location: shop.php');
}


if($_POST["add_flag"]!=""){
	
	$error_flag = 0;
	if($_FILES['article_img1']['tmp_name'] != ''){
        $allowedTypes = array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF);
		$detectedType = exif_imagetype($_FILES['article_img1']['tmp_name']);
		$error = !in_array($detectedType, $allowedTypes);
		
		if($error){
			$PAGE_VALUE[article_img1_err] ='<tr><td></td><td><p class="red">※正しくご入力ください。[png,jpg,gif]のみアップロード可能</p></td></tr>';
			$error_flag = 1;
		}
        else{
            $domainUrl = $INI_DATA['domain_url'];
            $uploaddir = $INI_DATA['upload_path'];
            $basename = basename($_FILES['article_img1']['tmp_name']);
            $fileext = strrchr($_FILES['article_img1']['name'], '.');
            $filename = $basename . $fileext;
            $uploadfile = $uploaddir . "/" . $filename;
            $is_uploaded = move_uploaded_file($_FILES['article_img1']['tmp_name'], $uploadfile);
            $_POST["up_img1"] = $domainUrl."".$filename;
        }
        
	}
    
    if($_FILES['article_img2']['tmp_name'] != ''){
        $allowedTypes = array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF);
		$detectedType = exif_imagetype($_FILES['article_img2']['tmp_name']);
		$error = !in_array($detectedType, $allowedTypes);
		
		if($error){
			$PAGE_VALUE[article_img2_err] ='<tr><td></td><td><p class="red">※正しくご入力ください。[png,jpg,gif]のみアップロード可能</p></td></tr>';
			$error_flag = 1;
		}
        else{
            $domainUrl = $INI_DATA['domain_url'];
            $uploaddir = $INI_DATA['upload_path'];
            $basename = basename($_FILES['article_img2']['tmp_name']);
            $fileext = strrchr($_FILES['article_img2']['name'], '.');
            $filename = $basename . $fileext;
            $uploadfile = $uploaddir . "/" . $filename;
            $is_uploaded = move_uploaded_file($_FILES['article_img2']['tmp_name'], $uploadfile);
            $_POST["up_img2"] = $domainUrl."".$filename;
        }
        
	}
    
    if($_FILES['article_img3']['tmp_name'] != ''){
        $allowedTypes = array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF);
		$detectedType = exif_imagetype($_FILES['article_img3']['tmp_name']);
		$error = !in_array($detectedType, $allowedTypes);
		
		if($error){
			$PAGE_VALUE[article_img3_err] ='<tr><td></td><td><p class="red">※正しくご入力ください。[png,jpg,gif]のみアップロード可能</p></td></tr>';
			$error_flag = 1;
		}
        else{
            $domainUrl = $INI_DATA['domain_url'];
            $uploaddir = $INI_DATA['upload_path'];
            $basename = basename($_FILES['article_img3']['tmp_name']);
            $fileext = strrchr($_FILES['article_img3']['name'], '.');
            $filename = $basename . $fileext;
            $uploadfile = $uploaddir . "/" . $filename;
            $is_uploaded = move_uploaded_file($_FILES['article_img3']['tmp_name'], $uploadfile);
            $_POST["up_img3"] = $domainUrl."".$filename;
        }
        
	}

	
	if($_POST["shop_name"] ==""){
		$PAGE_VALUE['shop_name_err'] ='<br><p class="red">※必須項目です。正しくご入力ください。</p>';
		$error_flag = 1;
	}
	elseif(mb_strlen($_POST["shop_name"], 'UTF-8') > 80){
		$PAGE_VALUE['shop_name_err'] ='<br><p class="red">※80文字以内で入力してください。</p>';
		$error_flag = 1;
	}
    
    if($_POST["shop_name_kana"] ==""){
		$PAGE_VALUE['shop_name_kana_err'] ='<br><p class="red">※必須項目です。正しくご入力ください。</p>';
		$error_flag = 1;
	}
	elseif(mb_strlen($_POST["shop_name"], 'UTF-8') > 80){
		$PAGE_VALUE['shop_name_err'] ='<br><p class="red">※80文字以内で入力してください。</p>';
		$error_flag = 1;
	}
    
//    if($_POST["shop_email"] ==""){
//		$PAGE_VALUE['shop_email_err'] ='<br><p class="red">※「メールアドレス」は必須項目です、入力して下さい。</p>';
//		$error_flag = 1;
//	}else{
    if($_POST["shop_email"])
    {
        if(!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $_POST["shop_email"])){
            $PAGE_VALUE['shop_email_err'] ='<br><p class="red">※ 「メールアドレス」に正しく入力してください。</p>';
            $error_flag = 1;
        }
		
	}
    
	if($error_flag !=1){
		mb_regex_encoding('UTF-8');
        mb_internal_encoding("UTF-8"); 
		$_DATA = array();
		$_DATA['shoptbl']['shop_name'] = $_POST['shop_name'];
        $_DATA['shoptbl']['shop_name_kana'] = $_POST['shop_name_kana'];
        $_DATA['shoptbl']['shop_category'] = implode(',', $_POST['category']);
		$_DATA['shoptbl']['shop_notes'] = $_POST["shop_notes"];
        $_DATA['shoptbl']['shop_pref'] = $_POST["shop_pref"];
        $_DATA['shoptbl']['shop_zip'] = $_POST["shop_zip1"]."-".$_POST["shop_zip2"];
        $_DATA['shoptbl']['shop_city'] = $_POST["shop_city"];
		$_DATA['shoptbl']['shop_address'] = $_POST["shop_address"];
        $_DATA['shoptbl']['shop_eki'] = $_POST["shop_eki"];
        $_DATA['shoptbl']['shop_access'] = $_POST["shop_access"];
        $_DATA['shoptbl']['shop_phone'] = $_POST["shop_phone1"]."-".$_POST["shop_phone2"]."-".$_POST["shop_phone3"];
        $_DATA['shoptbl']['shop_opentime'] = $_POST["shop_opentime"];
        $_DATA['shoptbl']['shop_holiday'] = $_POST["shop_holiday"];
        $_DATA['shoptbl']['shop_homepage'] = $_POST["shop_homepage"];
        $_DATA['shoptbl']['shop_email'] = $_POST["shop_email"];
        $_DATA['shoptbl']['shop_average_price'] = $_POST["shop_average_price"];
        
        $_DATA['shoptbl']['shop_aff_tag'] = $_POST["shop_aff_tag"];
        
        $_DATA['shoptbl']['shop_img'] = $_POST["up_img1"];
        $_DATA['shoptbl']['shop_img2'] = $_POST["up_img2"];
        $_DATA['shoptbl']['shop_img3'] = $_POST["up_img3"];
		
        $_DATA['shoptbl']['shop_updatetime'] = date('Y-m-d H:i:s');
		

        
        $ins_ipfDB->dataControl("update", "id = ".$_GET["sid"]);
		
		header('Location: shop.php');
	}else{
        $PAGE_VALUE["shop_name"] = $_POST["shop_name"];
        $PAGE_VALUE["todoufukens"] = setOptions($todoufukens,$_POST['todoufukens']); 
        $PAGE_VALUE["zip"] = $_POST["zip"];
        $PAGE_VALUE["address"] = $_POST["address"];
        $PAGE_VALUE["eki"] = $_POST["eki"];
        $PAGE_VALUE["access"] = $_POST["access"];
        $PAGE_VALUE["eigyo_jikan"] = $_POST["eigyo_jikan"];
        $PAGE_VALUE["holiday"] = $_POST["holiday"];
        
        $PAGE_VALUE["email"] = $_POST["email"];
        
        $PAGE_VALUE["phone"] = $_POST["phone"];
        
        $PAGE_VALUE["shop_website"] = $_POST["shop_website"];
        $PAGE_VALUE["shop_details"] = $_POST["shop_details"];
        
        
        $PAGE_VALUE["average_price"] = $_POST["average_price"];
        $PAGE_VALUE["up_img1"] = $_POST["up_img1"];
        $PAGE_VALUE["up_img2"] = $_POST["up_img2"];
        $PAGE_VALUE["up_img3"] = $_POST["up_img3"];
        $PAGE_VALUE["shop_aff_tag"] = $_POST["shop_aff_tag"]; 
	}
}

//テンプレートファイルの読込
$templateData = $ins_ipfTemplate->loadTemplate($template_file);
$templateData = $ins_ipfTemplate->makeTemplateData($templateData, $PAGE_VALUE, $valuesForLoop);
$ins_ipfTemplate->putMemory($templateData);
$ins_ipfTemplate->view();
?>