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
          
    $errorid = 'get-group-stacks-error';
    
    $resultset = null;
    try {
        $querystring = "SELECT  dw.DewarName AS Dewar,
                                s.Stack,
                                r.ID AS ResearchGroupID,
                                r.ResearchGroupName AS ResearchGroup,
                                d.Department,
                                (r.ResearchGroupName IS NULL) AS IsUnassigned
                        FROM DewarDefinition dw
                            LEFT JOIN Stack s
                                ON dw.DewarName = s.Dewar
                            LEFT JOIN ResearchGroup r
                                ON s.ResearchGroup = r.ID
                            LEFT JOIN Department d
                                ON r.Department = d.ID
                        ORDER BY dw.DewarName, s.Stack ASC";
	
        $resultset = $dbh->query($querystring);
	
    } catch(PDOException $e) {
        echoError($xmldoc, $errorid, "Error running query: " . $e->getMessage());
        die();
    }
            
    echo saveRecordSetToXML($xmldoc, $resultset);
    
     /* close the database connection */
     $dbh = null;
    

?>