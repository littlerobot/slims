<?php
    session_start();
    include_once("connect-database-write.php");
    include_once("../session.php");
    include_once("save-xml.php");
    
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
    
    header("Content-Type: text/xml");
    
    $xmldoc = createXmlDocument();
    
    $errorid = 'user-not-logged-in';
    
    if(!isValidUser()) {
	echoError($xmldoc,  $errorid, "User not logged in");
	die();
    }
    
    $key = 'basalmedia';
      
    $xmldoc = new DOMDocument("1.0");
    $results = $xmldoc->appendChild($xmldoc->createElement("results"));
    $success = $results->setAttribute("success", "true");
        
    if(!isset($_REQUEST[$key])) {
        echoError($xmldoc, $key, "basalmedia parameter not set");
        die();
    }
    
    $basalmedia = trim($_REQUEST[$key]);
    if(strlen($basalmedia) == 0) {
        echoError($xmldoc, $key, "basalmedia parameter is empty");
        die();
    }
    
    $resultset = null;
    try {
        $querystring = sprintf("SELECT b.BasalMedia
                                FROM BasalMedia b
                                WHERE b.BasalMedia LIKE '%s' LIMIT 1;", $basalmedia);
        $resultset = $dbh->query($querystring);
    } catch(PDOException $e) {
        echoError($xmldoc, "select-query-error", $e->getMessage());
        die();
    }
    if($resultset && $resultset->rowCount() != 0) {
        echoError($xmldoc, $key, "basal media with name '$basalmedia' already exists");
        die();
    }
    
    $querystring = sprintf("INSERT INTO BasalMedia (BasalMedia) VALUES ('%s');", $basalmedia);
    try {
        $resultset = $dbh->query($querystring);
    } catch(PDOException $e) {
        echoError($xmldoc, $key, $e->getMessage());
        die();
    }
    
    echo $xmldoc->saveXML();
    $dbh = null;
    
    
?>