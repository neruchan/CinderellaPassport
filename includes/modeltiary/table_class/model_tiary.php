<?php
class model_tiary extends ipfDB1{
	//カワイイキャペンーランキング
	function selectModelDataByID($id){
		if(!$id)
		return array();
		$sql = "SELECT
					m.model_id,
					m.name,
					m.romaji_name,
					m.pic_url_icon,
					m.facebook_url,
					m.twitter_url,
					m.blog_url,
					wr.before_rank
				FROM
					model m
				LEFT JOIN week_model_rank wr ON wr.model_id = m.model_id
				WHERE
					m.model_id = $id AND m.visible_flag<>0 ";
		// 				print $sql;
		$data = $this->query($sql);
		return $data[0];
	}

	//週間モデルランキング更新時間取得
	function selectModelRankByID($id){
		if(!$id)
		return array();
		$sql = "SELECT updatetime FROM week_model_rank WHERE model_id = $id ";
		// 				print $sql;
		$data = $this->query($sql);
		return $data[0]["updatetime"];
	}

	//モデルブログ情報取得
	function selectModelBlog($amount=8){
		$sql = "SELECT * FROM model WHERE visible_flag<>0 ORDER BY blog_time DESC LIMIT $amount ";
// 		$sql = "SELECT model_id,name,pic_url_icon,facebook_url,twitter_url,blog_url,romaji_name,blog_rss FROM model WHERE visible_flag<>0 ORDER BY blog_time DESC LIMIT $amount ";
		// 				print $sql;
		$data = $this->query($sql);
		return $data;
	}

	//トップページモデルブログ情報取得
	function selectModelBlogTop($type=1){
		$sql = "SELECT * FROM model WHERE visible_flag=$type ORDER BY blog_time DESC ";
		// 				print $sql;
		$data = $this->query($sql);
		return $data;
	}

	//ブログページモデル全体数取得
	function selectModelBlogCnt(){
		$sql = "SELECT count(*)cnt FROM model WHERE visible_flag<>0 ORDER BY model_id ASC ";
		//  		print $sql;
		$data = $this->query($sql);
		return $data[0]["cnt"];
	}

	//ブログページモデル全体情報取得
	function selectModelBlogAll($num=20,$page=0){
		$sql = "SELECT model_id,name,romaji_name,pic_url_icon,facebook_url,twitter_url,blog_url,blog_rss FROM model WHERE visible_flag<>0 ORDER BY blog_time DESC LIMIT $num OFFSET ".($page * $num)." ";
	// 		print $sql;
		$data = $this->query($sql);
		return $data;
	}

	//Ｒｓｓ情報存在チェック
	function checkRssLink($link){
		if(!$link)
		return array();
		$sql = "SELECT count(*)cnt FROM article WHERE links ='$link' AND delete_flag = 0 AND visible_flag = 1";
		// 				print $sql;
		$data = $this->query($sql);
		return $data[0]["cnt"];
	}

	//記事新着情報取得
	function selectArticleNew($num=10){
		$sql = "SELECT
					title,
					id,
					addtime
				FROM
					article
				WHERE
					delete_flag = 0
					 AND visible_flag = 1
				AND addtime > date_sub(now(), INTERVAL 1 DAY)
				ORDER BY
					addtime DESC LIMIT $num ";
		// 				print $sql;
		$data = $this->query($sql);
		return $data;
	}

	//記事ランキング
	function selectArticleRank($num=5){
		$sql = "SELECT
					a.id,
					a.categroy,
					a.title,
					a.addtime,
					a.access_num,
					ar.before_rank,
					a.image,
					a.contents
				FROM
					article a
				LEFT JOIN article_rank ar ON ar.article_id = a.id
				WHERE
					a.delete_flag = 0
					 AND a.visible_flag = 1
				ORDER BY
					a.access_num DESC
				LIMIT $num ";
		// 				print $sql;
		$data = $this->query($sql);
		return $data;
	}

	//記事ランキングＩＤ取得
	function selectArticleRankID(){
		$sql = "SELECT id FROM article WHERE delete_flag = 0 AND visible_flag = 1 ORDER BY access_num DESC ";
		// 				print $sql;
		$data = $this->query($sql);
		return $data;
	}

	//記事ランキング前回情報存在チェック
	function checkArticleRankByID($id){
		if(!$id)
		return array();
		$sql = "SELECT count(*)cnt FROM article_rank WHERE article_id=$id  ";
		// 				print $sql;
		$data = $this->query($sql);
		return $data[0]["cnt"];
	}

	//記事新着情報取得
	function selectRightArticleNew($num=10){
		$sql = "SELECT
						title,
						id,
						addtime,
						image

					FROM
						article
					WHERE
						delete_flag = 0
						 AND visible_flag = 1
						AND userid is null 
					ORDER BY
						addtime DESC LIMIT $num ";
		// 				print $sql;
		$data = $this->query($sql);
		return $data;
	}
	
	
	//記事新着情報取得
	function selectRightArticlePv($num=10,$numDays=30,$category=""){
		
		$catSql = "";
		if($category){
			$catSql = " AND categroy LIKE '%$category%' ";
		}
	
		$sql = "SELECT
						title,
						id,
						addtime,
						image,
						contents,
						access_num
					FROM
						article
					WHERE
						delete_flag = 0
						 AND visible_flag = 1
						AND userid is null 
						AND DATE_SUB(CURDATE(), INTERVAL ".$numDays." DAY) <= addtime
						$catSql
					ORDER BY
						access_num DESC LIMIT $num ";
		// 				print $sql;
		$data = $this->query($sql);
		return $data;
	}
	
