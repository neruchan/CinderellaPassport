<?php
/*
 *ファイル名 : add-shop.php
 * 機能名    : 店舗新規登録ページ
 * 作成者    : ネルソン
 * 作成日    : 15/10/19
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
$INI_DATA = parse_ini_file("cinderella/ipf.ini");
/***********************
 * 画面表示処理
***********************/

$template_file = "add-shop.template";

session_start();
if(!$_SESSION["admin"]){
	header('Location: index.php');
}

$PAGE_VALUE["category_checkbox"] = setCheckboxArticle($coupon_categorys,($_POST["category"]!=""?implode(",", $_POST["category"]):$_POST["category"]));

$PAGE_VALUE["shop_name"] = $_POST["shop_name"];
$PAGE_VALUE["shop_name_kana"] = $_POST["shop_name_kana"];
$PAGE_VALUE["shop_notes"] = $_POST["shop_notes"];
$PAGE_VALUE["shop_city"] = $_POST["shop_city"];
$PAGE_VALUE["shop_pref"] = setOptions($todoufukens,$_POST['shop_pref']);
$PAGE_VALUE["shop_zip1"] = $_POST["shop_zip1"];
$PAGE_VALUE["shop_zip2"] = $_POST["shop_zip2"];
$PAGE_VALUE["shop_address"] = $_POST["shop_address"];
$PAGE_VALUE["shop_access"] = $_POST["shop_access"];

$PAGE_VALUE["shop_phone1"] = $_POST["shop_phone1"];
$PAGE_VALUE["shop_phone2"] = $_POST["shop_phone2"];
$PAGE_VALUE["shop_phone3"] = $_POST["shop_phone3"];
$PAGE_VALUE["shop_opentime"] = $_POST["shop_opentime"];
$PAGE_VALUE["shop_holiday"] = $_POST["shop_holiday"];
$PAGE_VALUE["shop_homepage"] = $_POST["shop_homepage"];
$PAGE_VALUE["shop_eki"] = $_POST["shop_eki"];
$PAGE_VALUE["shop_average_price"] = $_POST["shop_average_price"];
$PAGE_VALUE["shop_email"] = $_POST["shop_email"];

$PAGE_VALUE["shop_aff_tag"] = $_POST["shop_aff_tag"];

$PAGE_VALUE["up_img"] = "";

$PAGE_VALUE["up_img1"] = "";
$PAGE_VALUE["up_img2"] = "";
$PAGE_VALUE["up_img3"] = "";
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

$PAGE_VALUE['article_img1_err'] ="";
$PAGE_VALUE['article_img2_err'] ="";
$PAGE_VALUE['article_img3_err'] ="";

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
//    else{
//		$PAGE_VALUE[article_img1_err] ='<br><p class="red">※必須項目です。正しくご入力ください。</p>';
//		$error_flag = 1;
//	}
    
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
//    else{
//		$PAGE_VALUE[article_img2_err] ='<br><p class="red">※必須項目です。正しくご入力ください。</p>';
//		$error_flag = 1;
//	}
    
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
//    else{
//		$PAGE_VALUE[article_img3_err] ='<br><p class="red">※必須項目です。正しくご入力ください。</p>';
//		$error_flag = 1;
//	}
	
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
        $_DATA['shoptbl']['shop_category'] = implode(',', $_POST['category']);
		$_DATA['shoptbl']['shop_name'] = $_POST['shop_name'];
        $_DATA['shoptbl']['shop_name_kana'] = $_POST['shop_name_kana'];
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
        
        $_DATA['shoptbl']['shop_img'] = $_POST["up_img1"];
        $_DATA['shoptbl']['shop_img2'] = $_POST["up_img2"];
        $_DATA['shoptbl']['shop_img3'] = $_POST["up_img3"];
        
        $_DATA['shoptbl']['shop_aff_tag'] = $_POST["shop_aff_tag"];
		
		$_DATA['shoptbl']['shop_addtime'] = date('Y-m-d H:i:s');
        $_DATA['shoptbl']['shop_updatetime'] = date('Y-m-d H:i:s');
		
		$shop_id = $ins_ipfDB->dataControl("insert", $_DATA);
		
		header('Location: shop.php');
	}else{
		$PAGE_VALUE["name"] = $_POST["name"];
		$PAGE_VALUE["up_img1"] = $_POST["up_img1"];
        $PAGE_VALUE["up_img2"] = $_POST["up_img2"];
        $PAGE_VALUE["up_img3"] = $_POST["up_img3"];
		
	}
}

//テンプレートファイルの読込
$templateData = $ins_ipfTemplate->loadTemplate($template_file);
$templateData = $ins_ipfTemplate->makeTemplateData($templateData, $PAGE_VALUE, $valuesForLoop);
$ins_ipfTemplate->putMemory($templateData);
$ins_ipfTemplate->view();
?>