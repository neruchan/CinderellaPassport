<?php
class entry extends ipfDB{

	//投稿ページタブ追加用
	function selectNextID(){
		$sql = "select nextval('startyfreetab_id_seq')";
        //print $sql;
        $data = $this->query($sql);
        return $data[0]['nextval'];
    }

    //マイページほしいアイテム重複方チェック
    function isExist($user_id, $tag){
    	if(!$user_id || !$tag)
    	return true;

    	$sql = "select count(id) as cnt from startyfreetab where st_userid = $user_id and st_name = '$tag' and st_delete_flag = 0";

    	//print $sql;
    	$data = $this->query($sql);
    	return $data[0]['cnt'] > 0;
    }

    //投稿公開ページ投稿情報取得
    function selectEntry($id){
    	if(!$id)
    	return array();
    	$sql = "SELECT * FROM startyfreecontribute WHERE id =$id AND sc_delete_flag=0";
    	//print $sql;
    	$data = $this->query($sql);
    	return $data;
    }

    //投稿ページ新規情報自生成ID取得
    function selectContributeNextID(){
    	$sql = "select nextval('startyfreecontribute_id_seq')";
    	//print $sql;
    	$data = $this->query($sql);
    	return $data[0]['nextval'];
    }

    //投稿公開ページ公開フラグ設定
	function selectEntryPublic($id){
		if(!$id)
		return;
		$sql = "UPDATE startyfreecontribute SET sc_public_flag = 1 WHERE id = $id";
		//print $sql;
		$data = $this->query($sql, 1);
		return $data;
	}

	//トップページ投稿公開全体情報取得
	function selectContributeAll($category='',$num=20,$secret=""){
		$sqlnew='';
		$sqllike='';
		$sqlcategory='';
		$sqllikes = "";
		if($category=="9"){
			$sqlnew=' ORDER BY sc.sc_addtime DESC ';
		}elseif($category=="10"){
			$sqllikes = "LEFT JOIN (SELECT c_sc_id cscid ,count(c_sc_id) cnum FROM charmuser GROUP BY c_sc_id) c ON c.cscid = sc.id";
			$sqllike=' ORDER BY IFNULL(c.cnum,0) DESC ';
		}elseif($category!="" && $category!="9" && $category!="10"){
			$sqlcategory=" AND sc.sc_genre=$category ";
			$sqlnew=' ORDER BY sc.sc_addtime DESC ';
		}else{
			$sqlnew=' ORDER BY sc.sc_addtime DESC ';
		}

		$sqlsecret = "";
		if($secret!=""){
			$sqlsecret = " AND sc.sc_secret_flag = 0";
		}

		$sql = "SELECT
							sc.*, ui.userid,
							ui.userpic,
							ui.username,
							ui.nickname
						FROM
							startyfreecontribute sc
						JOIN users_info ui ON ui.userid = sc.sc_userid
						$sqllikes
						WHERE
						sc.sc_delete_flag = 0 AND sc.sc_secret_flag = 0
						$sqlsecret $sqlcategory  $sqlnew $sqllike LIMIT $num";
//  		print $sql;
		$data = $this->query($sql);
		return $data;
	}

	//トップページもっと見るボタンクリーク情報追加
	function selectEntryList($last_id,$category='',$num=6,$secret=""){
		if(!$last_id)
		return array();
		$sqlnew='';
		$sqllike='';
		$sqlcategory='';
		$sqllikes = "";
		if($category=="9"){
			$sqlnew=' ORDER BY sc.sc_addtime DESC ';
		}elseif($category=="10"){
			$sqllikes = "LEFT JOIN (SELECT c_sc_id cscid ,count(c_sc_id) cnum FROM charmuser GROUP BY c_sc_id) c ON c.cscid = sc.id";
			$sqllike=' ORDER BY IFNULL(c.cnum,0) DESC ';
		}elseif($category!="" && $category!="9" && $category!="10"){
			$sqlcategory=" AND sc.sc_genre=$category ";
			$sqlnew=' ORDER BY sc.sc_addtime DESC ';
		}else{
			$sqlnew=' ORDER BY sc.sc_addtime DESC ';
		}
		$sqlsecret = "";
		if($secret!=""){
			$sqlsecret = " AND sc_secret_flag = 0";
		}
		$sql = "SELECT sc.*, ui.userid,
							ui.userpic,
							ui.username,
							ui.nickname
						FROM
							startyfreecontribute sc
						JOIN users_info ui ON ui.userid = sc.sc_userid
						$sqllikes
						WHERE
						sc_delete_flag = 0 AND sc_secret_flag = 0 $sqlsecret $sqlcategory  $sqlnew $sqllike  LIMIT $num OFFSET $last_id";
// 		print $sql;
		$data = $this->query($sql);
		return $data;

	}

	//アイテム詳細情報取得
	function selectContribute($id,$userid=""){
		if(!$id)
		return array();
		if($userid!=""){
			$userid = " AND sc.sc_userid= $userid ";
		}
		$sql = "SELECT sc.*,ui.userid,ui.userpic,ui.username,ui.nickname FROM startyfreecontribute sc JOIN users_info ui ON ui.userid=sc.sc_userid WHERE id=$id  $userid AND sc.sc_delete_flag=0 ";
		//print $sql;
		$data = $this->query($sql);
		return $data;
	}

	//アイテム評価情報取得
	function selectAssess($id,$uid=""){
		if(!$id)
		return array();
		if($uid!=""){
			$sql1 = "SELECT count(*) FROM startyfreeassess WHERE sas_sc_id=$id AND sas_userid=$uid ";
			//print $sql;
			$dataCount = $this->query($sql1);
			$data=array();
			if($dataCount[0]['count']>0){
				$sql = "SELECT sas_detailed,sas_userid,sas_img FROM startyfreeassess WHERE sas_sc_id = $id";
				//print $sql;
				$data = $this->query($sql);
			}
		}else{
			$sql = "SELECT sas_detailed,sas_userid,sas_img FROM startyfreeassess WHERE sas_sc_id = $id";
			//print $sql;
			$data = $this->query($sql);
		}

		return $data;
	}


	//エントリー数
	function selectEntryNum($id){
		if(!$id)
		return array();
		$sql = "SELECT count(*) FROM startyfreecontribute WHERE sc_public_flag=1AND sc_delete_flag=0 AND sc_userid=$id ";
		//print $sql;
		$data = $this->query($sql);
		$sql1 = "SELECT count(*) FROM startyfreecontribute WHERE sc_success_flag=1 AND sc_delete_flag=0 AND sc_public_flag=1 AND sc_userid=$id ";
		//print $sql;
		$data1 = $this->query($sql1);
		$sql2 = "SELECT count(*) FROM startyfreecontribute WHERE sc_finish_advance_flag =1 AND sc_delete_flag=0 AND sc_public_flag=1 AND sc_userid=$id ";
		//print $sql;
		$data2 = $this->query($sql2);
		$data['entry_num'] = $data[0]['count'];
		if($data[0]['count']>0){
			$entry_percent = explode('.', ($data1[0]['count']/$data[0]['count']*100));
			if($entry_percent[0]!=''){
				$entry_percent = $entry_percent[0];
			}
		}else{
			$entry_percent = 0;
		}
		if($data[0]['count']>0){
			$entry_cancel = explode('.', ($data2[0]['count']/$data[0]['count']*100));
			if($entry_cancel[0]!=''){
				$entry_cancel = $entry_percent[0];
			}
		}else{
			$entry_cancel = 0;
		}

		$data['entry_percent'] = $entry_percent;
		$data['entry_cancel'] = $entry_cancel;
		return $data;
	}

	//エントリー一覧
	function selectContributeMy($uid,$endtag=0,$num=5){
		if(!$uid)
		return array();
		if($endtag==0){
			//早期終了
			$sql = "SELECT T.* FROM startyfreecontribute T WHERE sc_public_flag=1 AND sc_delete_flag=0 AND sc_userid=$uid AND sc_finish_advance_flag=0 AND not EXISTS (SELECT 1 from startyfreeassess WHERE sas_sc_id = T.id) ORDER BY T.sc_addtime DESC LIMIT $num;";
		}else{
			$sql = "SELECT T.* FROM startyfreecontribute T WHERE sc_public_flag=1 AND sc_delete_flag=0 AND sc_userid=$uid  AND ( EXISTS (SELECT 1 from startyfreeassess WHERE sas_sc_id = T.id) OR sc_finish_advance_flag=1) ORDER BY T.sc_addtime DESC LIMIT $num;";
		}

		 		//print $sql;
		$data = $this->query($sql);
		return $data;
	}

