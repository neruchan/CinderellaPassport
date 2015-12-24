<?php
class users extends ipfDB{

	function selectUsersByUserAndEmail($username,$email){
    

    	$sql = "SELECT * FROM users WHERE username = '$username' or email='$email'";
    	//     	print $sql;
    	$data = $this->query($sql);
    	return $data;
    }

	function selectUsersByID($id){
    	if(!$id)
    	return array();

    	$sql = "SELECT * FROM users WHERE id=$id";
    	//     	print $sql;
    	$data = $this->query($sql);
    	return $data[0];
    }

 	function selectLastUserId(){

    	$sql = "SELECT id FROM `users` order by id DESC LIMIT 1";
    	//     	print $sql;
    	$data = $this->query($sql);
    	return $data[0]['id'];
    }

    function selectUserInfo($uid, $chk_delflg = false){
        if(!$uid)
            return array();

        $sql = "SELECT a.password,a.email,b.* FROM users a, users_info b WHERE a.username = b.username AND a.id = $uid";

//         print $sql;
        $data = $this->query($sql);
        return $data;
    }

    function selectNamePic($userid){
        if(!$userid)
            return array();

        $sql = "SELECT username, nickname, userpic FROM users_info WHERE userid = $userid";

        //print $sql;
        $data = $this->query($sql);
        return $data[0];
    }

    /**
     * Added by Gimx 2012/4/27 For Web Service
     * @param unknown_type $userid
     * @return multitype:|Ambigous <>
     */
    function getUserList($username = null){

        $sql = "SELECT a.username, a.email, b.nickname, b.cmflag FROM users a, users_info b WHERE a.username = b.username";
        if(isset($username)){
            $sql = "SELECT a.username, a.email, b.nickname, b.cmflag FROM users a, users_info b WHERE a.username = b.username AND a.username = $username";
        }

        //print $sql;
        $data = $this->query($sql);
        return $data;
    }
    
    function selectUsersmail($email){

        $sql = "SELECT count(*) as cnt FROM users WHERE email = '$email' AND delete_flag =0 ";

        //print $sql;
        $data = $this->query($sql);
        return $data[0]['cnt'];
    }
    
    function selectUsersname($usersname){
        $sql = "SELECT count(*) as cnt FROM users WHERE username = '$usersname' AND delete_flag =0 ";

        //print $sql;
        $data = $this->query($sql);
        return $data[0]['cnt'];
    }

    function selectNickname($nickname){
    	$sql = "SELECT count(*) as cnt FROM users_info WHERE nickname = '$nickname'";

    	//print $sql;
    	$data = $this->query($sql);
    	return $data[0]['cnt'];
    }
    

}

?>