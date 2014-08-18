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
    
    $key = 'Dewar';
      
    $xmldoc = new DOMDocument("1.0");
    $results = $xmldoc->appendChild($xmldoc->createElement("results"));
    $success = $results->setAttribute("success", "true");
        
    if(!isset($_REQUEST[$key])) {
        echoError($xmldoc, $key, "dewar parameter not set");
        die();
    }
    
    $dewarname = trim($_REQUEST[$key]);
    if(strlen($dewarname) == 0) {
        echoError($xmldoc, $key, "dewar parameter is empty");
        die();
    }
    
    $key = 'Stack';
    
    if(!isset($_REQUEST[$key])) {
        echoError($xmldoc, $key, "stack parameter not set");
        die();
    }
    
    $stack = trim($_REQUEST[$key]);
    if(strlen($stack) == 0) {
        echoError($xmldoc, $key, "stack parameter is empty");
        die();
    }
    
    if(!is_numeric($stack)) {
        echoError($xmldoc, $key, "stack value '$stack' parameter is invalid");
        die();
    }
    
    $comment = '';
    if(isset($_REQUEST['Comment']))
        $comment = $_REQUEST['Comment'];
        
    $key = 'Width';
    
    if(!isset($_REQUEST[$key])) {
        echoError($xmldoc, $key, "box width parameter not set");
        die();
    }
    
    $boxwidth = trim($_REQUEST[$key]);
    if(strlen($boxwidth) == 0) {
        echoError($xmldoc, $key, "box width parameter is empty");
        die();
    }
    
    if(!is_numeric($boxwidth)) {
        echoError($xmldoc, $key, "box width value '$boxwidth' parameter is invalid");
        die();
    }
    
    if($boxwidth <= 0) {
        echoError($xmldoc, $key, "box width value must be greater than 0");
        die();
    }
    
    $key = 'Height';
    
    if(!isset($_REQUEST[$key])) {
        echoError($xmldoc, $key, "box height parameter not set");
        die();
    }
    
    $boxheight = trim($_REQUEST[$key]);
    if(strlen($boxheight) == 0) {
        echoError($xmldoc, $key, "box height parameter is empty");
        die();
    }
    
    if(!is_numeric($boxheight)) {
        echoError($xmldoc, $key, "box height value '$boxheight' parameter is invalid");
        die();
    }
    
    if($boxheight <= 0) {
        echoError($xmldoc, $key, "box height value must be greater than 0");
        die();
    }
    
    $key = 'Boxcount';
    
    if(!isset($_REQUEST[$key])) {
        echoError($xmldoc, $key, "box count parameter not set");
        die();
    }
    
    $boxcount = trim($_REQUEST[$key]);
    if(strlen($boxheight) == 0) {
        echoError($xmldoc, $key, "box count parameter is empty");
        die();
    }
    
    if(!is_numeric($boxcount)) {
        echoError($xmldoc, $key, "box count value '$boxcount' parameter is invalid");
        die();
    }
    
    if($boxcount <= 0) {
        echoError($xmldoc, $key, "box count value must be greater than 0");
        die();
    }
    
    $transaction = $dbh->beginTransaction();
    $resultset = null;
    try {
        $querystring = sprintf("SELECT t.Dewar, t.Stack, MAX(t.Box) + 1 AS NextBox
                                FROM (SELECT d.DewarName AS Dewar, s.Stack, IFNULL(b.Box, 0) AS Box
                                        FROM DewarDefinition d
                                            LEFT JOIN Stack s
                                                ON d.DewarName = s.Dewar
                                            LEFT JOIN Box b
                                                ON s.Dewar = b.Dewar
                                                AND s.Stack = b.Stack) t
                                GROUP BY t.Dewar, t.Stack
                                HAVING t.Dewar LIKE '%s' AND t.Stack = %d LIMIT 1;", $dewarname, $stack);
        $resultset = $dbh->query($querystring);
    } catch(PDOException $e) {
        if($transaction) {
            $dbh->rollBack();
        }
        echoError($xmldoc, "select-query-error", $e->getMessage());
        die();
    }
    if(!$resultset || $resultset->rowCount() == 0) {
        echoError($xmldoc, $key, "dewar / stack combination '$dewarname $stack' does not exist");
        die();
    }
    
    
    $results = $resultset->fetch(PDO::FETCH_ASSOC);
    $box = $results['NextBox'];
    
    $querystring = "INSERT INTO Box (Dewar, Stack, Box, Comment, Width, Height) VALUES ";
    
    for($b = $box; $b < ($box + $boxcount); $b++) {
        $querystring = $querystring . sprintf(" ('%s', %d, %d, '%s', %d, %d),",
                                    $dewarname, $stack, $b, $comment, $boxwidth, $boxheight);
    }
    $querystring = substr($querystring, 0, strlen($querystring) - 1); // strip trailing comma
    
    try {
        $resultset = $dbh->query($querystring);
    } catch(PDOException $e) {
        if($transaction) {
            $dbh->rollBack();
        }
        echoError($xmldoc, 'box-error', $e->getMessage());
        die();
    }
    
    
    $locationquerystring = "INSERT INTO Location (  Dewar,
                                            Stack,
                                            Box,
                                            Position,
                                            Sample)
                    VALUES";
   
    $maxheight = $boxheight;
   
    for($b = $box; $b < ($box + $boxcount); $b++) {
        for($w = 0; $w < $boxwidth; $w++) {
            for($h = 0; $h < $boxheight; $h++) {
                $position = ($h % $boxheight) + ($w * $maxheight) + 1;
                $locationquerystring = $locationquerystring . sprintf(" ('%s', %d, %d, %d, -1),", $dewarname, $stack, $b, $position);
            }
        }
    }
   
    $locationquerystring = substr($locationquerystring, 0, strlen($locationquerystring) - 1); // strip trailing comma
    $querystring = $locationquerystring . "; ";
    try {
        $resultset = $dbh->query($querystring);
    } catch(PDOException $e) {
        if($transaction) {
            $dbh->rollBack();
        }
        echoError($xmldoc, 'location-error', $e->getMessage() . $querystring);
        die();
    }
    
    if($transaction && $dbh->commit()) {
        $transaction = false;
    }
    
    echo $xmldoc->saveXML();
    $dbh = null;
    
    
?>