	//エントリー一覧もっと見るボタンクリーク情報追加
	function selectContributeMyAdd($uid,$last_id,$endtag=0,$num=5){
		if(!$uid || !$last_id)
		return array();
		if($endtag==0){
			//早期終了
			$sql = "SELECT T.* FROM startyfreecontribute T WHERE sc_public_flag=1 AND sc_delete_flag=0 AND sc_userid=$uid AND sc_finish_advance_flag=0 AND not EXISTS (SELECT 1 from startyfreeassess WHERE sas_sc_id = T.id) ORDER BY T.sc_addtime DESC LIMIT $num OFFSET $last_id";
		}else{
			$sql = "SELECT T.* FROM startyfreecontribute T WHERE sc_public_flag=1 AND sc_delete_flag=0 AND sc_userid=$uid  AND ( EXISTS (SELECT 1 from startyfreeassess WHERE sas_sc_id = T.id) OR sc_finish_advance_flag=1) ORDER BY T.sc_addtime DESC LIMIT $num OFFSET $last_id";
		}
// 		$sql = "SELECT T.* FROM startyfreecontribute T WHERE sc_public_flag=1 AND sc_delete_flag=0 AND sc_userid=$uid AND $not EXISTS (SELECT 1 from startyfreeassess WHERE sas_sc_id = T.id) ORDER BY T.sc_addtime DESC LIMIT $num OFFSET $last_id";
		//print $sql;
		$data = $this->query($sql);
		return $data;
	}

	//通報時ユーザ情報とアイテム情報取得
	function selectContributeNotify($eid,$uid){
		if(!$eid)
		return array();
		$sql = "SELECT u.email,ui.username,ui.nickname FROM users u JOIN users_info ui ON ui.userid=u.id  WHERE u.id=$uid ";
		//print $sql;
		$data = $this->query($sql);
		$sql1 = "SELECT sc_title FROM startyfreecontribute WHERE sc_public_flag=1 AND sc_delete_flag=0 AND id=$eid ";
		//print $sql;
		$data1 = $this->query($sql1);
		$data['user_data'] = $data[0];
		$data['entry_data'] = $data1[0];
		return $data[0];
	}

	//プレイヤー
	function selectPlayer($eid,$uid){
		if(!$eid)
		return array();
		$sql = "SELECT count(*) FROM startyfreeplayer WHERE sp_sc_id = $eid AND sp_userid=$uid";
		//print $sql;
		$data = $this->query($sql);
		return $data[0]['count'];
	}

	//ログインID確認
	function selectCheckUser($email,$birthday){
		if(!$email)
		return array();
		$sql = "SELECT u.username,u.password FROM users u JOIN  users_info ui ON ui.userid = u.id WHERE u.email='$email' AND ui.birthday = '$birthday'";
		//print $sql;
		$data = $this->query($sql);
		return $data[0];
	}

	//取引該当者なし終了
	function itemCancel($eid){
		if(!$eid)
		return array();
		$sql = "UPDATE startyfreecontribute SET sc_finish_flag = 1 WHERE id = $eid";
		//print $sql;
		$data = $this->query($sql);
		return $data;
	}

	//取引相手選定
	function itemDealplayer($eid,$uid=0,$cid=0){
		if(!$eid)
		return array();
		$sql = "UPDATE startyfreecontribute SET sc_success_flag = 1,sc_change_userid=$uid WHERE id = $eid";
		//print $sql;
		$data = $this->query($sql);
		return $data;
	}

	//アイテムタイトル取得
	function selectItemTitle($eid){
		if(!$eid)
		return array();
		$sql = "SELECT sc_title,sc_userid FROM startyfreecontribute WHERE sc_delete_flag=0 AND id = $eid ";
		//print $sql;
		$data = $this->query($sql);
		return $data;
	}

	//メーセジ情報取得
	function selectMessage($eid){
		if(!$eid)
		return array();
		$sql = "SELECT sm.*,sc.sc_userid,ui.username,ui.nickname,ui.userpic FROM startyfreemessage sm JOIN  startyfreecontribute sc ON sc.id = sm.sm_sc_id JOIN users_info  ui ON ui.userid=sm.sm_userid WHERE sm.sm_sc_id = $eid AND sc.sc_delete_flag=0 ORDER BY sm.sm_addtime DESC ";
		//print $sql;
		$data = $this->query($sql);
		return $data;
	}

	//退会ユーザチェック
	function checkUser($uname,$email){
		if(!$uname)
		return array();
		$sql = "SELECT count(*) FROM users WHERE username = '$uname' AND email = '$email'";
		//print $sql;
		$data = $this->query($sql);
		$datas['user_true'] = $data[0]['count'];

		$sql1 = "SELECT count(*) FROM users u JOIN startyfreecontribute s ON s.sc_userid=u.id  WHERE u.username = '$uname' AND u.email = '$email' AND s.sc_success_flag=0 AND s.sc_delete_flag=0 ";
		//print $sql;
		$data1 = $this->query($sql1);
		$datas['all_clean'] = $data1[0]['count'];

		return $datas;
	}

	//退会

	function deleteUser($uname,$email){
		if(!$uname)
		return array();
		$sql = "UPDATE users SET delete_flag = 1 WHERE username = '$uname' AND email = '$email'";
		//print $sql;
		$data = $this->query($sql);

		return $data;

	}

	//ディール一覧
	function selectContributeDealMy($uid,$endtag=0,$num=5){
		if(!$uid)
		return array();
		if($endtag==0){
			$not = 'not';
		}else{
			$not = '';
		}
		$sql = "SELECT * FROM startyfreeplayer sp JOIN startyfreecontribute s ON s.id=sp.sp_sc_id WHERE sp.sp_userid=$uid AND sc_public_flag=1 AND sc_delete_flag=0 AND $not EXISTS (SELECT 1 from startyfreeassess WHERE sas_sc_id = s.id) ORDER BY s.sc_addtime DESC LIMIT $num;";
// 		print $sql;
		$data = $this->query($sql);
		return $data;
	}

	//ディール一覧もっと見るボタンクリーク情報追加
	function selectContributeDealMyAdd($uid,$last_id,$endtag=0,$num=5){
		if(!$uid || !$last_id)
		return array();
		if($endtag==0){
			$not = 'not';
		}else{
			$not = '';
		}
		$sql = "SELECT * FROM startyfreeplayer sp JOIN startyfreecontribute s ON s.id=sp.sp_sc_id WHERE sp.sp_userid=$uid AND sc_public_flag=1 AND sc_delete_flag=0 AND $not EXISTS (SELECT 1 from startyfreeassess WHERE sas_sc_id = s.id) ORDER BY s.sc_addtime DESC LIMIT $num OFFSET $last_id";
		//print $sql;
		$data = $this->query($sql);
		return $data;
	}

	//ダイレクトメーセジ情報取得
	function selectdirect($uid,$suid){
		if(!$uid)
		return array();
		$sql = "SELECT sd.*,ui.username,ui.nickname,ui.userpic FROM startyfreedirect sd  JOIN users_info  ui ON ui.userid=sd.sd_userid JOIN users_info ui1 ON ui1.userid=sd.sd_receive_userid WHERE (sd.sd_userid = $uid AND sd.sd_receive_userid =$suid) OR (sd.sd_userid = $suid AND sd.sd_receive_userid =$uid) ORDER BY sd.sd_addtime ASC";
// 		print $sql;
		$data = $this->query($sql);
		return $data;
	}

