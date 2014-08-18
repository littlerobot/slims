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
    
    $deptkey = 'Department'; 
      
    $xmldoc = new DOMDocument("1.0");
    $results = $xmldoc->appendChild($xmldoc->createElement("results"));
    $success = $results->setAttribute("success", "true");
   
    if(!isset($_REQUEST[$deptkey])) {
        echoError($xmldoc, $deptkey, $deptkey . " parameter not set");
        die();
    }
    
    $dept = trim($_REQUEST[$deptkey]);
    if(strlen($dept) == 0) {
        echoError($xmldoc, $deptkey, $deptkey . "parameter is empty");
        die();
    }
    
    $resultset = null;
    try {
        $querystring = sprintf("SELECT d.Department
                                FROM Department d
                                WHERE d.Department LIKE '%s' LIMIT 1;", $dept);
        $resultset = $dbh->query($querystring);
    } catch(PDOException $e) {
        echoError($xmldoc, "select-query-error", $e->getMessage());
        die();
    }
    if($resultset && $resultset->rowCount() != 0) {
        echoError($xmldoc, $deptkey, $deptkey . " with name '$dept' already exists");
        die();
    }
    
    $querystring = sprintf("INSERT INTO Department (Department) VALUES ('%s');", $dept);
    try {
        $resultset = $dbh->query($querystring);
    } catch(PDOException $e) {
        echoError($xmldoc, $key, $e->getMessage());
        die();
    }
    
    echo $xmldoc->saveXML();
    $dbh = null;
        
    
?>