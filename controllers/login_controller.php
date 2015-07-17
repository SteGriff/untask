<?php

function user_login($request, $response, $app){

	//Check for logged-in-cookies
	if (isset($_COOKIE['didit-user']) && isset($_COOKIE['didit-password'])){
		tryLogin($response, $app, $_COOKIE['didit-user'], $_COOKIE['didit-password']);
	}
	else{
		
		//Log in from POSTed input
		
		$un = $_POST['username'];
		$pw = $_POST['password'];
		
		if (isset($un) && isset($pw)){
			tryLogin($response, $app, $un, $pw);
		}
		else{
			rejectUser($response, "Please log in");
		}
	}
	
	//If we came to the specific login controller, we need to redirect
	if (strpos($request->uri(), '/login') !== false){
		global $PATH;
		$response->redirect("$PATH/note/");
	}
	
	// Everything else that comes through here will hopefully render the correct view by itself.
	// Bye
}

function tryLogin($response, $app, $un, $pw){
	$db = $app->db;
	$successfulUserID = user_can_login($un, $pw, $db);
	if ($successfulUserID){
		setLoggedInCookies($response, $un, $pw);
		return $successfulUserID;
	}
	else{
		rejectUser($response, 'Incorrect login details');
	}
}

function setLoggedInCookies($response, $un, $pw){
	global $PATH, $DOMAIN;
	$expiry = time()+60*60*24*7; //1 week
	//Make cookies available across the whole application
	$response->cookie('didit-user', $un, $expiry);
	$response->cookie('didit-password', $pw, $expiry);
}

function rejectUser($response, $message, $loggingOut){
	global $PATH;
	if (!$loggingOut){
		header("HTTP/1.1 401 Please log in");
	}
	$response->message = $message;
	$response->render("views/login_view.php");
	exit;
}

function user_logout($request, $response){
	global $PATH, $DOMAIN;
	$expiry = time()-60; //Expire a minute ago
	$response->cookie('didit-user', null);
	$response->cookie('didit-password', null);
	rejectUser($response, 'You have logged out');
}

?>