<?php

	require 'notes_controller.php';
	require 'users_controller.php';
	require 'login_controller.php';
	
	// ----- Generics ---------------
	
	//Render a view of entities (people, timeslots, etc.) using content-type from request
	function view_entities($type, $request, $response){
		//Choose the view by accept-header, then render it!
		switch (response_type($request)){
			case "json":
				render_json_view($type, $request, $response);
				//$response->render("views/" . $type  . "_index_json.php"); //Deprecated JSON view style
				break;
			default:
				//HTML, other markup, catch all...
				$response->render("views/" . $type  . "_index.php");
				break;
		}
	}

	function render_json_view($type, $request, $response){
	
		$stuff = null;
		global $NOTES;
		
		switch ($type){
			case $NOTES:
				$stuff = $response->notes;
			default:
		}
		
		$result = "";
		if ($stuff){
			$result = array(
				"status" => "OK",
				$type => $stuff
			);
		}
		else{
			//Print attached error if it exists
			$result = array("status" => $response->error);
		}
		header("content-type: application/json");
		echo json_encode($result);
	}

?>