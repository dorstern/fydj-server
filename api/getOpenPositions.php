<?php

require_once "../db/dbConnect.php";
include_once "simple_html_dom.php";
include_once "company.php";
include_once "utils.php";

header("Content-Type: application/json");

function getCompaniesData($companies){
    global $pdo;
    $companiesData = array();

    try {
        $query = "SELECT * FROM companies WHERE name IN (?)";
        $stmt = $pdo->prepare($query);
        $stmt->execute(array($companies));
        if ($row = $stmt->fetch()){
            $companyData = new Company($row["job_page_url"], $row["job_dom_wrapper"], $row["job_dom_title"], 
                $row["job_dom_location"], $row["job_dom_description"]);
            $companiesData[] = $companyData;
        }
    } catch (PDOException $e) {
        print 'PDOException failed: ' . $e->getMessage();
        exit;
    }

    return $companiesData;
}

function getOpenPositionsByURL($companiesData){
    $companiesData = $companiesData[0];
    $openPositions = array();
    $curl = curl_init();
    $user_agent = "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/533.4 (KHTML, like Gecko) Chrome/5.0.375.125 Safari/533.4";
    $curl_opt_dynamic_array = array(
        CURLOPT_URL => $companiesData->job_page_url,
        CURLOPT_REFERER => $companiesData->job_page_url,
        CURLOPT_USERAGENT => $user_agent,
    );
    $curl_opt_array = Utils::$CURL_OPT_ARRAY + $curl_opt_dynamic_array;
    curl_setopt_array($curl, $curl_opt_array);
    $html_str = curl_exec($curl);
    curl_close($curl);

    $html = str_get_html($html_str);

    foreach($html->find($companiesData->job_dom_wrapper) as $wrapper){
        foreach($wrapper->find($companiesData->job_dom_title) as $title){
            $title = $title->plaintext;
        }
        foreach($wrapper->find($companiesData->job_dom_location) as $location){
            $location = $location->plaintext;
        }
        foreach($wrapper->find($companiesData->job_dom_description) as $description){
            $description = $description->plaintext;
        }

        $openPosition = array(
            "title" => $title,
            "location" => $location,
            "description" => $description
        );
        $openPositions[] = $openPosition;
    }

    return $openPositions;
}

if (empty($_POST["companies"]) ){
    print "No companies has been sent";
    exit;
}
$companies = $_POST["companies"];

if (empty($_POST["position_title"])){
    print "No parameters has been sent";
    exit;
}
$positionTitle = $_POST["position_title"];

$companiesData = getCompaniesData($companies);

$openPositions = getOpenPositionsByURL($companiesData);

echo json_encode($openPositions);

?>
