<?php
class timeline extends ipfDB{

	//ÉuÉbÉNÉ}Å[ÉNàÍóó
	function selectMyBookmarks($uid,$type=0,$num=4,$last_id=0){
		if(!$uid)
			return array();
		if($type == 1) {
			$sql = "SELECT 
					b.id,
					b.sb_userid as user_id,
					b.sb_sc_id as marked_id,
					DATE_FORMAT(b.sb_addtime, '%m/%d %H:%i') as addtime,
					s.shop_category as category_id,
					s.shop_name as title,
					IFNULL(s.shop_img,'no-img.jpg') as image,
					s.shop_pay as shop_pay,
					sy.picture_logo as picture_logo
				FROM 
					startyfreebookmark b, shoptbl s, shoptbl_yuuryo sy
				WHERE b.sb_sc_id = s.id AND 
					b.sb_userid = $uid AND s.shop_delete_flag = 0 AND s.id = sy.shop_id
				ORDER BY 
					b.sb_addtime DESC LIMIT $num OFFSET $last_id";
		}else {
			$sql = "SELECT 
					b.id,
					b.sb_userid as user_id,
					b.sb_sc_id as marked_id,
					DATE_FORMAT(b.sb_addtime, '%m/%d %H:%i') as addtime,
					s.sc_genre as category_id,
					s.sc_title as img_title,
					IFNULL(s.sc_img,'no-img.jpg') as image,
					ui.userid as user_id,
					IFNULL(ui.nickname,ui.username) as title,
					IFNULL(ui.userpic,'no-img.jpg') as user_image 
				FROM 
					startyfreebookmark b, (startyfreecontribute s LEFT JOIN users_info ui ON ui.userid = s.sc_userid) 
				WHERE b.sb_sc_id = s.id AND 
					b.sb_userid = $uid AND s.sc_delete_flag = 0 
				ORDER BY 
					b.sb_addtime DESC LIMIT $num OFFSET $last_id";
		}

		//print $sql;
		$data = $this->query($sql);
		return $data;
	}

