<?
/*
UserNougat
https://github.com/Etuldan/UserNougat
*/
Class Owncloud_Module {
	private $db_table_plugin_prefix = "oc_";
	private $currentVersion = 1;
    private $PASSWORD_DEFAULT = 1;
	
	private $mysqli;
	private $options = array();
	function __construct($connector_sql) {
		$this->mysqli = $connector_sql;
	}
	
	public function setAsAdmin($id, $username, $displayname) {
		if($stmt = $this->mysqli->prepare("INSERT INTO ".$this->db_table_plugin_prefix."group_user (
				gid,
				uid
				)
				VALUES (
				admin,
				?
				)")) {
			$stmt->bind_param("s", $username);
			$stmt->execute();
			$stmt->close();
		} else {
			return false;
		}
		return true;
	}
	
	public function createUser($id, $username, $displayname, $clean_password, $email) {
		if($stmt = $this->mysqli->prepare("INSERT INTO ".$this->db_table_plugin_prefix."users (
				uid,
				displayname,
				password
				)
				VALUES (
				?,
				?,
				?
				)")) {
			$stmt->bind_param("sss", $username, $displayname, $this->$this->hash($clean_password));
			$stmt->execute();
			$stmt->close();
		} else {
			return false;
		}
		
		if($stmt = $this->mysqli->prepare("INSERT INTO ".$this->db_table_plugin_prefix."preferences (
				userid,
				appid,
				configkey,
				configvalue
				)
				VALUES (
				?,
				'settings',
				'email',
				?
				)")) {
			$stmt->bind_param("ss", $username, $email);
			$stmt->execute();
			$stmt->close();
		} else {
			return false;
		}
		
		return true;
	}
	
	public function updatePassword($id, $username, $displayname, $clean_password) {
		if($stmt = $this->mysqli->prepare("UPDATE ".$this->db_table_plugin_prefix."users
				SET
				password = ?
				WHERE
				uid = ?")) {
			$stmt->bind_param("ss", $this->hash($clean_password), $displayname);
			$stmt->execute();
			$stmt->close();
		} else {
			return false;
		}
		return true;
	}
	
	public function updateEmail($id, $username, $displayname, $email) {
		if($stmt = $this->mysqli->prepare("UPDATE ".$this->db_table_plugin_prefix."preferences
				SET
				configvalue = ?
				WHERE
				userid = ? AND
				appid = 'settings' AND
				configkey = 'email")) {
			$stmt->bind_param("ss", $email, $displayname);
			$stmt->execute();
			$stmt->close();	
		} else {
			return false;
		}
		return true;
	}
	
	public function install() {
		return true;
	}
	
	
	/**
	 * Hashes a message using PHP's `password_hash` functionality.
	 * Please note that the size of the returned string is not guaranteed
	 * and can be up to 255 characters.
	 *
	 * @param string $message Message to generate hash from
	 * @return string Hash of the message with appended version parameter
	 */
	private function hash($message) {
		return $this->currentVersion . '|' . password_hash($message, $this->PASSWORD_DEFAULT, $this->options);
	}
	
}	

$module = new Owncloud_Module($mysqli);