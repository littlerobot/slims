<?php
    session_start();
    
    include_once("connect-database-read.php");
    include_once("save-xml.php");
    include_once("../session.php");
    
    error_reporting(E_ERROR);
    ini_set("display_errors", 1);
    
    header('Content-Type:  text/xml');
   
     
    $xmldoc = createXmlDocument();
    
    $grouponly = isset($_REQUEST['grouponly']) ? $_REQUEST['grouponly'] : 0;
       
    $group = getGroup();
    $isadmin = isAdminUser();
    
    $dewar = $stack = $box = null;
      
    $errorid = 'get-dewar-info-error';
    
    $clause = "";
    if($grouponly && !$isadmin)  
	$clause = !$isadmin ? sprintf("AND s.ReseachGroup = %d", $group) : '';
        
    $querystring = sprintf("SELECT l.Dewar
		    FROM Location l
		    INNER JOIN Stack s
			ON	l.Dewar = s.Dewar
			    AND l.Stack = s.Stack
		    %s
		    GROUP BY l.Dewar
		    ORDER BY l.Dewar ASC", $clause);
    
    $key = 'Dewar';
    
    if(isset($_REQUEST[$key])) {
	$dewar = $_REQUEST[$key];
	$querystring = sprintf("SELECT l.Stack
				FROM Location l
				    INNER JOIN Stack s
				    ON	l.Dewar = s.Dewar
					AND l.Stack = s.Stack
				    WHERE l.Dewar = '%s'
				    %s
				    GROUP BY l.Stack
				    ORDER BY l.Dewar, l.Stack ASC", $dewar, $clause);
	$errorid = 'get-stack-info-error';
    }
    
    $key = 'Stack';
    
    if(isset($_REQUEST[$key]) && $dewar != null) {
	$stack = $_REQUEST[$key];
	$querystring = sprintf("SELECT l.Box
				FROM Location l
				    INNER JOIN Stack s
				    ON	l.Dewar = s.Dewar
					AND l.Stack = s.Stack
					WHERE l.Dewar = '%s'
					AND l.Stack = %d
					%s
					GROUP BY l.Dewar, l.Stack, l.Box
					ORDER BY l.Dewar, l.Stack, l.Box ASC", $dewar, $stack, $clause);
	$errorid = 'get-box-info-error';
    }
    
    $key = 'Box';
    
    if(isset($_REQUEST[$key]) && $dewar != null && $stack != null) {
	$box = $_REQUEST[$key];
	$querystring = sprintf("SELECT 	l.Dewar,
					l.Stack,
					l.Box,
					l.Position AS PositionID,
					CONCAT(CAST(CHAR( FLOOR((l.Position - 1) / b.Height) + ASCII('A') USING utf8) AS CHAR), (((l.Position - 1) %% b.Width ) + 1)) AS Position,
					l.Sample,
					b.Width,
					b.Height
				FROM Location l
				    INNER JOIN Stack s
				    ON  l.Dewar = s.Dewar
					AND l.Stack = s.Stack
				    INNER JOIN Box b
				    ON 	l.Dewar = b.Dewar
					AND l.Stack = b.Stack
					AND l.Box = b.Box
				    WHERE l.Dewar = '%s'
					AND l.Stack = %d
					AND l.Box = %d
				    %s
				    ORDER BY l.Dewar, l.Stack, l.Box, l.Position ASC", $dewar, $stack, $box, $clause);
	$errorid = 'get-positions-info-error';
    }
      
      
    // $xmldoc->documentElement->setAttribute("query", $querystring);
    $resultset = null;
    try {
	
	$resultset = $dbh->query($querystring);
	
    } catch(PDOException $e) {
        echoError($xmldoc,  $errorid, "Error running query: " . $e->getMessage());
        die();
    }
    
    
        
    echo saveRecordSetToXML($xmldoc, $resultset);
    
     /* close the database connection */
     $dbh = null;
	
?>