	//プレイヤー評価の場合投稿と投稿者評価メッセージ取得
	function selectEntryAssess($eid){
		if(!$eid)
		return array();
		$sql = "SELECT s.sc_img,sa.sas_detailed FROM startyfreecontribute s JOIN startyfreeassess sa ON sa.sas_sc_id=s.id WHERE s.id=$eid AND s.sc_userid=sas_userid AND s.sc_delete_flag=0 ";
		// 		print $sql;
		$data = $this->query($sql);
		return $data;
	}

	//お知らせ情報取得
	function selectNotice($uid,$last_id='',$num=3){
		if($last_id==''){
			$sql = "SELECT * FROM startyfreenotice WHERE sn_delete_flag = 0 AND sn_read_flag = 0 AND (sn_userid=0 OR sn_userid=$uid) ORDER BY sn_addtime DESC LIMIT $num";
		}else{
			$sql = "SELECT * FROM startyfreenotice WHERE sn_delete_flag = 0 AND sn_read_flag = 0 AND (sn_userid=0 OR sn_userid=$uid) ORDER BY sn_addtime DESC LIMIT $num OFFSET $last_id";
		}

		// 		print $sql;
		$data = $this->query($sql);
		return $data;
	}

	//お知らせ読んだチェック
	function selectNoticeRead($uid,$nid){
		if(!$uid)
		return array();
		$sql = "SELECT count(*) FROM startyfreenoticeread WHERE snr_userid=$uid AND snr_noticeid = $nid";
		// 		print $sql;
		$data = $this->query($sql);
		return $data[0]["count"];
	}

	//お知らせ詳細ページ情報取得
	function selectNoticeID($id){
		if(!$id)
		return array();
		$sql = "SELECT * FROM startyfreenotice WHERE id=$id AND sn_delete_flag=0";
		// 		print $sql;
		$data = $this->query($sql);
		return $data;
	}

	//お知らせ詳細ページ情報取得
	function selectNoticeReaded($id,$uid){
		if(!$id)
		return array();
		$sql = "INSERT INTO startyfreenoticeread (snr_userid,snr_noticeid) VALUES($id,$uid);";
		// 		print $sql;
		$data = $this->query($sql);
		return $data;
	}

	//チェックメッセージ設定テーブル情報存在
	function selectUserMail($uid){
		if(!$uid)
		return array();
		$sql = "SELECT id FROM startyfreemail WHERE sma_userid=$uid";
		// 		print $sql;
		$data = $this->query($sql);
		return $data[0];
	}

	//メッセージ設定情報取得
	function selectMail($uid){
		if(!$uid)
		return array();
		$sql = "SELECT * FROM startyfreemail WHERE sma_userid=$uid";
		// 		print $sql;
		$data = $this->query($sql);
		return $data;
	}

	//ユーザメールアドレス取得
	function selectUserEmail($uid){
		if(!$uid)
		return array();
		$sql = "SELECT sma_help_mail FROM startyfreemail where sma_userid=$uid";
		// 		print $sql;
		$data = $this->query($sql);
		return $data[0]['sma_help_mail'];
	}

	//メッセージ送信設定確認
	function selectMailSet($uid,$type){
		if(!$uid || !$type)
		return array();
		if($type==1){
			$sql = "SELECT sma_message_flag FROM startyfreemail WHERE sma_userid=$uid";
		}elseif($type==2){
			$sql = "SELECT sma_direct_flag FROM startyfreemail WHERE sma_userid=$uid";
		}elseif($type==3){
			$sql = "SELECT sma_bookmark_flag FROM startyfreemail WHERE sma_userid=$uid";
		}elseif($type==4){
			$sql = "SELECT sma_deal_flag FROM startyfreemail WHERE sma_userid=$uid";
		}elseif($type==5){
			$sql = "SELECT sma_change_flag FROM startyfreemail WHERE sma_userid=$uid";
		}elseif($type==6){
			$sql = "SELECT sma_assess_flag FROM startyfreemail WHERE sma_userid=$uid";
		}
		// 		print $sql;
		$data = $this->query($sql);
		return $data[0];
	}

	//ディール申請ユーザID取得
	function seletEntryID($eid){
		if(!$eid)
		return array();
		$sql = "SELECT sc_userid FROM startyfreecontribute WHERE id=$eid";
		// 		print $sql;
		$data = $this->query($sql);
		return $data[0]['sc_userid'];
	}

	//評価送信時プレイヤーID取得
	function seletEntryPlayerID($eid){
		if(!$eid)
		return array();
		$sql = "SELECT sc_change_userid FROM startyfreecontribute WHERE id=$eid";
		// 		print $sql;
		$data = $this->query($sql);
		return $data[0]['sc_change_userid'];
	}

	//headメッセージ表示数取得
	function selectMessageTabNum($uid){
		if(!$uid)
		return array();
		$sql = "SELECT
	count(sm.*)
FROM
	(
		SELECT
			ID
		FROM
			startyfreecontribute
		WHERE
			sc_userid = $uid AND sc_delete_flag=0
	) eid
LEFT JOIN startyfreemessage sm ON sm.sm_sc_id = eid.ID
WHERE
	sm.sm_userid <> $uid AND sm.sm_read_flag = 0 ";
		// 		print $sql;
		$data = $this->query($sql);
		return $data[0]['count'];
	}

	//headダイレクトメッセージ表示数取得
	function selectDirectTabNum($uid){
		if(!$uid)
		return array();
		$sql = "SELECT
					count(*)cnt
				FROM
					(
						SELECT
							*
						FROM
							startyfreedirect
						WHERE
							sd_receive_userid = $uid
						AND sd_read_flag = 0
						GROUP BY
							sd_userid
					)num ";
		// 		print $sql;
		$data = $this->query($sql);
		return $data[0]['cnt'];
	}

	//headダイレクトメッセージユーザー情報取得
	function selectDirectTabUserdata($uid){
		if(!$uid)
		return array();
		$sql = "SELECT
						sd_userid,
						ui.username,
						ui.nickname,
						ui.userpic
					FROM
						(
							SELECT
								*
							FROM
								startyfreedirect
							WHERE
								sd_receive_userid = $uid
							AND sd_read_flag = 0
							GROUP BY
								sd_userid
						)num
					JOIN users_info ui ON ui.userid = sd_userid ";
		// 		print $sql;
		$data = $this->query($sql);
		return $data;
	}

	//head取引希望表示数取得
	function selectChangeTabNum($uid){
		if(!$uid)
		return array();
		$sql = "
		SELECT
	count(sp.*)
FROM
	(
		SELECT
			ID
		FROM
			startyfreecontribute
		WHERE
			sc_userid = $uid AND sc_delete_flag=0
	) eid
LEFT JOIN startyfreeplayer sp ON sp.sp_sc_id = eid.ID
WHERE
	sp.sp_userid <> $uid AND sp.sp_read_flag = 0
		";
		$data = $this->query($sql);
		return $data[0]['count'];
	}

	//headお知らせ表示数取得
	function selectNoticeTabNum($uid,$registtime='2012-10-02'){
		if(!$uid)
		return array();
		$sql = "SELECT
COUNT(1)
FROM
	startyfreenotice sn
WHERE
	sn.sn_addtime > '$registtime' -- AND sn_read_flag = 0
AND(
	(
		sn_userid = $uid
		AND sn_read_flag = 0
	)
	OR(
		(
			sn_userid = 0
			AND NOT EXISTS(
				SELECT
					1
				FROM
					startyfreenoticeread snr
				WHERE
					snr.snr_userid = $uid
				AND snr.snr_noticeid = sn. ID
			)
		)
	)
)";
// 		 		print $sql;
		$data = $this->query($sql);
		return $data[0]['count'];
	}

	//早期終了設定関数
	function eartyFinish($eid,$del=''){
		if(!$eid)
		return array();
		if($del!=''){
			$deltext =',sc_delete_flag=1';
		}
		$sql = "UPDATE startyfreecontribute SET sc_finish_advance_flag = 1$deltext WHERE id = $eid";
		//print $sql;
		$data = $this->query($sql);

		return $data;
	}

	//ユーザメールアドレス取得
	function selectUserRegistEmail($uname){
		if(!$uname)
		return array();
		$sql = "SELECT id,email FROM users WHERE username ='$uname' AND activated=1";
		//print $sql;
		$data = $this->query($sql);

		return $data;
	}

