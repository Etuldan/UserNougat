<?php
/*
UserNougat
https://github.com/Etuldan/UserNougat
*/

//Database Information
$db_host = ""; //Host address (most likely localhost)
$db_name = ""; //Name of Database
$db_user = ""; //Name of database user
$db_pass = ""; //Password for database user
$db_table_prefix = "un_";

GLOBAL $errors;
GLOBAL $successes;

$errors = array();
$successes = array();

/* Create a new mysqli object with database connection parameters */
$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);
GLOBAL $mysqli;

if(mysqli_connect_errno()) {
	echo "Connection Failed: " . mysqli_connect_errno();
	exit();
}

//Direct to install directory, if it exists
if(is_dir("install/"))
{
	header("Location: install/");
	die();

}

?>