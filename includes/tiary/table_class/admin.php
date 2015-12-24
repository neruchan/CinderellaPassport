<?php
class admin extends ipfDB{
	function CheckPassword($name,$pass){
		if(!$name)
		return array();

		$sql = "SELECT count(*)cnt FROM admin WHERE account='$name' AND password='$pass' AND delete_flag=0; ";
		//     	print $sql;
		$data = $this->query($sql);
		return $data[0]["cnt"];
	}
	
// 	//全ユーザー数取得
// 	function selectAllUser(){
// 		$sql = "SELECT u.id FROM users u where u.delete_flag = 0";
// 		// 				print $sql;
// 		$data = $this->query($sql);
// 		return $data;
// 	}
// 	
// 	//全ユーザー数取得
// 	function checkMailSetting($uid){
// 		$sql = "SELECT count(*)cnt FROM startyfreemail sf where sf.sma_userid = $uid";
// 		// 				print $sql;
// 		$data = $this->query($sql);
// 		return $data[0]["cnt"];
// 	}

	//全ユーザー数取得
	function selectUserAllNum(){
		$sql = "SELECT
						count(*)cnt
					FROM
						users u
					JOIN users_info ui ON ui.userid = u.id
					WHERE
						u.delete_flag = 0
					";
		// 				print $sql;
		$data = $this->query($sql);
		return $data[0]["cnt"];
	}
	//ユーザー管理ユーザー登録数取得
	function selectUserCntAll($id,$username,$email,$terminal=0,$cmflag=0,$model=0){
		$keywordsql = "";
		if($id!=""){
			$keywordsql .= " AND u.id = '$id' ";
		}

		if($username!=""){
			$keywordsql .= " AND ui.nickname LIKE '%$username%' ";
		}
		if($email!=""){
			$keywordsql .= " AND u.email LIKE '%$email%' ";
		}
		$terminalsql = "";
		if($terminal==1){
			$terminalsql = " AND u.terminal=0 ";
		}elseif($terminal==2){
			$terminalsql = " AND u.terminal=1 ";
		}
		$cmflagsql = "";
		if($cmflag==1){
			$cmflagsql = " AND u.cmflag=0 ";
		}elseif($cmflag==2){
			$cmflagsql = " AND u.cmflag=1 ";
		}

		$modelsql = "";
		if($model==1){
			$modelsql = " AND ui.model_type=0 ";
		}elseif($model==2){
			$modelsql = " AND ui.model_type=1 ";
		}elseif($model==3){
			$modelsql = " AND ui.model_type=2 ";
		}

		$sql = "SELECT
					count(*)cnt
				FROM
					users u
				JOIN users_info ui ON ui.userid = u.id
				WHERE
					u.delete_flag = 0
					$keywordsql
					$terminalsql
					$cmflagsql
					$modelsql
				";
		// 				print $sql;
		$data = $this->query($sql);
		return $data[0]["cnt"];
	}

