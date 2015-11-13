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
require_once "cinderella/ipfDB.php";
require_once "define_admin.php";
/***********************
 * コンストラクタ
***********************/

$ins_ipfTemplate = new ipfTemplate();
$ins_ipfDB = new ipfDB;
$ins_ipfDB->ini("cinderella_admin");
$INI_DATA = parse_ini_file("cinderella/ipf.ini");
/***********************
 * 画面表示処理
***********************/

$template_file = "edit-shop.template";
$PAGE_VALUE['name_err'] = "";
$PAGE_VALUE['email_err'] = "";
$PAGE_VALUE['article_img1_err'] = "";
$PAGE_VALUE['article_img2_err'] = "";
$PAGE_VALUE['article_img3_err'] = "";
session_start();
if(!$_SESSION["admin"]){
	header('Location: index.php');
}

if($_GET["no"]=="" || $_GET["sid"]==""){
	header('Location: shop.php');
}

$shopData = $cinderella_admin->selectShopByID($_GET["sid"]);
if(count($shopData)>0){
    $PAGE_VALUE["no"] = $_GET["no"];
	$PAGE_VALUE["id"] =$_GET["sid"];
	$PAGE_VALUE["name"] = $shopData["name"];
	$PAGE_VALUE["address"] = $shopData["address"];
    $PAGE_VALUE["email"] = $shopData["email"];
    $PAGE_VALUE["phone"] = $shopData["phone"];
    $PAGE_VALUE["eki"] = $shopData["station"];
    $PAGE_VALUE["todoufukens"] = setOptions($todoufukens,$shopData["pref"]); 
    $PAGE_VALUE["zip"] = $shopData["zip"];
    $PAGE_VALUE["access"] = $shopData["access"];
    $PAGE_VALUE["shop_website"] = $shopData["website"];
    $PAGE_VALUE["shop_details"] = $shopData["detail"];
    $PAGE_VALUE["eigyo_jikan"] = $shopData["eigyo_jikan"];
    $PAGE_VALUE["holiday"] = $shopData["holiday"];
    $PAGE_VALUE["average_price"] = $shopData["average_price"];
    $PAGE_VALUE["addtime"] = date('Y/m/d H:s',strtotime($shopData["addtime"]));
    $PAGE_VALUE["pic_url1"] = $shopData["pic_url1"];
    $PAGE_VALUE["pic_url2"] = $shopData["pic_url2"];
    $PAGE_VALUE["pic_url3"] = $shopData["pic_url3"];
}
else{
	header('Location: shop.php');
}


