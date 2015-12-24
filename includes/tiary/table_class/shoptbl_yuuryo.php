<?php
class shoptbl_yuuryo extends ipfDB{

	//TEST no you dake!!! ato wa keshite!
	function selectShopAllPC($category='',$category1='',$num=20,$page=0,$keyword="",$city_data_str=""){
		$sqlnew='';
			$sqllike='';
			$sqlcategory='';
			$sqllikes = "";
			if($category1=="9"){
				$sqlnew=' ORDER BY sp.shop_addtime DESC ';
			}elseif($category1=="10"){
				$sqllikes = "LEFT JOIN (SELECT sc_shop_id ,count(sc_shop_id) spnum FROM startyfreecontribute WHERE sc_delete_flag=0 AND sc_secret_flag = 0 GROUP BY sc_shop_id) c ON c.sc_shop_id = sp.id";
				$sqllike=' ORDER BY IFNULL(c.spnum,0) DESC ';
			}else{
				$sqlnew=' ORDER BY sp.shop_addtime DESC ';
			}
			if($category!="" && $category!="9" && $category!="10"){
				$sqlcategory=" AND sp.shop_category in($category) ";
// 				$sqlnew=' ORDER BY sp.shop_addtime DESC ';
			}else{
// 				$sqlnew=' ORDER BY sp.shop_addtime DESC ';
			}
		$keywordsql = "";
		if($keyword!=""){
			$keywordsql = " AND (sp.shop_name LIKE '%$keyword%' OR sp.shop_name_kana LIKE '%$keyword%' OR sp.shop_pref LIKE '%$keyword%' OR sp.shop_city LIKE '%$keyword%' OR sp.shop_address LIKE '%$keyword%' OR sp.shop_keyword LIKE '%$keyword%' ) ";
		}

		if($city_data_str!=""){
			$city_data_str = str_replace(",","','",$city_data_str);
			$keywordsql .=" AND sp.shop_city IN ('$city_data_str') ";
		}

			$sql = "SELECT
									*
								FROM
									shoptbl sp
			$sqllikes
									WHERE
								sp.shop_delete_flag = 0
								
			$keywordsql $sqlcategory  $sqlnew $sqllike LIMIT $num OFFSET ".($page * $num)." ";
// 				print $sql;
		$data = $this->query($sql);
		return $data;
	}
	
	function selectYuuryoShopAllPC($category='',$category1='',$num=20,$page=0,$keyword="",$city_data_str=""){
		$sqlnew='';
			$sqllike='';
			$sqlcategory='';
			$sqllikes = "";
			if($category1=="9"){
				$sqlnew=' ORDER BY sp.shop_addtime DESC ';
			}elseif($category1=="10"){
				$sqllikes = "LEFT JOIN (SELECT sc_shop_id ,count(sc_shop_id) spnum FROM startyfreecontribute WHERE sc_delete_flag=0 AND sc_secret_flag = 0 GROUP BY sc_shop_id) c ON c.sc_shop_id = sp.id";
				$sqllike=' ORDER BY IFNULL(c.spnum,0) DESC ';
			}else{
				$sqlnew=' ORDER BY sp.shop_addtime DESC ';
			}
			if($category!="" && $category!="9" && $category!="10"){
				$sqlcategory=" AND sp.shop_category in($category) ";
// 				$sqlnew=' ORDER BY sp.shop_addtime DESC ';
			}else{
// 				$sqlnew=' ORDER BY sp.shop_addtime DESC ';
			}
		$keywordsql = "";
		if($keyword!=""){
			$keywordsql = " AND (sp.shop_name LIKE '%$keyword%' OR sp.shop_name_kana LIKE '%$keyword%' OR sp.shop_pref LIKE '%$keyword%' OR sp.shop_city LIKE '%$keyword%' OR sp.shop_address LIKE '%$keyword%' OR sp.shop_keyword LIKE '%$keyword%' ) ";
		}

		if($city_data_str!=""){
			$city_data_str = str_replace(",","','",$city_data_str);
			$keywordsql .=" AND sp.shop_city IN ('$city_data_str') ";
		}

			$sql = "SELECT
									*
								FROM
									shoptbl sp
			$sqllikes
									WHERE
								sp.shop_delete_flag = 0 AND shop_pay = 1
								
			$keywordsql $sqlcategory  $sqlnew $sqllike LIMIT $num OFFSET ".($page * $num)." ";
// 				print $sql;
		$data = $this->query($sql);
		return $data;
	}
	
