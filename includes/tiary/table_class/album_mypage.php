<?php
class album_mypage extends ipfDB{
    function selectAlbumMyAll($userid,$num,$offset,$tags){
    	if($tags != ""){
    		$sql = "SELECT * FROM album WHERE user_id = $userid AND delete_flag=0 AND $tags = ANY (tags) order by addtime desc  LIMIT $num OFFSET $offset";
    	}else{
    		$sql = "SELECT * FROM album WHERE user_id = $userid AND delete_flag=0 order by addtime desc  LIMIT $num OFFSET $offset";
    	}

        //print $sql;
        $data = $this->query($sql);
        /*
        for($i=0;$i<count($data);$i++){
        	$data[$i]["tags"] = str_replace("{}","",$data[$i]["tags"]);
        	//$data[$i]["tags"] = str_replace("","",$data[$i]["tags"]);
        	print($data[$i]["tags"]);
        	if(){

        	}
        }
        */
        return $data;
    }

	function selectAlbumMyAllCount($userid,$tags){
		if($tags != ""){
			$sql = "SELECT count(0) FROM album WHERE user_id = $userid AND delete_flag=0 AND $tags = ANY (tags)";
		}else{
			$sql = "SELECT count(0) FROM album WHERE user_id = $userid AND delete_flag=0";
		}

        //print $sql;
        $data = $this->query($sql);
        return $data[0]["count"];
    }

    function selectAlMy($userid, $page=0, $npage=20,$tags){
    	if(!$userid)
    	return array();
    	if($tags != ""){
    		$sql = "SELECT * FROM album WHERE user_id = $userid AND delete_flag=0 AND $tags = ANY (tags) order by addtime desc  LIMIT $npage OFFSET  ($page * $npage)";
    	}else{
    		$sql = "SELECT * FROM album WHERE user_id = $userid AND delete_flag=0 order by addtime desc  LIMIT $npage OFFSET ($page * $npage)";
    	}
    	//$sql = "select a.sender, b.id, b.receiver, b.message, case when b.addtime > current_date then to_char(b.addtime, 'HH24:MI') else to_char(b.addtime, 'MM/DD') end as addtime, b.read_flag, c.username, c.nickname, c.userpic from (SELECT sender, max(addtime) as addtime FROM (SELECT sender, max(addtime) as addtime FROM messages WHERE (receiver = $userid AND receiver_delete_flag = 0) group by sender UNION SELECT receiver, max(addtime) as addtime FROM messages WHERE (sender = $userid AND sender_delete_flag = 0) group by receiver) aa  group by sender ORDER BY addtime desc OFFSET " . ($page * $npage) . "LIMIT $npage) a LEFT JOIN users_info c ON c.userid = a.sender, messages b WHERE (a.sender = b.sender OR a.sender = b.receiver) AND a.addtime = b.addtime  order by id desc";
    	//print $sql;
    	$data = $this->query($sql);
    	return $data;
    }

    function selectAlMyCnt($userid,$tags){
    	if(!$userid)
    	return 0;
    	if($tags != ""){
    		$sql = "SELECT count(*) as cnt  FROM album WHERE user_id = $userid AND delete_flag=0 AND $tags = ANY (tags)";
    	}else{
    		$sql = "SELECT count(*) as cnt  FROM album WHERE user_id = $userid AND delete_flag=0";
    	}
    	//$sql = "SELECT count(*) as cnt from (SELECT sender, max(addtime) as addtime FROM (SELECT sender, max(addtime) as addtime FROM messages WHERE (receiver = $userid AND receiver_delete_flag = 0) group by sender UNION SELECT receiver, max(addtime) as addtime FROM messages WHERE (sender = $userid AND sender_delete_flag = 0) group by receiver) aa  group by sender) a";

    	$data = $this->query($sql);
    	//print_r($data);
    	return $data[0]['cnt'];
    }

