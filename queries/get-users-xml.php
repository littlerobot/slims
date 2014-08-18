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
	echoError($xmldoc,  $errorid, "User not logged in");
	die();
    }
    
    $errorid = "user-error";
       
        
    $querystring = "SELECT  s.ID,
                            s.StaffName AS Staff,
                            r.ID AS ResearchGroupID,
                            r.ResearchGroupName AS ResearchGroup,
                            d.ID AS DepartmentID,
                            d.Department,
                            r.IsAdmin AS IsAdmin
                    FROM Staff s
                        INNER JOIN ResearchGroup r
                            ON s.ResearchGroup = r.ID
                        INNER JOIN Department d
                            ON r.Department = d.ID";
    try {
        $resultset = $dbh->query($querystring);
    } catch(Exception $e) {
        echoError($xmldoc, $errorid, "error retrieving user information: " . $e->getMessage());
        die();
    }
    
    echo saveRecordSetToXML($xmldoc, $resultset);
    $dbh = null;
?>