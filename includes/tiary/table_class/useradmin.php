<?php
class useradmin extends ipfDB{

    function selectUserAdmin(){
        $sql = "SELECT a.*,b.* FROM users a LEFT JOIN users_info b ON b.userid = a.id ORDER BY a.id DESC";
        //print $sql;
        $data = $this->query($sql);
        return $data;
    }

    function selectUserActivated(){
    	$sql = "SELECT count(0) FROM users WHERE activated =1 ";
    	//print $sql;
    	$data = $this->query($sql);
    	return $data[0]["count"];
    }

    function selectPS($user_id){
    	if(!$user_id)
    	return array();
    	$sql = "SELECT a.*,b.projectname FROM stand_support a  LEFT JOIN project_info b ON a.ss_projectid = b.id WHERE a.ss_support_userid = $user_id";
        //print $sql;
        $data = $this->query($sql);
        return $data;
    }
}

?>