	function selectTagExists($tagName){
    	if(!$tagName)
            return array();

        $sql = "SELECT id FROM article_tag WHERE name = '$tagName'";

        //print $sql;
        $data = $this->query($sql);
        return $data[0]['id'];
    	
    }
    
    function selectTagDetailById($tid){
    	if(!$tid)
            return array();

        $sql = "SELECT * FROM article_tag WHERE id = $tid";

        //print $sql;
        $data = $this->query($sql);
        return $data[0];
    	
    }
    
    //記事新着情報取得
	function selectRightUserArticleRank($num=5, $numDays=30){

		$sql = "SELECT
						a.title,
						a.id,
						a.addtime,
						a.image,
						a.contents,
						a.access_num,
						a.userid
					FROM
						article a
					WHERE
						a.delete_flag = 0
						 AND a.visible_flag = 1
						AND a.userid is NOT null 
						AND DATE_SUB(CURDATE(), INTERVAL ".$numDays." DAY) <= a.addtime
					ORDER BY
						a.access_num DESC LIMIT $num";
		

		$data = $this->query($sql);
		return $data;
	}
	
	//記事新着情報取得
	function selectRandomOsusume($num=5, $numDays=30){

// 		$sql = "SELECT
// 						a.title,
// 						a.id,
// 						a.addtime,
// 						a.image,
// 						a.contents,
// 						a.access_num,
// 						a.userid
// 					FROM
// 						article a
// 					WHERE
// 						a.delete_flag = 0
// 						AND a.userid is NOT null 
// 						AND DATE_SUB(CURDATE(), INTERVAL ".$numDays." DAY) <= a.addtime
// 					ORDER BY
// 						a.access_num DESC LIMIT $num";
		
		$sql = "SELECT
						a.title,
						a.id,
						a.addtime,
						a.image,
						a.contents,
						a.access_num,
						a.userid
					FROM
						article a
					WHERE
						a.delete_flag = 0
						 AND a.visible_flag = 1
						AND a.userid is NOT null 
						AND DATE_SUB(CURDATE(), INTERVAL ".$numDays." DAY) <= a.addtime
					ORDER BY
						RAND() LIMIT $num";

		// 				print $sql;
		$data = $this->query($sql);
		return $data;
	}

	//記事カテゴリ分類全体数取得
	function selectArticleCategroyCnt($keyword="",$categroy="",$isuser="",$isVideo=""){
		$isvideosql = " AND a.is_video = 0";
		if($isVideo!=""){
			$isvideosql = " AND a.is_video = 1";
		}
		$isusersql = " AND a.userid is null";
		if($isuser!=""){
			//showboth
			if($isuser === 3){
				$isusersql = "";
			}
			else{
				$isusersql = " AND a.userid is not null ";
			}
			
		}
		$categroysql = "";
		if($categroy!=""){
// 			$categroysql = " AND categroy IN($categroy) ";
			$categroysql = " AND categroy LIKE '%$categroy%' ";
		}
		$keywordsql = "";
		if($keyword!=""){
			$keywordsql = " AND a.title LIKE '%$keyword%' ";
		}
		$sql = "SELECT
					count(*)cnt
				FROM
					article a
				LEFT JOIN model m ON m.model_id = a.entry_name
				WHERE
					a.delete_flag = 0
					 AND a.visible_flag = 1
					$isusersql $categroysql $keywordsql $isvideosql";
		//  		print $sql;
		$data = $this->query($sql);
		return $data[0]["cnt"];
	}
	
	

	//記事カテゴリ分類全体情報取得
	function selectArticleCategroyAll($keyword="",$categroy="",$num=20,$page=0,$isuser="",$isVideo="",$idlist=""){
		$isvideosql = " AND a.is_video = 0";
		if($isVideo!=""){
			$isvideosql = " AND a.is_video = 1";
		}
		// $isusersql = " AND a.userid is null";
// 		if($isuser!=""){
// 			$isusersql = " AND a.userid is not null ";
// 		}
		$isusersql = " AND (a.userid is null or a.userid = 0)";
		if($isuser!=""){
			//showboth
			if($isuser === 3){
				$isusersql = "";
			}
			else{
				$isusersql = " AND (a.userid is not null and a.userid != 0)";
			}
			
		}
		$categroysql = "";
		if($categroy!=""){
// 			$categroysql = " AND categroy IN($categroy) ";
			$categroysql = " AND categroy LIKE '%$categroy%' ";
		}
		$keywordsql = "";
		$joinsql = "";
		if($keyword!=""){
			$keywordsql = " AND (a.title LIKE '%$keyword%' OR t.name in ('$keyword'))";
			$joinsql = "JOIN article_c_tag at ON at.article_id = a.id 
	JOIN article_tag t ON t.id = at.tag_id";
		}
		$idListSql = "";
		if($idlist!=""){
			$idListSql = " AND a.id in ($idlist) ";
		}
		
		$sql = "SELECT
					a.*
				FROM
					article a
				
				$joinsql
				WHERE
					a.delete_flag = 0
					 AND a.visible_flag = 1
					$isusersql 
					$categroysql
					$keywordsql
					$isvideosql
					$idListSql
					GROUP by a.id
				ORDER BY
					a.addtime DESC
					 LIMIT $num OFFSET ".($page * $num)." ";
// 				print $sql;
		$data = $this->query($sql);
		return $data;
	}
	
