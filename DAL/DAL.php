<?php

// 'Data Access Layer' for Untask
// (SQL Helpers and stuff)

// Globals table names for reusable SQL
// 	From within a function, define 'global $PersonTable;' to make available
$NoteTable = 'didit_note';
$UserTable = 'didit_user';

$SQL_db_now= 'utc_timestamp()'; //The ONE TRUE TIME

// ----- Converters with anti-injection --------------------

function sqlBool($b, $db){
	$b = $db->real_escape_string($b);
	if ($b) return '1';
	else return '0';
}

function sqlString($s, $db){
	//Allow all alphanum, spaces, and characters for email and times, that's:
	// @ : _ - . 
	$result = preg_replace("/[^a-zA-Z0-9@: _\-\.]+/", "", $s);
	return trim($db->real_escape_string($result));
}

function sqlNote($s, $db){
	//Allow a set of characters in notes, including:
	// @ ! : _ - ? . , ' # /
	$result = preg_replace("/[^a-zA-Z0-9@!:& _\-\?\.\,\'\#\/]+/", "", $s);
	return trim($db->real_escape_string($result));
}
	
function sqlUser($s, $db){
	//Allow all alphanum, scores _ - and . for usernames
	$result = preg_replace("/[^a-zA-Z0-9_\-\.]+/", "", $s);
	return trim($db->real_escape_string($result));
}

function sqlInt($i, $db){
	return $db->real_escape_string((int)$i);
}

// ----- Query the database and provide objects or object arrays --------------------

require 'notes_DAL.php';
require 'users_DAL.php';

?>