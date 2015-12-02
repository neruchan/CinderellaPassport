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

 require_once "cinderella/user_class/startingClass.php";
 $ins_startingClass = new startingClass;
 $sysinfo = $ins_startingClass->getStartingData(9);	//9はログインチェックしない


/***********************
 * コンストラクタ
***********************/
$ins_ipfTemplate = new ipfTemplate();
$ins_ipfDB = new ipfDB;
$ins_ipfDB->ini("cinderella");
$INI_DATA = parse_ini_file("cinderella/ipf.ini");
require_once "define.php";
/***********************
 * 画面表示処理
***********************/

//ログインした状態
if($sysinfo['user_id']){
    $PAGE_VALUE['comment_login_text'] = "";
    $PAGE_VALUE['clickable_comment_tag'] = "id='login-input-comment'";
}
else{
    $PAGE_VALUE['comment_login_text'] = '<span id="before-login" style="color:red;font-size:10px;">「ログイン必要」</span>';
    $PAGE_VALUE['clickable_comment_tag'] = "";
}


//自動ログイン

$coupon_id = $_GET['id'];

$PAGE_VALUE['coupon_id'] = $coupon_id;

if(!$coupon_id){
	header('Location: index.php');
}
$PAGE_VALUE[comment_img_err] ='';
$PAGE_VALUE['comment'] = $_POST['comment'];
if($_POST['comment_flag']){
    $error_flag = 0;
    if($_FILES['comment_img']['tmp_name'] != ''){
		$domainUrl = $INI_DATA['domain_url'];
        $uploaddir = $INI_DATA['upload_path'];
		$basename = basename($_FILES['comment_img']['tmp_name']);
		$fileext = strrchr($_FILES['comment_img']['name'], '.');
		$filename = $basename . $fileext;
		$uploadfile = $uploaddir . "/" . $filename;
		$is_uploaded = move_uploaded_file($_FILES['comment_img']['tmp_name'], $uploadfile);
		$_POST["up_img"] = $domainUrl."".$filename;
	}else{
		$PAGE_VALUE[comment_img_err] ='<tr><td></td><td><p class="red">※必須項目です。正しくご入力ください。</p></td></tr>';
		//$error_flag = 1;
	}
    
    if(!$sysinfo['user_id']){
        header('Location: login.php');
        exit;
    }
	
	if($error_flag == 0){
		$comment = $_POST['comment'];
	    $commenterId = $sysinfo['user_id'];
        
		if($comment != "" && mb_strlen($comment,'UTF-8') <= 100){
			unset($_DATA);
			$_DATA = array();
			$_DATA['coupon_comment']['comment'] = $comment;
			$_DATA['coupon_comment']['coupon_id'] = $coupon_id;
            $_DATA['coupon_comment']['user_id'] = $sysinfo['user_id'];
            $_DATA['coupon_comment']['pic_url'] = $_POST["up_img"];
			$_DATA['coupon_comment']['addtime'] = date('Y-m-d H:i:s');
			$ins_ipfDB->dataControl("insert", $_DATA);	
			
		}
        else{
            $PAGE_VALUE['comment_err'] = "※入力に誤りがあります。100文字以内で入力して下さい";
        }
	}
	
	
	
}



$couponData = $cinderella->selectCouponByID($coupon_id);