	//記事カテゴリ分類全体情報取得
	function selectArticleCategroyAllWithout($categroy="",$num=20,$page=0,$isuser="",$ownId=""){
		$isusersql = " AND a.userid is null";
		if($isuser!=""){
			$isusersql = " AND a.userid is not null ";
		}
		$categroysql = "";
		if($categroy!=""){
// 			$categroysql = " AND categroy IN($categroy) ";
			$categroysql = " AND categroy LIKE '%$categroy%' ";
		}
		$ownSql = "";
		if($ownId!=""){
// 			$categroysql = " AND categroy IN($categroy) ";
			$ownSql = " AND a.id !='$ownId' ";
		}
		$sql = "SELECT
					a.*,
					m.name
				FROM
					article a
				LEFT JOIN model m ON m.model_id = a.entry_name
				WHERE
					a.delete_flag = 0
					 AND a.visible_flag = 1
					$isusersql 
					$categroysql
					$ownSql
				ORDER BY
					a.addtime DESC
					 LIMIT $num OFFSET ".($page * $num)." ";
// 				print $sql;
		$data = $this->query($sql);
		return $data;
	}
	
	
	//記事詳細情報取得
	function selectArticleByID($id){
		if(!$id)
		return array();
		$sql = "SELECT a.*,m.name FROM article a LEFT JOIN model m ON m.model_id = a.entry_name WHERE a.id=$id AND a.delete_flag=0  AND a.visible_flag = 1";
		//print $sql;
		$data = $this->query($sql);
		return $data[0];
	}
	
	//記事詳細情報取得
	function selectArticleByIDForPostEdit($id){
		if(!$id)
		return array();
		$sql = "SELECT a.*,m.name FROM article a LEFT JOIN model m ON m.model_id = a.entry_name WHERE a.id=$id AND a.delete_flag=0  AND (a.visible_flag = 1 OR a.visible_flag = 2)";
		//print $sql;
		$data = $this->query($sql);
		return $data[0];
	}

	//関連記事情報取得
	function selectConnectionArticle($categroy, $num=8,$ownId=""){
		$ownSql = "";
		if($ownId!=""){
// 			$categroysql = " AND categroy IN($categroy) ";
			$ownSql = " AND id !='$ownId' ";
		}
		if(!$categroy)
		return array();
		$sql = "SELECT
								title,
								id,
								addtime,
								image
							FROM
								article
							WHERE
								delete_flag = 0
								 AND visible_flag = 1
								 AND categroy IN($categroy)
								 $ownSql
							ORDER BY
								addtime DESC LIMIT ".$num;
		// 				print $sql;
		$data = $this->query($sql);
		return $data;
	}
	
	//関連記事情報取得
	function selectConnectionArticleByTag($tagString, $num=8,$ownId=""){
		$ownSql = "";
		if($ownId!=""){
// 			$categroysql = " AND categroy IN($categroy) ";
			$ownSql = " AND a.id !='$ownId' ";
		}
		if(!$tagString)
			return array();
			
		$sql = "SELECT
					a.title,
					a.id,
					a.addtime,
					a.image
				FROM
					article a
				JOIN article_c_tag ti on a.id = ti.article_id
				JOIN article_tag t on t.id = ti.tag_id
				WHERE t.name in ($tagString) AND a.delete_flag = 0  AND a.visible_flag = 1
				$ownSql
				GROUP by a.id having count(a.id) <= 8
				ORDER by addtime DESC LIMIT ".$num;
		
		 //print $sql;
		$data = $this->query($sql);
		return $data;
	}
	
	function searchForTagByString($keywordId){
		if(!$keywordId)
			return array();
			
		$sql = "SELECT
					*
				FROM
					article_tag
				WHERE
					delete_flag = 0
			 	AND id = $keywordId
				";
		// 				print $sql;
		$data = $this->query($sql);
		return $data[0];
	}
	
	function searchForTagByArticleId($aid){
		if(!$aid)
			return array();
			
		$sql = "SELECT
					tag_id
				FROM
					article_c_tag
				WHERE
			 		article_id = $aid
				";
		 				//print $sql;
		$data = $this->query($sql);
		return $data;
	}
	
	//関連記事情報取得
	function selectConnectionArticleUsingTag($tagString, $num=8){
		if(!$tagString)
			return array();
		
		
		
		
		$sql = "SELECT
								title,
								id,
								addtime,
								image
							FROM
								article
							WHERE
								delete_flag = 0
								 AND visible_flag = 1
								 AND categroy IN($categroy)
							ORDER BY
								addtime DESC LIMIT ".$num;
		// 				print $sql;
		$data = $this->query($sql);
		return $data;
	}

	//アクセス数変更
	function selectUpdateAccessByID($id){
		if(!$id)
		return array();
		$sql = "UPDATE article SET access_num = access_num+1 WHERE id = $id ";
		//print $sql;
		$data = $this->query($sql,1);
		return $data;
	}

	//モデル個人情報取得
	function selectModelByID($id){
		if(!$id)
		return array();
		$sql = "SELECT * FROM model WHERE model_id = $id AND visible_flag<>0 ";
// 		print $sql;
		$data = $this->query($sql);
		return $data[0];
	}
	
	//モデル個人情報取得
	function selectListTestModel(){
		$sql = "SELECT * FROM model WHERE test_visible_flag = 1";
// 		print $sql;
		$data = $this->query($sql);
		return $data;
	}

	//tiary投稿者カワイイランキングチェック
	function checkEntryerRankByID($id){
		if(!$id)
		return array();
		$sql = "SELECT updatetime FROM tiary_entryer_rank WHERE user_id=$id  ";
		// 				print $sql;
		$data = $this->query($sql);
		return $data[0]["updatetime"];
	}

	//tiary投稿者カワイイランキング番号
	function selectEntryerRankByID($id){
		if(!$id)
		return array();
		$sql = "SELECT before_rank FROM tiary_entryer_rank WHERE user_id=$id  ";
		// 				print $sql;
		$data = $this->query($sql);
		return $data[0]["before_rank"];
	}

