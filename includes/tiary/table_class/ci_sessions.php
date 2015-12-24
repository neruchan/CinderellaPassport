<?php
class ci_sessions extends ipfDB{

    function selectLoginUserData($session_id){
        $sql = "SELECT user_data FROM ci_sessions WHERE session_id = '$session_id'";

        //print $sql;
        $data = $this->query($sql);
        return $data[0]['user_data'];
    }
}
