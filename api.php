<?php

require_once "../db/dbConnect.php";

header('Content-type: application/json');

if (empty($_POST["function"])){
    print "No function";
    exit;
}
$function = $_POST["function"];

$params = "";
if (!empty($_POST["params"])){
    $params = $_POST["params"]; 
}

switch ($function){
    //
}


?>