	//ãCÇ…ì¸ÇËÉÜÅ[ÉUÅ[ìäçeàÍóó
	function selectDearUserNewTimeline($uid,$num=4,$last_id=0){
		if(!$uid)
			return array();

			$sql = "SELECT * FROM 
					( 
						(SELECT 
							s.sc_userid,
							s.sc_genre as category_id,
							s.id as img_id,
							s.sc_title as img_title,
							IFNULL(s.sc_img,'no-img.jpg') as image,
							s.sc_addtime as addtime,
							null as shop_id,
							spui.userid as dear_id,
							IFNULL(spui.nickname,spui.username) as dear_name,
							IFNULL(spui.userpic,'no-img.jpg') as dear_image,
							spui.model_type,
							'' as picture_1,'' as picture_2,'' as picture_3,'' as picture_4, '' as picture_logo,
							0 as is_shop,
							s.sc_entry_type as entry_type
							FROM 
								startyfreeplayer sp, users, users_info spui, startyfreecontribute s 
							WHERE sp.sp_userid = $uid AND sp.sp_userfriendid = users.id AND users.delete_flag = 0 
							AND users.id = spui.userid AND sp.sp_userfriendid = s.sc_userid AND s.sc_delete_flag = 0
						) 
						UNION 
						(
							SELECT 
								s.sc_userid,
								sp.shop_category as category_id,
								s.id as img_id,
								s.sc_title as img_title,
								IFNULL(s.sc_img,'no-img.jpg') as image,
								s.sc_addtime as addtime,
								s.sc_shop_id as shop_id,
								b.sb_sc_id as dear_id,
								sp.shop_name as dear_name,
								IFNULL(sp.shop_img,'no-img.jpg') as dear_image,
								null as model_type,
								'' as picture_1,'' as picture_2,'' as picture_3,'' as picture_4, '' as picture_logo,
								1 as is_shop,
								s.sc_entry_type as entry_type
							FROM 
								startyfreebookmark b, shoptbl sp, startyfreecontribute s 
							WHERE b.sb_sc_id = sp.id AND b.sb_sc_id = s.sc_shop_id AND 
								b.sb_userid = $uid AND sp.shop_delete_flag = 0
						) 
						UNION 
						(
							SELECT 
								null as sc_userid,
								s.shop_category as category_id,
								null as img_id,
								'' as img_title,
								COALESCE(sy.picture_1,sy.picture_2,sy.picture_3,sy.picture_4) as image,
								sy.update_time as addtime,
								null as shop_id,
								b.sb_sc_id as dear_id,
								s.shop_name as dear_name,
								IFNULL(s.shop_img,'no-img.jpg') as dear_image,
								null as model_type,
								sy.picture_1,sy.picture_2,sy.picture_3,sy.picture_4, '' as picture_logo,
								1 as is_shop,
								'' as entry_type
							FROM 
								startyfreebookmark b, shoptbl s, shoptbl_yuuryo sy 
							WHERE b.sb_sc_id = s.id AND b.sb_userid = $uid AND s.shop_delete_flag = 0 
								AND sy.shop_id = s.id
						) 
						UNION
						(
							SELECT 
								s.sc_userid,
								s.sc_genre as category_id,
								s.id as img_id,
								s.sc_title as img_title,
								IFNULL(s.sc_img,'no-img.jpg') as image,
								s.sc_addtime as addtime,
								null as shop_id,
								spui.userid as dear_id,
								IFNULL(spui.nickname,spui.username) as dear_name,
								IFNULL(spui.userpic,'no-img.jpg') as dear_image,
								spui.model_type,
								'' as picture_1,'' as picture_2,'' as picture_3,'' as picture_4, '' as picture_logo,
								0 as is_shop,
								s.sc_entry_type as entry_type
							FROM 
								users, users_info spui, startyfreecontribute s 
							WHERE users.delete_flag = 0 AND users.id = $uid
							AND users.id = spui.userid AND s.sc_userid = $uid AND s.sc_delete_flag = 0
							
							
						)
						UNION
						(
							SELECT 
								$uid as sc_userid, 
								s.shop_category as category_id,
								spui.userpic as img_id,
								IFNULL(spui.nickname,spui.username) as img_title,
								COALESCE(sy.picture_1,sy.picture_2,sy.picture_3,sy.picture_4) as image,
								b.sb_addtime as addtime,
								s.shop_address as shop_id,
								b.sb_sc_id as dear_id,
								s.shop_name as dear_name,
								IFNULL(s.shop_img,'no-img.jpg') as dear_image,
								null as model_type,
								sy.picture_1,sy.picture_2,sy.picture_3,sy.picture_4,sy.picture_logo,
								2 as is_shop,
								'' as entry_type
							FROM 
								startyfreebookmark b, shoptbl s, shoptbl_yuuryo sy, users u, users_info spui
							WHERE b.sb_sc_id = s.id AND 
								b.sb_userid = $uid AND u.id = $uid AND spui.userid = u.id AND s.shop_delete_flag = 0 AND s.id = sy.shop_id
						)
						UNION
						(
							SELECT 
								$uid as sc_userid, 
								s.sc_genre as category_id,
								s.id as img_id,
								s.sc_title as img_title,
								IFNULL(s.sc_img,'no-img.jpg') as image,
								b.sb_addtime as addtime,
								null as shop_id,
								s.sc_userid as dear_id,
								IFNULL(spui.nickname,spui.username) as dear_name,
								IFNULL(spui.userpic,'no-img.jpg') as dear_image,
								spui.model_type,
								'' as picture_1,'' as picture_2,'' as picture_3,'' as picture_4, '' as picture_logo,
								3 as is_shop,
								s.sc_entry_type as entry_type 
							FROM 
								startyfreebookmark b, (startyfreecontribute s LEFT JOIN users_info spui ON spui.userid = $uid) 
							WHERE b.sb_sc_id = s.id AND 
								b.sb_userid = $uid AND s.sc_delete_flag = 0 
						)
						
					) 
					a ORDER BY addtime DESC LIMIT $num OFFSET $last_id";

		//print $sql;
		$data = $this->query($sql);
		return $data;
	}
	
	
	//byネルソン
	function selectShopDetails($sid){
		if(!$sid)
			return array();
		
		$sql = "SELECT 
				s.shop_pay,
				sy.picture_logo
			FROM 
				shoptbl s
			JOIN shoptbl_yuuryo sy ON sy.shop_id = s.id
			WHERE s.id = $sid AND s.shop_delete_flag = 0;
			";
	

		//print $sql;
		$data = $this->query($sql);
		return $data[0];
	}

}
