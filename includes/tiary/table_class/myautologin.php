<?php
class myautologin extends ipfDB{

	function create_autologin($user_id){
		$key = substr(md5(uniqid(rand())), 0, 32);
		$this->delete_auto($user_id);

		if ($this->insert_auto($user_id, $key)) {
			setcookie('autologin',serialize(array('user_id' => $user_id, 'key' => $key)),time()+60*60*24*31*2,'/');
			return TRUE;
		}
		return FALSE;
	}

	function delete_autologin(){
		if ($_COOKIE["autologin"]) {
			$data = unserialize($_COOKIE["autologin"]);
			$this->delete_cookie($data["user_id"], $data["key"]);
			setcookie('autologin','');
			//setcookie('autologin','','0','/','starty');
			//setcookie('autologin','','0','/','mystarty');
		}
	}

	function autologin(){

		if ($_COOKIE["autologin"]) {

			$data = unserialize($_COOKIE["autologin"]);

			if(isset($data['key']) AND isset($data['user_id'])) {
			$cookiedata = $this->select_cookie($data['user_id'], $data['key']);
				if (!is_null($cookiedata)) {

					$user_data = serialize(array(
								'user_id'	=> $cookiedata[0]["id"],
								'username'	=> $cookiedata[0]["username"],
								'email'     => $cookiedata[0]["email"],
								'status'	=> ($cookiedata[0]["activated"] == 1 ? '1' : '0')
						));

						$ip = getenv("REMOTE_ADDR");
						$sessid = '';
						while (strlen($sessid) < 32)
						{
							$sessid .= mt_rand(0, mt_getrandmax());
						}
						$session_id = md5(uniqid($sessid, TRUE));

						$user_agent =  $_SERVER["HTTP_USER_AGENT"];

						$now = time();
						$time = mktime(gmdate("H", $now), gmdate("i", $now), gmdate("s", $now), gmdate("m", $now), gmdate("d", $now), gmdate("Y", $now));

						$aryCookie = unserialize($_COOKIE['ci_session']);
						$this->clearsession($aryCookie['session_id']);
						setcookie('ci_session','',time()-1,'/');

						$sessiondata = $this->creatsession($session_id,$ip,$user_agent,$time,$user_data);
						$ci_session_data = serialize(array(
												'session_id'	=> $session_id,
												'ip_address'	=> $_SERVER['REMOTE_ADDR'],
												'user_agent'     => $user_agent,
												'last_activity'	=> $time
						));
						setcookie('autologin',serialize(array('user_id' => $data['user_id'], 'key' => $data['key'])),time()+60*60*24*31*2,'/');

						//setcookie('ci_session',$ci_session_data,0,'/');
						//print_r($_COOKIE['ci_session']);
						$this->update_user($data['user_id']);
					return $session_id;
				}
			}
		}
		return FALSE;
	}

	function delete_auto($user_id){
		$user_agent =  $_SERVER["HTTP_USER_AGENT"];
		$last_ip = $_SERVER['REMOTE_ADDR'];
		$sql = "DELETE FROM user_autologin WHERE user_id=$user_id AND user_agent='$user_agent' AND last_ip ='$last_ip'";
		//print $sql;
		$data = $this->query($sql);
		return $data;
	}

	function delete_cookie($user_id,$key){
		$sql = "DELETE FROM user_autologin WHERE user_id=$user_id AND key_id='$key'";
		//print $sql;
		$data = $this->query($sql, 1);
		return $data;
	}

	function insert_auto($user_id,$key){
		$user_agent =  $_SERVER["HTTP_USER_AGENT"];
		$last_ip = $_SERVER['REMOTE_ADDR'];
		$lasttime = date("Y-m-d H:i:s");
		$sql = "INSERT INTO user_autologin (key_id,user_id,user_agent,last_ip,last_login) VALUES('$key',$user_id,'$user_agent','$last_ip','$lasttime')";
		//print $sql;
		$data = $this->query($sql,1);
		return $data;
	}

	function select_cookie($user_id, $key){
		$sql = "SELECT u.id,u.username,u.email,u.activated FROM users u LEFT JOIN user_autologin ua ON ua.user_id=u.id LEFT JOIN users_info ui ON ui.userid=u.id WHERE ua.user_id=$user_id AND ua.key_id='$key' AND u.activated=1";
		//print $sql;
		$data = $this->query($sql);

		if (count($data) == 1)  return $data;
		return NULL;
	}

	function clearsession($session_id){
		//$sql = "UPDATE ci_sessions SET user_data = null WHERE session_id = '$session_id'";
		$sql = "DELETE FROM ci_sessions WHERE session_id= '$session_id'";
		$data = $this->query($sql, 1);
		return $data;
	}

	function creatsession($session_id,$ip_address,$user_agent,$last_activity,$user_data){
		$sql = "INSERT INTO ci_sessions (session_id,ip_address,user_agent,last_activity,user_data) VALUES('$session_id','$ip_address,','$user_agent',$last_activity,'$user_data') ";
		$data = $this->query($sql, 1);
		return $data;
	}

	function update_user($user_id){
		$last_ip = $_SERVER['REMOTE_ADDR'];
		$lasttime = date("Y-m-d H:i:s");
		$sql = "UPDATE users SET last_ip = '$last_ip',last_login='$lasttime' WHERE id = $user_id";
		$data = $this->query($sql, 1);
		return $data;
	}

}
?>