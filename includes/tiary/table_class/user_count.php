<?php
class user_count extends ipfDB{

    function userCount($uid,$uflag){
		if(!$uid)
			return array();
		$nowday = date('Y-m-d');
        $sql = "SELECT id FROM user_count WHERE userid=$uid AND userflag=$uflag AND to_char(usetime,'yyyy-mm-dd')='$nowday';";
        //print $sql;
        $userdata = $this->query($sql);
        if(count($userdata)>0){
        	$sql1 = "UPDATE user_count set usetime=CURRENT_TIMESTAMP WHERE id=".$userdata[0]['id'].";";
        	$usercupdate = $this->query($sql1);
        }else{
			$sql2 ="INSERT INTO user_count (userid,userflag,usetime) VALUES ($uid,$uflag,CURRENT_TIMESTAMP);";
			$usercinsert = $this->query($sql2);
        }
        return true;
    }
}


?>