<?php
    session_start();
    include_once("../session.php");
    include_once("connect-database-write.php");
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
    
    $errorid = 'not-admin-user';
    if(!isAdminUser()) {
        echoError($xmldoc,  $errorid, "User not admin");
	die();
    }
    
    $idkey = 'Id';
        
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
    
    $ids = array_filter(explode('+', $_REQUEST['Id']));
    if(count($ids) === 0) {
        echoError($xmldoc, $key, $key . " parameter is empty");
        die();
    }
    
    for($i = 0; $i < count($ids); $i++) {
        $params[] = ':p' . $i;
    } 
        
    $querystring = sprintf('DELETE FROM Staff WHERE Id IN (%s);', implode(',', $params));
    
    $intransaction = false;
    try{
        $intransaction = $dbh->beginTransaction();
        $statement = $dbh->prepare($querystring,  array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        for($i = 0; $i < count($ids); $i++)
            $statement->bindValue(':p'.$i, $ids[$i], PDO::PARAM_STR);
        $result = $statement->execute();
        if(!$result) {
            throw new Exception('No records removed');
        }
        if($intransaction)
            $dbh->commit();
    } catch(Exception $e) {
        if($intransaction) {
            $dbh->rollback();
        }
        echoError($xmldoc, "remove-user-error", $e->getMessage());
        die();
    }
    
    echo $xmldoc->saveXML();
    $dbh = null;

?>