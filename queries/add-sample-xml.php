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
    
    $group = $_SESSION['Group'];
    $user = $_SESSION['id'];
    $isadmin = $_SESSION['IsAdmin'];
        
    $errorid = 'add-sample-error';
    
    /* expect the data to come in as XML, load into DOMDocument object */
    $xmlinputdata = isset($_REQUEST['xmlData']) ? $_REQUEST['xmlData'] : null;
    $xmlinputdoc = new DOMDocument("1.0", "ISO-8859-15");
    $xmlinputdoc->loadXML(trim($xmlinputdata));
        
    /*
     *  Data expected as
     *      -xrequest
     *          -row
     *              -dewar (mandatory)
     *              -stack (mandatory)
     *              -box (mandatory)
     *              -position (mandatory)
     *              -cellname (mandatory)
     *              -passage-number
     *              -basal-media
     *              -comment
     *              -operation-date
     *              -operation-time
     *              -operation
     */
    $positions = array();
    
    $rows = $xmlinputdoc->getElementsByTagName('row');
    $count = $rows->length;
    
    $locations = array( 'dewar' => null,
                        'stack' => null,
                        'box' => null);
    $records = array();
    
    $dewar = null;
    $stack = null;
    $box = null;
    $position = null;
    
    for($r = 0; $r < $count; $r++) {
        $row = $rows->item($r);
        $record = array();
        $childcount = $row->childNodes->length;
        for($i = 0; $i < $childcount; $i++) {
            $child = $row->childNodes->item($i);
            $record[$child->nodeName] = $child->nodeValue;
        }
        $records[] = $record;
        
        $dewar = $record['Dewar'];
        $stack = $record['Stack'];
        $box = $record['Box'];
        $position = $record['Position'];
               
        if($dewar == null || $stack == null || $box == null || $position == null) {
            echoError($xmldoc, $errorid, 'line '.__LINE__.': location information missing or null');
            die();
        }
        
        if($locations['dewar'] != null // this is not the first time around
            && ($dewar != $locations['dewar'] || $stack != $locations['stack'] || $box != $locations['box'])) {
            echoError($xmldoc, $errorid, 'Slims currently only supports insertion of data from a single dewar/stack/box combination in one transaction. To insert data from multiple sources, run each insertion separately.');
            die();
        }
        
        // keep these for the next iteration to make sure all records come from the same box
        // positions must be different, so we don't include them
        $locations['dewar'] = $dewar;
        $locations['stack'] = $stack;
        $locations['box'] = $box;
        
        $positions[] = $position;
        
        $cellname = $record['Cell-Line'];
              
        // only cellname is mandatory
        if($cellname == null) {
            echoError($xmldoc, $errorid, 'cell line name missing or null');
            die();
        }
    }
    
    // make sure that the current user is either admin, or is a member of the group
    // that owns the target stack
    if(!$isadmin) {
        $resultset = null;
        $querystring = "";
        try {
            $statement = $dbh->prepare("SELECT COUNT(*)
                                         FROM Stack s
                                         WHERE
                                             s.Dewar = :d
                                         AND s.Stack = :s
                                         AND s.ResearchGroup = :g;");
            $statement->bindValue(':d', $dewar, PDO::PARAM_STR);
            $statement->bindValue(':s', $stack, PDO::PARAM_INT);
            $statement->bindValue(':g', $group, PDO::PARAM_INT);
            $results = $statement->execute();
            $resultset = $statement->fetch();
            if(!$results || $resultset[0] == 0)
                throw new Exception("Unable to insert sample into stack '$stack' in dewar '$dewar': unauthorised");
        } catch(Exception $e) {
            echoError($xmldoc, "add-sample-error", $e->getMessage());
            die();
        }
    }
    
    $positionstring = implode(',', $positions);
    
    /* check that all locations are free */
    $resultset = null;
    $querystring = "";
    try {
        $querystring = sprintf("SELECT sample
                                FROM Location l
                                WHERE
                                        l.sample != -1
                                    AND l.dewar LIKE :d
                                    AND l.stack = :s
                                    AND l.box = :b
                                    AND l.position IN (%s)
                                LIMIT 1;", $positionstring);
        $statement = $dbh->prepare($querystring);
        $statement->bindValue(':d', $dewar, PDO::PARAM_STR);
        $statement->bindValue(':s', $stack, PDO::PARAM_INT);
        $statement->bindValue(':b', $box, PDO::PARAM_INT);
        $resultset = $statement->execute();
    } catch(PDOException $e) {
        echoError($xmldoc, "add-sample-error", $e->getMessage()." ".$querystring);
        die();
    }
            
    if($resultset && $statement->fetch() !== false) {
        echoError($xmldoc, $errorid, 'Sample already exists at one or more given positions '.$querystring);
        die();
    }
    
    // insert the unchanging location data into the SQL statement
    $querystring = sprintf("UPDATE Location l
                            SET l.sample = :id
                            WHERE   l.dewar = :d
                                    AND l.stack = %d
                                    AND l.box = %d
                                    AND l.position = :pos
                                    AND l.sample = -1
                                        OR l.sample IS NULL;", $stack, $box);
    // just a flag to keep track of if we're in a current transaction. 
    $intransaction = false;
    
    try {
        
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
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
        
        $activitystatement = $dbh->prepare("INSERT INTO Activity (
                                                                    Dewar,
                                                                    Stack,
                                                                    Box,
                                                                    Position,
                                                                    Sample,
                                                                    ActivityDate,
                                                                    Operation,
                                                                    Staff,
                                                                    ResearchGroup,
                                                                    RecordAddedDate,
                                                                    User
                                                                ) VALUES (
                                                                    :d,
                                                                    :s,
                                                                    :b,
                                                                    :p,
                                                                    :sample,
                                                                    :date,
                                                                    (SELECT o.ID FROM Operation o WHERE o.Operation = :op),
                                                                    :staff,
                                                                    :group,
                                                                    NOW(),
                                                                    :u
                                                                )",
                                                                array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        
        // dewar, stack, box should be consistent for all samples added
        //$activitystatement->bindValue(':d', $dewar, PDO::PARAM_STR);
        //$activitystatement->bindValue(':s', $stack, PDO::PARAM_INT);
        //$activitystatement->bindValue(':b', $box, PDO::PARAM_INT);
            
        // add a record to CellSample
        foreach($records as $record) {
            $cellname = $record['Cell-Line'];
            $passagenumber = $record['Passage-Number'];
            $basalmedia = $record['Basal-Media'];
            $comment = $record['Comment'];
            $staff = $record['User']; // owner of the sample (may or may not be a member of the Staff table)
            $resultset = $samplestatement->execute(array(
                                                         ':cell' => $cellname,
                                                         ':passage' => $passagenumber ? $passagenumber : null,
                                                         ':media' => $basalmedia ? $basalmedia : null,
                                                         ':comment' => $comment
                                                        )
                                                   );
            if(!$resultset) {
                throw new Exception("Unable to insert sample for '$cellname' at position '$position'");
            }
            // get the last auto-increment id from CellSample and set that as the value for Sample field in Location
            $identity = $dbh->lastInsertId();
            $position = $record['Position'];
            $resultset = $locationstatement->execute(array(
                                                           ':d' => $dewar,
                                                           ':id' => $identity,
                                                           ':pos' => $position
                                                        )
                                                     );
            if(!$resultset) {
                throw new Exception("Unable to insert sample for '$cellname' at position '$position'");
            }
                       
            $operationdate = date("Y-m-d", $record['Operation-Time']);
            $operation = $record['Operation'];
            $resultset = $activitystatement->execute(array(
                                            ':d' => $dewar,
                                            ':s' => $stack,
                                            ':b' => $box,
                                            ':p' => $position,
                                            ':sample' => $identity,
                                            ':date' => $operationdate,
                                            ':op' => $operation, // for the moment, should always be freeze $operation (1),
                                            ':staff' => $staff,
                                            ':group' => $group,
                                            ':u' => $user // log who does the activity, not necessarily the sample owner
                                        ));
            if(!$resultset) {
                throw new Exception("Unable to insert sample for '$cellname' at position '$position'");
            }
        }
        if($intransaction) {
           $dbh->commit();
           $intransaction = false;
        }
    } catch(Exception $e) {
        if($intransaction) {
            $dbh->rollBack();
            $intransaction = false;
        }
        echoError($xmldoc, "add-sample-error", $e->getMessage());
        die();
    }
    
    
    echo $xmldoc->saveXML();
    $dbh = null;
  
?>