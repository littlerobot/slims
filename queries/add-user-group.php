<?php
    session_start();
    include_once("connect-database-write.php");
    include_once("../session.php");
    include_once("save-xml.php");
    
    error_reporting(E_ERROR);
    ini_set("display_errors", 1);
        
    
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
    
    $groupkey = 'ResearchGroup';
    $deptkey = 'Department';
    $adminkey = 'IsAdmin';
      
    
        
    if(!isset($_REQUEST[$groupkey])) {
        echoError($xmldoc, $groupkey, $groupkey . " parameter not set");
        die();
    }
    
    $group = trim($_REQUEST[$groupkey]);
    if(strlen($group) == 0) {
        echoError($xmldoc, $groupkey, $groupkey . "ResearchGroup parameter is empty");
        die();
    }
    
    if(!isset($_REQUEST[$deptkey])) {
        echoError($xmldoc, $deptkey, $deptkey . " parameter not set");
        die();
    }
    
    $dept = trim($_REQUEST[$deptkey]);
    if(strlen($dept) == 0) {
        echoError($xmldoc, $deptkey, $deptkey . "parameter is empty");
        die();
    }
    
    $isadmin = 0;
    if(isset($_REQUEST[$adminkey])) {
        $isadmin = ($_REQUEST[$adminkey] == 1) ? 1 : 0; 
    }
    
    $resultset = null;
    try {
        $querystring = sprintf("SELECT b.ResearchGroupName
                                FROM ResearchGroup b
                                WHERE b.ResearchGroupName LIKE '%s' LIMIT 1;", $group);
        $resultset = $dbh->query($querystring);
    } catch(PDOException $e) {
        echoError($xmldoc, "select-query-error", $e->getMessage());
        die();
    }
    if($resultset && $resultset->rowCount() != 0) {
        echoError($xmldoc, $groupkey, $groupkey . " with name '$group' already exists");
        die();
    }
    
    $querystring = sprintf("INSERT INTO ResearchGroup (ResearchGroupName, Department) VALUES ('%s', %d);", $group, $dept);
    try {
        $resultset = $dbh->query($querystring);
    } catch(PDOException $e) {
        echoError($xmldoc, $key, $e->getMessage());
        die();
    }
    
    echo $xmldoc->saveXML();
    $dbh = null;
        
    
?>