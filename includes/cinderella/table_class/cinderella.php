<?php
class cinderella extends ipfDB{

	function selectCouponNumberCnt($keyword="",$category=""){
		$categorysql = "";
		if($category!=""){
			$categorysql = " AND c.category LIKE '%$category%' ";
		}
		$keywordsql = "";
		if($keyword!=""){
			$keywordsql = " AND c.title LIKE '%$keyword%' ";
		}
		$sql = "SELECT
					count(*)cnt
				FROM
					coupon c
				WHERE
					c.delete_flag = 0
					AND c.visible_flag = 1
				$categorysql $keywordsql";
		//print $sql;
		$data = $this->query($sql);
		return $data[0]["cnt"];
	}


	function selectCoupon($keyword="",$category="",$num=20,$page=0){
		
		$categorysql = "";
		if($category!="" && $category!= 0){
			$categorysql = " AND c.category LIKE '%$category%' ";
		}
		$keywordsql = "";
		
		if($keyword!=""){
			$keywordsql = " AND c.title LIKE '%$keyword%' ";
		}
		
		$sql = "SELECT
					c.id,
					c.category,
					c.exp_date_until,
					c.title,
					c.description,
					c.pic_url,
					c.before_price,
					c.after_price,
					c.addtime,
					s.name as shop_name,
					s.station as shop_station
				FROM
					coupon c
				JOIN coupon_x_shop cs ON cs.coupon_id = c.id
				JOIN shop s ON s.id = cs.shop_id
				WHERE
					c.delete_flag = 0
					AND c.visible_flag = 1
                    AND s.delete_flag = 0
					$categorysql
					$keywordsql
					GROUP by c.id
				ORDER BY
					c.addtime DESC
				LIMIT $num OFFSET ".($page * $num)." ";
// 				print $sql;
		$data = $this->query($sql);
		return $data;
	}
    
    function selectCouponOsusume($category=""){
		
		$categorysql = "";
		if($category!="" && $category!= 0){
			$categorysql = " AND c.category LIKE '%$category%' ";
		}
		
		$sql = "SELECT
					c.id,
					c.category,
					c.exp_date_until,
					c.title,
					c.description,
					c.pic_url,
					c.before_price,
					c.after_price,
					c.addtime,
					s.name as shop_name,
					s.station as shop_station
				FROM
					coupon c
				JOIN coupon_x_shop cs ON cs.coupon_id = c.id
				JOIN shop s ON s.id = cs.shop_id
				WHERE
					c.delete_flag = 0
					AND c.visible_flag = 1
                    AND s.delete_flag = 0
                    AND c.osusume_flag = 1
					$categorysql
					GROUP by c.id
				";
// 				print $sql;
		$data = $this->query($sql);
		return $data;
	}
    
    function selectCouponNewest($category="",$amount=1){
        
        $categorysql = "";
		if($category!="" && $category!= 0){
			$categorysql = " AND c.category LIKE '%$category%' ";
		}
		
		$sql = "SELECT
					c.id,
					c.category,
					c.exp_date_until,
					c.title,
					c.description,
					c.pic_url,
					c.before_price,
					c.after_price,
					c.addtime,
					s.name as shop_name,
					s.station as shop_station
				FROM
					coupon c
				JOIN coupon_x_shop cs ON cs.coupon_id = c.id
				JOIN shop s ON s.id = cs.shop_id
				WHERE
					c.delete_flag = 0
					AND c.visible_flag = 1
                    AND s.delete_flag = 0
                    $categorysql
                GROUP by c.id
                ORDER BY
				    addtime DESC
                LIMIT $amount
				";
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
					s.pic_url3 as shop_pic3,
                    s.access,
                    s.website,
                    s.detail,
                    s.eigyo_jikan,
                    s.holiday,
                    s.average_price
				FROM coupon c 
					JOIN coupon_x_shop cs ON cs.coupon_id = c.id
					JOIN shop s ON s.id = cs.shop_id
				WHERE c.id=$id 
				AND c.delete_flag=0 
				AND c.visible_flag = 1
                AND s.delete_flag = 0";
		//print $sql;
		$data = $this->query($sql);
		return $data[0];
	}
    
    //関連記事情報取得
	function selectConnectionCouponByTag($tagString, $num=3,$ownId=""){
		$ownSql = "";
		if($ownId!=""){
// 			$categroysql = " AND categroy IN($categroy) ";
			$ownSql = " AND c.id !='$ownId' ";
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
    
    //関連記事情報取得
	function selectConnectionCoupon($category, $num=3,$ownId=""){
		$ownSql = "";
		if($ownId!=""){
// 			$categroysql = " AND categroy IN($categroy) ";
			$ownSql = " AND id !='$ownId' ";
		}
		if(!$category)
		return array();
		$sql = "SELECT
								title,
								id,
								addtime,
								pic_url
							FROM
								coupon
							WHERE
								delete_flag = 0
								 AND visible_flag = 1
								 AND category IN($category)
								 $ownSql
							ORDER BY
								addtime DESC LIMIT ".$num;
		// 				print $sql;
		$data = $this->query($sql);
		return $data;
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
    
    function selectCommentsByCouponId($aid,$num=20,$page=0){

        $sql = "SELECT a.*
        		FROM coupon_comment a WHERE a.coupon_id = $aid AND a.delete_flag = 0
        		ORDER BY
				a.addtime DESC 
        		LIMIT $num OFFSET ".($page * $num)." ";

        //print $sql;
        $data = $this->query($sql);
        return $data;
    }

}
?>