<?php

//	notes DAL

//Find ALL the notes! (For current logged-in user)
function notes($db){
	return notes_for_current_user($db);
}

//Looks up a note name in the db and returns false, or an array of note.
//There should probably only be one note with that name, but it's not a unique ID.
function notes_for_current_user($db){
	$uid = user_ID($db);
	$result = $db->query( SQL_notes_for_user($uid) );
	switch ($result->num_rows){
		case 0:
			return false;
			break;
		default:
			$notes = array();
			while ($obj = $result->fetch_object()){
				$notes[] = $obj;
			}
			return $notes;
	}
}
function SQL_notes_for_user($uid){
	global $NoteTable;
	$SQL = "Select * from $NoteTable
			Where UserID = $uid";
	return $SQL;
}

//Lookup a note uniquely
function note_from_id($id, $db){
	$result = $db->query( SQL_note_from_id($id) );
	switch ($result->num_rows){
		case 1:
			$note = $result->fetch_object();
			return $note;
			break;
		default:
			//Error if there's anything other than ONE result
			return false;
	}
}
function SQL_note_from_id($id){
	global $NoteTable;
	$SQL = "Select * from $NoteTable
			Where ID = '$id'";
	return $SQL;
}

function create_note($content, $db){
	$userID = user_ID($db);
	$result = $db->query(SQL_create_note($userID, $content, $db));
	if ($result){
		$id = $db->insert_id;
		return $id;
	}
	else return false;
}
function SQL_create_note($userId, $content, $db){
	
	global $NoteTable;

	$UserID = sqlInt($userId, $db);
	$Content = sqlNote($content, $db);

	$SQL = "insert into $NoteTable(ParentID, UserID, Content)
			values(null, $UserID, '$Content');";
	//die($SQL);
	return $SQL;
}

function Delete_note($ID, $db){
	$result = $db->query( SQL_delete_note($ID) );
	if ($result){
		return true;
	}
	else return false;
}
function SQL_delete_note($ID){
	global $NoteTable;
	$SQL = "delete from $NoteTable where ID=$ID";
	return $SQL;
}

?>