<?php

// ----- ERRORS -----

// ----- HTTP MANAGEMENT --------------------

//Boolean, returns true if the request object has some GET querystrings
function request_has_GET_params($request){
	$hasGET = false;
	$params = $request->params();
	//If there's a non-numeric key, that's a GET param.
	foreach ($params as $k => $v){
		if (!is_numeric($k)){
			$hasGET = true;
			break;
		}
	}
	return $hasGET;
}

//Return an array of Accept types by preference
function parse_accept_header($header) {
	$accept = array();
	foreach (preg_split('/\s*,\s*/', $header) as $i => $term) {
		$o = new \stdclass;
		$o->pos = $i;
		if (preg_match(",^(\S+)\s*;\s*(?:q|level)=([0-9\.]+),i", $term, $M)) {
			$o->type = $M[1];
			$o->q = (double)$M[2];
		} else {
			$o->type = $term;
			$o->q = 1;
		}
		$accept[] = $o;
	} 
	usort($accept, function ($a, $b) {
		/* first tier: highest q factor wins */
		$diff = $b->q - $a->q;
		if ($diff > 0) { 
			$diff = 1;
		} else if ($diff < 0) {
			$diff = -1;
		} else {
			/* tie-breaker: first listed item wins */
			$diff = $a->pos - $b->pos;
		}
		return $diff;
	});
	$result = array();
	for ($i = 0; $i < count($accept); $i++){
		$result[$i] = $accept[$i]->type;
	}
	/*
	foreach ($accept as $a) {
		$foobar = $a->type;
		//echo "[$foobar]\n";
		$result[$a->type] = $a->type;
	}
	return $result;
	*/
	return $result;
}

// Return the first recognised content type in a simplified form, 
// i.e. 'markup' or 'json'
function response_type($request){
	$accepts = parse_accept_header($request->header("Accept"));

	foreach ($accepts as $a){
		$recognised = type_recognised($a);
		if ($recognised){
			return $recognised;
		}
	}
}

// Return a simplified form of a recognised content type, or nothing.
function type_recognised($type){
	switch ($type){
		case "text/html":
		case "application/xhtml+xml":
		case "application/xml":
			return "markup";
			break;
		case "application/json":
			return "json";
			break;	
		default:
			return "";
			break;
	}
}

// ----- Formatting strings --------------------

//Take away opening and closing quotes
function strip_quotes($aString){
	//echo "<pre>[$aString]";
	$aString = trim($aString);
	//Remove all quotes:
	$aString = str_replace(["\'","\""],"",$aString);
	return $aString;
}

function datify($date){
	$zone = new DateTimeZone('Europe/London');
	return is_string($date) ? new DateTime($date, $zone) : $date;
}

function timeOf($date){
	//'9:00am'
	$date = datify($date);
	$result = null;
	try {
		$result = $date->Format('g:ia');
		$result = $result == "12:00am" ? "Midnight" : $result;
	}
	catch (Exception $ex){
		return $ex;
	}
	return $result;
}

function dateOf($date){
	//'Tuesday 26th Mar'
	//WARN: If the program ever shows more than a year at once,
	//		these dates will be ambiguous in comparisons.
	$date = datify($date);
	$result = null;
	try {
		$result = $date->Format('l jS M');
	}
	catch (Exception $ex){
		return $ex;
	}
	return $result;
}

function lastMidnight($date){
	$midnight = clone datify($date);
	$midnight = $midnight->setTime(0, 0);
	return $midnight;
}

function strToBool($x){
	if (is_bool($x)){
		return $x;
	}
	elseif (is_int($x)){
		return $x == true;
	}
	else{
		return strtolower($x) == "true";
	}
}
/*
function is_quote_char($aChar){
	return $aChar == "'" || $aChar == "\"";
}
*/

// DEBUG

function whatsupwith($this){
	header("content-type: text/plain");
	var_dump($this);
}

?>