	//全体ユーザー情報取得
	function selectUserAll($id,$username,$email,$entry_flag=0,$charm_flag=0,$terminal=0,$cmflag=0,$model=0,$num=20,$page=0,$limit=""){
		
		$limitsql = "LIMIT $num OFFSET ".($page * $num)."";
		if($limit!=""){
			$limitsql = "";
		}
		
		$keywordsql = "";
		if($id!=""){
			$keywordsql .= " AND u.id = '$id' ";
		}

		if($username!=""){
			$keywordsql .= " AND ui.nickname LIKE '%$username%' ";
		}
		
		if($email!=""){
			$keywordsql .= " AND u.email LIKE '%$email%' ";
		}
		
		$order = " ORDER BY u.id ASC ";
		if($entry_flag==1){
			$order = " ORDER BY entrynum DESC ";
		}elseif($entry_flag==2){
			$order = " ORDER BY entrynum ASC ";
		}

		if($charm_flag==1){
			$order = " ORDER BY charmnum DESC ";
		}elseif($charm_flag==2){
			$order = " ORDER BY charmnum ASC ";
		}
		$terminalsql = "";
		if($terminal==1){
			$terminalsql = " AND u.terminal=0 ";
		}elseif($terminal==2){
			$terminalsql = " AND u.terminal=1 ";
		}
		$cmflagsql = "";
		if($cmflag==1){
			$cmflagsql = " AND u.cmflag=0 ";
		}elseif($cmflag==2){
			$cmflagsql = " AND u.cmflag=1 ";
		}

		$modelsql = "";
		if($model==1){
			$modelsql = " AND ui.model_type=0 ";
		}elseif($model==2){
			$modelsql = " AND ui.model_type=1 ";
		}elseif($model==3){
			$modelsql = " AND ui.model_type=2 ";
		}
// 		$sql = "SELECT
// 					u.id,
// 					ui.nickname,
// 					ui.username,
// 					ui.birthday,
// 					u.created,
// 					u.terminal,
// 					u.cmflag
// 				FROM
// 					users u
// 				JOIN users_info ui ON ui.userid = u.id
// 				WHERE
// 					u.delete_flag = 0
// 					$keywordsql
// 					ORDER BY u.id ASC
// 				LIMIT $num OFFSET ".($page * $num)." ";
		$sql = "SELECT
						u.id,
						ui.nickname,
						ui.username,
						ui.birthday,
						ui.model_type,
						ui.fullname,
						ui.fullname_kana,
						ui.banti,
						ui.todoufuken,
						ui.phonenumber,
						ui.zipcode,
						ui.model_id,
						u.created,
						u.terminal,
						u.cmflag,
						u.email,
						IFNULL(c.entrynum, 0)entrynum,
						IFNULL(c1.charmnum, 0)charmnum
					FROM
						users u
					JOIN users_info ui ON ui.userid = u.id
					LEFT JOIN(
						SELECT
							sc_userid scuid,
							count(sc_userid)entrynum
						FROM
							startyfreecontribute
						GROUP BY
							sc_userid
					)c ON c.scuid = u.id
					LEFT JOIN(
						SELECT
							count(cham.c_sc_id)charmnum,
							sc.sc_userid chamuid
						FROM
							charmuser cham
						JOIN startyfreecontribute sc ON sc.id = cham.c_sc_id
						GROUP BY
							sc.sc_userid
					)c1 ON c1.chamuid = u.id
					WHERE
						u.delete_flag = 0
						$keywordsql
						$terminalsql
						$cmflagsql
						$modelsql
						$order
					$limitsql";
 						//print $sql;
		$data = $this->query($sql);
		return $data;
	}
	
