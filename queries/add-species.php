<?php
    session_start();
    include_once("connect-database-write.php");
    include_once("../session.php");
    include_once("save-xml.php");
    
    header("Content-Type: text/xml");
    
    $xmldoc = createXmlDocument();
    
    $errorid = 'user-not-logged-in';
    
    if(!isValidUser()) {
	echoError($xmldoc,  $errorid, "User not logged in");
	die();
    }
    
    $key = 'Species';
      
    $xmldoc = new DOMDocument("1.0");
    $results = $xmldoc->appendChild($xmldoc->createElement("results"));
    $success = $results->setAttribute("success", "true");
        
    if(!isset($_REQUEST[$key])) {
        echoError($xmldoc, $key, "species parameter not set");
        die();
    }
    
    $species = trim($_REQUEST[$key]);
    if(strlen($species) == 0) {
        echoError($xmldoc, $key, "species parameter is empty");
        die();
    }
    
    $resultset = null;
    try {
        $querystring = sprintf("SELECT m.Species
                                FROM Species m
                                WHERE m.Species LIKE '%s' LIMIT 1;", $species);
        $resultset = $dbh->query($querystring);
    } catch(PDOException $e) {
        echoError($xmldoc, "select-query-error", $e->getMessage());
        die();
    }
    if($resultset && $resultset->rowCount() != 0) {
        echoError($xmldoc, $key, "species with name '$species' already exists");
        die();
    }
    
    $querystring = sprintf("INSERT INTO Species (Species) VALUES ('%s');", $species);
    try {
        $resultset = $dbh->query($querystring);
    } catch(PDOException $e) {
        echoError($xmldoc, $key, $e->getMessage());
        die();
    }
    
    echo $xmldoc->saveXML();
    $dbh = null;
        
?>