if($_POST["add_flag"]!=""){
	
	$error_flag = 0;
	if($_FILES['article_img1']['tmp_name'] != ''){
		$uploaddir = $INI_DATA['upload_path'];
		$basename = basename($_FILES['article_img1']['tmp_name']);
		$fileext = strrchr($_FILES['article_img1']['name'], '.');
		$filename = $basename . $fileext;
		$uploadfile = $uploaddir . "/" . $filename;
		$is_uploaded = move_uploaded_file($_FILES['article_img1']['tmp_name'], $uploadfile);
		$_POST["pic_url1"] = "http://press.tiary.jp/_dev/cinderella/pjpic/".$filename;
	}else{
		$PAGE_VALUE[article_img1_err] ='<tr><td></td><td><p class="red">※必須項目です。正しくご入力ください。</p></td></tr>';
		
	}
    
    if($_FILES['article_img2']['tmp_name'] != ''){
		$uploaddir = $INI_DATA['upload_path'];
		$basename = basename($_FILES['article_img2']['tmp_name']);
		$fileext = strrchr($_FILES['article_img2']['name'], '.');
		$filename = $basename . $fileext;
		$uploadfile = $uploaddir . "/" . $filename;
		$is_uploaded = move_uploaded_file($_FILES['article_img2']['tmp_name'], $uploadfile);
		$_POST["pic_url2"] = "http://press.tiary.jp/_dev/cinderella/pjpic/".$filename;
	}else{
		$PAGE_VALUE[article_img2_err] ='<tr><td></td><td><p class="red">※必須項目です。正しくご入力ください。</p></td></tr>';
		
	}
    
    if($_FILES['article_img3']['tmp_name'] != ''){
		$uploaddir = $INI_DATA['upload_path'];
		$basename = basename($_FILES['article_img3']['tmp_name']);
		$fileext = strrchr($_FILES['article_img3']['name'], '.');
		$filename = $basename . $fileext;
		$uploadfile = $uploaddir . "/" . $filename;
		$is_uploaded = move_uploaded_file($_FILES['article_img3']['tmp_name'], $uploadfile);
		$_POST["pic_url3"] = "http://press.tiary.jp/_dev/cinderella/pjpic/".$filename;
	}else{
		$PAGE_VALUE[article_img3_err] ='<tr><td></td><td><p class="red">※必須項目です。正しくご入力ください。</p></td></tr>';
		
	}

	
	if($_POST["name"] ==""){
		$PAGE_VALUE[name_err] ='<tr><td></td><td><p class="red">※必須項目です。正しくご入力ください。</p></td></tr>';
		$error_flag = 1;
	}
	elseif(mb_strlen($_POST["name"], 'UTF-8') > 80){
		$PAGE_VALUE[name_err] ='<tr><td></td><td><p class="red">※80文字以内で入力してください。</p></td></tr>';
		$error_flag = 1;
	}
    
    if($_POST["email"] ==""){
		$PAGE_VALUE[email_err] ='<p class="red">※「メールアドレス」は必須項目です、入力して下さい。</p>';
		$error_flag = 1;
	}else{

        if(!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $_POST["email"])){
            $PAGE_VALUE[email_err] ='<p class="red">※ 「メールアドレス」に正しく入力してください。</p>';
            $error_flag = 1;
        }
		
	}
    
	if($error_flag !=1){
	   mb_regex_encoding('UTF-8');
        mb_internal_encoding("UTF-8"); 
		$_DATA = array();
		$_DATA['shop']['name'] = $_POST['name'];
		$_DATA['shop']['pic_url1'] = $_POST["pic_url1"];
        $_DATA['shop']['pic_url2'] = $_POST["pic_url2"];
        $_DATA['shop']['pic_url3'] = $_POST["pic_url3"];
		$_DATA['shop']['station'] = $_POST['eki'];
        $_DATA['shop']['pref'] = $_POST['todoufukens'];
		$_DATA['shop']['address'] = $_POST["address"];
        $_DATA['shop']['zip'] = $_POST["zip"];
        $_DATA['shop']['email'] = $_POST["email"];
        $_DATA['shop']['average_price'] = $_POST["average_price"];
        $_DATA['shop']['website'] = $_POST["shop_website"];
        $_DATA['shop']['detail'] = $_POST["shop_details"];
        $_DATA['shop']['eigyo_jikan'] = $_POST["eigyo_jikan"];
        $_DATA['shop']['access'] = $_POST["access"];
        $_DATA['shop']['holiday'] = $_POST["holiday"];
        $_DATA['shop']['phone'] = $_POST["phone"];
		$_DATA['shop']['addtime'] = date('Y-m-d H:i:s');
		

        
        $ins_ipfDB->dataControl("update", "id = ".$_GET["sid"]);
		
		header('Location: main.php');
	}else{
        $PAGE_VALUE["name"] = $_POST["name"];
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
        $PAGE_VALUE["pic_url1"] = $_POST["pic_url1"];
        $PAGE_VALUE["pic_url2"] = $_POST["pic_url2"];
        $PAGE_VALUE["pic_url3"] = $_POST["pic_url3"];
	}
}

//テンプレートファイルの読込
$templateData = $ins_ipfTemplate->loadTemplate($template_file);
$templateData = $ins_ipfTemplate->makeTemplateData($templateData, $PAGE_VALUE, $valuesForLoop);
$ins_ipfTemplate->putMemory($templateData);
$ins_ipfTemplate->view();
?>