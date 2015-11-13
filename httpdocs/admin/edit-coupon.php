<?php
/*
 *ファイル名 : add-coupon.php
 * 機能名    : クーポン新規登録ページ
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
$INI_DATA = parse_ini_file("cinderella/ipf.ini");
/***********************
 * 画面表示処理
***********************/

$template_file = "edit-coupon.template";
$PAGE_VALUE['article_img_err'] = "";
$PAGE_VALUE['title_err'] = "";
$PAGE_VALUE['category_err'] ="";
$PAGE_VALUE['contents_err'] ="";
$PAGE_VALUE['shop_err'] ="";

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
    $PAGE_VALUE["addtime"] = date('Y/m/d H:s',strtotime($couponData["addtime"]));
    $PAGE_VALUE["up_img"] = $couponData["pic_url"];
    
    $PAGE_VALUE["exp_date_from_y"] = setOptions($open_years,date('Y',strtotime($couponData["exp_date_from"])));
    $PAGE_VALUE["exp_date_from_m"] = setOptions($open_months,date('m',strtotime($couponData["exp_date_from"])));
    $PAGE_VALUE["exp_date_from_d"] = setOptions($open_days,date('d',strtotime($couponData["exp_date_from"])));
    $PAGE_VALUE["exp_date_until_y"] = setOptions($open_years,date('Y',strtotime($couponData["exp_date_until"])));
    $PAGE_VALUE["exp_date_until_m"] = setOptions($open_months,date('m',strtotime($couponData["exp_date_until"])));
    $PAGE_VALUE["exp_date_until_d"] = setOptions($open_days,date('d',strtotime($couponData["exp_date_until"])));
    
    $PAGE_VALUE["osusume"] = ($couponData['osusume_flag']=="1"?" checked":"");
    $PAGE_VALUE["warning"] = $couponData["warning"];
    
    $PAGE_VALUE["before_price"] = $couponData["before_price"];
    $PAGE_VALUE["after_price"] = $couponData["after_price"];
	$PAGE_VALUE["access_num"] = $couponData["access_num"];
	$PAGE_VALUE["shop_id"] = $couponData["shop_id"];
    $PAGE_VALUE["shop_id_show"] = $couponData["shop_id"];
    $PAGE_VALUE["shop_name"] = $couponData["shop_name"];
    
    $PAGE_VALUE["category_checkbox"] = setCheckboxArticle($coupon_categorys,$couponData["category"]);
    
    $PAGE_VALUE["category"] = $catestr;
    
    $PAGE_VALUE["tag"] = "";
    $listTags = $cinderella_admin->selectTagsByCouponId($_GET["aid"]);
	if($listTags){
		$printsTags  ="";
		foreach($listTags as $key => $val) {
			$comma = "";
			if($key != 0){
				$comma = " ";
			}
			$printsTags .= $comma.$val['tag_name'];
		}
		$PAGE_VALUE["tag"] = $printsTags;
	}
    
}
else{
	header('Location: main.php');
}







