<?php

require_once "../db/dbConnect.php";

header('Content-type: application/json');

function getJobsPageURLByCompany($companies){
    global $pdo;

    try {
        $query = "SELECT jobs_page_url FROM companies WHERE name = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute(array($companies));
        if ($row = $stmt->fetch()){
            return $row["jobs_page_url"];
        }
    } catch (PDOException $e) {
        print 'PDOException failed: ' . $e->getMessage();
        exit;
    }
}

function getOpenPositionsByURL($jobs_page_url){
    $curl = curl_init();
    $user_agent = 'Mozilla/5.0 (Windows NT 6.1; rv:8.0) Gecko/20100101 Firefox/8.0';
    curl_setopt_array($curl, array(
        CURLOPT_URL => $jobs_page_url, //set request url
        CURLOPT_CUSTOMREQUEST  => "GET",        //set request type post or get
        CURLOPT_POST           => false,        //set to GET
        CURLOPT_USERAGENT      => $user_agent, //set user agent
        CURLOPT_RETURNTRANSFER => true,     // return web page
        CURLOPT_HEADER         => false,    // don't return headers
        CURLOPT_FOLLOWLOCATION => true,     // follow redirects
        CURLOPT_ENCODING       => "",       // handle all encodings
        CURLOPT_AUTOREFERER    => true,     // set referer on redirect
        CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
        CURLOPT_TIMEOUT        => 120,      // timeout on response
        CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
    ));

    $result = curl_exec($curl);
    curl_close($curl);

    if ($result){
        echo "result: $result";
    }
}

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

$jobs_page_url = getJobsPageURLByCompany($companies);

$open_positions = getOpenPositionsByURL($jobs_page_url);

?>