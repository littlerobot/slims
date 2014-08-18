<?php
    session_start();
    
    include_once("../session.php");
    include_once("connect-database-read.php");
    include_once("save-xml.php");
    
    header("Content-Type: text/xml");
    
    $xmldoc = createXmlDocument();
   
       
    $resultset = null;
    try {
        $querystring = sprintf("SELECT s.id, s.species FROM Species s ORDER BY s.species ASC");
	
        $resultset = $dbh->query($querystring);
	
    } catch(PDOException $e) {
        echoError($xmldoc, $errorid,  "Error running query: " . $e->getMessage());
        die();
    }
     
        
    echo saveRecordSetToXML($xmldoc, $resultset);
    
     /* close the database connection */
     $dbh = null;
	
?>