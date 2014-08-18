<?php
    session_start();
    include_once("connect-database-write.php");
    include_once("../session.php");
        
    header("Content-Type: text/xml");
    
    $xmldoc = createXmlDocument();
    
    $errorid = 'user-not-logged-in';
    
    if(!isValidUser()) {
	echoError($xmldoc,  $errorid, "User not logged in");
	die();
    }
            
    $errorid = 'add-sample-error';
       
    $dewar = isset($_REQUEST['dewar']) ? $_REQUEST['dewar'] : null;
    $stack = isset($_REQUEST['stack']) ? $_REQUEST['stack'] : null;
    $box = isset($_REQUEST['box']) ? $_REQUEST['box'] : null;
    $positions = isset($_REQUEST['positions']) ? array_filter(explode('+', $_REQUEST['positions'])) : null;
    
    if($dewar == null || $stack == null || $box == null || $positions == null || count($positions) == 0) {
        echoError($xmldoc, $errorid, 'location information missing or null');
        die();
    }
    
    $cellname = isset($_REQUEST['cellname']) ? $_REQUEST['cellname'] : null;
    $passagenumber = isset($_REQUEST['passage-number']) ? $_REQUEST['passage-number'] : null;
    $basalmedia = isset($_REQUEST['basal-media']) ? $_REQUEST['basal-media'] : null;
    $comment = isset($_REQUEST['comment']) ? $_REQUEST['comment'] : null;
    $operationdate = isset($_REQUEST['operation-date']) ? $_REQUEST['operation-date'] : null;
    $operationtime = isset($_REQUEST['operation-time']) ? $_REQUEST['operation-time'] : null;
    $operation = isset($_REQUEST['operation']) ? $_REQUEST['operation'] : null;
    
    /* only cellname is mandatory */
    if($cellname == null) {
        echoError($xmldoc, $errorid, 'cell line name missing or null');
        die();
    }
        
    /* serialise positions to a comma-delimited string */
    $positionstring = implode(",", $positions);
    
    /* check that all locations are free */
    $resultset = null;
    $querystring = "";
    try {
        $querystring = sprintf("SELECT sample
                                FROM Location l
                                WHERE
                                        l.sample != -1
                                    AND l.dewar LIKE '%s'
                                    AND l.stack = %d
                                    AND l.box = %d
                                    AND l.position IN (%s)
                                LIMIT 1;",
                                $dewar, $stack, $box, $positionstring); 
        $resultset = $dbh->query($querystring);
    } catch(PDOException $e) {
        echoError($xmldoc, "add-sample-error", $e->getMessage());
        die();
    }
            
    if($resultset && $resultset->rowCount() != 0) {
        echoError($xmldoc, $errorid, 'Sample already exists at one or more given positions '.$querystring);
        die();
    }
    
    // insert the unchanging location data into the SQL statement
    $querystring = sprintf("UPDATE Location l
                            SET l.sample = :id
                            WHERE   l.dewar = '%s'
                                    AND l.stack = %d
                                    AND l.box = %d
                                    AND l.position = :pos
                                    AND l.sample = -1
                                        OR l.sample IS NULL;", $dewar, $stack, $box);
    // just a flag to keep track of if we're in a current transaction. 
    $intransaction = false;
    
    try {
        $intransaction = $dbh->beginTransaction();
        $locationstatement = $dbh->prepare($querystring, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        // prepare the CellSample query. The parameters won't actually change but it will/can be executed multiple times
        $samplestatement = $dbh->prepare("INSERT INTO CellSample   (
                                                                        CellLine,
                                                                        PassageNumber,
                                                                        BasalMedia,
                                                                        Comment
                                                                    ) VALUES (
                                                                        :cell,
                                                                        :passage,
                                                                        :media,
                                                                        :comment
                                                                    );",
                                                                    array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $samplestatement->bindValue(':cell', $cellname, PDO::PARAM_STR);
        $samplestatement->bindValue(':passage', $passagenumber ?: null, PDO::PARAM_STR);
        $samplestatement->bindValue(':media', $basalmedia ?: null, PDO::PARAM_INT);
        $samplestatement->bindValue(':comment', $comment, PDO::PARAM_STR);
        
        // add a record to CellSample for each position
        foreach($positions as $position) {
            /* $resultset = $samplestatement->execute(array(
                                                         ':cell' => $cellname,
                                                         ':passage' => $passagenumber ?: null,
                                                         ':media' => $basalmedia ?: null,
                                                         ':comment' => $comment)
                                                   );
            */
            $resultset = $samplestatement->execute();
            if(!$resultset) {
                throw new Exception("Unable to insert sample for '$cellname' at position '$position'");
            }
            // get the last auto-increment id from CellSample and set that as the value for Sample field in Location
            $identity = $dbh->lastInsertId();
            $resultset = $locationstatement->execute(array(
                                                           ':id' => $identity,
                                                           ':pos' => $position
                                                        )
                                                     );
        }
        if($dbh->commit()) {
            $intransaction = false;
        }
    } catch(Exception $e) {
        if($intransaction) {
            $dbh->rollBack();
        }
        echoError($xmldoc, "add-sample-error", $e->getMessage());
        die();
    }
    
    
    echo $xmldoc->saveXML();
    $dbh = null;
       
       
?>