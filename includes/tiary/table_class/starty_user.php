<?php
class starty_user extends ipfDB{

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


    function selectPromotioncode($code){
    	$sql = "SELECT count(*) as cnt FROM startyfreepromotioncode WHERE sp_code = '$code'";

    	//print $sql;
    	$data = $this->query($sql);
    	return $data[0]['cnt'];
    }

    function selectUsersmail($email){

        $sql = "SELECT count(*) as cnt FROM users WHERE email = '$email' AND delete_flag =0 ";

        //print $sql;
        $data = $this->query($sql);
        return $data[0]['cnt'];
    }

    function selectUserinfo($userid){
    	$sql = "SELECT u1.email,u1.username,u2.lastname,u2.firstname FROM users u1 LEFT JOIN users_info u2 ON u2.userid=u1.id WHERE u1.id = $userid";

    	//print $sql;
    	$data = $this->query($sql);
    	return $data;

    }

    function updatefullname($lastname,$firstname,$userid){
    	$sql = "UPDATE users_info SET lastname = '$lastname' , firstname = '$firstname' WHERE userid = $userid";
    	//print $sql;
    	$data = $this->query($sql);
    	return $data;
    }

    function updateemail($email,$userid){
    	$sql = "UPDATE users SET email = '$email'  WHERE id = $userid";
    	//print $sql;
    	$data = $this->query($sql);
    	return $data;
    }

    function updateNewmail($email,$userid,$new_key){
    	$sql = "UPDATE users SET new_email = '$email',new_email_key='$new_key'  WHERE id = $userid";
    	//print $sql;
    	$data = $this->query($sql);
    	return $data;

    }

    function updatenewpassword($password,$userid){
    	$sql = "UPDATE users SET password = '$password'  WHERE id = $userid";
    	//print $sql;
    	$data = $this->query($sql);
    	return $data;
    }

    function loginuser($uid){

    	$sql = "SELECT * FROM users WHERE id=$uid";

    	//print $sql;
    	$data = $this->query($sql);
    	return $data;
    }

    function selectPassmail($uid){
    	$sql = "SELECT * FROM users WHERE (username = '$uid' OR email = '$uid') AND activated = 1";
    	//print $sql;
    	$data = $this->query($sql);
    	return $data;
    }

    function setPassKey($uid,$new_pass_key,$new_password_requested){
    	$sql = "UPDATE users SET new_password_key = '$new_pass_key',new_password_requested='$new_password_requested'  WHERE id = $uid";
    	//print $sql;
    	$data = $this->query($sql);
    	return $data;
    }

    function setPassReset($uid,$new_pass_key,$password,$expire_period=900){
    	$sql = "UPDATE users SET password = '$password', new_password_key = NULL, new_password_requested = NULL WHERE id = $uid AND new_password_key = '$new_pass_key' AND unix_timestamp(new_password_requested)>(unix_timestamp(now())-$expire_period)";

    	//print $sql;
    	$data = $this->query($sql);
    	return $data;
    }

    function selectPassUser($uid,$new_pass_key){
    	$sql = "SELECT * FROM users WHERE id=$uid AND new_password_key = '$new_pass_key' AND activated=1";
    	$data = $this->query($sql);
    	return $data;

    }

    function setPassResettime($uid,$new_pass_key,$expire_period=900){
    	$sql = "SELECT count(*) as cnt FROM users WHERE id=$uid AND new_password_key = '$new_pass_key' AND activated=1 AND unix_timestamp(new_password_requested)>(unix_timestamp(now())-$expire_period)";
    	//print $sql;
    	$data = $this->query($sql);
    	return $data[0]["cnt"];

    }

    function selectUser($uid){
    	$sql = "SELECT b.* FROM users a, users_info b WHERE a.username = b.username AND a.id = $uid";

    	//print $sql;
    	$data = $this->query($sql);
    	return $data;
    }


}

?>