	//tiaryカワイイランキングチェック
	function checkKawaiiRankByID($id){
		if(!$id)
		return array();
		$sql = "SELECT updatetime FROM tiary_kawaii_rank WHERE entry_id=$id  ";
		// 				print $sql;
		$data = $this->query($sql);
		return $data[0]["updatetime"];
	}

	//tiaryカワイイランキング番号
	function selectKawaiiRankByID($id){
		if(!$id)
		return array();
		$sql = "SELECT before_rank FROM tiary_kawaii_rank WHERE entry_id=$id  ";
		// 				print $sql;
		$data = $this->query($sql);
		return $data[0]["before_rank"];
	}


	//店舗ランキングチェック
	function checkShopRankByID($id){
		if(!$id)
		return array();
		$sql = "SELECT updatetime FROM tiary_shop_rank WHERE shop_id=$id  ";
		// 				print $sql;
		$data = $this->query($sql);
		return $data[0]["updatetime"];
	}

	//店舗カワイイランキング番号
	function selectShopRankByID($id){
		if(!$id)
		return array();
		$sql = "SELECT before_rank FROM tiary_shop_rank WHERE shop_id=$id  ";
		// 				print $sql;
		$data = $this->query($sql);
		return $data[0]["before_rank"];
	}

	//新着記事情報取得
	function selectArticleNewTop($num=5){
		$sql = "SELECT
						a.id,
						a.title,
						a.categroy,
						a.entry_name,
						a.image,
						a.addtime,
						a.contents,
						m.name
					FROM
						article a
					LEFT JOIN model m ON m.model_id = a.entry_name
					WHERE
						a.delete_flag = 0
						 AND a.visible_flag = 1
					ORDER BY
						a.addtime DESC
						 LIMIT $num ";
		// 				print $sql;
		$data = $this->query($sql);
		return $data;
	}

	//オーディショントップ
	function selectAuditionTop(){
		$sql = "SELECT audition_title,audition_url,audition_new_flag FROM audition WHERE delete_flag = 0 ORDER BY addtime DESC LIMIT 10 ";
		// 				print $sql;
		$data = $this->query($sql);
		return $data;
	}


	//オーディション全体数取得
	function selectAuditionCnt(){

	$sql = "SELECT
					count(*)cnt
				FROM
					audition
				WHERE
					delete_flag = 0";
	//  		print $sql;
	$data = $this->query($sql);
	return $data[0]["cnt"];
	}

	//オーディション全体情報取得
	function selectAuditionAll($num=20,$page=0){
		$sql = "SELECT
					audition_title,
					audition_url,
					audition_new_flag,
					audition_text,
					end_time
				FROM
					audition
				WHERE
					delete_flag = 0
				ORDER BY
					addtime DESC
					LIMIT $num OFFSET ".($page * $num)." ";
	// 				print $sql;
			$data = $this->query($sql);
			return $data;
	}

	//ライター全体数取得
	function selectWriterCnt(){

		$sql = "SELECT
					count(*)cnt
				FROM
					writer w
				LEFT JOIN(
					SELECT
						count(writer_id)access_num,
						writer_id
					FROM
						article
					GROUP BY
						writer_id
				) a ON a.writer_id = w.id
				WHERE
					w.delete_flag = 0";
		//  		print $sql;
		$data = $this->query($sql);
		return $data[0]["cnt"];
	}

	//ライター全体情報取得
	function selectWriterAll($num=20,$page=0){
		$sql = "SELECT
					w.id,
					w.roma_name,
					w.image,
					w.fb_url,
					w.blog_url,
					w.tw_url,
					w.introduce,
					IFNULL(a.access_num,0) access
				FROM
					writer w
				LEFT JOIN(
					SELECT
						count(writer_id)access_num,
						writer_id
					FROM
						article
					GROUP BY
						writer_id
				) a ON a.writer_id = w.id
				WHERE
					w.delete_flag = 0
				ORDER BY
					w.addtime DESC
				LIMIT $num OFFSET ".($page * $num)." ";
		// 				print $sql;
		$data = $this->query($sql);
		return $data;
	}

	//ライター情報取得
	function selectWriterRankdata($num=15){
		$sql = "SELECT
						w.id
					FROM
						writer w
					LEFT JOIN(
						SELECT
							count(writer_id)access_num,
							writer_id
						FROM
							article
						GROUP BY
							writer_id
					)a ON a.writer_id = w.id
					WHERE
						w.delete_flag = 0
					ORDER BY
						IFNULL(a.access_num, 0) DESC
				LIMIT $num ";
		// 				print $sql;
		$data = $this->query($sql);
		return $data;
	}

	//ライターランキングチェック
	function checkWriterRankByID($id){
		if(!$id)
		return array();
		$sql = "SELECT updatetime FROM writer_rank WHERE writer_id=$id  ";
		// 				print $sql;
		$data = $this->query($sql);
		return $data[0]["updatetime"];
	}

	//記事ランキング
	function selectWriterRank($num=5){
		$sql = "SELECT
						w.id,
						w.name,
						w.roma_name,
						w.image,
						w.fb_url,
						w.blog_url,
						w.tw_url,
						w.introduce,
						IFNULL(a.access_num, 0)access,
						wr.before_rank
					FROM
						writer w
					LEFT JOIN(
						SELECT
							count(writer_id)access_num,
							writer_id
						FROM
							article
						GROUP BY
							writer_id
					)a ON a.writer_id = w.id
					LEFT JOIN writer_rank wr ON wr.writer_id = w.id
					WHERE
						w.delete_flag = 0
					ORDER BY
						IFNULL(a.access_num, 0) DESC
					LIMIT $num ";
		// 				print $sql;
		$data = $this->query($sql);
		return $data;
	}
	//バナー
	function selectBannelData(){
		$sql = "SELECT * FROM banner_advertising WHERE delete_flag = 0 ";
		// 				print $sql;
		$data = $this->query($sql);
		return $data;
	}

