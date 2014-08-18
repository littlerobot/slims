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
    
    $key = 'Tissue';
      
    $xmldoc = new DOMDocument("1.0");
    $results = $xmldoc->appendChild($xmldoc->createElement("results"));
    $success = $results->setAttribute("success", "true");
        
    if(!isset($_REQUEST[$key])) {
        echoError($xmldoc, $key, "tissue parameter not set");
        die();
    }
    
    $tissue = trim($_REQUEST[$key]);
    if(strlen($tissue) == 0) {
        echoError($xmldoc, $key, "tissue parameter is empty");
        die();
    }
    
    $resultset = null;
    try {
        $querystring = sprintf("SELECT m.Tissue
                                FROM Tissue m
                                WHERE m.Tissue LIKE '%s' LIMIT 1;", $tissue);
        $resultset = $dbh->query($querystring);
    } catch(PDOException $e) {
        echoError($xmldoc, "select-query-error", $e->getMessage());
        die();
    }
    if($resultset && $resultset->rowCount() != 0) {
        echoError($xmldoc, $key, "tissue with name '$tissue' already exists");
        die();
    }
    
    $querystring = sprintf("INSERT INTO Tissue (Tissue) VALUES ('%s');", $tissue);
    try {
        $resultset = $dbh->query($querystring);
    } catch(PDOException $e) {
        echoError($xmldoc, $key, $e->getMessage());
        die();
    }
    
    echo $xmldoc->saveXML();
    $dbh = null;
        
?>