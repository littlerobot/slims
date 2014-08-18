<?php
    session_start();
    include_once("connect-database-read.php");
    include_once("../session.php");
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
    
    $errorid = 'not-admin-user';
    if(!isAdminUser()) {
        echoError($xmldoc,  $errorid, "User not admin");
	die();
    }
    
    $key = 'cellline'; 
    $errorid = 'invalid-cellline-name';
    if(!isset($_REQUEST[$key])) {
        echoError($xmldoc, $errorid, $key . " parameter invalid or missing");
        die();
    }
    $cellline = $_REQUEST['cellline'];
    if($cellline == null || strlen($cellline) == 0) {
        echoError($xmldoc, $errorid, $key . " parameter invalid or missing");
        die();
    }
    
    $errorid = 'get-cell-line-documents-error';
    
    $resultset = null;
    try {
        $querystring = sprintf("SELECT 	d.CellLineName, d.Document
                                FROM    Documents d
                                WHERE   d.CellLineName = '%s'", $cellline);
        $resultset = $dbh->query($querystring);
    } catch(PDOException $e) {
        echoError($xmldoc, $errorid, "Error running query: " . $e->getMessage());
        die();
    }
    
       
    echo saveRecordSetToXML($xmldoc, $resultset);
    
     /* close the database connection */
     $dbh = null;
?>