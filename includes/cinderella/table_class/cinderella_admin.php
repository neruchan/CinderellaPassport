<?php
class cinderella_admin extends ipfDB{
    
    //全記事数取得
	function selectShopAllNum(){	
		$sql = "SELECT
				count(*)cnt
					FROM
				shop s
				WHERE
					s.delete_flag = 0
					";
		// 				print $sql;
		$data = $this->query($sql);
		return $data[0]["cnt"];
	}

	//全記事数取得
	function selectCouponAllNum(){	
		$sql = "SELECT
				count(*)cnt
					FROM
				coupon c
                JOIN coupon_x_shop cs ON cs.coupon_id = c.id
				JOIN shop s ON s.id = cs.shop_id
				WHERE
					c.delete_flag = 0
                    AND s.delete_flag = 0
					";
		// 				print $sql;
		$data = $this->query($sql);
		return $data[0]["cnt"];
	}
    
    //記事登録数取得
	function selectShopCnt($pref="",$addtime=0){
		
		$addtimesql = "";
		if($addtime){
			$addtimesql = " ORDER BY s.addtime DESC ";
		}elseif($addtime==2){
			$addtimesql = " ORDER BY s.addtime ASC ";
		}else{
			$addtimesql = " ORDER BY s.id ASC ";
		}

		$prefsql = "";
		if($pref){
			$prefsql = " AND pref=$pref";
		}

		$sql = "SELECT
					count(*)cnt
				FROM
					shop s
				WHERE
					s.delete_flag = 0
					$prefsql
					$addtimesql ";
// 		print $sql;
		$data = $this->query($sql);
		return $data[0]["cnt"];
	}
    
	//記事登録数取得
	function selectCouponCnt($category=0,$addtime=0){
		
		$categorysql = "";
		if($category!="" && $category!="0"){
			$categorysql = " AND c.category IN($category) ";
		}

		$addtimesql = "";
		if($addtime==1){
			$addtimesql = " ORDER BY c.addtime DESC ";
		}elseif($addtime==2){
			$addtimesql = " ORDER BY c.addtime ASC ";
		}else{
			$addtimesql = " ORDER BY c.id ASC ";
		}

		$sql = "SELECT
					count(*)cnt
				FROM
					coupon c
                JOIN coupon_x_shop cs ON cs.coupon_id = c.id
				JOIN shop s ON s.id = cs.shop_id
				WHERE
					c.delete_flag = 0
                    AND s.delete_flag = 0
					$categorysql
					$addtimesql ";
// 		print $sql;
		$data = $this->query($sql);
		return $data[0]["cnt"];
	}
	
	//全体記事情報取得
	function selectCouponAll($category=0,$addtime=0,$num=20,$page=0,$pv=0){
		$categorysql = "";
		if($category!="" && $category!="0"){
			$categorysql = " AND c.category IN($category) ";
		}
		
		$sortsql = "ORDER BY id DESC";
		if($addtime==1){
			$sortsql = " ORDER BY c.addtime DESC ";
		}elseif($addtime==2){
			$sortsql = " ORDER BY c.addtime ASC ";
		}
		

		if($pv==1){
			$sortsql = " ORDER BY c.access_num DESC ";
		}elseif($pv==2){
			$sortsql = " ORDER BY c.access_num ASC ";
		}
		
		$sql = "SELECT
					c.id,
					c.title,
					c.category,
					c.exp_date_until,
					c.addtime,
					c.access_num,
					s.id as shop_id,
					s.name as shop_name
				FROM
					coupon c
				JOIN coupon_x_shop cs ON cs.coupon_id = c.id
				JOIN shop s ON s.id = cs.shop_id
				WHERE
					c.delete_flag = 0
                    AND s.delete_flag = 0
					$categorysql
					$sortsql
				LIMIT $num OFFSET ".($page * $num)."";
 						//print $sql;
		$data = $this->query($sql);
		return $data;
	}
    
    //全体記事情報取得
	function selectShopAll($pref="",$addtime=0,$num=20,$page=0){
		$addtimesql = "";
		if($addtime){
			$addtimesql = " ORDER BY s.addtime DESC ";
		}elseif($addtime==2){
			$addtimesql = " ORDER BY s.addtime ASC ";
		}else{
			$addtimesql = " ORDER BY s.id ASC ";
		}

		$prefsql = "";
		if($pref){
			$prefsql = " AND pref=$pref";
		}
		
		$sql = "SELECT
					s.*
				FROM
					shop s
				
				WHERE
					s.delete_flag = 0
					$prefsql
					$addtimesql
				LIMIT $num OFFSET ".($page * $num)."";
 						//print $sql;
		$data = $this->query($sql);
		return $data;
	}
	
