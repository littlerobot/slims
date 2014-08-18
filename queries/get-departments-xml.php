<?php
    session_start();
    
    include_once('connect-database-read.php');
    include_once('save-xml.php');
    include_once("../session.php");
    
    error_reporting(E_ERROR);
    ini_set("display_errors", 1);
    
    header("Content-Type: text/xml");
    
    $xmldoc = createXmlDocument();
    
    $errorid = "user-error";
    
     /* start up an xml doc for the reply*/
    $xmldoc = new DOMDocument("1.0", "ISO-8859-15");
    $results = $xmldoc->appendChild($xmldoc->createElement("results"));
    $success = $results->setAttribute("success", "true");
    
    $querystring = "SELECT  r.ID AS DepartmentID,
                            r.Department
                    FROM Department r;";
    try {
        $resultset = $dbh->query($querystring);
    } catch(Exception $e) {
        echoError($xmldoc, $errorid, "error retrieving user information: " . $e->getMessage());
        die();
    }
    
    echo saveRecordSetToXML($xmldoc, $resultset);
    $dbh = null;
?>