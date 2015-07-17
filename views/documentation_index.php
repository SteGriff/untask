<?php

//Set it to text file.
header("content-type: text/plain");

//Include the documentation file itself
include ("docfile.txt");

//Build an error information object
$EM = new errorModel();

//Print 'em all out
echo "\n\n## ERRORS\n";
$EM->TabulateAll();

?>