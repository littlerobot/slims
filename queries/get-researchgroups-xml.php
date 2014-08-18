<?php
    session_start();
    
    include_once('connect-database-read.php');
    include_once('save-xml.php');
    include_once("../session.php");
    
    error_reporting(E_ERROR);
    ini_set("display_errors", 1);
        
    header("Content-Type: text/xml");
    
    $xmldoc = createXmlDocument();
    
    $errorid = 'user-not-logged-in';
    
    if(!isValidUser()) {
	echoError($xmldoc,  $errorid, "User not logged in with sufficient privilege");
	die();
    }
    
    $errorid = 'not-admin-user';
    if(!isAdminUser()) {
        echoError($xmldoc,  $errorid, "User not admin");
	die();
    }
    
    $errorid = "user-error";
    
        
    $querystring = "SELECT  r.ID AS ResearchGroupID,
                            r.ResearchGroupName AS ResearchGroup,
                            r.Department AS DepartmentID,
                            d.Department,
                            r.IsAdmin
                    FROM ResearchGroup r
                         INNER JOIN Department d
                            ON r.Department = d.ID;";
    try {
        $resultset = $dbh->query($querystring);
    } catch(Exception $e) {
        echoError($xmldoc, $errorid, "error retrieving user information: " . $e->getMessage());
        die();
    }
    
    echo saveRecordSetToXML($xmldoc, $resultset);
    $dbh = null;
?>