	//ブックマークアイテム存在チェック
	function selectBookmarkTrue($eid,$uid,$flag=0){
		if(!$eid)
		return array();
		$sql = "SELECT count(*)cnt FROM startyfreebookmark WHERE sb_sc_id=$eid AND sb_userid=$uid AND sb_flag = $flag";
// 		print $sql;
		$data = $this->query($sql);

		return $data[0]['cnt'];
	}

	//ブックマーク一覧
	function selectContributeBookmarkMy($uid,$num=6){
		if(!$uid)
		return array();
		$sql = "SELECT
				s.id,
				s.sc_userid,
				s.sc_title,
				s.sc_img,
				s.sc_addtime,
				s.sc_entry_type,
				ui.username,
			    ui.nickname
			FROM
				startyfreebookmark sb
			JOIN startyfreecontribute s ON s.id = sb.sb_sc_id
			JOIN users_info ui ON ui.userid = s.sc_userid
			WHERE
				sb.sb_userid = $uid
			AND sb.sb_flag = 0
			AND s.sc_delete_flag = 0
			ORDER BY
				s.sc_addtime DESC LIMIT $num";

		// 		print $sql;
		$data = $this->query($sql);
		return $data;
	}

	//ブックマーク一覧もっと見るボタンクリーク情報追加
	function selectContributeBookmarkMyAdd($uid,$last_id,$num=6){
		if(!$uid || !$last_id)
		return array();
		$sql = "SELECT
				s.id,
				s.sc_userid,
				s.sc_title,
				s.sc_img,
				s.sc_addtime,
				ui.username,
			  ui.nickname
			FROM
				startyfreebookmark sb
			JOIN startyfreecontribute s ON s.id = sb.sb_sc_id
			JOIN users_info ui ON ui.userid = s.sc_userid
			WHERE
				sb.sb_userid = $uid
			AND s.sc_delete_flag = 0 AND sb.sb_flag = 0
			ORDER BY
				s.sc_addtime DESC LIMIT $num OFFSET $last_id";

		//print $sql;
		$data = $this->query($sql);
		return $data;
	}

	//お気に入りユーザー一覧
	function selectDealUserMy($userid,$last_id='',$num=6){
		if(!$userid)
		return array();
		$offset = '';
		if($last_id!=''){
			$offset = "OFFSET $last_id";
		}
		$sql = "SELECT ui.userid,ui.username,ui.nickname,ui.userpic,sp.sp_userfriendid FROM startyfreeplayer sp JOIN users_info ui ON ui.userid = sp.sp_userfriendid WHERE sp.sp_userid = $userid  LIMIT $num $offset";
// 		print $sql;
		$data = $this->query($sql);

		return $data;
	}

	//アルバムエントリー情報取得
	function selectAlbumEntryMy($uid,$last_id='',$num=5){
		if(!$uid)
		return array();
		$offset = '';
		if($last_id!=''){
			$offset = "OFFSET $last_id";
		}
		$sql = "SELECT T.* FROM startyfreecontribute T WHERE sc_public_flag=1 AND sc_success_flag=1 AND sc_userid=$uid AND sc_delete_flag=0  ORDER BY T.sc_addtime DESC LIMIT $num $offset";
		//print $sql;
		$data = $this->query($sql);

		return $data;

	}

	//アルバムディール成立情報取得
	function selectAlbumDealMy($uid,$last_id='',$num=5){
		if(!$uid)
		return array();
		$offset = '';
		if($last_id!=''){
			$offset = "OFFSET $last_id";
		}
		$sql = "SELECT T.* FROM startyfreecontribute T WHERE sc_public_flag=1 AND sc_delete_flag=0 AND sc_success_flag=1 AND sc_change_userid=$uid  ORDER BY T.sc_addtime DESC LIMIT $num $offset";
		//print $sql;
		$data = $this->query($sql);

		return $data;

	}

	//メッセージ一覧
	function selectMessageList($uid,$last_id='',$num=5){
		if(!$uid)
		return array();
		$offset = '';
		if($last_id!=''){
			$offset = "OFFSET $last_id";
		}
		$sql = "SELECT
					*
				FROM
					(
						SELECT
							sm_sc_id
						FROM
							startyfreemessage
						WHERE
							sm_userid = $uid
						GROUP BY
							sm_sc_id
					)sm
				LEFT JOIN startyfreecontribute sc ON sc.id = sm.sm_sc_id WHERE  sc.sc_delete_flag=0 ORDER BY  sc.sc_addtime DESC LIMIT $num $offset";
// 		print $sql;
		$data = $this->query($sql);

		return $data;
	}

	//ダイレクトメッセージ一覧
	function selectDirectMessageList($uid,$last_id='',$num=5){
		if(!$uid)
		return array();
		$offset = '';
		if($last_id!=''){
			$offset = "OFFSET $last_id";
		}
		$sql = "SELECT
					sd_detailed,
					sd_addtime,
					sd_read_flag,
					ui.userid,
					ui.username,
					ui.nickname,
					ui.userpic
				FROM
					(
						SELECT
							*
						FROM
							startyfreedirect
						WHERE
							sd_receive_userid = $uid
						ORDER BY
							sd_addtime DESC
					) new
				LEFT JOIN users_info ui ON ui.userid = sd_userid
				GROUP BY
					sd_userid
				ORDER BY
					sd_addtime DESC ";


//		$sql ="SELECT ui.userid,ui.nickname,ui.username,ui.userpic FROM startyfreecontribute sc JOIN startyfreeplayer sp ON sp.sp_sc_id = sc.id JOIN users_info ui ON ui.userid=sp.sp_userid WHERE sc.id = $eid";
// 		 		print $sql;
		$data = $this->query($sql);

		return $data;
	}

	//最新ダイレクトメッセージ取得
	function selectBestnewMessage($uid,$suid){
		if(!$uid)
		return array();
		$sql = "SELECT sd_detailed,sd_type,sd_addtime FROM startyfreedirect WHERE (sd_userid=$uid AND sd_receive_userid=$suid) OR (sd_userid=$suid AND sd_receive_userid=$uid) ORDER BY sd_addtime DESC LIMIT 1";
		// 		print $sql;
		$data = $this->query($sql);

		return $data;
	}

	//headメッセージ一覧取得
	function selectMessageTabList($uid,$last_id='',$num=5){
		if(!$uid)
		return array();
		$offset = '';
		if($last_id!=''){
			$offset = "OFFSET $last_id";
		}
		$sql = "SELECT
		sm.sm_detailed,eid.*
	FROM
		(
			SELECT
				*
			FROM
				startyfreecontribute
			WHERE
				sc_userid = $uid AND sc_delete_flag=0
		) eid
	LEFT JOIN startyfreemessage sm ON sm.sm_sc_id = eid.ID
	WHERE
		sm.sm_userid <> $uid AND sm.sm_read_flag = 0 ORDER BY sm.sm_addtime DESC LIMIT $num $offset";
		 	//	print $sql;
		$data = $this->query($sql);
		return $data;
	}

	//メッセージ読むフラグ修正
	function messageReadflagUpdate($eid){
		if(!$eid)
		return array();
		$sql = "UPDATE startyfreemessage SET sm_read_flag = 1 WHERE sm_sc_id = $eid";
		// 		print $sql;
		$data = $this->query($sql);
		return $data;
	}

	//ヘッダダイレクトメッセージ一覧取得
	function selectDirectMessageTabList($uid,$last_id='',$num=5){
		if(!$uid)
		return array();
		$offset = '';
		if($last_id!='' && $last_id != null){
			$offset = "OFFSET $last_id";
		}
		$sql = "SELECT sd.sd_userid,sd.sd_addtime,sd.sd_detailed,ui.nickname,ui.username,ui.userpic FROM startyfreedirect sd JOIN users_info ui ON ui.userid=sd.sd_userid WHERE sd.sd_receive_userid=$uid AND sd.sd_read_flag=0 ORDER BY sd.sd_addtime DESC LIMIT $num $offset";
		// 		print $sql;
		$data = $this->query($sql);

		return $data;
	}