	//全体ユーザー情報取得
	function selectUserAll2($id,$username,$entry_flag=0,$charm_flag=0,$terminal=0,$cmflag=0,$model=0,$num=99999,$page=0,$limit=""){
		
		$limitsql = "LIMIT $num OFFSET ".($page * $num)."";
		if($limit!=""){
			$limitsql = "";
		}
		
		$keywordsql = "";
		if($id!=""){
			$keywordsql .= " AND u.id = '$id' ";
		}

		if($username!=""){
			$keywordsql .= " AND ui.nickname LIKE '%$username%' ";
		}
		$order = " ORDER BY u.id ASC ";
		if($entry_flag==1){
			$order = " ORDER BY entrynum DESC ";
		}elseif($entry_flag==2){
			$order = " ORDER BY entrynum ASC ";
		}

		if($charm_flag==1){
			$order = " ORDER BY charmnum DESC ";
		}elseif($charm_flag==2){
			$order = " ORDER BY charmnum ASC ";
		}
		$terminalsql = "";
		if($terminal==1){
			$terminalsql = " AND u.terminal=0 ";
		}elseif($terminal==2){
			$terminalsql = " AND u.terminal=1 ";
		}
		$cmflagsql = "";
		if($cmflag==1){
			$cmflagsql = " AND u.cmflag=0 ";
		}elseif($cmflag==2){
			$cmflagsql = " AND u.cmflag=1 ";
		}

		$modelsql = "";
		if($model==1){
			$modelsql = " AND ui.model_type=0 ";
		}elseif($model==2){
			$modelsql = " AND ui.model_type=1 ";
		}elseif($model==3){
			$modelsql = " AND ui.model_type=2 ";
		}
// 		$sql = "SELECT
// 					u.id,
// 					ui.nickname,
// 					ui.username,
// 					ui.birthday,
// 					u.created,
// 					u.terminal,
// 					u.cmflag
// 				FROM
// 					users u
// 				JOIN users_info ui ON ui.userid = u.id
// 				WHERE
// 					u.delete_flag = 0
// 					$keywordsql
// 					ORDER BY u.id ASC
// 				LIMIT $num OFFSET ".($page * $num)." ";
		$sql = "SELECT
						u.id,
						ui.nickname,
						ui.username,
						ui.birthday,
						ui.model_type,
						ui.fullname,
						ui.fullname_kana,
						ui.banti,
						ui.todoufuken,
						ui.phonenumber,
						ui.zipcode,
						ui.model_id,
						u.created,
						u.terminal,
						u.cmflag,
						u.email,
						u.password,
						IFNULL(c.entrynum, 0)entrynum,
						IFNULL(c1.charmnum, 0)charmnum
					FROM
						users u
					JOIN users_info ui ON ui.userid = u.id
					LEFT JOIN(
						SELECT
							sc_userid scuid,
							count(sc_userid)entrynum
						FROM
							startyfreecontribute
						GROUP BY
							sc_userid
					)c ON c.scuid = u.id
					LEFT JOIN(
						SELECT
							count(cham.c_sc_id)charmnum,
							sc.sc_userid chamuid
						FROM
							charmuser cham
						JOIN startyfreecontribute sc ON sc.id = cham.c_sc_id
						GROUP BY
							sc.sc_userid
					)c1 ON c1.chamuid = u.id
					WHERE
						u.delete_flag = 0
						$keywordsql
						$terminalsql
						$cmflagsql
						$modelsql
						$order
					$limitsql";
 						//print $sql;
		$data = $this->query($sql);
		return $data;
	}

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

	//ユーザー削除
	function deleteUser($userid){
		if(!$userid)
		return array();
		$useridarr = explode(",", $userid);
		$sqlstr = "(";
		foreach ($useridarr as $key=>$val){
			if($key>0)$or = " OR ";
			$sqlstr .=" $or id= $val ";
		}
		$sqlstr .= ") ";
		$sql = "UPDATE users SET delete_flag = 1 WHERE $sqlstr ";
// 				print $sql;
		$data = $this->query($sql,1);
		return $data;
	}

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
							ui.zipcode,
							ui.model_type,
							ui.model_id
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

	//管理全体投稿数取得
	function selectEntryAllNum(){
				$sql = "SELECT
						count(*)cnt
					FROM
						startyfreecontribute sc
					JOIN users_info ui ON ui.userid = sc.sc_userid
					WHERE
						sc.sc_delete_flag = 0
						";
		// 						print $sql;
		$data = $this->query($sql);
		return $data[0]["cnt"];
	}

	//管理投稿数取得
	function selectEntryCntAll($id,$title,$terminal=0,$category=0){
		$keywordsql = "";
		if($id!=""){
			$keywordsql .= " AND sc.id = '$id' ";
		}

		if($title!=""){
			$keywordsql .= " AND sc.sc_title LIKE '%$title%' ";
		}

		$terminalsql = "";
		if($terminal==1){
			$terminalsql = " AND sc.sc_terminal=0 ";
		}elseif($terminal==2){
			$terminalsql = " AND sc.sc_terminal=1 ";
		}
		$categorysql="";
		if($category!=0){
			$categorysql = " AND sc.sc_genre=$category ";
		}

		$sql = "SELECT
					count(*)cnt
				FROM
					startyfreecontribute sc
				JOIN users_info ui ON ui.userid = sc.sc_userid
				WHERE
					sc.sc_delete_flag = 0
					$keywordsql
					$terminalsql
					$categorysql
					";
// 						print $sql;
		$data = $this->query($sql);
		return $data[0]["cnt"];
	}

