<?php
class startyadmin extends ipfDB{
	function selecttable($tablename){
		$sql = "select tablename from pg_tables where tablename = '$tablename'";
		$data = $this->query($sql);
		return $data;
	}
    function selectkey($sday,$eday="",$uid=""){
    	if($uid!=""){
    		$table = $this->selecttable("skeyword".$id);
    		if($table){
    			$sql = "SELECT COUNT(skey) ,skey FROM skeyword$uid WHERE addtime BETWEEN '$sday' AND '$eday' GROUP BY skey ORDER BY  COUNT(skey) DESC";
    		}else{
    			return;
    		}
    	}else{
    		$sql = "SELECT COUNT(skey) ,skey FROM skeyword_guest WHERE addtime BETWEEN '$sday' AND '$eday' GROUP BY skey ORDER BY  COUNT(skey) DESC";
    	}
        //print $sql;
        $data = $this->query($sql);
        return $data;
    }

}

?>