	function getAllInquiryByShopId($num=20,$page=0, $shop_id){
		$sql = "SELECT
					*
				FROM
					shop_inquiry y
				WHERE
					y.shop_id = $shop_id
					ORDER BY y.id ASC
					LIMIT $num OFFSET ".($page * $num).";
				";
		//print $sql;
		$data = $this->query($sql);
		return $data;
	}
	
	function getAccountNameById($accountId){
		$sql = "SELECT
					y.username
				FROM
					users y
				WHERE
					y.id = $accountId
				";
		//print $sql;
		$data = $this->query($sql);
		return $data[0];
	}
	
	function getInquiryDetails($inquiry_id){
		$sql = "SELECT
					y.*
				FROM
					shop_inquiry y
				WHERE
					y.id = $inquiry_id
				";
		//print $sql;
		$data = $this->query($sql);
		return $data[0];
	}
	
	function getReservationDetails($reservation_id){
		$sql = "SELECT
					y.*
				FROM
					shop_reservation y
				WHERE
					y.id = $reservation_id
				";
		//print $sql;
		$data = $this->query($sql);
		return $data[0];
	}
	
	function getInquiryMailTemplateByShopId($shop_id){
		$sql = "SELECT
					m.*
				FROM
					mail_template m
				WHERE
					m.shop_id = $shop_id AND type = 1;
				";
		//print $sql;
		$data = $this->query($sql);
		return $data[0];
	}
	
	function getReservationMailTemplateByShopId($shop_id){
		$sql = "SELECT
					m.*
				FROM
					mail_template m
				WHERE
					m.shop_id = $shop_id AND type = 2;
				";
		//print $sql;
		$data = $this->query($sql);
		return $data[0];
	}
	
	function getAllReservationByShopId($num=20,$page=0, $shop_id){
		$sql = "SELECT
					*
				FROM
					shop_reservation y
				WHERE
					y.shop_id = $shop_id
					ORDER BY y.id ASC
					LIMIT $num OFFSET ".($page * $num).";
				";
		//print $sql;
		$data = $this->query($sql);
		return $data;
	}
	
	function getAllReservationByUserId($user_id){
		$sql = "SELECT
					y.*,
					s.shop_name
				FROM
					shop_reservation y
				JOIN shoptbl s ON s.id = y.shop_id
				WHERE
					y.user_id = $user_id
					ORDER BY y.apply_date DESC;
				";
		// 				print $sql;
		$data = $this->query($sql);
		return $data;
	}

	function getAllNumberOfReservation($shop_id){
		
		$sql = "SELECT
					count(*)cnt
				FROM
					shop_reservation
				WHERE
					shop_id = $shop_id
				";
		// 				print $sql;
		$data = $this->query($sql);
		return $data[0]["cnt"];
	}
	
	function getAllNumberOfCallHistory($shop_id){
		
		$sql = "SELECT
					count(*)cnt
				FROM
					call_history
				WHERE
					shop_id = $shop_id
				";
		// 				print $sql;
		$data = $this->query($sql);
		return $data[0]["cnt"];
	}
		
	function getAllNumberOfYuuryoShop(){
		
		$sql = "SELECT
					count(*)cnt
				FROM
					shoptbl
				WHERE
					shop_pay = 1 AND
					shop_delete_flag = 0
				";
		// 				print $sql;
		$data = $this->query($sql);
		return $data[0]["cnt"];
	}
	
	function getAllNumberOfInquiry($shop_id){
		
		$sql = "SELECT
					count(*)cnt
				FROM
					shop_inquiry
				WHERE
					shop_id = $shop_id
				";
		// 				print $sql;
		$data = $this->query($sql);
		return $data[0]["cnt"];
	}
	
