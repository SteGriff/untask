<?php

function note_index($request, $response, $app){
	
	global $HTTP_GET;
	global $HTTP_POST;
	
	//Short db instance
	$db = $app->db;
	
	//Cache the HTTP Verb
	$verb = $request->method();
	
	// View a notes query (GET)
	// Create a new note (POST)
	switch ($verb){
	
		case $HTTP_GET:
			//Put all notes in the viewmodel
			$response->notes = notes($db);
			
			//View 'em.
			view_notes($request, $response);
			break;
			
		case $HTTP_POST:
			$content = $request->param("Content");

			$createdID = create_note($content, $db);

			if ($createdID){
				$createdResource = "$FQDN/note/$createdID";
				echo $createdID;
				header("Location: $createdResource", true, 201); // 201 CREATED
			}
			else{	
				echo $db->error;
				header("HTTP/1.1 400 Bad Request [$e]");
			}
			break;
			
		default:
	}
}

function notes_resource($request, $response, $app){

	global $HTTP_GET;
	global $HTTP_POST;
	global $HTTP_PUT;
	global $HTTP_DELETE;

	//Short db instance
	$db = $app->db;
	$em = $app->errors;

	//Get the id requested
	$id = $request->id;
	
	//Lookup the note in the db and put them in the viewmodel
	$note = note_from_id($id, $db);
	$theirTimeslots = Timeslots_for_note($request->id, $db);
	
	$response->notes = $note;
	$response->timeslots = $theirTimeslots;
	
	//Cache the HTTP Verb
	$verb = $request->method();
	
	switch ($verb){
		case $HTTP_GET:
			view_notes($request, $response);
			break;
		case $HTTP_POST:
		
			break;
		default:
			echo "Unknown http verb, [$verb]";
	}
	
}

//Render a view of notes/note, using given content type.
function view_notes($request, $response){
	global $NOTES;
	view_entities($NOTES, $request, $response);
}

function note_delete($request, $response, $app){

	$db = $app->db;
	
	$passedNoteID = $request->id;
	$areYouSure = strToBool( $request->param("Sure") );
	if ($areYouSure){
		$status = Delete_note($passedNoteID, $db);
		if ($status){
			header("ID: $passedNoteID");
			header("HTTP/1.1 204 Deleted note");
		}
		else{
			echo $db->error;
			header("HTTP/1.1 500 I screwed up");
		}
	}
	else{
		echo "NO";
		header("HTTP/1.1 400 Deletion not confirmed by flag");
	}
}

?>