	//店舗情報取得
	function selectModelShopRankdata($num=15){
		$sql = "SELECT id,ranking FROM shop WHERE delete_flag = 0 ORDER BY ranking ASC LIMIT $num ";
		// 				print $sql;
		$data = $this->query($sql);
		return $data;
	}

	//店舗ランキングチェック
	function checkModelShopRankByID($id){
		if(!$id)
		return array();
		$sql = "SELECT updatetime FROM shop_rank WHERE shop_id=$id  ";
		// 				print $sql;
		$data = $this->query($sql);
		return $data[0]["updatetime"];
	}

	//店舗ランキング
	function selectModelShopRank($num=5){
		$sql = "SELECT
					s.*, sk.before_rank
				FROM
					shop s
				LEFT JOIN shop_rank sk ON sk.shop_id = s.id
				WHERE
					s.delete_flag = 0
				ORDER BY
					s.ranking ASC LIMIT $num ";
		// 				print $sql;
		$data = $this->query($sql);
		return $data;
	}

	//広告新着記事情報取得
	function selectArticleNewPayToppage($num=2){
		$sql = "SELECT
								id,
								title,
								categroy,
								entry_name,
								image,
								addtime,
								contents
							FROM
								article
							WHERE
								delete_flag = 0  AND visible_flag = 1 AND pay_flag = 1 AND DATE_ADD(addtime, INTERVAL 7 DAY) > NOW()
							ORDER BY
								addtime DESC
								 LIMIT $num ";
		// 				print $sql;
		$data = $this->query($sql);
		return $data;
	}

	//今月広告新着記事情報取得
	function selectArticleNewPayMonthToppage($num=6){
		$sql = "SELECT
									id,
									title,
									categroy,
									entry_name,
									image,
									addtime,
									contents
								FROM
									article
								WHERE
									delete_flag = 0  AND visible_flag = 1 AND pay_flag = 1 AND DATE_ADD(addtime, INTERVAL 7 DAY) < NOW() AND DATE_ADD(addtime, INTERVAL 37 DAY) > NOW()
								ORDER BY
									addtime DESC
									 LIMIT $num ";
// 		print $sql;
		$data = $this->query($sql);
		return $data;
	}

	//新着記事情報取得
	function selectArticleNewToppage($num=6){
		$sql = "SELECT
							id,
							title,
							categroy,
							entry_name,
							image,
							addtime,
							contents
						FROM
							article
						WHERE
							delete_flag = 0 AND pay_flag = 0  AND visible_flag = 1
						ORDER BY
							addtime DESC
							 LIMIT $num ";
		// 				print $sql;
		$data = $this->query($sql);
		return $data;
	}

	//モデルブログ情報取得
	function selectModelBlogRss(){
		$sql = "SELECT model_id,blog_rss FROM model WHERE visible_flag<>0 ORDER BY model_id ASC ";
		// 				print $sql;
		$data = $this->query($sql);
		return $data;
	}

	function updateModelBlogTime($id,$date){
		if(!$id)
		return array();
		$sql = "UPDATE model SET blog_time = '$date' WHERE model_id = $id ";
		// 				print $sql;
		$data = $this->query($sql,1);
		return $data;
	}

	//バナーチェック
	function checkBanner($bid){
		if(!$bid)
		return array();
		$sql = "SELECT ba_url FROM banner_advertising WHERE id=$bid AND delete_flag=0 ";
		// 				print $sql;
		$data = $this->query($sql);
		return $data[0]["ba_url"];
	}

	//バナーチェック
	function addBannerClickNum($bid){
		if(!$bid)
		return array();
// 		$sql = "UPDATE banner_advertising SET click_num =click_num+1  WHERE id = $bid ";
		$sql = "INSERT INTO banner_click (banner_id,addtime) VALUES ($bid,now()) ";
		// 				print $sql;
		$data = $this->query($sql,1);
		return $data;
	}

	//ヘッダバナー
	function selectHeadBanner(){
		$sql = "SELECT
					id,
					ba_image
				FROM
					banner_advertising
				WHERE
					delete_flag = 0
				AND ba_start <= CURDATE()
				AND ba_end >= CURDATE()
				AND part_flag = 3
				ORDER BY
					RAND()
				LIMIT 1 ";
		// 				print $sql;
		$data = $this->query($sql);
		return $data;
	}

	//左バナー
	function selectLeftBanner(){
		$sql = "SELECT
						id,
						ba_image
					FROM
						banner_advertising
					WHERE
						delete_flag = 0
					AND ba_start <= CURDATE()
					AND ba_end >= CURDATE()
					AND part_flag = 4
					ORDER BY
						RAND()
					LIMIT 2 ";
		// 				print $sql;
		$data = $this->query($sql);
		return $data;
	}

	//左バナー
	function selectLeftBanner2($year,$month){
		$sql = "SELECT
					id,
					ba_image
				FROM
					banner_advertising
				WHERE
					delete_flag = 0
				AND part_flag = 6
				AND(
					(
						EXTRACT(YEAR FROM ba_start)= $year
						AND EXTRACT(MONTH FROM ba_start)= $month
					)
					OR(
						EXTRACT(YEAR FROM ba_end)= $year
						AND EXTRACT(MONTH FROM ba_end)= $month
					)
				)
				ORDER BY
					RAND()
				LIMIT 5 ";
// 		print $sql;
		$data = $this->query($sql);
		return $data;
	}

