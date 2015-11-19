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

// require_once "akb/startingClass.php";
// $ins_startingClass = new startingClass;
// $sysinfo = $ins_startingClass->getStartingData(9);	//9はログインチェックしない


/***********************
 * コンストラクタ
***********************/
$ins_ipfTemplate = new ipfTemplate();
$ins_ipfDB = new ipfDB;
$ins_ipfDB->ini("cinderella");
$ins_ipfDB->ini("login");
$ins_ipfDB->ini("myautologin");
// require_once "define.php";
/***********************
 * 画面表示処理
***********************/
//自動ログイン

$PAGE_VALUE['iderror'] = "";
$PAGE_VALUE['login_id'] = $_POST['login_id'];

// require_once "common/user_main.php";

if($_REQUEST["login_btn"]!=""){
    //普通の会員ログイン関数
	if(!$_REQUEST["login_id"]){
		$PAGE_VALUE['iderror'] = '<tr><td colspan="2"><span style="color:red;">ログインIDが未入力。</span></td></tr>';
	}
		
	if(!$_REQUEST["password"]){
		$PAGE_VALUE['iderror'] = '<tr><td colspan="2"><span style="color:red;">パスワードIDが未入力。</span></td></tr>';
	}
    
    if($_REQUEST["login_id"] && $_REQUEST["password"]){
        $data = json_decode(file_get_contents('http://tiary.jp/app/login.php?u='.$_REQUEST["login_id"].'&p='.$_REQUEST["password"]));

		$status = $data->{'status'};
        
        if($status == "OK"){
        	$passValidation = get_object_vars($data->{'result'});
        	
        	$user_data = serialize(array(
												'user_id'	=> $passValidation["id"],
												'username'	=> $passValidation["username"],
												'email'     => $passValidation["email"],
												'status'	=> ($passValidation["activated"] == 1 ? '1' : '0')
			));

			$ip = getenv("REMOTE_ADDR");
			$sessid = '';
			while (strlen($sessid) < 32)
			{
				$sessid .= mt_rand(0, mt_getrandmax());
			}
			$session_id = md5(uniqid($sessid, TRUE));

			$user_agent =  $_SERVER["HTTP_USER_AGENT"];

			$now = time();
			$time = mktime(gmdate("H", $now), gmdate("i", $now), gmdate("s", $now), gmdate("m", $now), gmdate("d", $now), gmdate("Y", $now));

			$login->clearsession($aryCookie['session_id']);
			$sessiondata = $login->creatsession($session_id,$ip,'',$time,$user_data);
// 			if($_POST["autologin"]=="autologin"){
// 				$autologin = $myautologin->create_autologin($passValidation[0]["id"]);
// 			}
			$ci_session_data = serialize(array(
												'session_id'	=> $session_id,
												'ip_address'	=> $_SERVER['REMOTE_ADDR'],
												'user_agent'     => $user_agent,
												'last_activity'	=> $time
			));
			//setcookie('ci_session',$ci_session_data);
			setcookie('ci_session',$ci_session_data,0,'/');
			
			header('Location: profile_user.php');
			exit;
        }
		else{
			$PAGE_VALUE['iderror'] = '<tr><td colspan="2"><span style="color:red;">※ID、またはパスワードが間違っています。</span></td></tr>';
		}
    }
}


$template_file = "login.template";
//テンプレートファイルの読込
$templateData = $ins_ipfTemplate->loadTemplate($template_file);
$templateData = $ins_ipfTemplate->makeTemplateData($templateData, $PAGE_VALUE, $valuesForLoop);
$ins_ipfTemplate->putMemory($templateData);
$ins_ipfTemplate->view();

?>