<?php
class cupoun extends ipfDB{
	// function CheckPassword($name,$pass){
		// if(!$name)
		// return array();

		// $sql = "SELECT count(*)cnt FROM admin WHERE account='$name' AND password='$pass' AND delete_flag=0; ";
		// //     	print $sql;
		// $data = $this->query($sql);
		// return $data[0]["cnt"];
	// }
	//ユーザー管理ユーザー登録数取得
	function getAllNumberOfCupoun($shop_id){
		
		$sql = "SELECT
					count(*)cnt
				FROM
					shop_cupoun
				WHERE
					shop_id = $shop_id
				";
		// 				print $sql;
		$data = $this->query($sql);
		return $data[0]["cnt"];
	}
	
	function getAllCupoun($num=20,$page=0, $shop_id){
		$sql = "SELECT
					*
					
				FROM
					shop_cupoun s
				WHERE
					shop_id = $shop_id
					ORDER BY s.id ASC
				LIMIT $num OFFSET ".($page * $num)." ";
		// 				print $sql;
		$data = $this->query($sql);
		return $data;
	}

	// //全体ユーザー情報取得
	// function selectUserAll($id,$username,$num=20,$page=0){
		// $keywordsql = "";
		// if($id!=""){
			// $keywordsql .= " AND u.id = $id ";
		// }

		// if($username!=""){
			// $keywordsql .= " AND ui.nickname LIKE '%$username%' ";
		// }

		// $sql = "SELECT
					// u.id,
					// ui.nickname,
					// ui.username,
					// ui.birthday,
					// u.created,
					// u.terminal,
					// u.cmflag
				// FROM
					// users u
				// JOIN users_info ui ON ui.userid = u.id
				// WHERE
					// u.delete_flag = 0
					// $keywordsql
					// ORDER BY u.id ASC
				// LIMIT $num OFFSET ".($page * $num)." ";
		// // 				print $sql;
		// $data = $this->query($sql);
		// return $data;
	// }

	//ユーザー投稿COUNT取得
	function selectEntryCount($userid){
		if(!$userid)
		return array();
		$sql = "SELECT
							count(*) cnt
						FROM
							startyfreecontribute sc
						JOIN users_info ui ON ui.userid = sc.sc_userid
						WHERE sc_userid = $userid";
		// 		print $sql;
		$data = $this->query($sql);
		return $data[0]["cnt"];
	}

	//カワイイ獲得数
	function selectCharmCount($userid){
		if(!$userid)
		return array();
		$sql = "SELECT count(*)cnt FROM charmuser cu JOIN startyfreecontribute sc ON sc.id = cu.c_sc_id WHERE sc.sc_userid =$userid ";
		// 		print $sql;
		$data = $this->query($sql);
		return $data[0]["cnt"];
	}

	
	function deleteCupoun($cupounId){
		if(!$cupounId)
			return array();
		$cupounidarr = explode(",", $cupounId);
		$sqlstr = "(";
		foreach ($cupounidarr as $key=>$val){
			if($key>0)$or = " OR ";
			$sqlstr .=" $or id= $val ";
		}
		$sqlstr .= ") ";
		$sql = "DELETE FROM shop_cupoun WHERE $sqlstr ";
 				print $sql;
		$data = $this->query($sql,1);
		return $data;
	}
	
	// //ユーザー削除
	// function deleteUser($userid){
		// if(!$userid)
		// return array();
		// $useridarr = explode(",", $userid);
		// $sqlstr = "(";
		// foreach ($useridarr as $key=>$val){
			// if($key>0)$or = " OR ";
			// $sqlstr .=" $or id= $val ";
		// }
		// $sqlstr .= ") ";
		// $sql = "UPDATE users SET delete_flag = 1 WHERE $sqlstr ";
// // 				print $sql;
		// $data = $this->query($sql,1);
		// return $data;
	// }

	//ユーザー情報取得
	function selectUserDataByID($userid){
		if(!$userid)
		return array();
		$sql = "SELECT
							u.id,
							ui.nickname,
							ui.username,
							ui.birthday,
							u.created,
							u.terminal,
							u.email,
							u.cmflag,
							ui.fullname,
							ui.fullname_kana,
							ui.banti,
							ui.todoufuken,
							ui.phonenumber,
							ui.zipcode
						FROM
							users u
						JOIN users_info ui ON ui.userid = u.id
						WHERE
							u.delete_flag = 0 AND
							u.id=$userid
							";
		// 				print $sql;
		$data = $this->query($sql);
		return $data[0];
	}

	//管理投稿数取得
	function selectEntryCntAll($id,$title){
		$keywordsql = "";
		if($id!=""){
			$keywordsql .= " AND sc.id = $id ";
		}

		if($title!=""){
			$keywordsql .= " AND sc.sc_title LIKE '%$title%' ";
		}

		$sql = "SELECT
					count(*)cnt
				FROM
					startyfreecontribute sc
				JOIN users_info ui ON ui.userid = sc.sc_userid
				WHERE
					sc.sc_delete_flag = 0
					$keywordsql
					";
// 						print $sql;
		$data = $this->query($sql);
		return $data[0]["cnt"];
	}

	//全体投稿情報取得
	function selectEntryAll($id,$title,$num=20,$page=0){
		$keywordsql = "";
		if($id!=""){
			$keywordsql .= " AND sc.id = $id ";
		}

		if($title!=""){
			$keywordsql .= " AND sc.sc_title LIKE '%$title%' ";
		}

		$sql = "SELECT
					sc.id,
					sc.sc_genre,
					sc.sc_title,
					sc.sc_satisfied,
					sc.sc_addtime,
					sc.sc_terminal,
					ui.nickname,
					ui.username
				FROM
					startyfreecontribute sc
				JOIN users_info ui ON ui.userid = sc.sc_userid
				WHERE
					sc.sc_delete_flag = 0
						$keywordsql
						ORDER BY
					sc.id ASC
					LIMIT $num OFFSET ".($page * $num)." ";
// 						print $sql;
		$data = $this->query($sql);
		return $data;
	}

