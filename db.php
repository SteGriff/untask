<?php

function db_instance(){
	$sv = "db425111171.db.1and1.com";
	$un = "dbo425111171";
	$conn = new mysqli($sv, $un, "password", "db425111171");
	if ($conn->connect_error) die("Could not connect to database: " . $conn->connect_error);
	return $conn;
}

?>