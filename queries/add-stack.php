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
    
    $key = 'dewarname';
      
    $xmldoc = new DOMDocument("1.0");
    $results = $xmldoc->appendChild($xmldoc->createElement("results"));
    $success = $results->setAttribute("success", "true");
        
    if(!isset($_REQUEST[$key])) {
        echoError($xmldoc, $key, "dewarname parameter not set");
        die();
    }
    
    $dewarname = trim($_REQUEST[$key]);
    if(strlen($dewarname) == 0) {
        echoError($xmldoc, $key, "dewarname parameter is empty");
        die();
    }
    
    //$key = 'nostacks';
    //
    //if(!isset($_REQUEST[$key])) {
    //    echoError($xmldoc, $key, "no of stacks parameter not set");
    //    die();
    //}
    //
    //$nostacks = trim($_REQUEST[$key]);
    //if(strlen($nostacks) == 0) {
    //    echoError($xmldoc, $key, "no of stacks parameter is empty");
    //    die();
    //}
    //
    //if(!is_numeric($nostacks)) {
    //    echoError($xmldoc, $key, "no of stacks value '$nostacks' parameter is invalid");
    //    die();
    //}
    //
    //$key = 'noboxes';
    //
    //if(!isset($_REQUEST[$key])) {
    //    echoError($xmldoc, $key, "no of boxes parameter not set");
    //    die();
    //}
    //
    //$noboxes = trim($_REQUEST[$key]);
    //if(strlen($noboxes) == 0) {
    //    echoError($xmldoc, $key, "no of boxes parameter is empty");
    //    die();
    //}
    //
    //if(!is_numeric($noboxes)) {
    //    echoError($xmldoc, $key, "no of boxes value '$noboxes' parameter is invalid");
    //    die();
    //}
    //
    //$key = 'boxwidth';
    //
    //if(!isset($_REQUEST[$key])) {
    //    echoError($xmldoc, $key, "boxwidth parameter not set");
    //    die();
    //}
    //
    //$boxwidth = trim($_REQUEST[$key]);
    //if(strlen($boxwidth) == 0) {
    //    echoError($xmldoc, $key, "boxwidth parameter is empty");
    //    die();
    //}
    //
    //if(!is_numeric($boxwidth)) {
    //    echoError($xmldoc, $key, "boxwidth value '$boxwidth' parameter is invalid");
    //    die();
    //}
    //
    //$key = 'boxheight';
    //
    //if(!isset($_REQUEST[$key])) {
    //    echoError($xmldoc, $key, "boxheight parameter not set");
    //    die();
    //}
    //
    //$boxheight = trim($_REQUEST[$key]);
    //if(strlen($boxheight) == 0) {
    //    echoError($xmldoc, $key, "boxheight parameter is empty");
    //    die();
    //}
    //
    //if(!is_numeric($boxheight)) {
    //    echoError($xmldoc, $key, "boxheight value '$boxheight' parameter is invalid");
    //    die();
    //}
    
    
    $resultset = null;
    try {
        $querystring = sprintf("SELECT t.DewarName AS Dewar, MAX(t.Stack) + 1 AS NextStack
                                FROM
                                    (SELECT d.DewarName, IFNULL(s.Stack, 0) AS Stack
                                        FROM DewarDefinition d
                                            LEFT JOIN Stack s
                                                ON d.DewarName = s.Dewar) t
                                GROUP BY t.DewarName
                                HAVING t.DewarName LIKE '%s' LIMIT 1;", $dewarname);
        $resultset = $dbh->query($querystring);
    } catch(PDOException $e) {
        echoError($xmldoc, "select-query-error", $e->getMessage());
        die();
    }
    if(!$resultset || $resultset->rowCount() == 0) {
        echoError($xmldoc, $key, "dewar with name '$dewarname' does not exist");
        die();
    }
    
    
    $results = $resultset->fetch(PDO::FETCH_ASSOC);
    $stack = $results['NextStack'];
    
    $querystring = sprintf("INSERT INTO Stack (Dewar, Stack)
                            VALUES ('%s', %d);",
                                    $dewarname, $stack);
    try {
        $resultset = $dbh->query($querystring);
    } catch(PDOException $e) {
        echoError($xmldoc, $key, $e->getMessage());
        die();
    }
    
    
    //$locationquerystring = "INSERT INTO Location (  Dewar,
    //                                        Stack,
    //                                        Box,
    //                                        Position,
    //                                        Sample)
    //                VALUES";
    //$stackquerystring = "INSERT INTO Stack (Dewar, Stack) VALUES ";
    //$maxheight = 10;
    //for($s = 0; $s < $nostacks; $s++) {
    //    $stack = $s + 1;
    //    $stackquerystring = $stackquerystring . sprintf(" ('%s', %d),", $dewarname, $stack);
    //    for($b = 0; $b < $noboxes; $b++) {
    //        $box = $b + 1;
    //        for($w = 0; $w < $boxwidth; $w++) {
    //            for($h = 0; $h < $boxheight; $h++) {
    //                $position = ($h % $boxheight) + ($w * $maxheight) + 1;
    //                $locationquerystring = $locationquerystring . sprintf(" ('%s', %d, %d, %d, -1),", $dewarname, $stack, $box, $position);
    //            }
    //        }
    //    }
    //}
    //$locationquerystring = substr($locationquerystring, 0, strlen($locationquerystring) - 1); // strip trailing comma
    //$stackquerystring = substr($stackquerystring, 0, strlen($stackquerystring) - 1); // strip trailing comma
    //$querystring = $locationquerystring . "; " . $stackquerystring;
    //$dbh->query($querystring);
    
    echo $xmldoc->saveXML();
    $dbh = null;
    
    
?>