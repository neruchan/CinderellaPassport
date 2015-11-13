<?PHP
/*! @brief startingClassクラス

	startingClassクラスはステート制御、アクセス制御クラスです。

	@package	user_class.startingClass
	@access	public
	@author Reiun Ni <reiun@nipponwide.com>
	@version	$Id: startingClass.php, v 1.0 2010/03/03 Reiun Ni Exp $
*/
class startingClass{

	//******************************
	//    コンストラクタ
	//******************************
	function __construct(){
		//セッションスタート
		//session_start();
		//リンクなどに自動的にguid=ONを付ける
		//output_add_rewrite_var('guid', 'ON');
	}

	//******************************
	//    初期値取得
	//    param : 会員「1」有料会員コンテンツの場合「2」 TOPページの場合「9」
	//    param : 画面ID
	//    return: array
	//******************************
	public function getStartingData($userType = 0, $view_id = ""){
		if($userType != 9 && !$_COOKIE['ci_session']) {
			header("Location: login.php");
			exit;
		}

		$aryCookie = unserialize($_COOKIE['ci_session']);

		require_once "cinderella/ipfDB.php";
		$ins_ipfDB = new ipfDB;


		//ユーザーの会員種別取得
		$ins_ipfDB->ini("ci_sessions");
		//require_once "myvision/table_class/ci_sessions.php";
		//$ci_sessions = new ci_sessions;

		$loginUserData = $GLOBALS['ci_sessions']->selectLoginUserData($aryCookie['session_id']);
		//$loginUserData = $ci_sessions->selectLoginUserData($aryCookie['session_id']);


		//非会員が会員ページにアクセスした場合の処理

		//12/07/30 修正tou「$loginUserData['user_data']＝＞$loginUserData」

		if($userType != 9 && !$loginUserData){
			header("Location: login.php");
			exit;
		}

		//リターン用配列作成

		///////////////////////
		//ログイン履歴記録

		return unserialize($loginUserData);

/*
		if($userType != 9 && !$_COOKIE['session']) {
			header("Location: /index.php/auth/login");
			exit;
		}

		$aryCookie = unserialize($_COOKIE['session']);

		//非会員が会員ページにアクセスした場合の処理
		if($userType != 9 && !$aryCookie['user_id']){
			header("Location: /index.php/auth/login");
			exit;
		}

		return $aryCookie;
/*
		require_once "myvision/ipfDB.php";
		$ins_ipfDB = new ipfDB;

		//ユーザーの会員種別取得
		$ins_ipfDB->ini("ci_sessions");
		$loginUserData = $GLOBALS['ci_sessions']->selectLoginUserData($aryCookie['session_id']);

		//非会員が会員ページにアクセスした場合の処理
		if($userType != 9 && !$loginUserData['user_data']){
			header("Location: /index.php/auth/login");
			exit;
		}

		//リターン用配列作成

		///////////////////////
		//ログイン履歴記録

		return unserialize($loginUserData);
*/
	}
}
?>
