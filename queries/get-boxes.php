<?php

    session_start();
    include_once("connect-database-read.php");
    include_once("save-xml.php");
    
    include_once("../session.php");
    
    header('Content-Type:  text/xml');
    
    $xmldoc = createXmlDocument();
    
    $errorid = 'user-not-logged-in';
    
    if(!isValidUser()) {
	echoError($xmldoc,  $errorid, "User not logged in with sufficient privilege");
	die();
    }
          
    $errorid = 'get-boxes-error';
    
    $resultset = null;
    try {
        $querystring = "SELECT  d.DewarName AS Dewar,
                                s.Stack,
                                b.Box,
                                b.Height,
                                b.Width,
                                b.Comment
                        FROM DewarDefinition d
                            LEFT JOIN Stack s
                                ON d.DewarName = s.Dewar
                            LEFT JOIN Box b
                                ON s.Dewar = b.Dewar
                                AND s.Stack = b.Stack
                        ORDER BY b.Dewar, b.Stack, b.Box ASC";
	
        $resultset = $dbh->query($querystring);
	
    } catch(PDOException $e) {
        echoError($xmldoc, $errorid, "Error running query: " . $e->getMessage());
        die();
    }
            
    echo saveRecordSetToXML($xmldoc, $resultset);
    
     /* close the database connection */
     $dbh = null;
    

?>