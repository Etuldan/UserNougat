<?
/*
UserNougat
https://github.com/Etuldan/UserNougat
*/
Class Template_Module {
	private $db_table_plugin_prefix = "";
	
	
	private $mysqli;
	
	function __construct($connector_sql) {
		$this->mysqli = $connector_sql;
	}
	
	public function createUser($id, $username, $displayname, $clean_password, $email) {
		return true;
	}
	
	public function updatePassword($id, $username, $displayname, $clean_password) {
		return true;
	}
	
	public function updateEmail($id, $username, $displayname, $email) {
		return true;
	}
	
	public function install() {
		return true;
	}
			
	public function setAsAdmin($id, $username, $displayname) {
		return true;
	}
}

$module = new Template_Module($mysqli);
?>