	//ダイレクトメッセージ読むフラグ修正
	function directMessageReadflagUpdate($suid,$uid){
		if(!$uid)
		return array();
		$sql = "UPDATE startyfreedirect SET sd_read_flag = 1 WHERE sd_receive_userid = $uid AND sd_userid=$suid";
		// 		print $sql;
		$data = $this->query($sql);
		return $data;
	}

	//ヘッダダイレクトメッセージ一覧取得
	function selectPlayerTabList($uid,$last_id='',$num=5){
		if(!$uid)
		return array();
		$offset = '';
		if($last_id!=''){
			$offset = "OFFSET $last_id";
		}
		$sql = "SELECT sp.*,sc.sc_title,ui.nickname,ui.username,ui.userpic FROM (SELECT id,sc_title FROM startyfreecontribute WHERE sc_userid=$uid AND sc_delete_flag=0 ) sc JOIN startyfreeplayer sp  ON sp.sp_sc_id = sc.id JOIN users_info ui ON ui.userid=sp.sp_userid WHERE sp.sp_read_flag = 0  ORDER BY sp.sp_addtime DESC LIMIT $num $offset";
// 				print $sql;
		$data = $this->query($sql);
		return $data;

	}

	//取引希望読むフラグ修正
	function playerReadflagUpdate($uid,$eid){
		if(!$uid)
		return array();
		$sql = "UPDATE startyfreeplayer SET sp_read_flag = 1 WHERE sp_userid = $uid AND sp_sc_id=$eid";
		// 		print $sql;
		$data = $this->query($sql);
		return $data;
	}

	//エントリータブ数取得
	function selectEntryTabNum($tab){
		if(!$tab)
		return array();
		$sql = "SELECT count(*) FROM startyfreecontribute WHERE sc_tab LIKE '%$tab%' AND sc_delete_flag=0 ";
		// 		print $sql;
		$data = $this->query($sql);
		return $data[0]['count'];
	}

	//お礼制御
	function selectEntryerAssess($eid){
		if(!$eid)
		return array();
		$sql = "SELECT count(*) FROM startyfreecontribute sc JOIN startyfreeassess sa ON sa.sas_sc_id=sc.id WHERE sc.id = $eid AND sa.sas_userid=sc.sc_userid";
		// 		print $sql;
		$data = $this->query($sql);
		return $data[0]['count'];
	}

	//お知らせ読んだフラグ
	function informationReadflagUpdate($id){
		if(!$id)
		return array();
		$sql = "UPDATE startyfreenotice SET sn_read_flag = 1 WHERE id = $id";
		// 		print $sql;
		$data = $this->query($sql);
		return $data;
	}

	//アイテムタイプ取得
	function selectEntryType($eid){
		if(!$eid)
		return array();
		$sql = "SELECT sc_genre from startyfreecontribute WHERE id=$eid";
		// 		print $sql;
		$data = $this->query($sql);
		return $data[0]['sc_genre'];
	}

	//アイテムブックマーク追加した判定
	function selectBookmarkTure($uid,$eid,$flag=0){
		if(!$uid)
		return array();
		$sql = "SELECT count(*) FROM startyfreebookmark WHERE sb_sc_id=$eid AND sb_userid=$uid AND sb_flag = $flag";
		// 		print $sql;
		$data = $this->query($sql);
		return $data[0]['count'];
	}

	//マイページタグ追加チェック
	function selectMytabCheck($uid,$tag){
		if(!$tag || !$uid)
		return array();
		$sql = "SELECT count(*) FROM startyfreetab WHERE st_userid=$uid AND st_name='$tag' AND st_delete_flag=0";
		// 		print $sql;
		$data = $this->query($sql);
		return $data[0]['count'];
	}

	//マイページタグ取得
	function selectMytab($uid){
		if(!$uid)
		return array();
		$sql = "SELECT * FROM startyfreetab WHERE st_userid=$uid AND st_delete_flag=0";
		// 		print $sql;
		$data = $this->query($sql);
		return $data;
	}

	//マイページタグ削除
	function deleteMytab($id){
		if(!$id)
		return array();
		$sql = "UPDATE startyfreetab SET st_delete_flag = 1 WHERE id = $id";
		// 		print $sql;
		$data = $this->query($sql);
		return $data;
	}

	//マイページタグ明と数字取得
	function selectMytabNum($uid){
		if(!$uid)
		return array();
		$sql = "SELECT st.st_name,COUNT(1) FROM startyfreetab st
				LEFT JOIN startyfreecontribute sc
				ON sc.sc_tab LIKE concat('%',st.st_name,'%')
				 WHERE st_userid=$uid AND st_delete_flag=0
				GROUP BY st.st_name";
		// 		print $sql;
		$data = $this->query($sql);
		return $data;
	}

	//COOKIE情報削除
	function deleteCookie($cookieid){
		if(!$cookieid)
		return array();
		$sql = "DELETE FROM ci_sessions WHERE session_id = '$cookieid'";
		// 		print $sql;
		$data = $this->query($sql);
		return $data;
	}


	//ユーザー投稿公開全体情報取得
	function selectEntryUse($userid="",$num=20,$last_id='',$usertype=""){
		if(!$userid)
		return array();
		if($last_id!=''){
			$offset = "OFFSET $last_id";
		}
		$sql = "SELECT
					sc.*, ui.userid,
					ui.userpic,
					ui.username,
					ui.nickname
				FROM
					startyfreecontribute sc
				JOIN users_info ui ON ui.userid = sc.sc_userid
				WHERE
				sc_delete_flag = 0
				AND sc_userid = $userid
				".($usertype!=""?" AND sc_secret_flag = 0 ":"")."
				ORDER BY sc.sc_addtime DESC
		 		LIMIT $num $offset";

// 		print $sql;
		$data = $this->query($sql);
		return $data;
	}

	//ユーザー投稿COUNT取得
	function selectEntryCount($userid,$usertype=""){
		if(!$userid)
		return array();


		$sql = "SELECT
						count(*) cnt
					FROM
						startyfreecontribute sc
					JOIN users_info ui ON ui.userid = sc.sc_userid
					WHERE
					sc_delete_flag = 0
					".($usertype!=""?" AND sc_secret_flag = 0 ":"")."
					AND sc_userid = $userid";
		// 		print $sql;
		$data = $this->query($sql);
		return $data[0]["cnt"];
	}

	//チェック可愛い
	function checkCharm($userid,$eid){
		if(!$userid)
		return array();


		$sql = "SELECT count(*)cnt FROM charmuser WHERE c_sc_id =$eid AND c_userid = $userid";
		// 		print $sql;
		$data = $this->query($sql);
		return $data[0]["cnt"];
	}

	//可愛いCOUNT
	function selectCharmCount($eid){
		if(!$eid)
		return array();


		$sql = "SELECT count(*)cnt FROM charmuser WHERE c_sc_id =$eid ";
		// 		print $sql;
		$data = $this->query($sql);
		return $data[0]["cnt"];
	}

	//可愛いした数
	function selectMyCharmCount($uid){
		if(!$uid)
		return array();


		$sql = "SELECT count(*)cnt FROM charmuser WHERE c_userid =$uid ";
		// 	print $sql;
		$data = $this->query($sql);
		return $data[0]["cnt"];
	}

	//可愛いされた数
	function selectPersonCharmCount($uid){
		if(!$uid)
		return array();
		$sql = "SELECT count(*)cnt FROM startyfreecontribute s JOIN charmuser c ON c.c_sc_id = s.id WHERE s.sc_userid = $uid ";
		// 		print $sql;
		$data = $this->query($sql);
		return $data[0]["cnt"];
	}


	//ユーザー可愛いリスト情報取得
	function selectEntrykawaii($userid="",$num=20,$last_id='',$usertype=""){
		if(!$userid)
		return array();
		if($last_id!=''){
			$offset = "OFFSET $last_id";
		}
		$sql = "SELECT
						sc.*, ui.userid,
						ui.userpic,
						ui.username,
						ui.nickname
					FROM
					charmuser c
					JOIN
						startyfreecontribute sc ON sc.id = c.c_sc_id
					JOIN users_info ui ON ui.userid = sc.sc_userid
					WHERE
						sc.sc_delete_flag = 0
					AND c.c_userid = $userid
					".($usertype!=""?" AND sc.sc_secret_flag = 0 ":"")."
					ORDER BY
						sc.sc_addtime DESC
						LIMIT $num $offset
					";

// 			print $sql;
		$data = $this->query($sql);
		return $data;
	}