if(count($couponData)>0){	
	$PAGE_VALUE['shop_id'] = $couponData["shop_id"];
	$PAGE_VALUE['shop_name'] = $couponData["shop_name"];
	$PAGE_VALUE['shop_address'] = $couponData["address"];
	$PAGE_VALUE['shop_email'] = $couponData["email"];
	$PAGE_VALUE['shop_phone'] = $couponData["phone"];
	$PAGE_VALUE['shop_station'] = $couponData["station"];
	$PAGE_VALUE['shop_pref'] = $couponData["pref"];
	$PAGE_VALUE['shop_zip'] = $couponData["zip"];
	$PAGE_VALUE['shop_pic1'] = $couponData["shop_pic1"];
	$PAGE_VALUE['shop_pic2'] = $couponData["shop_pic2"];
	$PAGE_VALUE['shop_pic3'] = $couponData["shop_pic3"];
    $PAGE_VALUE['shop_access'] = $couponData["access"];
    $PAGE_VALUE['shop_website'] = $couponData["website"];
    $PAGE_VALUE['shop_detail'] = $couponData["detail"];
    $PAGE_VALUE['shop_jikan'] = $couponData["eigyo_jikan"];
    $PAGE_VALUE['shop_holiday'] = $couponData["holiday"];
    $PAGE_VALUE['shop_average'] = $couponData["average_price"];
	
	$PAGE_VALUE['title'] = $couponData["title"];
	$PAGE_VALUE['category'] = $coupon_category[$couponData['category']];
	$PAGE_VALUE['description'] = $couponData["description"];
	$PAGE_VALUE['coupon_pic'] = $couponData["pic_url"];
    $PAGE_VALUE['addtime'] = $couponData["addtime"];
    $PAGE_VALUE['exp_date_from'] = $couponData["exp_date_from"];
	$PAGE_VALUE['exp_date_until'] = $couponData["exp_date_until"];
    $PAGE_VALUE['warning'] = $couponData["warning"];
	
	$PAGE_VALUE['before_price'] = $couponData["before_price"];
	$PAGE_VALUE['after_price'] = $couponData["after_price"];
	
    $listTags = $cinderella->selectTagsByCouponId($coupon_id);
    
    $numberOfConnection = 3;
    
    if($listTags){
        
        $tag  ="";
        foreach($listTags as $key => $val) {
            $comma = "";// 
             if($key != 0){
                 $comma = ",";
             }
            $tag .= $comma."'".$val['tag_name']."'";
        }
        //echo "tag = ".$tag;
         //関連記事
        $valuesForLoop['coupon_connection'] = $cinderella->selectConnectionCouponByTag($tag,$numberOfConnection,$coupon_id);
        $currentNoConnection = count($valuesForLoop['coupon_connection']);
        if($currentNoConnection < $numberOfConnection){
            $numberPlusAlpha = $numberOfConnection-$currentNoConnection;
            $tempArray = $cinderella->selectConnectionCoupon($couponData["category"],$numberPlusAlpha,$coupon_id);
            $valuesForLoop['coupon_connection'] = array_merge($valuesForLoop['coupon_connection'],$tempArray);
        }

        foreach($valuesForLoop['coupon_connection'] as $key => $val) {
            $valuesForLoop['coupon_connection'][$key]["coupon_id"] = $val['id'];
            $valuesForLoop['coupon_connection'][$key]["coupon_title"] = mb_strimwidth($val['title'], 0, 25,'…',utf8);
            $valuesForLoop['coupon_connection'][$key]["coupon_image"] = $val['pic_url'];
        }
    
    
    
    }
    else{
        //関連記事
        $valuesForLoop['coupon_connection'] = $cinderella->selectConnectionCoupon($couponData["category"],3,$coupon_id);
        foreach($valuesForLoop['coupon_connection'] as $key => $val) {
            $valuesForLoop['coupon_connection'][$key]["coupon_id"] = $val['id'];
            $valuesForLoop['coupon_connection'][$key]["coupon_title"] = mb_strimwidth($val['title'], 0, 25,'…',utf8);
            $valuesForLoop['coupon_connection'][$key]["coupon_image"] = $val['pic_url'];
        }
    }
    
    
    
    $data = json_decode(file_get_contents('http://press.tiary.jp/api/article_list.php?a=3&u=1'));

    $temp = $data->{'result'};
    $pressData = get_object_vars($temp[0]);
    

	$statusTiary = $data->{'status'};

	if($statusTiary == "OK"){
		$resultTiary = $data->{'result'};
		for($i=0 ; $i<count($resultTiary) ; $i++){
			$obj = get_object_vars($resultTiary[$i]);
			$valuesForLoop['press_matome'][$i]["article_id"] = $obj['article_id'];
			$valuesForLoop['press_matome'][$i]["article_title"] = mb_strimwidth($obj['article_title'],0,55,"…",utf8);
			$valuesForLoop['press_matome'][$i]["addtime"] = timeOpen(((strtotime(date('Y-m-d H:i:s'))-strtotime($obj['addtime']))/3600/24),$obj['addtime']);
			$valuesForLoop['press_matome'][$i]["article_image"] = ($obj['article_image']!=""?$obj['article_image']:"https://press.tiary.jp/img/noimg.jpg");
		}
	}
    
    //print_r($pressData);
    //$valuesForLoop['press_matome'] = $pressData;
    
    
    
    $valuesForLoop['dataAllComments'] = $cinderella->selectCommentsByCouponId($coupon_id);
    
    if(count($valuesForLoop['dataAllComments']) > 0){
        $PAGE_VALUE['comment_message_on1'] = "";
        $PAGE_VALUE['comment_message_on2'] = "";
        $PAGE_VALUE['comment_message_off1'] = "<!--";
        $PAGE_VALUE['comment_message_off2'] = "-->";
        foreach($valuesForLoop['dataAllComments'] as $key => $val) {
            $valuesForLoop['dataAllComments'][$key]["no"] = ($key+1);
            $valuesForLoop['dataAllComments'][$key]["comment_id"] = $val['id'];
            if($val["user_id"]){
                $userComment = json_decode(file_get_contents('http://tiary.jp/app/member_detail.php?i='.$val["user_id"]));
                $temp = $userComment->{'result'};
                $userData = get_object_vars($temp[0]);
                if(!$userData){
                	$valuesForLoop['dataAllComments'][$key]["username"] = "";
                    $valuesForLoop['dataAllComments'][$key]["user_id"] = $val['user_id'];
                    $valuesForLoop['dataAllComments'][$key]["userpic"] = "img/startyfree.jpg";
                }
                else{
                	$valuesForLoop['dataAllComments'][$key]["username"] = ($userData["nickname"]!=""?$userData["nickname"]:$userData["username"]);
					$valuesForLoop['dataAllComments'][$key]["user_id"] = $val['user_id'];
					$valuesForLoop['dataAllComments'][$key]["userpic"] = ($userData["userpic"]!=""?"http://tiary.jp/s/pjpic/".$userData["userpic"]:"img/startyfree.jpg");
                }
            }
            else{
                $valuesForLoop['dataAllComments'][$key]["username"] = "ゲスト";
                $valuesForLoop['dataAllComments'][$key]["user_id"] = "";
                $valuesForLoop['dataAllComments'][$key]["userpic"] = "https://press.tiary.jp/img/startyfree.jpg";
            }
            
            $valuesForLoop['dataAllComments'][$key]["comment"] = $val['comment'];
            $valuesForLoop['dataAllComments'][$key]["pic_url"] = $val['pic_url'];
            
            $valuesForLoop['dataAllComments'][$key]["addtime"] = $val['addtime'];
            
            
        }
    }
    else{
        $PAGE_VALUE['comment_message_on1'] = "<!--";
        $PAGE_VALUE['comment_message_on2'] = "-->";
        $PAGE_VALUE['comment_message_off1'] = "";
        $PAGE_VALUE['comment_message_off2'] = "";
    }
    
}
else{
	header('Location: index.php');
}




$template_file = "detail.template";
//テンプレートファイルの読込
$templateData = $ins_ipfTemplate->loadTemplate($template_file);
$templateData = $ins_ipfTemplate->makeTemplateData($templateData, $PAGE_VALUE, $valuesForLoop);
$ins_ipfTemplate->putMemory($templateData);
$ins_ipfTemplate->view();

?>