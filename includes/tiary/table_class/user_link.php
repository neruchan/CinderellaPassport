<?php
class user_link extends ipfDB{

    function selectUserLink($userid){
		if(!$userid)
			return array();

        $sql = "SELECT * FROM user_link WHERE user_id = $userid";

        //print $sql;
        $data = $this->query($sql);
        return $data[0];
    }

}
