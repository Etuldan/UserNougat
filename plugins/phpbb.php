<?
/*
UserNougat
https://github.com/Etuldan/UserNougat
*/
Class PhpBB_Module {
	private $db_table_plugin_prefix = "phpbb_";
	
	
	private $mysqli;
	
	function __construct($connector_sql) {
		$this->mysqli = $connector_sql;
	}
	
	public function setAsAdmin($id, $username, $displayname) {
		//Set user as Founder and default group Administrator
		if($stmt = $this->mysqli->prepare("UPDATE ".$this->db_table_plugin_prefix."users
				SET
				user_type = '3',
				group_id = '5'
				WHERE
				user_id = ?")) {
			$stmt->bind_param("i", $id);
			$stmt->execute();
			$stmt->close();
		} else {
			return false;
		}
		
		//Set groups for user to Administrator and GlobalModerators (as group leader)
		if($stmt = $this->mysqli->prepare("INSERT INTO ".$this->db_table_plugin_prefix."user_group (
				group_id,
				user_id,
				group_leader,
				user_pending
				)
				VALUES (
				4,
				?,
				1,
				0
				)")) {
			$stmt->bind_param("i", $id);
			$stmt->execute();
			$stmt->close();
		} else {
			return false;
		}
		if($stmt = $this->mysqli->prepare("INSERT INTO ".$this->db_table_plugin_prefix."user_group (
				group_id,
				user_id,
				group_leader,
				user_pending
				)
				VALUES (
				5,
				?,
				1,
				0
				)")) {
			$stmt->bind_param("i", $id);
			$stmt->execute();
			$stmt->close();
		} else {
			return false;
		}
		//Set user as group leader for Registered
		if($stmt = $this->mysqli->prepare("UPDATE ".$this->db_table_plugin_prefix."user_group
				SET
				group_leader = 1
				WHERE
				group_id = 2 AND
				user_id = ?")) {
			$stmt->bind_param("i", $id);
			$stmt->execute();
			$stmt->close();
		} else {
			return false;
		}
		
		return true;
	}
	
	public function createUser($id, $username, $displayname, $clean_password, $email) {
		//Insert the user into the PhpBB.
		if($stmt = $this->mysqli->prepare("INSERT INTO ".$this->db_table_plugin_prefix."users (
				user_id,
				username,
				username_clean,
				user_password,
				user_email,
				group_id,
				user_regdate
				)
				VALUES (
				?,
				?,
				?,
				?,
				?,
				2,
				'".time()."'
				)")) {
			$stmt->bind_param("sssss", $id, $displayname, $username, $this->_plugin_hash($clean_password), $email);
			$stmt->execute();
			$stmt->close();
		} else {
			return false;
		}
		//Set group as Registered
		if($stmt = $this->mysqli->prepare("INSERT INTO ".$this->db_table_plugin_prefix."user_group (
				group_id,
				user_id,
				group_leader,
				user_pending
				)
				VALUES (
				2,
				?,
				0,
				0
				)")) {				
			$stmt->bind_param("s", $id);
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
				user_password = ?
				WHERE
				user_id = ?")) {
			$stmt->bind_param("si", $this->_plugin_hash($clean_password), $id);
			$stmt->execute();
			$stmt->close();	
		} else {
			return false;
		}
		return true;
	}
	
	public function updateEmail($id, $username, $displayname, $email) {
		if($stmt = $this->mysqli->prepare("UPDATE ".$this->db_table_plugin_prefix."users
				SET
				user_email = ?
				WHERE
				user_id = ?")) {
			$stmt->bind_param("si", $email, $id);
			$stmt->execute();
			$stmt->close();	
		} else {
			return false;
		}
	}
	
	public function install() {
		// Disallow registration on PhpBB
		if($stmt = $this->mysqli->prepare("UPDATE ".$this->db_table_plugin_prefix."config
				SET
				config_value = '3'
				WHERE
				config_name = 'require_activation'")) {
			$stmt->execute();
			$stmt->close();	
		} else {
			return false;
		}
				
		// Forbid email change on PhpBB profile
		if($stmt = $this->mysqli->prepare("DELETE FROM ".$this->db_table_plugin_prefix."acl_groups WHERE
				group_id = 2 AND
				forum_id = 0 AND
				auth_option_id = 89 AND
				auth_role_id = 0")) {
			$stmt->execute();
			$stmt->close();	
		} else {
			return false;
		}
		if($stmt = $this->mysqli->prepare("INSERT INTO ".$this->db_table_plugin_prefix."acl_groups (
				group_id,
				forum_id,
				auth_option_id,
				auth_role_id,
				auth_setting 
				) VALUES (
				2,
				0,
				89,
				0,
				0
				)")) {
			$stmt->execute();
			$stmt->close();	
		} else {
			return false;
		}
		// Forbid username change on PhpBB profile
		if($stmt = $this->mysqli->prepare("DELETE FROM ".$this->db_table_plugin_prefix."acl_groups WHERE
				group_id = 2 AND
				forum_id = 0 AND
				auth_option_id = 91 AND
				auth_role_id = 0")) {
			$stmt->execute();
			$stmt->close();	
		} else {
			return false;
		}
		if($stmt = $this->mysqli->prepare("INSERT INTO ".$this->db_table_plugin_prefix."acl_groups (
				group_id,
				forum_id,
				auth_option_id,
				auth_role_id,
				auth_setting 
				) VALUES (
				2,
				0,
				91,
				0,
				0
				)")) {
			$stmt->execute();
			$stmt->close();	
		} else {
			return false;
		}
		// Forbid password change on PhpBB profile
		if($stmt = $this->mysqli->prepare("DELETE FROM ".$this->db_table_plugin_prefix."acl_groups WHERE
			group_id = 2 AND
			forum_id = 0 AND
			auth_option_id = 92 AND
			auth_role_id = 0")) {
			$stmt->execute();
			$stmt->close();	
		} else {
			return false;
		}
		if($stmt = $this->mysqli->prepare("INSERT INTO ".$this->db_table_plugin_prefix."acl_groups (
				group_id,
				forum_id,
				auth_option_id,
				auth_role_id,
				auth_setting 
				) VALUES (
				2,
				0,
				92,
				0,
				0
				)")) {
			$stmt->execute();
			$stmt->close();	
		} else {
			return false;
		}
		
		return true;
	}
	
	/**
	*
	* @version Version 0.1 / slightly modified for phpBB 3.0.x (using $H$ as hash type identifier)
	*
	* Portable PHP password hashing framework.
	*
	* Written by Solar Designer <solar at openwall.com> in 2004-2006 and placed in
	* the public domain.
	*
	* There's absolutely no warranty.
	*
	* The homepage URL for this framework is:
	*
	*	http://www.openwall.com/phpass/
	*
	* Please be sure to update the Version line if you edit this file in any way.
	* It is suggested that you leave the main version number intact, but indicate
	* your project name (after the slash) and add your own revision information.
	*
	* Please do not change the "private" password hashing method implemented in
	* here, thereby making your hashes incompatible.  However, if you must, please
	* change the hash type identifier (the "$P$") to something different.
	*
	* Obviously, since this code is in the public domain, the above are not
	* requirements (there can be none), but merely suggestions.
	*
	*
	* Hash the password
	*/
	private function _plugin_hash($password)
	{
		$itoa64 = './0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
	
		//$random_state = unique_id();
		$random = '';
		$count = 6;
	
		if (($fh = @fopen('/dev/urandom', 'rb')))
		{
			$random = fread($fh, $count);
			fclose($fh);
		}
	
		if (strlen($random) < $count)
		{
			$random = '';
	
			$random = $this->_plugin_get_random_bytes($count);
			//for ($i = 0; $i < $count; $i += 16)
			//{
			//	$random_state = md5(unique_id() . $random_state);
			//	$random .= pack('H*', md5($random_state));
			//}
			//$random = substr($random, 0, $count);
		}
	
		$hash = $this->_plugin_hash_crypt_private($password, $this->_plugin_hash_gensalt_private($random, $itoa64), $itoa64);
	
		if (strlen($hash) == 34)
		{
			return $hash;
		}
	
		return md5($password);
	}
	
	
	/**
	* Generate salt for hash generation
	*/
	private function _plugin_hash_gensalt_private($input, &$itoa64, $iteration_count_log2 = 6)
	{
		if ($iteration_count_log2 < 4 || $iteration_count_log2 > 31)
		{
			$iteration_count_log2 = 8;
		}
	
		$output = '$H$';
		$output .= $itoa64[min($iteration_count_log2 + ((PHP_VERSION >= 5) ? 5 : 3), 30)];
		$output .= $this->_plugin_hash_encode64($input, 6, $itoa64);
	
		return $output;
	}
	
	/**
	* Encode hash
	*/
	private function _plugin_hash_encode64($input, $count, &$itoa64)
	{
		$output = '';
		$i = 0;
	
		do
		{
			$value = ord($input[$i++]);
			$output .= $itoa64[$value & 0x3f];
	
			if ($i < $count)
			{
				$value |= ord($input[$i]) << 8;
			}
	
			$output .= $itoa64[($value >> 6) & 0x3f];
	
			if ($i++ >= $count)
			{
				break;
			}
	
			if ($i < $count)
			{
				$value |= ord($input[$i]) << 16;
			}
	
			$output .= $itoa64[($value >> 12) & 0x3f];
	
			if ($i++ >= $count)
			{
				break;
			}
	
			$output .= $itoa64[($value >> 18) & 0x3f];
		}
		while ($i < $count);
	
		return $output;
	}
	
	/**
	* The crypt function/replacement
	*/
	private function _plugin_hash_crypt_private($password, $setting, &$itoa64)
	{
		$output = '*';
	
		// Check for correct hash
		if (substr($setting, 0, 3) != '$H$' && substr($setting, 0, 3) != '$P$')
		{
			return $output;
		}
	
		$count_log2 = strpos($itoa64, $setting[3]);
	
		if ($count_log2 < 7 || $count_log2 > 30)
		{
			return $output;
		}
	
		$count = 1 << $count_log2;
		$salt = substr($setting, 4, 8);
	
		if (strlen($salt) != 8)
		{
			return $output;
		}
	
		/**
		* We're kind of forced to use MD5 here since it's the only
		* cryptographic primitive available in all versions of PHP
		* currently in use.  To implement our own low-level crypto
		* in PHP would result in much worse performance and
		* consequently in lower iteration counts and hashes that are
		* quicker to crack (by non-PHP code).
		*/
		if (PHP_VERSION >= 5)
		{
			$hash = md5($salt . $password, true);
			do
			{
				$hash = md5($hash . $password, true);
			}
			while (--$count);
		}
		else
		{
			$hash = pack('H*', md5($salt . $password));
			do
			{
				$hash = pack('H*', md5($hash . $password));
			}
			while (--$count);
		}
	
		$output = substr($setting, 0, 12);
		$output .= $this->_plugin_hash_encode64($hash, 16, $itoa64);
	
		return $output;
	}
	
	private function _plugin_get_random_bytes($count)
	{
		$output = '';
		if (is_readable('/dev/urandom') &&
			($fh = @fopen('/dev/urandom', 'rb'))) {
			$output = fread($fh, $count);
			fclose($fh);
		}
	
		if (strlen($output) < $count) {
			$output = '';
			for ($i = 0; $i < $count; $i += 16) {
				$this->random_state =
					md5(microtime() . $this->random_state);
				$output .=
					pack('H*', md5($this->random_state));
			}
			$output = substr($output, 0, $count);
		}
	
		return $output;
	}
}

$module = new PhpBB_Module($mysqli);
?>