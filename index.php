<?php
// Klein is our routing library. https://github.com/chriso/klein.php
	require 'klein.php';

//Data access layer and extensional functions
	require 'DAL/DAL.php';
	require 'Extensions.php';
	require 'db.php';
	
//Controllers
	require 'controllers/controller.php';
	
//TODO should probably tidy up some of these

//HTTP Verbs
	$HTTP_GET = "GET";			// Get a resource/resources
	$HTTP_POST = "POST";		// Create a resource
	$HTTP_PATCH = "PATCH";		// Nothing uses this
	$HTTP_PUT = "PUT";			// Some updates
	$HTTP_DELETE = "DELETE";	// Delete	
	
//Data types
	$NOTES = "notes";
	$USERS = "users";
	
//Install path compared to domain root (or blank if in root)
	//Used to locate page resources 
	$PATH = '';
	//Used for the createdResource address on 201s
	$FQDN = 'http://untask.me.uk'; 
	//Used as the domain on cookies
	$DOMAIN = 'untask.me.uk';
	
	//Used in <title>s and stuff. Probably.
	$PRODUCT = 'Untask';
	
// SET UP LAZY APP OBJECTS TO USE THROUGHOUT
//		Database instance - Get it with $app->db

	respond(function ($request, $response, $app) {
		$app->register('db', function() {
			return db_instance();
		});
	});

// Structure notes:
// Closing slash is made optional with '~/?' in respond() definitions

// ----- HOME PAGE ---------------

respond('GET', "$PATH/?", function($request, $response){
	$response->render("views/home.htm");
});

// ----- DOCUMENTATION ---------------

//docs_index view gets the docfile.txt and appends a list of error code and meanings :)
respond('GET', "$PATH/docs/?", function ($request, $response) {
	$response->render("views/documentation_index.php");
});

respond("$PATH/error/?", function ($request, $response) {
	$response->render("views/error.php");
}); 

// ----- LOGIN -----

respond("$PATH/login/?",  user_login);
respond("$PATH/logout/?", user_logout);

// ----- REGISTER -----
// Just a view for registering
respond("$PATH/register/?", function ($request, $response){
	$response->render("views/register_view.php");
});

respond("$PATH/registered/?", function ($request, $response){
	$response->message = "Success! Now you can log in.";
	$response->render("views/login_view.php");
});

// ----- USER CONTROLLER -----
// POST to create a user

with("$PATH/user", function() {

	respond('POST', '/?', user_create);

});

// ----- NOTE CONTROLLER ---------------

with("$PATH/note", function() {

	//Everything goes through the login gate to check auth
	respond('*', user_login);

	// Find these functions in controllers/note_controller.php
	
	// Index View
	// todolist.net/note/
	respond('/?', note_index);
	
	//One person resource. Can do verbs on them.
	//respond('/[i:id]/?', note_resource);
	
	//TODO Replace with REST!
	respond('/[i:id]/delete/?', note_delete);

});

// ----- DEBUG STUFF --------------------

respond('GET', "$PATH/my-accept-header/?", function ($request, $response){
	$x = parse_accept_header($request->header("Accept"));
	header("content-type: text/plain");
	echo "Your accept header, from best to worst preference:\n\n";
	var_dump($x);
	//echo $x[0];
});

respond("$PATH/my-http-method", function($request, $response){
	header("content-type: text/plain");
	var_dump($request->method());
});

respond("$PATH/is-young-person", function($request, $response, $app){
	header("content-type: text/plain");
	$response->db = $app->db;
	$response->render("tests/age.php");
});


dispatch(); ?>