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
	echoError($xmldoc,  $errorid, "User not logged in with sufficient privilege");
	die();
    }
    
    $errorid = 'not-admin-user';
    if(!isAdminUser()) {
        echoError($xmldoc,  $errorid, "User not admin");
	die();
    }
    
    $start = isset($_REQUEST['start']) ? $_REQUEST['start'] : 0;
    $limit = isset($_REQUEST['limit']) ? $_REQUEST['limit'] : 30;
    $start = is_numeric($start) ? $start : 0;
    $limit = is_numeric($limit) ? $limit : 30;
    
    $sort = isset($_REQUEST['sort']) ? $_REQUEST['sort'] : 'RecordAddedDate';
    if(array_search($sort, array('ID', 'Dewar', 'Stack', 'Box', 'Position', 'CellLine', 'ActivityDate', 'Operation', 'Staff', 'ResearchGroup', 'RecordAddedDate', 'User')) === false)
        $sort = 'RecordAddedDate';
    $dir = isset($_REQUEST['dir']) ? $_REQUEST['dir'] : 'ASC';
    if($dir != "ASC" && $dir != "DESC") {
        $dir = "ASC";
        if($sort == 'RecordAddedDate' || $sort == 'ActivityDate')
            $dir = "DESC";
    }
    
    $errorid = "activity-error";
            
    $querystring = sprintf("SELECT  *
                            FROM
                                (SELECT
                                        a.ID,
                                        a.Dewar,
                                        a.Stack,
                                        a.Box,
                                        p.Position,
                                        c.CellLine,
                                        DATE_FORMAT(a.ActivityDate, '%%d/%%m/%%Y') AS ActivityDate,
                                        o.Operation,
                                        a.Staff,
                                        a.ResearchGroup,
                                        DATE_FORMAT(a.RecordAddedDate, '%%d/%%m/%%Y %%H:%%i:%%s') AS RecordAddedDate,
                                        a.User
                                FROM Activity a
                                    LEFT JOIN CellSample c
                                        ON a.Sample = c.ID
                                    LEFT JOIN Operation o
                                        ON a.Operation = o.ID
                                    LEFT JOIN Position p
                                        ON a.Position = p.ID
                                LIMIT %d, %d) t
                                ORDER BY t.%s %s", $start, $limit, $sort, $dir);
    try {
        $resultset = $dbh->query($querystring);
        $count = $dbh->query("SELECT COUNT(*) FROM Activity;")->fetchColumn();
    } catch(Exception $e) {
        echoError($xmldoc, $errorid, "error retrieving activity information: " . $e->getMessage());
        die();
    }
    
    $xmldoc->documentElement->setAttribute("total", $count);
    
    echo saveRecordSetToXML($xmldoc, $resultset);
    $dbh = null;
?>