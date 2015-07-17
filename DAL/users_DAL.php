<?php

//	users DAL

function user_can_login($username, $password, $db){
	$result = $db->query( SQL_credentials_for_user($username, $db) );
	switch ($result->num_rows){
		case 0:
			//No such user
			return false;
			break;
		case 1:
			$user = $result->fetch_object();
			$storedPassword = $user->Password;
			if (crypt($password, $storedPassword) == $storedPassword) {
				return $user->ID;
			}
			else{
				return false;
			}
			break;
		default:
			//Too many users with that name or it's broken or whatever
			return false;
			break;
	}
}
function SQL_credentials_for_user($username, $db){
	global $UserTable;
	$cleanUsername = sqlString($username, $db);
	$SQL = "select * from $UserTable
			where Username='$cleanUsername'";
	//die($SQL);
	return $SQL;
}

function create_user($username, $password, $db){
	$result = $db->query( SQL_create_user( $username, $password, $db) );
	if ($result){
		$id = $db->insert_id;
		return $id;
	}
	else return false;
}
function SQL_create_user($username, $password, $db){
	global $UserTable;
	$un = sqlUser($username, $db);
	$pw = crypt($password);

	$SQL = "insert into $UserTable(Username, Password)
			values('$un', '$pw');";
	return $SQL;
}

function user_exists($name, $db){
	$result = $db->query( SQL_user_from_name($name, $db) );
	switch ($result->num_rows){
		case 0:
			return false;
			break;
		default:
			return true;
			break;
	}
}
function user_from_name($name, $db){
	$result = $db->query( SQL_user_from_name($name, $db) );
	switch ($result->num_rows){
		case 0:
			return false;
			break;
		default:
			$users = array();
			while ($obj = $result->fetch_object()){
				$users[] = $obj;
			}
			return $users;
	}
}
function SQL_user_from_name($name, $db){
	global $UserTable;
	$username=sqlUser($name, $db);
	$SQL = "Select * from $UserTable
			Where Username = '$username'";
	return $SQL;
}

?>