if($_POST["add_flag"]!=""){
	
	$error_flag = 0;
	if($_FILES['article_img']['tmp_name'] != ''){
		$uploaddir = $INI_DATA['upload_path'];
		$basename = basename($_FILES['article_img']['tmp_name']);
		$fileext = strrchr($_FILES['article_img']['name'], '.');
		$filename = $basename . $fileext;
		$uploadfile = $uploaddir . "/" . $filename;
		$is_uploaded = move_uploaded_file($_FILES['article_img']['tmp_name'], $uploadfile);
		$_POST["up_img"] = "http://press.tiary.jp/_dev/cinderella/pjpic/".$filename;
	}else{
		$PAGE_VALUE[article_img_err] ='<tr><td></td><td><p class="red">※必須項目です。正しくご入力ください。</p></td></tr>';
		
	}

	
	if($_POST["title"] ==""){
		$PAGE_VALUE[title_err] ='<tr><td></td><td><p class="red">※必須項目です。正しくご入力ください。</p></td></tr>';
		$error_flag = 1;
	}
	elseif(mb_strlen($_POST["title"], 'UTF-8') > 80){
		$PAGE_VALUE[title_err] ='<tr><td></td><td><p class="red">※80文字以内で入力してください。</p></td></tr>';
		$error_flag = 1;
	}
    
    if($_POST["shop_id"] ==""){
		$PAGE_VALUE[shop_err] ='<tr><td></td><td><p class="red">※必須項目です。店舗を選択して下さい。</p></td></tr>';
		$error_flag = 1;
	}

	if(!$_POST["category"]){
		$PAGE_VALUE[category_err] ='<tr><td></td><td><p class="red">※必須項目です。正しくご入力ください。</p></td></tr>';
		$error_flag = 1;
	}

	if($error_flag !=1){
	    mb_regex_encoding('UTF-8');
        mb_internal_encoding("UTF-8"); 
		$_DATA = array();
		$_DATA['coupon']['category'] = implode(',', $_POST['category']);
		$_DATA['coupon']['title'] = $_POST['title'];
		$_DATA['coupon']['pic_url'] = $_POST["up_img"];
        $_DATA['coupon']['warning'] = $_POST['warning'];
		$_DATA['coupon']['description'] = $_POST['description'];
		$_DATA['coupon']['before_price'] = $_POST["before_price"];
        $_DATA['coupon']['after_price'] = $_POST["after_price"];
        
        $_DATA['coupon']['osusume_flag'] = (isset($_POST['osusume'])?"1":"0");
        
        $exp_date_from = $_POST["exp_date_from_y"]."-".$_POST["exp_date_from_m"]."-".$_POST["exp_date_from_d"];
        
        $exp_date_until = $_POST["exp_date_until_y"]."-".$_POST["exp_date_until_m"]."-".$_POST["exp_date_until_d"];
        
		$_DATA['coupon']['exp_date_from'] = $exp_date_from;
        $_DATA['coupon']['exp_date_until'] = $exp_date_until;
		
		$ins_ipfDB->dataControl("update", "id = ".$_GET["aid"]);
	    
        unset($_DATA);
        $_DATA = array();
        $_DATA['coupon_x_shop']['shop_id'] = $_POST["shop_id"];
        $_DATA['coupon_x_shop']['coupon_id'] = $_GET["aid"];
        
        $ins_ipfDB->dataControl("update", "id = ".$_GET["aid"]);
        
        $cinderella_admin->deleteAllTagByCouponId($_GET["aid"]);
        
        $tagArray = mb_split('[[:space:]]', $_POST['tag']);
        
        for($i = 0 ; $i < count($tagArray); $i++){
			if($tagArray[$i] != "" && is_string($tagArray[$i])){
				$id = $cinderella_admin->selectTagExists($tagArray[$i]);
				if(!$id){
					unset($_DATA);
	 				$_DATA = array();
					$_DATA['coupon_tag']['name'] = $tagArray[$i];
					$_DATA['coupon_tag']['delete_flag'] = 0;
					$_DATA['coupon_tag']['addtime'] = date("Y-m-d H:i:s");
			
					$id = $ins_ipfDB->dataControl("insert", $_DATA);
				}
				unset($_DATA);
	 			$_DATA = array();
                $_DATA['coupon_x_tag']['tag_id'] = $id;
                $_DATA['coupon_x_tag']['coupon_id'] = $_GET["aid"];
                $ins_ipfDB->dataControl("insert", $_DATA);
				
			}
		}
        
		
		header('Location: main.php');
	}else{
		$PAGE_VALUE["title"] = $_POST["title"];
        $PAGE_VALUE["description"] = $_POST["description"];
$PAGE_VALUE["warning"] = $_POST["warning"];
        $PAGE_VALUE["category_checkbox"] = setCheckboxArticle($coupon_categorys,($_POST["category"]!=""?implode(",", $_POST["category"]):$_POST["category"]));
        $PAGE_VALUE["exp_date_from_y"] = setOptions($open_years,$_POST['exp_date_from_y']);
$PAGE_VALUE["exp_date_from_m"] = setOptions($open_months,$_POST['exp_date_from_m']);
$PAGE_VALUE["exp_date_from_d"] = setOptions($open_days,$_POST['exp_date_from_d']);
$PAGE_VALUE["exp_date_until_y"] = setOptions($open_years,$_POST['exp_date_until_y']);
$PAGE_VALUE["exp_date_until_m"] = setOptions($open_months,$_POST['exp_date_until_m']);
$PAGE_VALUE["exp_date_until_d"] = setOptions($open_days,$_POST['exp_date_until_d']);

$PAGE_VALUE["before_price"] = $_POST["before_price"];
$PAGE_VALUE["after_price"] = $_POST["after_price"];
        $PAGE_VALUE['tag'] = $_POST["tag"];
	}
}

//テンプレートファイルの読込
$templateData = $ins_ipfTemplate->loadTemplate($template_file);
$templateData = $ins_ipfTemplate->makeTemplateData($templateData, $PAGE_VALUE, $valuesForLoop);
$ins_ipfTemplate->putMemory($templateData);
$ins_ipfTemplate->view();
?>