	function getPictureDetailsById($id ,$shop_id){
		$sql = "SELECT
					*
				FROM
					picture_yuuryo y
				WHERE
					y.shop_id = $shop_id
					AND
					y.id = $id
				";
		//print $sql;
		$data = $this->query($sql);
		return $data[0];
	}
	
	function getYuuryoImage($shop_id){
		$sql = "SELECT
					y.picture_1
				FROM
					shoptbl_yuuryo y
				JOIN shoptbl s ON s.id = y.shop_id
				WHERE
					y.shop_id = $shop_id
				";
		//print $sql;
		$data = $this->query($sql);
		return $data[0]["picture_1"];
	}
	
	function getShopYuuryoDetails($shop_id){
		$sql = "SELECT
					y.*
				FROM
					shoptbl_yuuryo y
				JOIN shoptbl s ON s.id = y.shop_id
				WHERE
					y.shop_id = $shop_id
				";
		//print $sql;
		$data = $this->query($sql);
		return $data[0];
	}
	
	function getImageYuuryoPathById($pic_id){
		$sql = "SELECT
					path
				FROM
					picture_yuuryo
				WHERE
					id = $pic_id
				";
		$data = $this->query($sql);
		return $data[0];
	}
	
	function getShopDetailsForYuuryo($shop_id){
		$sql = "SELECT
					y.chosen_template,
					y.picture_1,
					y.picture_2,
					y.picture_3,
					y.picture_4,
					y.picture_logo,
					y.introductory_essay,
					s.shop_homepage,
					y.googlemap_url,
					s.shop_category,
					s.shop_name,
					s.shop_phone,
					s.shop_address,
					s.shop_access,
					s.shop_opentime,
					s.shop_holiday,
					s.shop_pref
				FROM
					shoptbl_yuuryo y
				JOIN shoptbl s ON s.id = y.shop_id
				WHERE
					y.shop_id = $shop_id
				";
		//print $sql;
		$data = $this->query($sql);
		return $data[0];
	}
	
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
	
	function getAllNumberOfPictureByShopId($shop_id){
		
		$sql = "SELECT
					count(*)cnt
				FROM
					picture_yuuryo
				WHERE
					shop_id = $shop_id
				";
		// 				print $sql;
		$data = $this->query($sql);
		return $data[0]["cnt"];
	}
	
	function getAllPictureByShopId($num=20,$page=0, $shop_id){
		$sql = "SELECT
					*
				FROM
					picture_yuuryo
				WHERE
					shop_id = $shop_id
					ORDER BY id ASC
				LIMIT $num OFFSET ".($page * $num)." ";
		// 				print $sql;
		$data = $this->query($sql);
		return $data;
	}
	
