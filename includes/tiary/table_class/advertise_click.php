<?php
class advertise_click extends ipfDB{
    function selectNextID(){
		$sql = "select nextval('album_id_seq')";

        //print $sql;
        $data = $this->query($sql);
        return $data[0]['nextval'];
    }

    function selectAds($kid){
		if(!$kid)
			return array();

        $sql = "SELECT * FROM starty_advertise WHERE keywordid = $kid ORDER BY createtime DESC LIMIT 1";

        //print $sql;
        $data = $this->query($sql);
        return $data;
    }

    function updateAds($sid){
    	$sql = "UPDATE starty_advertise SET clicknum = clicknum + 1 WHERE id = $sid";
    	//print $sql;
    	$data = $this->query($sql);
    	return $data;
    }

    function insertAds($kid){
    	$sql = "INSERT INTO starty_advertise (keywordid,createtime,clicknum) VALUES ($kid,'".date('Y-m-d')."',1)";
    	//print $sql;
    	$data = $this->query($sql);
    	return $data;
    }

    function selectkey($sday,$eday){
    	$sql = "SELECT keywordid FROM starty_advertise WHERE createtime BETWEEN '$sday' AND '$eday' GROUP BY keywordid ";
    	//print $sql;
    	$data = $this->query($sql);
    	return $data;
    }

    function selectkeynum($kid,$sday,$eday){
    	$sql = "SELECT sum(clicknum) FROM starty_advertise WHERE keywordid =$kid AND createtime BETWEEN '$sday' AND '$eday'";
    	//print $sql;
    	$data = $this->query($sql);
    	return $data;
    }
}
?>