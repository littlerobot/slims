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
    
    $key = 'Morphology';
      
    $xmldoc = new DOMDocument("1.0");
    $results = $xmldoc->appendChild($xmldoc->createElement("results"));
    $success = $results->setAttribute("success", "true");
        
    if(!isset($_REQUEST[$key])) {
        echoError($xmldoc, $key, "morphology parameter not set");
        die();
    }
    
    $morphology = trim($_REQUEST[$key]);
    if(strlen($morphology) == 0) {
        echoError($xmldoc, $key, "morphology parameter is empty");
        die();
    }
    
    $resultset = null;
    try {
        $querystring = sprintf("SELECT m.Morphology
                                FROM Morphology m
                                WHERE m.Morphology LIKE '%s' LIMIT 1;", $morphology);
        $resultset = $dbh->query($querystring);
    } catch(PDOException $e) {
        echoError($xmldoc, "select-query-error", $e->getMessage());
        die();
    }
    if($resultset && $resultset->rowCount() != 0) {
        echoError($xmldoc, $key, "morphology with name '$morphology' already exists");
        die();
    }
    
    $querystring = sprintf("INSERT INTO Morphology (Morphology) VALUES ('%s');", $morphology);
    try {
        $resultset = $dbh->query($querystring);
    } catch(PDOException $e) {
        echoError($xmldoc, $key, $e->getMessage());
        die();
    }
    
    echo $xmldoc->saveXML();
    $dbh = null;
        
?>