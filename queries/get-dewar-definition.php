<?php
    session_start();
    
    include_once("connect-database-read.php");
    include_once("save-xml.php");
    include_once("../session.php");
    
    error_reporting(E_ERROR);
    ini_set("display_errors", 1);
    
    header('Content-Type:  text/xml');
   
     
    $xmldoc = createXmlDocument();
        
    $isadmin = isAdminUser();
    
    $errorid = 'get-dewar-definition-error';
       
    $resultset = null;
    try {
	$querystring = "SELECT 	DewarName
			FROM	DewarDefinition";
	$resultset = $dbh->query($querystring);
	
    } catch(PDOException $e) {
        echoError($xmldoc,  $errorid, "Error running query: " . $e->getMessage());
        die();
    }
    
    
        
    echo saveRecordSetToXML($xmldoc, $resultset);
    
     /* close the database connection */
     $dbh = null;
	
?>