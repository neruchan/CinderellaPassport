<?php


class starty_fb extends ipfDB{
	function selectNextID(){
		$sql = "SELECT id FROM users u ORDER BY id DESC";

		//print $sql;
		$data = $this->query($sql);
		return $data[0]['id']+1;
	}

    function fbselect($username){

        $sql = "SELECT u.id,u.username,u.email,u.activated,ui.userpic FROM users u LEFT JOIN users_info ui ON ui.userid=u.id WHERE u.username = '$username' ";

        //print $sql;
        $data = $this->query($sql);
        return $data;
    }

}




// class starty_fb extends ipfDB{
// 	function selectNextID(){
// 		$sql = "select nextval('users_id_seq')";
// 
// 		//print $sql;
// 		$data = $this->query($sql);
// 		return $data[0]['nextval'];
// 	}
// 
//     function fbselect($username){
// 
//         $sql = "SELECT u.id,u.username,u.email,u.activated,ui.userpic,ui.lastname,ui.firstname FROM users u LEFT JOIN users_info ui ON ui.userid=u.id WHERE u.username = '$username' ";
// 
//         //print $sql;
//         $data = $this->query($sql);
//         return $data;
//     }
// 
// }

?>