	//ヘッダ可愛い数取得
	function selectReadCharm($uid){
		if(!$uid)
		return array();
		$sql = "SELECT count(*)cnt FROM startyfreecontribute   WHERE sc_userid = $uid AND sc_read_flag = 1 AND sc_delete_flag=0 ";
// 				print $sql;
		$data = $this->query($sql);
		return $data[0]["cnt"];
	}

	//可愛いボタンクリークフラグ設定
	function updateReadFlag($eid,$flag=0){
		if(!$eid)
		return array();
		$sql = "UPDATE startyfreecontribute SET sc_read_flag = $flag WHERE id = $eid  ";
		// 		print $sql;
		$data = $this->query($sql, 1);
		return $data;
	}


	//ヘッダ可愛い数取得
	function selectReadNum($uid){
		if(!$uid)
		return array();
		$sql = "SELECT id,sc_userid,sc_img FROM startyfreecontribute WHERE sc_userid = $uid AND sc_read_flag = 1 AND sc_delete_flag=0 ";
		// 		print $sql;
		$data = $this->query($sql);
		return $data;
	}

	//可愛い数クリークユーザー
	function selectCharmName($eid,$num=""){
		if(!$eid)
		return array();
		if($num!=""){
			$limit = " LIMIT 1 ";
		}
		$sql = "SELECT ui.username,ui.nickname,ui.userid,ui.userpic FROM charmuser c JOIN users_info ui ON ui.userid = c.c_userid WHERE c.c_sc_id =$eid ORDER BY c_addtime DESC ";
// 				print $sql;
		$data = $this->query($sql);
		return $data;
	}

	//可愛いボタンクリークフラグリセット
	function updateReadFlagReset($uid){
		if(!$uid)
		return array();
		$sql = "UPDATE startyfreecontribute SET sc_read_flag = 0 WHERE sc_userid = $uid  ";
		// 		print $sql;
		$data = $this->query($sql, 1);
		return $data;
	}

	//ヘッダ可愛い数取得
	function checkFriend($uid,$friendid){

		if(!$uid)
		return array();
		$sql = "SELECT id FROM startyfreeplayer WHERE sp_userid=$uid AND sp_userfriendid =$friendid";
// 				print $sql;
		$data = $this->query($sql);
		return $data[0]["id"];
	}

	//お気にユーザー削除
	function del_user_like($pid){
		if(!$pid)
		return array();
		$sql = "DELETE FROM startyfreeplayer WHERE id =$pid ";
		// 		print $sql;
		$data = $this->query($sql,1);
		return $data;
	}


	//ユーザー名検索
	function selectUsername($uid,$keyword){
		if(!$uid)
		return array();
		$sql = "SELECT
				ui.userid,
				ui.username,
				ui.nickname,
				ui.userpic
			FROM
				users u
			JOIN users_info ui ON ui.userid = u.id
			WHERE
				u.id <> $uid
			AND u.delete_flag = 0
			AND(
				ui.nickname LIKE '%$keyword%'
				OR ui.username LIKE '%$keyword%'
			)
			ORDER BY
				u.username ASC";
		// 		print $sql;
		$data = $this->query($sql);
		return $data;
	}

	//可愛いボタンクリークフラグ設定
	function updateMessageReadFlag($uid,$ruid){
		if(!$uid && !$ruid)
		return array();
		$sql = "UPDATE startyfreedirect SET sd_read_flag = 1 WHERE sd_userid = $uid AND sd_receive_userid=$ruid ";
		// 		print $sql;
		$data = $this->query($sql, 1);
		return $data;
	}

	//ユーザー名取得
	function getUsername($uid){
		if(!$uid)
		return array();
		$sql = "SELECT ui.username,ui.nickname FROM users u JOIN users_info ui ON ui.userid=u.id WHERE u.id = $uid AND u.delete_flag=0 ";
		// 		print $sql;
		$data = $this->query($sql);
		return $data[0];
	}

	function deleteBookmark($eid,$uid){
		if(!$uid && !$eid)
		return array();
		$sql = "DELETE FROM startyfreebookmark WHERE sb_userid =$uid AND sb_sc_id = $eid ";
		// 		print $sql;
		$data = $this->query($sql,1);
		return $data;
	}

	//店舗チェク
	function check_shop_phone($phone){
		if(!$phone)
		return array();
		$sql = "SELECT count(*)cnt FROM shoptbl WHERE shop_phone = '$phone' ";
		// 		print $sql;
		$data = $this->query($sql);
		return $data[0]['cnt'];
	}


	//店舗登録
	function insert_shop_data($_DATA){
		var_dump($_DATA);
		if(count($_DATA)==0)
		return array();

		$sql_fields = array();
		$sql_values = array();
		foreach($_DATA["shoptbl"] as $fieldName => $value){
			array_push($sql_fields, $fieldName);
			array_push($sql_values, "'$value'");
		}
		$sql_fields_str = join(", ", $sql_fields);
		$sql_values_str = join(", ", $sql_values);
		$sql = "
			INSERT INTO shoptbl (
				$sql_fields_str
			)
			VALUES (
				$sql_values_str
			)
		";
		// 		print $sql;
		$data = $this->query($sql,1);
		return $data;
	}

