<?php
class banner_admin extends ipfDB{
	//掲載広告数
	function openBannerNum(){
		$sql = "SELECT
					count(*)cnt
				FROM
					banner_advertising
				WHERE
					delete_flag = 0
				AND ba_start <= CURDATE()
				AND ba_end >= CURDATE()";
		//     	print $sql;
		$data = $this->query($sql);
		return $data[0]["cnt"];
	}

	//バナー数取得
	function selectBannerCnt($keyword=""){
		if($keyword!=""){
			$keywordsql = " AND bm.ba_title LIKE '%$keyword%' ";
		}
		$sql = "SELECT
					count(*)cnt
				FROM
					banner_advertising bm
				LEFT JOIN(
					SELECT
						count(bc.banner_id)click_num,
						bc.banner_id
					FROM
						banner_click bc
					GROUP BY
						bc.banner_id
				)a1 ON a1.banner_id = bm.id
				WHERE
					bm.delete_flag = 0 $keywordsql
					";
// 		print $sql;
		$data = $this->query($sql);
		return $data[0]["cnt"];
	}

	//バナー情報取得
	function selectBannerAll($keyword="",$num=20,$page=0){
		if($keyword!=""){
			$keywordsql = " AND bm.ba_title LIKE '%$keyword%' ";
		}
		$sql = "SELECT
					bm.id,
					bm.ba_title,
					bm.ba_image,
					bm.ba_url,
					bm.ba_start,
					bm.ba_end,
					IFNULL(a1.click_num,0)click_num
				FROM
					banner_advertising bm
				LEFT JOIN(
					SELECT
						count(bc.banner_id)click_num,
						bc.banner_id
					FROM
						banner_click bc
					GROUP BY
						bc.banner_id
				)a1 ON a1.banner_id = bm.id
				WHERE
					bm.delete_flag = 0 $keywordsql
					ORDER BY bm.id DESC
				LIMIT $num OFFSET ".($page * $num)."";
// 		print $sql;
		$data = $this->query($sql);
		return $data;
	}

	//バナー削除
	function deleteBanner($id){
		if(!$id)
		return array();
		$banneridarr = explode(",", $id);
		$sqlstr = "(";
		foreach ($banneridarr as $key=>$val){
			if($key>0)$or = " OR ";
			$sqlstr .=" $or id= $val ";
		}
		$sqlstr .= ") ";
		$sql = "UPDATE banner_advertising SET delete_flag = 1 WHERE $sqlstr ";
		// 				print $sql;
		$data = $this->query($sql,1);
		return $data;
	}

	//バナー詳細情報取得
	function selectBannerByID($id,$start_time="",$end_time=""){
		if(!$id)
		return array();

		if($start_time!="" && $end_time!=""){
			$timesql = " WHERE CONVERT(bc.addtime,DATE) >= '$start_time' AND CONVERT(bc.addtime,DATE)<='$end_time' ";
		}
		$sql = "SELECT
						bm.id,
						bm.ba_title,
						bm.ba_image,
						bm.ba_url,
						bm.ba_start,
						bm.ba_end,
						IFNULL(a1.click_num,0)click_num
					FROM
						banner_advertising bm
					LEFT JOIN(
						SELECT
							count(bc.banner_id)click_num,
							bc.banner_id
						FROM
							banner_click bc
							$timesql
						GROUP BY
							bc.banner_id
					)a1 ON a1.banner_id = bm.id
					WHERE
						bm.delete_flag = 0 AND bm.id=$id ";
		// 		print $sql;
		$data = $this->query($sql);
		return $data[0];
	}

}
?>