	function getAllCupoun($num=20,$page=0, $shop_id){
		$sql = "SELECT
					s.id,
					s.effective_date,
					s.category,
					s.title,
					s.use_period,
					s.number_of_use
					
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
	
	function getCallHistory($fromYear, $fromMonth, $fromDay, $toYear, $toMonth, $toDay, $num=20,$page=0, $shop_id){
		
		$scriptWhere = "";
		if($fromYear != "" && $fromYear != 0){
			$scriptWhere .= " AND YEAR(call_date) >= '".$fromYear."'";
		}
		
		if($toYear != "" && $toYear != 0){
			$scriptWhere .= " AND YEAR(call_date) <= '".$toYear."'";
		}
		
		if($fromMonth != "" && $fromMonth != 0){
			$scriptWhere .= " AND MONTH(call_date) >= '".$fromMonth."'";
		}
		
		if($toMonth != "" && $toMonth != 0){
			$scriptWhere .= " AND MONTH(call_date) <= '".$toMonth."'";
		}
		
		if($fromDay != "" && $fromDay != 0){
			$scriptWhere .= " AND DAY(call_date) >= '".$fromDay."'";
		}
		
		if($toDay != "" && $toDay != 0){
			$scriptWhere .= " AND DAY(call_date) <= '".$toDay."'";
		}
		
		$sql = "SELECT
					c.id AS call_id, c.shop_id, c.call_date,u.id AS u_id, u.username
				FROM
					call_history c
				LEFT JOIN users u ON u.id = c.user_id
				WHERE
					c.shop_id = $shop_id".$scriptWhere."
					ORDER BY c.call_date DESC
				LIMIT $num OFFSET ".($page * $num)." ";
		 				//print $sql;
		$data = $this->query($sql);
		return $data;
	}
	
	function getAllNumberOfShownCallHistory($fromYear, $fromMonth, $fromDay, $toYear, $toMonth, $toDay, $shop_id){
		$scriptWhere = "";
		if($fromYear != "" && $fromYear != 0){
			$scriptWhere .= " AND YEAR(call_date) >= '".$fromYear."'";
		}
		
		if($toYear != "" && $toYear != 0){
			$scriptWhere .= " AND YEAR(call_date) <= '".$toYear."'";
		}
		
		if($fromMonth != "" && $fromMonth != 0){
			$scriptWhere .= " AND MONTH(call_date) >= '".$fromMonth."'";
		}
		
		if($toMonth != "" && $toMonth != 0){
			$scriptWhere .= " AND MONTH(call_date) <= '".$toMonth."'";
		}
		
		if($fromDay != "" && $fromDay != 0){
			$scriptWhere .= " AND DAY(call_date) >= '".$fromDay."'";
		}
		
		if($toDay != "" && $toDay != 0){
			$scriptWhere .= " AND DAY(call_date) <= '".$toDay."'";
		}
		
		$sql = "SELECT
					count(*)cnt
				FROM
					call_history
				WHERE
					shop_id = $shop_id".$scriptWhere;
		 				//print "<br><br>".$sql;
		$data = $this->query($sql);
		return $data[0]["cnt"];
	}
	
	function getUserInfo($user_id){
		$sql = "SELECT
					c.id AS call_id, c.shop_id, c.call_date,  
					u.id AS u_id,u.username
				FROM
					call_history c
				LEFT JOIN users u ON u.id = c.user_id
				WHERE
					c.shop_id = $shop_id
					ORDER BY c.call_date DESC
				LIMIT $num OFFSET ".($page * $num)." ";
		$data = $this->query($sql);
		return $data[0];
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
	
	function deleteCallHistory($call_id){
		if(!$call_id)
			return array();
		$callidarr = explode(",", $call_id);
		$sqlstr = "(";
		foreach ($callidarr as $key=>$val){
			if($key>0)$or = " OR ";
			$sqlstr .=" $or id= $val ";
		}
		$sqlstr .= ") ";
		$sql = "DELETE FROM call_history WHERE $sqlstr ";
 				print $sql;
		$data = $this->query($sql,1);
		return $data;
	}
	
	function deleteReservation($reservationId){
		if(!$reservationId)
			return array();
		$reservationidarr = explode(",", $reservationId);
		$sqlstr = "(";
		foreach ($reservationidarr as $key=>$val){
			if($key>0)$or = " OR ";
			$sqlstr .=" $or id= $val ";
		}
		$sqlstr .= ") ";
		$sql = "DELETE FROM shop_reservation WHERE $sqlstr ";
 				print $sql;
		$data = $this->query($sql,1);
		return $data;
	}
	
	function deleteInquiry($inquiryId){
		if(!$inquiryId)
			return array();
		$inquiryidarr = explode(",", $inquiryId);
		$sqlstr = "(";
		foreach ($inquiryidarr as $key=>$val){
			if($key>0)$or = " OR ";
			$sqlstr .=" $or id= $val ";
		}
		$sqlstr .= ") ";
		$sql = "DELETE FROM shop_inquiry WHERE $sqlstr ";
 				print $sql;
		$data = $this->query($sql,1);
		return $data;
	}
	
	function deletePicture($pictureId){
		if(!$pictureId)
			return array();
		$pictureidarr = explode(",", $pictureId);
		$sqlstr = "(";
		foreach ($pictureidarr as $key=>$val){
			if($key>0)$or = " OR ";
			$sqlstr .=" $or id= $val ";
			
			$path = $this->getImageYuuryoPathById($val);
			$fullPath = "/var/www/html/tiary/s/pjpic/".$path["path"];
			if(file_exists($fullPath)){
				unlink($fullPath);
			}
		}
		$sqlstr .= ") ";
		$sql = "DELETE FROM picture_yuuryo WHERE $sqlstr ";
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