	//記事詳細情報取得
	function selectCouponByID($id){
		if(!$id)
			return array();
		
		$sql = "SELECT 
					c.*,
					s.id as shop_id,
					s.name as shop_name,
					s.address,
					s.email,
					s.phone,
					s.station,
					s.pref,
					s.zip,
					s.pic_url1 as shop_pic1,
					s.pic_url2 as shop_pic2,
					s.pic_url3 as shop_pic3
				FROM coupon c 
					JOIN coupon_x_shop cs ON cs.coupon_id = c.id
					JOIN shop s ON s.id = cs.shop_id
				WHERE c.id=$id 
				AND c.delete_flag=0 
                AND s.delete_flag = 0";
		//print $sql;
		$data = $this->query($sql);
		return $data[0];
	}
    
    //記事詳細情報取得
	function selectShopByID($id){
		if(!$id)
			return array();
		
		$sql = "SELECT
					s.*
				FROM shop s
				WHERE s.id=$id 
				AND s.delete_flag=0 ";
		//print $sql;
		$data = $this->query($sql);
		return $data[0];
	}
	
	//記事削除
	function deleteCoupon($id){
		if(!$id)
		return array();
		$idarr = explode(",", $id);
		$sqlstr = "(";
		foreach ($idarr as $key=>$val){
			if($key>0)$or = " OR ";
			$sqlstr .=" $or id= $val ";
		}
		$sqlstr .= ") ";
		$sql = "UPDATE coupon SET delete_flag = 1 WHERE $sqlstr ";
// 				print $sql;
		$data = $this->query($sql,1);
		return $data;
	}
    
    	//記事削除
	function deleteShop($id){
		if(!$id)
		return array();
		$idarr = explode(",", $id);
		$sqlstr = "(";
		foreach ($idarr as $key=>$val){
			if($key>0)$or = " OR ";
			$sqlstr .=" $or id= $val ";
		}
		$sqlstr .= ") ";
		$sql = "UPDATE shop SET delete_flag = 1 WHERE $sqlstr ";
// 				print $sql;
		$data = $this->query($sql,1);
		return $data;
	}
    
    
    //全体記事情報取得
	function searchShop($freeKeyword="", $num=20,$page=0){
        $keywordsql = "";
        if($freeKeyword){
            $keywordsql = "AND (s.name like '%$freeKeyword%' OR s.id = '$freeKeyword' OR s.address like '%$freeKeyword%')";
        }
        
		
		$sql = "SELECT
					s.*
				FROM
					shop s
				WHERE
					s.delete_flag = 0
					$keywordsql
				LIMIT $num OFFSET ".($page * $num)."";
 						//print $sql;
		$data = $this->query($sql);
		return $data;
	}
    
    //タグ
    function selectTagExists($tagName){
    	if(!$tagName)
            return array();

        $sql = "SELECT id FROM coupon_tag WHERE name = '$tagName'";

        //print $sql;
        $data = $this->query($sql);
        return $data[0]['id'];
    	
    }
    
    function selectTagsByCouponId($cid){

        $sql = "SELECT 
                c.id as coupon_id,
                c.title as coupon_title,
                c.pic_url as coupon_image,
                t.id as tag_id,
                t.name as tag_name
        		FROM coupon c 
        		LEFT JOIN coupon_x_tag b ON c.id = b.coupon_id 
        		LEFT JOIN coupon_tag t ON b.tag_id = t.id 
        		where c.id = $cid and t.name is not null";

        //print $sql;
        $data = $this->query($sql);
        return $data;
    }
    
    //タグ削除
	function deleteAllTagByCouponId($cid){
		if(!$cid)
		return array();
		
		
		$sql = "DELETE FROM coupon_x_tag where coupon_id = $cid ";
// 				print $sql;
		$data = $this->query($sql);
		return $data;
	}
    
    //関連記事情報取得
	function selectConnectionCouponByTag($tagString, $num=3,$ownId=""){
		$ownSql = "";
		if($ownId!=""){
// 			$categroysql = " AND categroy IN($categroy) ";
			$ownSql = " AND a.id !='$ownId' ";
		}
		if(!$tagString)
			return array();
			
		$sql = "SELECT
					c.title,
					c.id,
					c.addtime,
					c.pic_url
				FROM
					coupon c
				JOIN coupon_x_tag ti on c.id = ti.coupon_id
				JOIN coupon_tag t on t.id = ti.tag_id
				WHERE t.name in ($tagString) AND c.delete_flag = 0 AND c.visible_flag = 1
				$ownSql
				GROUP by c.id having count(c.id) <= $num
				ORDER by addtime DESC LIMIT ".$num;
		
		 //print $sql;
		$data = $this->query($sql);
		return $data;
	}

}
?>