<?
/*
UserNougat
https://github.com/griffin0/UserNougat
*/
Class MediaWiki_Module {
	private $db_table_plugin_prefix = "wiki_";
	
	
	private $mysqli;
	
	function __construct($connector_sql) {
		$this->mysqli = $connector_sql;
	}
	
	public function createUser($id, $username, $displayname, $clean_password, $email) {
	}
	
	public function updatePassword($id, $username, $clean_password) {
		$salt = $this->getRandom();
		$stmt = $this->mysqli->prepare("UPDATE ".$this->db_table_plugin_prefix."user SET
			user_password = CONCAT(':B:?:', MD5(CONCAT('?-', MD5('?')))) 
			WHERE user_id = '?'
			");
		$stmt->bind_param("ssss", $salt, $salt, $clean_password, $id);
		$stmt->execute();
		$stmt->close();
	}
	
	public function updateEmail($id, $username, $email) {
	}
	
	public function install() {
	}
			
	public function setAsAdmin($id, $username) {
	}
	
	private function getRandom() {
		$random = '';
		$count = 6;
		if (($fh = @fopen('/dev/urandom', 'rb'))) {
			$random = fread($fh, $count);
			fclose($fh);
		}	
		if (strlen($random) < $count) {
			$random = '';	
			$random = $this->_plugin_get_random_bytes($count);
		}
		return $random;
	}
}

$module = new MediaWiki_Module($mysqli);
?>