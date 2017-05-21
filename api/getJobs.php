<?php
require_once "../db/dbConnect.php";

header('Content-type: application/json');

global $pdo;

if (empty($_POST["companies"]) ){
    print "No parameters has been sent";
    exit;
}
$companies = $_POST["companies"];

if (empty($_POST["jobtitle"])){
    print "No parameters has been sent";
    exit;
}
$jobTitle = $_POST["jobtitle"];

try {
    $query = "SELECT jobs_page_url FROM companies WHERE name = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute(array($companies));
    if ($row = $stmt->fetch()){
        echo $row["jobs_page_url"];
    }
} catch (PDOException $e) {
    echo 'PDOException failed: ' . $e->getMessage();
}


?>