	//PC店舗全体情報取得
	function selectShopAll($category='',$city_data_str="",$num=20,$last_id=""){
		if($last_id!=''){
			$offset = "OFFSET $last_id";
		}

		$sqlnew='';
		$sqllike='';
		$sqlcategory='';
		$sqllikes = "";
		if($category=="9"){
			$sqlnew=' ORDER BY sp.shop_addtime DESC ';
		}elseif($category=="10"){
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
		$keywordsql $sqlcategory  $sqlnew $sqllike LIMIT $num $offset ";
// 		print $sql;
		$data = $this->query($sql);
		return $data;
	}

	//PC有料店舗全体情報取得
	function selectYuuryoShopAll($category='',$city_data_str="",$num=20,$last_id=""){
		if($last_id!=''){
			$offset = "OFFSET $last_id";
		}

		$sqlnew='';
		$sqllike='';
		$sqlcategory='';
		$sqllikes = "";
		if($category=="9"){
			$sqlnew=' ORDER BY sp.shop_addtime DESC ';
		}elseif($category=="10"){
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
									sp.shop_delete_flag = 0 AND sp.shop_pay = 1
		$keywordsql $sqlcategory  $sqlnew $sqllike LIMIT $num $offset ";
// 		print $sql;
		$data = $this->query($sql);
		return $data;
	}


	//投稿店舗数取得
	function selectShopContributeCount($spid){
	if(!$spid)
	return array();
	$sql = "SELECT count(*)cnt FROM startyfreecontribute WHERE sc_shop_id = $spid AND sc_delete_flag=0 AND sc_secret_flag = 0";
			// 		print $sql;
	$data = $this->query($sql);
	return $data[0]["cnt"];
		}


	//投稿店舗数情報取得
	function selectShopContributeAll($spid="",$num=20,$last_id=0){
		if(!$spid)
		return array();

		if($last_id!=''){
			$offset = "OFFSET $last_id";
		}
		$sql = "SELECT
								sc.*, ui.userid,
								ui.userpic,
								ui.username,
								ui.nickname
							FROM
								startyfreecontribute sc
							JOIN users_info ui ON ui.userid = sc.sc_userid
							WHERE
								sc.sc_delete_flag = 0
							AND sc.sc_shop_id = $spid
							AND sc.sc_secret_flag = 0
							ORDER BY
								sc.sc_addtime DESC
								LIMIT $num $offset
		";

		// 			print $sql;
		$data = $this->query($sql);
		return $data;
	}



	//店舗詳細情報取得
	function selectShop($id){
		if(!$id)
		return array();
		$sql = "SELECT * FROM shoptbl  WHERE id=$id AND shop_delete_flag=0 ";
			//print $sql;
			$data = $this->query($sql);
		return $data;
	}

	//投稿市区町村作成
	function selectAreaCity($pref){
		if(!$pref)
		return array();
		$sql = "SELECT city FROM areaitem WHERE pref='$pref'";
// 		print $sql;
		$data = $this->query($sql);
		return $data;
	}

	//店舗情報取得
		function selectShopData($key){
		if(!$key)
		return array();
		$sql = "SELECT shop_name,shop_img,id,shop_phone FROM shoptbl WHERE shop_name LIKE '%$key%' ";
		//print $sql;
		$data = $this->query($sql);
		return $data;
	}

	//店舗電話チェク
	function shopPhoneCheck($no){
		if(!$no)
		return array();
		$sql = "SELECT count(*)cnt FROM shoptbl WHERE shop_phone = '$no' ";
		//print $sql;
		$data = $this->query($sql);
		return $data[0]["cnt"];
	}

	//店舗一覧市区町村作成
	function selectShopAreaCity($prefarea,$pref){
		if(!$pref && !$prefarea)
		return array();
		$sql = "SELECT city_area FROM areaitem WHERE pref_area='$prefarea' AND pref='$pref' ";
		//print $sql;
		$data = $this->query($sql);
		return $data;
	}

	//都道府県作成
	function selectAreaPref($pref_area){
		if(!$pref_area)
		return array();
			$sql = "SELECT pref FROM areaitem WHERE pref_area='$pref_area' GROUP BY pref ";
		//print $sql;
		$data = $this->query($sql);
		return $data;
	}

	//市区町村エリアから市区町村情報取得
	function selectShopCityForPref($sess_city_area){
		if(!$sess_city_area)
		return array();
		$sess_city_area = str_replace(",","','",$sess_city_area);
		$sql = "SELECT city FROM areaitem WHERE city_area IN ('$sess_city_area')";
		// 				print $sql;
		$data = $this->query($sql);
		return $data;
	}

	//店舗名取得
	function selectShopName($id){
	if(!$id)
		return array();
		$sql = "SELECT shop_name,shop_homepage FROM shoptbl WHERE id=$id ";
	// 				print $sql;
		$data = $this->query($sql);
		return $data;
	}

	//ブックマーク一覧
	function selectContributeBookmarkMy1($uid,$num=6,$page=0){
		if(!$uid)
		return array();
		// 		$sql = "SELECT
		// 				s.id,
		// 				s.sc_userid,
		// 				s.sc_title,
		// 				s.sc_img,
		// 				s.sc_addtime,
		// 				ui.username,
		// 			    ui.nickname
		// 			FROM
		// 				startyfreebookmark sb
		// 			JOIN startyfreecontribute s ON s.id = sb.sb_sc_id
		// 			JOIN users_info ui ON ui.userid = s.sc_userid
		// 			WHERE
		// 				sb.sb_userid = $uid
		// 			AND s.sc_delete_flag = 0
		// 			ORDER BY
		// 				s.sc_addtime DESC LIMIT $num OFFSET ".($page * $num)." ";

		$sql = "SELECT
							*
						FROM
							(
								SELECT
									s.id contribute_shop_id,
									s.sc_userid userid,
									s.sc_title title_shopname,
									s.sc_img contribute_shop_img,
									ui.username username_phone,
									ui.nickname username_holiday,
									s.sc_where where_access,
									s.sc_who who_address,
									s.sc_whotext whotext_city,
									s.sc_addtime addtime,
									s.sc_satisfied opentime,
									sb.sb_flag flag,
									sb.id id
								FROM
									startyfreebookmark sb
								JOIN startyfreecontribute s ON s.id = sb.sb_sc_id
								JOIN users_info ui ON ui.userid = s.sc_userid
								WHERE
									sb.sb_userid = $uid
								AND s.sc_delete_flag = 0
								UNION
									SELECT
										sp.id contribute_shop_id,
										sb.sb_userid userid,
										sp.shop_name title_shopname,
										sp.shop_img contribute_shop_img,
										sp.shop_phone username_phone,
										sp.shop_holiday username_holiday,
										sp.shop_access where_access,
										sp.shop_address who_address,
										sp.shop_city whotext_city,
										sp.shop_addtime,
										sp.shop_opentime opentime,
										sb.sb_flag flag,
										sb.id id
									FROM
										startyfreebookmark sb
									JOIN shoptbl sp ON sp.id = sb.sb_sc_id
									WHERE
										sb.sb_flag = 1
									AND sb.sb_userid = $uid
							)a ORDER BY a.id DESC LIMIT $num OFFSET ".($page * $num)." ";
		// 				print $sql;
		$data = $this->query($sql);
		return $data;
	}

	//おすすめランキング
	function selectRankdata(){
		// 		if(!$eid)
		// 		return array();
		$sql = "SELECT
						sc.sc_userid,
						sc.sc_addtime,
						sc.id,
						sc.sc_title,
						sc.sc_img,
						sc.sc_entry_type,
						ui.username,
						ui.nickname
					FROM
						startyfreecontribute sc
					JOIN users_info ui ON ui.userid = sc.sc_userid
					LEFT JOIN(
						SELECT
							c_sc_id cscid,
							count(c_sc_id)cnum
						FROM
							charmuser
						GROUP BY
							c_sc_id
					)c ON c.cscid = sc.id
					WHERE
						sc.sc_delete_flag = 0
					AND sc.sc_secret_flag = 0
					ORDER BY
						IFNULL(c.cnum, 0)DESC
					LIMIT 5";
		// 				print $sql;
		$data = $this->query($sql);
		return $data;
	}

	//エリアと都道府県取得
	function selectAreaByCityArea($cityarea){
		if(!$cityarea)
		return array();
		$sql = "SELECT pref_area,pref FROM areaitem WHERE city_area='$cityarea' ";
		// 				print $sql;
		$data = $this->query($sql);
		return $data[0];
	}

	//カワイイキャペンーランキング
	function selectCampRankdata(){
		// 		if(!$eid)
		// 		return array();
		$sql = "SELECT
					u.id,
					ui.username,
					ui.nickname,
					ui.userpic,
					c.charmnum
				FROM
					users u
				JOIN users_info ui ON ui.userid = u.id
				JOIN(
					SELECT
						count(cm.c_sc_id)charmnum,
						sc.sc_userid
					FROM
						charmuser cm
					JOIN startyfreecontribute sc ON sc.id = cm.c_sc_id
					GROUP BY
						sc.sc_userid
				)c ON c.sc_userid = u.id
				WHERE
					u.delete_flag = 0 ORDER BY charmnum DESC LIMIT 3;
			";
		// 				print $sql;
		$data = $this->query($sql);
		return $data;
	}

	//ユーザー名取得
	function selectUsernameByID($uid){
		if(!$uid)
		return array();
		$sql = "SELECT username,nickname FROM users_info WHERE userid = $uid ";
		// 				print $sql;
		$data = $this->query($sql);
		return $data[0];
	}

	//コメント情報取得
	function selectComment($eid="",$num=20){
		if(!$eid)
		return array();
		$sql = "SELECT
					i.id,
					i.userid,
					i.comment,
					i.addtime,
					ui.username,
					ui.nickname,
					ui.userpic
				FROM
					image_comment i
				JOIN users_info ui ON ui.userid = i.userid
				WHERE
					i.delete_flag = 0
				AND i.entryid = $eid
				ORDER BY
					i.addtime DESC LIMIT $num ";

		// 				print $sql;
		$data = $this->query($sql);
		return $data;
	}

	//コメント情報取得
	function selectCommentAdd($eid="",$num=20,$last_id){
		if(!$eid)
		return array();
		$sql = "SELECT
					i.id,
					i.userid,
					i.comment,
					i.addtime,
					ui.username,
					ui.nickname,
					ui.userpic,
					s.sc_userid
				FROM
					image_comment i
				JOIN users_info ui ON ui.userid = i.userid
				LEFT JOIN startyfreecontribute s ON s.id = i.entryid
				WHERE
					i.delete_flag = 0
				AND i.entryid = $eid
				ORDER BY
					i.addtime DESC LIMIT $num OFFSET $last_id ";

// 		print $sql;
		$data = $this->query($sql);
		return $data;
	}

	//コメント数取得
	function selectCommentNum($eid=""){
		if(!$eid)
		return array();
		$sql = "SELECT
						count(*)cnt
					FROM
						image_comment
					WHERE
						delete_flag = 0
					AND entryid = $eid";

		// 				print $sql;
		$data = $this->query($sql);
		return $data[0]["cnt"];
	}

	//店舗コメント情報取得
	function selectShopComment($spid="",$num=20){
		if(!$spid)
		return array();
		$sql = "SELECT
						i.id,
						i.userid,
						i.comment,
						i.addtime,
						ui.username,
						ui.nickname,
						ui.userpic
					FROM
						shop_comment i
					JOIN users_info ui ON ui.userid = i.userid
					WHERE
						i.delete_flag = 0
					AND i.shopid = $spid
					ORDER BY
						i.addtime DESC LIMIT $num ";

		// 				print $sql;
		$data = $this->query($sql);
		return $data;
	}

	//店舗コメント情報取得
	function selectShopCommentAdd($spid="",$num=20,$last_id){
		if(!$spid)
		return array();
		$sql = "SELECT
						i.id,
						i.userid,
						i.comment,
						i.addtime,
						ui.username,
						ui.nickname,
						ui.userpic
					FROM
						shop_comment i
					JOIN users_info ui ON ui.userid = i.userid
					WHERE
						i.delete_flag = 0
					AND i.shopid = $spid
					ORDER BY
						i.addtime DESC LIMIT $num OFFSET $last_id ";

		// 		print $sql;
		$data = $this->query($sql);
		return $data;
	}

	//店舗コメント数取得
	function selectShopCommentNum($spid=""){
		if(!$spid)
		return array();
		$sql = "SELECT
							count(*)cnt
						FROM
							shop_comment
						WHERE
							delete_flag = 0
						AND shopid = $spid";

		// 				print $sql;
		$data = $this->query($sql);
		return $data[0]["cnt"];
	}

	///	///	///	///	///	///	///	///	///
	// スマホアプリ、通知機能部分
	// By Nelson
	///	///	///	///	///	///	///	///	///

	function checkDeviceId($device_id){
		$sql = "SELECT
							count(*)cnt,d.id
						FROM
							device_token_info d
						WHERE
							d.device_id = '$device_id'";

		// 				print $sql;
		$data = $this->query($sql);
		return $data[0];
	}

	function selectDeviceIdFromUserId($user_id){
		if(!$user_id)
		return array();

		$sql = "SELECT
						d.*
					FROM
						device_token_info d
					WHERE
						d.user_id = $user_id
					AND d.valid = 1
					AND d.device_id != '(null)'
					AND d.device_id IS NOT NULL
					ORDER BY
						d.addtime DESC";

		// 		print $sql;
		$data = $this->query($sql);
		return $data;
	}



	function selectAllDeviceId(){
		$sql = "SELECT
						d.*
					FROM
						device_token_info d
					WHERE
						d.valid = 1
					AND d.device_id != '(null)'
					AND d.device_id IS NOT NULL
					AND d.type = $type
					ORDER BY
						d.addtime DESC";

		// 		print $sql;
		$data = $this->query($sql);
		return $data;
	}

	function selectBadgeCountByDeviceId($device_token){
		$sql = "SELECT
						d.badge_count
					FROM
						device_token_info d
					WHERE
						d.valid = 1
						AND d.device_id = '$device_token'
					ORDER BY
						d.addtime DESC";

		// 		print $sql;
		$data = $this->query($sql);
		return $data['badge_count'];
	}

	//アイテム詳細情報取得
	function selectDataByEntryId($id){
		if(!$id)
		return array();
		$sql = "SELECT sc.sc_userid FROM startyfreecontribute sc WHERE sc.id=$id";
		//print $sql;
		$data = $this->query($sql);
		return $data[0];
	}
	
	function selectNamePic($userid){
        if(!$userid)
            return array();

        $sql = "SELECT username, nickname, userpic FROM users_info WHERE userid = $userid";

        //print $sql;
        $data = $this->query($sql);
        return $data[0];
    }
    
    function selectTagExists($tagName){
    	if(!$tagName)
            return array();

        $sql = "SELECT id FROM startyfreetag WHERE name = '$tagName'";

        //print $sql;
        $data = $this->query($sql);
        return $data[0]['id'];
    	
    }
    
    function selectTagDetailById($tid){
    	if(!$tid)
            return array();

        $sql = "SELECT * FROM startyfreetag WHERE id = $tid";

        //print $sql;
        $data = $this->query($sql);
        return $data[0];
    	
    }
    
    //トップページ投稿公開全体情報取得
	function selectContributeTag($tag, $category='',$num=20,$secret="" ){
		$sqlnew='';
		$sqllike='';
		$sqlcategory='';
		$sqllikes = "";
		if($category=="9"){
			$sqlnew=' ORDER BY sc.sc_addtime DESC ';
		}elseif($category=="10"){
			$sqllikes = "LEFT JOIN (SELECT c_sc_id cscid ,count(c_sc_id) cnum FROM charmuser GROUP BY c_sc_id) c ON c.cscid = sc.id";
			$sqllike=' ORDER BY IFNULL(c.cnum,0) DESC ';
		}elseif($category!="" && $category!="9" && $category!="10"){
			$sqlcategory=" AND sc.sc_genre=$category ";
			$sqlnew=' ORDER BY sc.sc_addtime DESC ';
		}else{
			$sqlnew=' ORDER BY sc.sc_addtime DESC ';
		}

		$sqlsecret = "";
		if($secret!=""){
			$sqlsecret = " AND sc.sc_secret_flag = 0";
		}

		$sql = "SELECT
							sc.*, ui.userid,
							ui.userpic,
							ui.username,
							ui.nickname
						FROM
							startyfreecontribute sc
						JOIN users_info ui ON ui.userid = sc.sc_userid
						$sqllikes
						WHERE
						sc.sc_delete_flag = 0 AND sc.sc_secret_flag = 0 AND sc.sc_tag LIKE '%$tag%'
						$sqlsecret $sqlcategory  $sqlnew $sqllike LIMIT $num";
//  		print $sql;
		$data = $this->query($sql);
		return $data;
	}
	
	///	///	///	///	///	///	///	///	///
	//トップページ投稿公開全体情報取得
	function selectContributeAllWithKeyword($category='',$sort_flag='',$num=20,$secret="",$keyword=""){
		$sqlnew='';
		$sqllike='';
		$sqlcategory='';
		$sqllikes = "";
		$keywordsql = "";
		
		if(!$sort_flag){
			$sqlnew=' ORDER BY sc.sc_addtime DESC ';
		}
		else if($sort_flag == "10"){
			$sqllikes = "LEFT JOIN (SELECT c_sc_id cscid ,count(c_sc_id) cnum FROM charmuser GROUP BY c_sc_id) c ON c.cscid = sc.id";
			$sqllike =' ORDER BY IFNULL(c.cnum,0) DESC ';
		}
			
		if($keyword!=""){
			$keywordsql = " AND (sc.sc_title LIKE '%$keyword%' OR sc.sc_where LIKE '%$keyword%' OR ui.nickname LIKE '%$keyword%' OR username LIKE '%$keyword%') ";
		}
		
		if(!$category){
			$sqlcategory="";
		}
		else if($category){
			$sqlcategory=" AND sc.sc_genre=$category ";
		}

		$sqlsecret = "";
		if($secret!=""){
			$sqlsecret = " AND sc.sc_secret_flag = 0";
		}
		
		
		

		$sql = "SELECT
							sc.*, ui.userid,
							ui.userpic,
							ui.username,
							ui.nickname
						FROM
							startyfreecontribute sc
						JOIN users_info ui ON ui.userid = sc.sc_userid
						$sqllikes
						WHERE
						sc.sc_delete_flag = 0 AND sc.sc_secret_flag = 0
						$keywordsql $sqlsecret $sqlcategory  $sqlnew $sqllike LIMIT $num";
//  		print $sql;
		$data = $this->query($sql);
		return $data;
	}
}
?>