	//投稿削除
	function deleteCharm($eid){
		if(!$eid)
		return array();
		$useridarr = explode(",", $eid);
		$sqlstr = "(";
		foreach ($useridarr as $key=>$val){
			if($key>0)$or = " OR ";
			$sqlstr .=" $or id= $val ";
		}
		$sqlstr .= ") ";
		$sql = "UPDATE startyfreecontribute SET sc_delete_flag = 1 WHERE $sqlstr ";
		// 				print $sql;
		$data = $this->query($sql,1);
		return $data;
	}

	//投稿のカワイイ獲得数
	function selectCharmCountSingle($eid){
		if(!$eid)
		return array();
		$sql = "SELECT count(*)cnt FROM charmuser WHERE c_sc_id = $eid ";
		// 		print $sql;
		$data = $this->query($sql);
		return $data[0]["cnt"];
	}
	
	//投稿情報取得
	function getCupounById($cupoun_id){
		if(!$cupoun_id)
		return array();
		$sql = "SELECT
					*
				FROM
					shop_cupoun sc
				WHERE
					sc.id = $cupoun_id";
						//print $sql;
		$data = $this->query($sql);
		return $data[0];
	}

	// //投稿情報取得
	// function selectEntryDataByID($eid){
		// if(!$eid)
		// return array();
		// $sql = "SELECT
					// sc.sc_genre,
					// sc.id,
					// sc.sc_title,
					// ui.userid,
					// ui.nickname,
					// ui.todoufuken,
					// ui.birthday,
					// ui.username,
					// s.shop_category,
					// s.id shop_id,
					// s.shop_name,
					// s.shop_pref,
					// sc.sc_where,
					// sc.sc_who,
					// sc.sc_money,
					// sc.sc_coupontype,
					// sc.sc_satisfied,
					// sc.sc_addtime,
					// sc.sc_terminal
				// FROM
					// startyfreecontribute sc
				// JOIN users_info ui ON ui.userid = sc.sc_userid
				// LEFT JOIN shoptbl s ON s.id = sc.sc_shop_id
				// WHERE
					// sc.id = $eid
				// AND sc.sc_delete_flag = 0";
		// // 				print $sql;
		// $data = $this->query($sql);
		// return $data[0];
	// }

	//全体店舗数取得
	function selectShopCntAll($id,$name){
		$keywordsql = "";
		if($id!=""){
			$keywordsql .= " AND id = $id ";
		}

		if($name!=""){
			$keywordsql .= " AND shop_name LIKE '%$name%' ";
		}

		$sql = "SELECT
					count(*)cnt
				FROM
					shoptbl
				WHERE
					shop_delete_flag = 0
				$keywordsql
						";
		// 						print $sql;
		$data = $this->query($sql);
		return $data[0]["cnt"];
	}

	//全体店舗情報取得
	function selectShopAll($id,$name,$num=20,$page=0){
		$keywordsql = "";
		if($id!=""){
			$keywordsql .= " AND id = $id ";
		}

		if($name!=""){
			$keywordsql .= " AND shop_name LIKE '%$name%' ";
		}

		$sql = "SELECT
					id,
					shop_category,
					shop_name,
					shop_pref,
					shop_address,
					shop_phone,
					shop_homepage
				FROM
					shoptbl
				WHERE
					shop_delete_flag = 0
			$keywordsql
						ORDER BY
						id ASC
						LIMIT $num OFFSET ".($page * $num)." ";
		// 						print $sql;
		$data = $this->query($sql);
		return $data;
	}

	//投稿のカワイイ投稿数
	function selectEntryShopCount($sid){
		if(!$sid)
		return array();
		$sql = "SELECT count(DISTINCT sc.id)cnt FROM charmuser c JOIN startyfreecontribute sc ON sc.id = c.c_sc_id WHERE sc.sc_shop_id = $sid ";
		// 		print $sql;
		$data = $this->query($sql);
		return $data[0]["cnt"];
	}

	//投稿のカワイイ獲得数
	function selectCharmShopCount($sid){
		if(!$sid)
		return array();
		$sql = "SELECT count(*)cnt FROM charmuser c JOIN startyfreecontribute sc ON sc.id = c.c_sc_id WHERE sc.sc_shop_id = $sid ";
		// 		print $sql;
		$data = $this->query($sql);
		return $data[0]["cnt"];
	}

	//店舗削除
	function deleteShop($eid){
		if(!$eid)
		return array();
		$useridarr = explode(",", $eid);
		$sqlstr = "(";
		foreach ($useridarr as $key=>$val){
			if($key>0)$or = " OR ";
			$sqlstr .=" $or id= $val ";
		}
		$sqlstr .= ") ";
		$sql = "UPDATE shoptbl SET shop_delete_flag = 1 WHERE $sqlstr ";
		// 				print $sql;
		$data = $this->query($sql,1);
		return $data;
	}

	//店舗詳細ページ情報取得
	//投稿情報取得
	function selectShopDataByID($id){
		if(!$id)
		return array();
		$sql = "SELECT
						shop_category,
						id,
						shop_name,
						shop_pref,
						shop_address,
						shop_phone,
						shop_homepage,
						shop_addtime,
						shop_access,
						shop_holiday,
						shop_opentime
					FROM
						shoptbl
					WHERE
						id = $id
					AND shop_delete_flag = 0";
		// 				print $sql;
		$data = $this->query($sql);
		return $data[0];
	}
}
?>