	//全体投稿情報取得
	function selectEntryAll($id,$title,$addtime_flag=0,$terminal=0,$charm_flag,$category=0,$num=20,$page=0){
		$keywordsql = "";
		if($id!=""){
			$keywordsql .= " AND sc.id = '$id' ";
		}

		if($title!=""){
			$keywordsql .= " AND sc.sc_title LIKE '%$title%' ";
		}

		$order = " ORDER BY sc.id ASC ";
		if($addtime_flag==1){
			$order = " ORDER BY sc.sc_addtime DESC ";
		}elseif($addtime_flag==2){
			$order = " ORDER BY sc.sc_addtime ASC ";
		}

		$terminalsql = "";
		if($terminal==1){
			$terminalsql = " AND sc.sc_terminal=0 ";
		}elseif($terminal==2){
			$terminalsql = " AND sc.sc_terminal=1 ";
		}

		if($charm_flag==1){
			$order = " ORDER BY charmnum DESC ";
		}elseif($charm_flag==2){
			$order = " ORDER BY charmnum ASC ";
		}

		$categorysql="";
		if($category!=0){
			$categorysql = " AND sc.sc_genre=$category ";
		}


// 		$sql = "SELECT
// 					sc.id,
// 					sc.sc_genre,
// 					sc.sc_title,
// 					sc.sc_satisfied,
// 					sc.sc_addtime,
// 					sc.sc_terminal,
// 					ui.nickname,
// 					ui.username
// 				FROM
// 					startyfreecontribute sc
// 				JOIN users_info ui ON ui.userid = sc.sc_userid
// 				WHERE
// 					sc.sc_delete_flag = 0
// 						$keywordsql
// 						ORDER BY
// 					sc.id ASC
// 					LIMIT $num OFFSET ".($page * $num)." ";
			$sql = "SELECT
						sc.id,
						sc.sc_genre,
						sc.sc_title,
						sc.sc_satisfied,
						sc.sc_addtime,
						sc.sc_terminal,
						ui.nickname,
						ui.username,
						ui.userid,
						IFNULL(c.charmnum,0) charmnum
					FROM
						startyfreecontribute sc
					JOIN users_info ui ON ui.userid = sc.sc_userid
					LEFT JOIN(
						SELECT
							count(cham.c_sc_id)charmnum,
							cham.c_sc_id c_sc_id
						FROM
							charmuser cham
						GROUP BY
							cham.c_sc_id
					)c ON c.c_sc_id = sc.id
					WHERE
						sc.sc_delete_flag = 0
						$keywordsql
						$terminalsql
						$categorysql
						$order
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
	function selectEntryDataByID($eid){
		if(!$eid)
		return array();
		$sql = "SELECT
					sc.sc_genre,
					sc.id,
					sc.sc_title,
					ui.userid,
					ui.nickname,
					ui.todoufuken,
					ui.birthday,
					ui.username,
					s.shop_category,
					s.id shop_id,
					s.shop_name,
					s.shop_pref,
					sc.sc_where,
					sc.sc_who,
					sc.sc_money,
					sc.sc_coupontype,
					sc.sc_satisfied,
					sc.sc_addtime,
					sc.sc_terminal
				FROM
					startyfreecontribute sc
				JOIN users_info ui ON ui.userid = sc.sc_userid
				LEFT JOIN shoptbl s ON s.id = sc.sc_shop_id
				WHERE
					sc.id = $eid
				AND sc.sc_delete_flag = 0";
		// 				print $sql;
		$data = $this->query($sql);
		return $data[0];
	}

	//全体店舗数取得
	function selectShopAllNum(){
		$sql = "SELECT
						count(*)cnt
					FROM
						shoptbl
					WHERE
						shop_delete_flag = 0 ";
		// 						print $sql;
		$data = $this->query($sql);
		return $data[0]["cnt"];
	}

	//全体店舗数取得(条件あり)
	function selectShopCntAll($id,$name,$category=0){
		$keywordsql = "";
		if($id!=""){
			$keywordsql .= " AND id = '$id' ";
		}

		if($name!=""){
			$keywordsql .= " AND shop_name LIKE '%$name%' ";
		}

		$categorysql="";
		if($category!=0){
			$categorysql = " AND shop_category in($category) ";
		}

		$paysql = "";
		if($money_flag==1){
			$paysql = " AND shop_pay=1 ";
		}elseif($money_flag==2){
			$paysql = " AND shop_pay=0 ";
		}

		$sql = "SELECT
					count(*)cnt
				FROM
					shoptbl
				WHERE
					shop_delete_flag = 0
				$keywordsql
				$categorysql
				$paysql
						";
		// 						print $sql;
		$data = $this->query($sql);
		return $data[0]["cnt"];
	}

	//全体店舗情報取得
	function selectShopAll($id,$name,$entry_flag=0,$charm_flag,$money_flag,$category=0,$num=20,$page=0){
		$keywordsql = "";
		if($id!=""){
			$keywordsql .= " AND id = '$id' ";
		}

		if($name!=""){
			$keywordsql .= " AND shop_name LIKE '%$name%' ";
		}
		$order = " ORDER BY s.shop_addtime DESC ";
		if($entry_flag==1){
			$order = " ORDER BY entrynum DESC ";
		}elseif($entry_flag==2){
			$order = " ORDER BY entrynum ASC ";
		}

		if($charm_flag==1){
			$order = " ORDER BY charmnum DESC ";
		}elseif($charm_flag==2){
			$order = " ORDER BY charmnum ASC ";
		}

		$paysql = "";
		if($money_flag==1){
			$paysql = " AND s.shop_pay=1 ";
		}elseif($money_flag==2){
			$paysql = " AND s.shop_pay=0 ";
		}

		$categorysql="";
		if($category!=0){
			$categorysql = " AND s.shop_category in($category) ";
		}


// 		$sql = "SELECT
// 					id,
// 					shop_category,
// 					shop_name,
// 					shop_pref,
// 					shop_address,
// 					shop_phone,
// 					shop_homepage
// 				FROM
// 					shoptbl
// 				WHERE
// 					shop_delete_flag = 0
// 			$keywordsql
// 						ORDER BY
// 						id ASC
// 						LIMIT $num OFFSET ".($page * $num)." ";
		$sql ="SELECT
					s.id,
					s.shop_category,
					s.shop_name,
					s.shop_pref,
					s.shop_address,
					s.shop_phone,
					s.shop_homepage,
					s.shop_pay,
                    s.shop_email,
                    s.shop_addtime,
					IFNULL(c.entry_shop_num, 0)entrynum,
					IFNULL(c1.charm_shop_num, 0)charmnum
				FROM
					shoptbl s
				LEFT JOIN(
					SELECT
						sc.sc_shop_id,
						count(sc.sc_shop_id)entry_shop_num
					FROM
						startyfreecontribute sc
					GROUP BY
						sc.sc_shop_id
				)c ON c.sc_shop_id = s.id
				LEFT JOIN(
					SELECT
						sc1.sc_shop_id,
						count(cm.c_sc_id)charm_shop_num
					FROM
						charmuser cm
					JOIN startyfreecontribute sc1 ON sc1.id = cm.c_sc_id
					GROUP BY
						cm.c_sc_id
				)c1 ON s.id = c1.sc_shop_id
				WHERE
					s.shop_delete_flag = 0
					$keywordsql
					$paysql
					$categorysql
					$order
					LIMIT $num OFFSET ".($page * $num)." ";
// 			print $sql;
		$data = $this->query($sql);
		return $data;
	}
    
    
    
    //全体店舗数取得(条件あり)-cinderellaversion
	function selectShopCntAllCin($id,$name,$pref="",$couponyn=""){
        $prefsql = "";
        if($pref!=""){
            $prefsql = " AND shop_pref = '$pref'";
        }
        
        $cuposql = "";
        if($couponyn!=""){
            //$cuposql = " AND shop_pref = '$pref'";
        }


		$sql = "SELECT
					count(*)cnt
				FROM
					shoptbl
				WHERE
					shop_delete_flag = 0
				$prefsql
                $cuposql
						";
		// 						print $sql;
		$data = $this->query($sql);
		return $data[0]["cnt"];
	}

    //全体店舗情報取得->cinderellaversion
	function selectShopAllCin($id,$name,$pref="",$addtime="",$couponyn="",$num=20,$page=0){
		$keywordsql = "";
		if($id!=""){
			$keywordsql .= " AND id = '$id' ";
		}

		if($name!=""){
			$keywordsql .= " AND shop_name LIKE '%$name%' ";
		}
        
		$order = " ORDER BY s.shop_addtime DESC ";
		if($addtime!=""){
			$order = " ORDER BY s.shop_addtime ASC ";
		}
        
        $prefsql = "";
        if($pref!=""){
            $prefsql = " AND shop_pref = '$pref'";
        }
        
        $cuposql = "";
        if($couponyn!=""){
            //$cuposql = " AND shop_pref = '$pref'";
        }

// 		$sql = "SELECT
// 					id,
// 					shop_category,
// 					shop_name,
// 					shop_pref,
// 					shop_address,
// 					shop_phone,
// 					shop_homepage
// 				FROM
// 					shoptbl
// 				WHERE
// 					shop_delete_flag = 0
// 			$keywordsql
// 						ORDER BY
// 						id ASC
// 						LIMIT $num OFFSET ".($page * $num)." ";
		$sql ="SELECT
					s.id,
					s.shop_category,
					s.shop_name,
					s.shop_pref,
					s.shop_address,
					s.shop_phone,
					s.shop_homepage,
					s.shop_pay,
                    s.shop_email,
                    s.shop_addtime
				FROM
					shoptbl s
				WHERE
					s.shop_delete_flag = 0
					$prefsql
                    $cuposql
					$order
					LIMIT $num OFFSET ".($page * $num)." ";
// 			print $sql;
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
						s.shop_category,
						s.id,
						s.shop_name,
						s.shop_pref,
						s.shop_address,
						s.shop_phone,
						s.shop_homepage,
						s.shop_addtime,
						s.shop_updatetime,
						s.shop_access,
						s.shop_holiday,
						s.shop_opentime,
						s.shop_pay,
						ads.account,
						ads.passwords
					FROM
						shoptbl s LEFT JOIN admin_shop ads ON ads.shop_id = s.id
					WHERE
						s.id = $id
					AND s.shop_delete_flag = 0";
		// 				print $sql;
		$data = $this->query($sql);
		return $data[0];
	}

	//店舗最新ID
	function selectShopNextID(){
		$sql = "SELECT id FROM shoptbl ORDER BY id DESC LIMIT 1 ";
		// 		print $sql;
		$data = $this->query($sql);
		return $data[0]["id"];
	}

	//店舗電話チェク
	function shopPhoneCheck($no){
		if(!$no)
		return array();
		$sql = "SELECT id FROM shoptbl WHERE shop_phone = '$no' ";
		//print $sql;
		$data = $this->query($sql);
		return $data;
	}
	
	//スマホアプリ用
	function checkSmahoApp($type=1){
		
		$sql = "SELECT * FROM tiary_app_version WHERE type = $type";
		//print $sql;
		$data = $this->query($sql);
		return $data[0];
	}
}
?>