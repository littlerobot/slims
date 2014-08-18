<?php
    session_start();
    include_once("../session.php");
    include_once('connect-database-write.php');
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
    
    $groupkey = 'ResearchGroupID';
    $dewarkey = 'Dewar';
    $stackkey = 'Stack';
    
    if(!isset($_REQUEST[$groupkey])) {
        echoError($xmldoc, $groupkey, $groupkey . " parameter not set");
        die();
    }
    
    $group = trim($_REQUEST[$groupkey]);
    if(strlen($group) == 0) {
        echoError($xmldoc, $groupkey, $groupkey . " parameter is empty");
        die();
    }
    if($group === 0)
        $group = null;
    
    if(!isset($_REQUEST[$dewarkey])) {
        echoError($xmldoc, $dewarkey, $dewarkey . " parameter not set");
        die();
    }
     
    $dewar = trim($_REQUEST[$dewarkey]);
    if(strlen($dewar) == 0) {
        echoError($xmldoc, $stackkey, $stackkey . " parameter is empty");
        die();
    }
    
    if(!isset($_REQUEST[$stackkey])) {
        echoError($xmldoc, $stackkey, $stackkey . " parameter not set");
        die();
    }
    
    $stack = trim($_REQUEST[$stackkey]);
    if(strlen($stack) == 0) {
        echoError($xmldoc, $stackkey, $stackkey . " parameter is empty");
        die();
    }
    
    try {
        $intransaction = $dbh->beginTransaction();
        
        $statement = $dbh->prepare("UPDATE  Stack s
                                    SET     s.ResearchGroup = :group
                                    WHERE   s.Dewar = :dewar
                                            AND s.Stack = :stack
                                    LIMIT 1;",
                                        array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
                                                                    
                                                                    
        $statement->bindValue(':group', $group, PDO::PARAM_INT);
        $statement->bindValue(':dewar', $dewar, PDO::PARAM_STR);
        $statement->bindValue(':stack', $stack, PDO::PARAM_INT);
               
        $resultset = $statement->execute();
        if(!$resultset) {
            throw new Exception(sprintf("Unable to insert set research group owner for stack %s in dewar '%s'", $stack, $dewar));
        }
    
        if($dbh->commit()) {
            $intransaction = false;
        }
    } catch(Exception $e) {
        if($intransaction) {
            $dbh->rollBack();
        }
        echoError($xmldoc, "set-stack-owner-error", $e->getMessage());
        die();
    }
    
    echo $xmldoc->saveXML();
    $dbh = null;
    
?>