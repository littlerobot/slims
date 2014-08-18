<?php
    session_start();
    
    include_once("connect-database-write.php");
    include_once("../session.php");
    include_once("save-xml.php");
    
    header("Content-Type: text/xml");
    
    
    $xmldoc = createXmlDocument();
    
    $errorid = 'user-not-logged-in';
    
    if(!isValidUser()) {
	echoError($xmldoc,  $errorid, "User not logged in");
	die();
    }
    
    $errorid = 'not-admin-user';
    if(!isAdminUser()) {
        echoError($xmldoc,  $errorid, "User not admin");
	die();
    }
    
    $idkey = 'Id';
    $namekey = 'Staff';
    $groupkey = 'ResearchGroup';
      
    $key = $idkey;
    
    if(!isset($_REQUEST[$key])) {
        echoError($xmldoc, $key, $key . " parameter not set");
        die();
    }
    
    $id = trim($_REQUEST[$key]);
    if(strlen($id) == 0) {
        echoError($xmldoc, $key, $key . " parameter is empty");
        die();
    }
    
    $key = $namekey;
    
    if(!isset($_REQUEST[$key])) {
        echoError($xmldoc, $key, $key . " parameter not set");
        die();
    }
    
    $name = trim($_REQUEST[$key]);
    if(strlen($name) == 0) {
        echoError($xmldoc, $key, $key . " parameter is empty");
        die();
    }
    
    $key = $groupkey;
    
    if(!isset($_REQUEST[$key])) {
        echoError($xmldoc, $key, $key . " parameter not set");
        die();
    }
    
    $groupid = trim($_REQUEST[$key]);
    if(strlen($groupid) == 0) {
        echoError($xmldoc, $key, $key . " parameter is empty");
        die();
    }
    
    $resultset = null;
    try {
        $querystring = sprintf("SELECT u.Id
                                FROM Staff u
                                WHERE u.StaffName LIKE '%s' LIMIT 1;", $id);
        $resultset = $dbh->query($querystring);
    } catch(PDOException $e) {
        echoError($xmldoc, "select-query-error", $e->getMessage());
        die();
    }
    if($resultset && $resultset->rowCount() != 0) {
        echoError($xmldoc, $idkey, $idkey . " with value '$id' already exists");
        die();
    }
     
    $querystring = sprintf("INSERT INTO Staff (Id, StaffName, ResearchGroup) VALUES ('%s', '%s', %d);", $id, $name, $groupid);
    try {
        $resultset = $dbh->query($querystring);
    } catch(PDOException $e) {
        echoError($xmldoc, $key, $e->getMessage());
        die();
    }
    
    echo $xmldoc->saveXML();
    $dbh = null;
        
    
?>