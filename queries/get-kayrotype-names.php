<?php
    session_start();
    include_once("../session.php");    
    include_once("connect-database-read.php");
    include_once("save-xml.php");
    
    header('Content-Type:  text/xml');
       
    $xmldoc = createXmlDocument();
    
    
    $errorid = 'get-kayrotype-error';
           
    $resultset = null;
    try {
        $querystring = sprintf("SELECT k.id,
				       k.kayrotype
				FROM Kayrotype k
				ORDER BY k.kayrotype ASC ");
	
        $resultset = $dbh->query($querystring);
    } catch(PDOException $e) {
        echoError($xmldoc, $errorid, "Error running query: " . $e->getMessage());
        die();
    }
                
    echo saveRecordSetToXML($xmldoc, $resultset);
    
     /* close the database connection */
     $dbh = null;
	
?>