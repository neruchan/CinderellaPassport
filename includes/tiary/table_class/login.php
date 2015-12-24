<?php
class login extends ipfDB{

    function loginuser($uid){
		if(!$uid)
			return array();

        $sql = "SELECT u.*,ui.nickname FROM users u LEFT JOIN users_info ui ON ui.userid=u.id WHERE (LOWER(u.username)='$uid'  OR LOWER(u.email)='$uid') delete_flag<>1";
        //print $sql;
        $data = $this->query($sql);
        return $data;
    }

    function selectusers($uid){
    	if(!$uid)
    	return array();

    	$sql = "SELECT * FROM users WHERE id=$uid";

    	//print $sql;
    	$data = $this->query($sql);
    	return $data;
    }

    function creatsession($session_id,$ip_address,$user_agent,$last_activity,$user_data){
    	$sql = "INSERT INTO ci_sessions (session_id,ip_address,user_agent,last_activity,user_data) VALUES('$session_id','$ip_address,','$user_agent',$last_activity,'$user_data') ";
    	$data = $this->query($sql, 1);
    	return $data;
    }

    function clearsession($session_id){
    	//$sql = "UPDATE ci_sessions SET user_data = null WHERE session_id = '$session_id'";
    	$sql = "DELETE FROM ci_sessions WHERE session_id= '$session_id'";
    	$data = $this->query($sql, 1);
    	return $data;
    }

    function selectsession($session_id){
    	$sql = "SELECT * FROM ci_sessions WHERE session_id='$session_id'";
    	$data = $this->query($sql);
    	return $data;
    }

    function CheckPassword($name,$pass){
    	if(!$name)
    	return array();

    	$sql = "SELECT u.*,ui.nickname,ui.birthday FROM users u LEFT JOIN users_info ui ON ui.userid=u.id WHERE (u.username='$name'  OR u.email='$name') AND u.password='$pass' AND delete_flag<>1 AND activated=1 ";
//     	print $sql;
    	$data = $this->query($sql);
    	return $data;
    }
}


?>