	//アドコードチェック
	function checkClient($id){
		if(!$id)
		return array();
		$sql = "SELECT client_url FROM article WHERE id=$id AND delete_flag=0 AND visible_flag = 1";
		// 				print $sql;
		$data = $this->query($sql);
		return $data[0]["client_url"];
	}

	//アドコードチェック
	function addClientClickNum($id){
		if(!$id)
		return array();
		// 		$sql = "UPDATE banner_advertising SET click_num =click_num+1  WHERE id = $bid ";
		$sql = "INSERT INTO client_click (article_id,addtime) VALUES ($id,now()) ";
		// 				print $sql;
		$data = $this->query($sql,1);
		return $data;
	}
	
	////////////////////////////////////////////////////////////////////////////////////////////
	// NELSON
	//追加投稿機能
	
	//右
	function selectRightEntryNum($uid){
		if(!$uid)
		return array();
		$sql = "SELECT count(*)num FROM article WHERE userid = $uid AND delete_flag=0 AND visible_flag = 1";
		 				//print $sql;
		$data = $this->query($sql);
		return $data[0]["num"];
	}
	
	//右ＰＶ数
	function selectRightPvNum($uid){
		if(!$uid)
		return array();
		$sql = "SELECT sum(a.access_num)total FROM article a WHERE a.userid = $uid AND a.delete_flag=0 AND visible_flag = 1";
		 				//print $sql;
		$data = $this->query($sql);
		return $data[0]["total"];
	}
	
	//アクセス数変更
	function checkIp($ipAddress){
		$sql = "SELECT * FROM visitor_ip WHERE ip_address = '$ipAddress' AND delete_flag = 0 ";
		//print $sql;
		$data = $this->query($sql);
		return $data[0];
	}
	
	//アクセス数変更
	function checkAccesstimeByIP($ipid,$aid){
		$sql = "SELECT * FROM ip_article WHERE ipid = $ipid AND aid = $aid AND delete_flag = 0 ";
		//print $sql;
		$data = $this->query($sql);
		return $data[0];
	}
	
	//カテゴリ分類
	function selectActivities($uid){
		if(!$uid)
		return array();

		$sql = "SELECT
							*
						FROM
							article a
						WHERE
							a.delete_flag = 0
							AND a.userid = $uid
							 AND a.visible_flag = 1
						ORDER BY
							a.addtime DESC
						LIMIT 5 ;

				";
		//print $sql;
		$data = $this->query($sql);


		return $data;
	}
	
	
	function selectMyNewEntryCnt($uid,$isvideo=""){
		$isvideosql = " AND a.is_video = 0";
		if($isvideo != ""){
			$isvideosql = " AND a.is_video = 1";
		}
		
		$sql = "SELECT
						count(*)cnt
					FROM
						article a
					WHERE a.userid=$uid AND a.delete_flag=0  AND visible_flag = 1 $isvideosql";
		//  		print $sql;
		$data = $this->query($sql);
		return $data[0]["cnt"];
	}

	//プロフィール新規HowTo取得
	function selectMyNewEntryAll($uid,$num=20,$page=0,$isvideo=""){
		$isvideosql = " AND a.is_video = 0";
		if($isvideo != ""){
			$isvideosql = " AND a.is_video = 1";
		}
		
		
		$sql = "SELECT
						*
					FROM
						article a
					
					WHERE a.userid=$uid AND a.delete_flag=0 AND a.visible_flag = 1
					$isvideosql
					ORDER BY
						a.addtime DESC
					LIMIT $num OFFSET ".($page * $num)." ";
		//  		print $sql;
		$data = $this->query($sql);
		return $data;
	}
	
	
	function selectMyDraftEntryCnt($uid,$isvideo=""){
		$isvideosql = " AND a.is_video = 0";
		if($isvideo != ""){
			$isvideosql = " AND a.is_video = 1";
		}
		
		$sql = "SELECT
						count(*)cnt
					FROM
						article a
					WHERE a.userid=$uid AND a.delete_flag=0  AND visible_flag = 2 $isvideosql";
		//  		print $sql;
		$data = $this->query($sql);
		return $data[0]["cnt"];
	}

	//プロフィール新規HowTo取得
	function selectMyDraftEntryAll($uid,$num=20,$page=0,$isvideo=""){
		$isvideosql = " AND a.is_video = 0";
		if($isvideo != ""){
			$isvideosql = " AND a.is_video = 1";
		}
		
		
		$sql = "SELECT
						*
					FROM
						article a
					
					WHERE a.userid=$uid AND a.delete_flag=0 AND a.visible_flag = 2
					$isvideosql
					ORDER BY
						a.addtime DESC
					LIMIT $num OFFSET ".($page * $num)." ";
		//  		print $sql;
		$data = $this->query($sql);
		return $data;
	}
	
	//プロフィールページもっと見るボタンクリーク情報追加
	function selectEntryListForSmaho($last_id=1,$uid,$num=10){
	
		if(!$last_id)
			return array();
			

		$sql = "SELECT
						*
					FROM
						article a
					
					WHERE a.userid=$uid AND a.delete_flag=0 AND a.visible_flag = 1
					ORDER BY
						a.addtime DESC
					LIMIT $num OFFSET $last_id";
// 		print $sql;
		$data = $this->query($sql);
		return $data;

	}
	
	//プロフィールページもっと見るボタンクリーク情報追加
	function selectArticleListForSmaho($last_id=1,$category="",$num=10,$isuser=""){
	
		if(!$last_id)
			return array();
			
		$isusersql = " AND a.userid is null";
		if($isuser!=""){
			$isusersql = " AND a.userid is not null ";
		}
		
		$categorysql = "";
		if($category!=""){
// 			$categroysql = " AND categroy IN($categroy) ";
			$categorysql = " AND categroy LIKE '%$category%' ";
		}

		$sql = "SELECT
						*
					FROM
						article a
					
					WHERE a.delete_flag=0 AND a.visible_flag = 1
					$categorysql
					$isusersql
					ORDER BY
						a.addtime DESC
					LIMIT $num OFFSET $last_id";
// 		print $sql;
		$data = $this->query($sql);
		return $data;

	}
	