    function selectAlMyY($userid, $page=0, $npage=20,$tags){
    	if(!$userid)
    	return array();
    	if($tags != ""){
    		$sql = "SELECT * FROM album WHERE user_id = $userid AND delete_flag=0 AND public = 1 AND $tags = ANY (tags) order by addtime desc  LIMIT $npage OFFSET  ($page * $npage)";
    	}else{
    		$sql = "SELECT * FROM album WHERE user_id = $userid AND delete_flag=0 AND public = 1 order by addtime desc  LIMIT $npage OFFSET ($page * $npage)";
    	}
    	//$sql = "select a.sender, b.id, b.receiver, b.message, case when b.addtime > current_date then to_char(b.addtime, 'HH24:MI') else to_char(b.addtime, 'MM/DD') end as addtime, b.read_flag, c.username, c.nickname, c.userpic from (SELECT sender, max(addtime) as addtime FROM (SELECT sender, max(addtime) as addtime FROM messages WHERE (receiver = $userid AND receiver_delete_flag = 0) group by sender UNION SELECT receiver, max(addtime) as addtime FROM messages WHERE (sender = $userid AND sender_delete_flag = 0) group by receiver) aa  group by sender ORDER BY addtime desc OFFSET " . ($page * $npage) . "LIMIT $npage) a LEFT JOIN users_info c ON c.userid = a.sender, messages b WHERE (a.sender = b.sender OR a.sender = b.receiver) AND a.addtime = b.addtime  order by id desc";
    	//print $sql;
    	$data = $this->query($sql);
    	return $data;
    }

    function selectAlMyYCnt($userid,$tags){
    	if(!$userid)
    	return 0;
    	if($tags != ""){
    		$sql = "SELECT count(*) as cnt  FROM album WHERE user_id = $userid AND delete_flag=0 AND public = 1 AND $tags = ANY (tags)";
    	}else{
    		$sql = "SELECT count(*) as cnt  FROM album WHERE user_id = $userid AND public = 1 AND delete_flag=0";
    	}
    	//$sql = "SELECT count(*) as cnt from (SELECT sender, max(addtime) as addtime FROM (SELECT sender, max(addtime) as addtime FROM messages WHERE (receiver = $userid AND receiver_delete_flag = 0) group by sender UNION SELECT receiver, max(addtime) as addtime FROM messages WHERE (sender = $userid AND sender_delete_flag = 0) group by receiver) aa  group by sender) a";

    	$data = $this->query($sql);
    	//print_r($data);
    	return $data[0]['cnt'];
    }



    function selectTag1Cnt($user_id){
		if(!$user_id)
			return 0;

        $sql = "SELECT count(id) as cnt FROM album WHERE user_id = $user_id AND 1 = ANY (tags) AND delete_flag = 0";

        //print $sql;
        $data = $this->query($sql);
        return $data[0]['cnt'];
    }

    function selectTags($userid){
    	if(!$userid)
    	return array();
    	$sql = "SELECT atgs.* FROM( SELECT tid.id FROM ( SELECT atg.id,atg.user_id FROM album_tag AS atg WHERE user_id = $userid AND delete_flag = 0	)tid LEFT JOIN album mv ON mv.user_id = tid.user_id WHERE mv.delete_flag = 0  AND tid. ID = ANY(mv.tags) GROUP BY tid.id) tgid LEFT JOIN album_tag atgs ON atgs.id=tgid.id";
    	//print $sql;
    	$data = $this->query($sql);

    	$sql1 = "SELECT count(*) as cnt  FROM album WHERE user_id = $userid AND delete_flag=0  AND 1 = ANY (tags);";
    	$data1 = $this->query($sql1);

    	if($data1[0]['cnt']>0){
    		array_unshift($data,array("id"=>1,"tag"=>"作品",));
    	}
		//print_r($data);
    	return $data;



    	return $data;
    }

    function selectTagsY($userid){
    	if(!$userid)
    	return array();
    	$sql = "SELECT atgs.* FROM( SELECT tid.id FROM ( SELECT atg.id,atg.user_id FROM album_tag AS atg WHERE user_id = $userid AND delete_flag = 0	)tid LEFT JOIN album mv ON mv.user_id = tid.user_id WHERE mv.delete_flag = 0 AND mv.PUBLIC = 1 AND tid. ID = ANY(mv.tags) GROUP BY tid.id) tgid LEFT JOIN album_tag atgs ON atgs.id=tgid.id";
    	//print $sql;
    	$data = $this->query($sql);

    	$sql1 = "SELECT count(*) as cnt  FROM album WHERE user_id = $userid AND delete_flag=0  AND public = 1 AND 1 = ANY (tags);";
    	$data1 = $this->query($sql1);

    	if($data1[0]['cnt']>0){
    		array_unshift($data,array("id"=>1,"tag"=>"作品",));
    	}
    	//print_r($data);
    	return $data;
    }


    function deleteView($id, $userid){
    	if(!$id || !$userid)
    	return;

    	$sql = "UPDATE album SET delete_flag = 1 WHERE id = $id AND user_id  =$userid";
    	//print $sql;
    	$data = $this->query($sql, 1);
    	return $data;
    }


}
?>