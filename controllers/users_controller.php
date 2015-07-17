<?php

function user_ID($db){
	return user_can_login($_COOKIE['didit-user'], $_COOKIE['didit-password'], $db);
}

function user_create($request, $response, $app){
	$db = $app->db;
	
	$username = $request->param("Username");
	$password = $request->param("Password");
	$cpassword = $request->param("ConfirmPassword");
	
	if ($password != $cpassword){
		echo "Can't create account: Passwords don't match";
		header("HTTP/1.1 400 Bad Request");
		exit;
	}
	if (user_exists($username, $db)){
		echo "The username $username is taken, sorry pro.";
		header("HTTP/1.1 400 Bad Request");
		exit;
	}
	
	$createdID = create_user($username, $password, $db);

	if ($createdID){
		$createdResource = "$FQDN/user/$createdID";
		echo $createdID;
		header("Location: $createdResource", true, 201); // 201 CREATED
	}
	else{	
		echo $db->error;
		header("HTTP/1.1 500 Could not create user");
	}

}

?>