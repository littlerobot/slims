<?php
    session_start();
    
    include_once("../session.php"); 
    include_once('connect-database-read.php');
    include_once('save-xml.php');
        
    error_reporting(E_ERROR);
    ini_set("display_errors", 1);
        
    header("Content-Type: text/xml");
    
    $xmldoc = createXmlDocument();
    
    $errorid = 'user-not-logged-in';
    
    if(!isValidUser()) {
	echoError($xmldoc,  $errorid, "User not logged in with sufficient privilege");
	die();
    }
    
    $errorid = "user-error";
       
        
    $querystring = "SELECT t.Staff, t.ResearchGroup FROM
                    (SELECT  s.StaffName AS Staff,
                            r.ResearchGroupName AS ResearchGroup
                    FROM Staff s
                        INNER JOIN ResearchGroup r
                            ON s.ResearchGroup = r.ID
                    UNION
                    SELECT a.Staff AS Staff,
                           '' AS ResearchGroup
                    FROM Activity a
                    WHERE a.Staff NOT IN (SELECT s2.StaffName FROM Staff s2)
                    GROUP BY a.Staff) t
                    ORDER BY t.Staff";
    try {
        $resultset = $dbh->query($querystring);
    } catch(Exception $e) {
        echoError($xmldoc, $errorid, "error retrieving user information: " . $e->getMessage());
        die();
    }
    
    echo saveRecordSetToXML($xmldoc, $resultset);
    $dbh = null;
?>