	//ブックマークアイテム存在チェック
	function selectBookmarkTrue($eid,$uid,$flag=0){
		if(!$eid)
		return array();
		$sql = "SELECT count(*)cnt FROM article_bookmark WHERE sb_sc_id=$eid AND sb_userid=$uid ";
		//AND sb_flag = $flag
// 		print $sql;
		$data = $this->query($sql);

		return $data[0]['cnt'];
	}
	
	function deleteBookmark($aid,$uid){
		if(!$uid && !$aid)
		return array();
		$sql = "DELETE FROM article_bookmark WHERE sb_userid =$uid AND sb_sc_id = $aid ";
		// 		print $sql;
		$data = $this->query($sql,1);
		return $data;
	}
	
	//ブックマーク一覧もっと見るボタンクリーク情報追加
	function selectContributeBookmarkMyAdd($uid,$num=20,$page=0){
		
		$sql = "SELECT
						*
					FROM
						article_bookmark ab
					JOIN article a ON a.id = ab.sb_sc_id
					WHERE ab.sb_userid = $uid AND a.delete_flag=0 AND ab.sb_flag = 0
					ORDER BY
						a.addtime DESC
					LIMIT $num OFFSET ".($page * $num);

		//print $sql;
		$data = $this->query($sql);
		return $data;
	}
	
	//ヘッダ可愛い数取得
	function checkFriend($uid,$friendid){

		if(!$uid)
			return array();
			
		$sql = "SELECT id FROM user_friend WHERE sp_userid=$uid AND sp_userfriendid =$friendid";
// 				print $sql;
		$data = $this->query($sql);
		return $data[0]["id"];
	}
	
	//お気にユーザー削除
	function del_user_like($pid){
		if(!$pid)
		return array();
		$sql = "DELETE FROM user_friend WHERE id =$pid ";
		// 		print $sql;
		$data = $this->query($sql,1);
		return $data;
	}
	
	//お気に入りユーザー一覧
	function selectDealUserMy($userid,$num=6,$page=0){
		if(!$userid)
		return array();
		$sql = "SELECT 
			ui.userid,ui.username,ui.nickname,ui.userpic,sp.sp_userfriendid 
			FROM user_friend sp JOIN users_info ui ON ui.userid = sp.sp_userfriendid 
			WHERE sp.sp_userid = $userid  LIMIT $num OFFSET ".($page * $num)." ";
// 		print $sql;
		$data = $this->query($sql);

		return $data;
	}
	
	//お気に入りユーザー一覧
	function selectKeywordExists($key){
		if(!$key)
		return array();
		$sql = "SELECT 
			sk.* 
			FROM search_keyword sk 
			WHERE sk.keyword = '$key' AND sk.delete_flag = 0 ORDER BY sk.addtime DESC";
 		//print $sql;
		$data = $this->query($sql);

		return $data[0];
	}
	
	//お気に入りユーザー一覧
	function displayByKeyword($key){
		if(!$key)
		return "";
		
		$sql = "SELECT 
			sk.* 
			FROM search_keyword_display sk 
			WHERE sk.keyword = '$key' AND sk.delete_flag = 0 ORDER BY sk.addtime DESC";
 		//print $sql;
		$data = $this->query($sql);

		return $data[0]['display'];
	}
	
	function countBlocks($article_id){
		if(!$article_id)
			return array();
			
		$sql = "SELECT count(*)cnt FROM article_block WHERE article_id=$article_id  AND delete_flag = 0 ";
		// 				print $sql;
		$data = $this->query($sql);
		return $data[0]["cnt"];
	}
	
	function selectBlocksByArticleId($article_id){
		if(!$article_id)
		return array();
		$sql = "SELECT
					ab.*
				FROM
					article_block ab
				WHERE
					ab.article_id = $article_id AND delete_flag = 0 ORDER BY ab.order_block ASC";
		// 				print $sql;
		$data = $this->query($sql);
		return $data;
	}
	
	function selectTopBlockByArticleId($article_id){
		if(!$article_id)
		return array();
		$sql = "SELECT
					ab.*
				FROM
					article_block ab
				WHERE
					ab.article_id = $article_id AND delete_flag = 0 ORDER BY ab.order_block ASC";
		// 				print $sql;
		$data = $this->query($sql);
		return $data[0];
	}
	
	function selectCommentNum($aid){
		$sql = "SELECT
						count(*)cnt
					FROM
						article_comment
					WHERE
						delete_flag = 0
					AND article_id = $aid";

		// 				print $sql;
		$data = $this->query($sql);
		return $data[0]["cnt"];
	}
	
	function selectCommentByCommentId($cid){
		$sql = "SELECT
						a.*
					FROM
						article_comment a
					WHERE
						delete_flag = 0
					AND id = $cid";

		// 				print $sql;
		$data = $this->query($sql);
		return $data[0];
	}
	
	function commentRateCheckIp($comment_id, $ipAddress,$state){
		$sql = "SELECT * FROM article_comment_score_user WHERE ip = '$ipAddress' AND delete_flag = 0 AND score = $state AND comment_id = $comment_id";
		//print $sql;
		$data = $this->query($sql);
		return $data[0];
	}
	
