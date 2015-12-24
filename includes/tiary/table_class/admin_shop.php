<?php
class admin_shop extends ipfDB{

   function CheckPassword($name,$pass){
    	if(!$name)
			return array();

    	$sql = "SELECT count(*)cnt, shop_id, id FROM admin_shop WHERE account='$name' AND passwords='$pass'; ";
		//     	print $sql;
		$data = $this->query($sql);
		return $data[0];
    }
	
	function getAdminShopDetailsById($id){
 

    	$sql = "SELECT * FROM admin_shop WHERE id = $id; ";
		//     	print $sql;
		$data = $this->query($sql);
		return $data[0];
    }
}

?>