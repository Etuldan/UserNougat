<?
/*
UserNougat
https://github.com/griffin0/UserNougat
*/
Class Template_Module {
	private $db_table_plugin_prefix = "";
	
	
	private $mysqli;
	
	function __construct($connector_sql) {
		$this->mysqli = $connector_sql;
	}
	
	public function createUser($id, $username, $displayname, $clean_password, $email) {
	}
	
	public function updatePassword($id, $username, $clean_password) {
	}
	
	public function updateEmail($id, $username, $email) {
	}
	
	public function install() {
	}
			
	public function setAsAdmin($id, $username) {
	}
}

$module = new Template_Module($mysqli);
?>