	function selectCommentsByArticleId($aid,$num=20,$page=0){

        $sql = "SELECT a.*
        		FROM article_comment a WHERE a.article_id = $aid AND a.delete_flag = 0
        		ORDER BY
				a.addtime DESC 
        		LIMIT $num OFFSET ".($page * $num)." ";

        //print $sql;
        $data = $this->query($sql);
        return $data;
    }
    
    function selectTagsByArticleId($aid){

        $sql = "SELECT a.id,a.title,a.contents,c.id,c.name 
        		FROM article a 
        		LEFT JOIN article_c_tag b ON a.id = b.article_id 
        		LEFT JOIN article_tag c ON b.tag_id = c.id 
        		where a.id = $aid and c.name is not null";

        //print $sql;
        $data = $this->query($sql);
        return $data;
    }
    
    function selectMyNewBookmarkCnt($uid,$isvideo=""){
		// $isvideosql = " AND a.is_video = 0";
// 		if($isvideo != ""){
// 			$isvideosql = " AND a.is_video = 1";
// 		}
		
		$sql = "SELECT
						count(*)cnt
					FROM
						article_bookmark ab
					WHERE ab.sb_userid = $uid AND ab.sb_flag = 0 ";
		//  		print $sql;
		$data = $this->query($sql);
		return $data[0]["cnt"];
	}
	
	function selectRSSSettings(){

        $sql = "SELECT *
        		FROM rss_yahoo_settings";

        //print $sql;
        $data = $this->query($sql);
        return $data;
    }
    
    //ヘッダ可愛い数取得
	function selectCommentedArticleUnread($uid){
		if(!$uid)
		return array();
		$sql = "SELECT count(*)cnt FROM article WHERE userid = $uid AND read_flag = 1 AND delete_flag=0 AND visible_flag = 1";
// 				print $sql;
		$data = $this->query($sql);
		return $data[0]["cnt"];
	}
	
	//ヘッダ可愛い数取得
	function selectCommentedArticleUnreadList($uid){
		if(!$uid)
		return array();
		$sql = "SELECT id,image,title,access_num FROM article WHERE userid = $uid AND read_flag = 1 AND delete_flag=0 AND visible_flag = 1";
		// 		print $sql;
		$data = $this->query($sql);
		return $data;
	}
	
	function updateReadFlag($aid,$flag=0){
		if(!$aid)
		return array();
		$sql = "UPDATE article SET read_flag = $flag WHERE id = $aid  ";
		// 		print $sql;
		$data = $this->query($sql, 1);
		return $data;
	}
	
	//可愛い数クリークユーザー
	function selectLastCommentingUser($aid,$num=""){
		if(!$aid)
		return array();
		if($num!=""){
			$limit = " LIMIT 1 ";
		}
		$sql = "SELECT c.user_id FROM article_comment c WHERE c.article_id =$aid ORDER BY c.addtime DESC ";
// 				print $sql;
		$data = $this->query($sql);
		return $data[0];
	}
	
	//バナー詳細情報取得
	function selectBannerByID($id){
		if(!$id)
			return array();

		$sql = "SELECT
						bm.*,
						
					FROM
						banner_advertising bm
					WHERE
						bm.delete_flag = 0 AND bm.id=$id ";
// 				print $sql;
		$data = $this->query($sql);
		return $data[0];
	}
	
	//バナー詳細情報取得
	function selectTrackByTrackCode($trackCode){
		if(!$trackCode)
			return array();

		$sql = "SELECT
						at.*
					FROM
						ads_track at
					WHERE
						at.delete_flag = 0 AND at.track_code='$trackCode' ";
// 				print $sql;
		$data = $this->query($sql);
		return $data[0];
	}
	
	// //記事カテゴリ分類全体数取得
// 	function selectArticleCategroyCntUserOnly($keyword="",$categroy=""){
// 		$categroysql = "";
// 		if($categroy!=""){
// // 			$categroysql = " AND categroy IN($categroy) ";
// 			$categroysql = " AND categroy LIKE '%$categroy%' ";
// 		}
// 		$keywordsql = "";
// 		if($keyword!=""){
// 			$keywordsql = " AND a.title LIKE '%$keyword%' ";
// 		}
// 		$sql = "SELECT
// 					count(*)cnt
// 				FROM
// 					article a
// 				LEFT JOIN model m ON m.model_id = a.entry_name
// 				WHERE
// 					a.delete_flag = 0
// 					AND a.userid is not null
// 					$categroysql $keywordsql ";
// 		//  		print $sql;
// 		$data = $this->query($sql);
// 		return $data[0]["cnt"];
// 	}

// //記事カテゴリ分類全体情報取得
// 	function selectArticleCategroyAllUserOnly($keyword="",$categroy="",$num=20,$page=0){
// 		$categroysql = "";
// 		if($categroy!=""){
// // 			$categroysql = " AND categroy IN($categroy) ";
// 			$categroysql = " AND categroy LIKE '%$categroy%' ";
// 		}
// 		$keywordsql = "";
// 		if($keyword!=""){
// 			$keywordsql = " AND a.title LIKE '%$keyword%' ";
// 		}
// 		$sql = "SELECT
// 					a.id,
// 					a.title,
// 					a.categroy,
// 					a.entry_name,
// 					a.image,
// 					a.addtime,
// 					a.contents,
// 					m.name
// 				FROM
// 					article a
// 				LEFT JOIN model m ON m.model_id = a.entry_name
// 				WHERE
// 					a.delete_flag = 0
// 					AND a.userid is NOT null
// 					$categroysql
// 					$keywordsql
// 				ORDER BY
// 					a.addtime DESC
// 					 LIMIT $num OFFSET ".($page * $num)." ";
// // 				print $sql;
// 		$data = $this->query($sql);
// 		return $data;
// 	}


}