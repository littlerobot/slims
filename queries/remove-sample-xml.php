<?php
    session_start();
    include_once('connect-database-write.php');
    include_once("../session.php");
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
     
    $group = $_SESSION['Group'];
    $user = $_SESSION['id'];
    $isadmin = $_SESSION['IsAdmin'];
        
    $errorid = 'remove-sample-error';
    
    /* expect the data to come in as XML, load into DOMDocument object */
    $xmlinputdata = isset($_REQUEST['xmlData']) ? $_REQUEST['xmlData'] : null;
    $xmlinputdoc = new DOMDocument("1.0", "ISO-8859-15");
    $xmlinputdoc->loadXML(trim($xmlinputdata));
       
    
    $positions = array();
    $activitydates = array();
    $rows = $xmlinputdoc->getElementsByTagName('row');
    $count = $rows->length;
    
    $locations = array( 'dewar' => null,
                        'stack' => null,
                        'box' => null);
    
    $records = array();
    
    $activities = array();
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
            echoError($xmldoc, $errorid, 'location information missing or null');
            die();
        }
        
        if($locations['dewar'] != null // this is not the first time around
            && ($dewar != $locations['dewar'] || $stack != $locations['stack'] || $box != $locations['box'])) {
            echoError($xmldoc, $errorid, 'Slims currently only supports removal of data from a single dewar/stack/box combination in one transaction. To remove data from multiple sources, run each removal separately.');
            die();
        }
        
       
        
        // keep these for the next iteration to make sure all records come from the same box
        // positions must be different, so we don't include them
        $locations['dewar'] = $dewar;
        $locations['stack'] = $stack;
        $locations['box'] = $box;
        
        $positions[] = $position;
        $activitydates[] = $record['ActivityDate-Time'];
        $activities[] = $record['Operation'];
        $activitystaff[] = $record['OperationUser'];
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
                throw new Exception("Unable to remove sample from stack '$stack' in dewar '$dewar': User is not authorised");
        } catch(Exception $e) {
            echoError($xmldoc, $errorid, $e->getMessage());
            die();
        }
    }
    
    $intransaction = false;
    try {
        
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $intransaction = $dbh->beginTransaction();
        $selectlocation = $dbh->prepare("SELECT Sample
                                            FROM Location l
                                            WHERE l.Dewar = :d
                                                    AND l.Stack = :s
                                                    AND l.Box = :b
                                                    AND l.Position = :p
                                                    AND l.Sample != -1
                                                    LIMIT 1;");
        $updatelocation = $dbh->prepare("UPDATE Location l
                                            SET l.Sample = -1
                                            WHERE   l.Dewar = :d
                                                    AND l.Stack = :s
                                                    AND l.Box = :b
                                                    AND l.Position = :p;");
        $insertactivity = $dbh->prepare("INSERT INTO Activity
                                                        (
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
                                                            :stack,
                                                            :b,
                                                            :p,
                                                            :s,
                                                            :a,
                                                            :o,
                                                            :staff,
                                                            :r,
                                                            NOW(),
                                                            :u
                                                        );");
        
        
        
        $selectlocation->bindValue(':d', $dewar, PDO::PARAM_STR);
        $selectlocation->bindValue(':s', $stack, PDO::PARAM_INT);
        $selectlocation->bindValue(':b', $box, PDO::PARAM_INT);
        
        $updatelocation->bindValue(':d', $dewar, PDO::PARAM_STR);
        $updatelocation->bindValue(':s', $stack, PDO::PARAM_INT);
        $updatelocation->bindValue(':b', $box, PDO::PARAM_INT);
        
        $insertactivity->bindValue(':d', $dewar, PDO::PARAM_STR);
        $insertactivity->bindValue(':stack', $stack, PDO::PARAM_INT);
        $insertactivity->bindValue(':b', $box, PDO::PARAM_INT);
        $insertactivity->bindValue(':r', $group, PDO::PARAM_INT);
        $insertactivity->bindValue(':u', $user, PDO::PARAM_STR);
        
        
        
         
        foreach($positions as $index => $position) {
            
            $selectlocation->bindValue(':p', $position, PDO::PARAM_INT);
            
            $updatelocation->bindValue(':p', $position, PDO::PARAM_INT);
                      
            if(!$selectlocation->execute())
                throw new Exception('Error selecting location');
            
            $location = $selectlocation->fetch(PDO::FETCH_ASSOC);
            $sample = $location['Sample'];
            if(!$updatelocation->execute())
                throw new Exception('Could not reset location');
            $insertactivity->bindValue(':p', $position, PDO::PARAM_INT);
            $insertactivity->bindValue(':s', $sample, PDO::PARAM_INT);
            $insertactivity->bindValue(':a', date("Y-m-d H:i:s", $activitydates[$index]), PDO::PARAM_STR);
            $insertactivity->bindValue(':o', $activities[$index], PDO::PARAM_INT); // should always be 2 (thaw)
            $insertactivity->bindValue(':staff', $activitystaff[$index], PDO::PARAM_STR);
            if(!$insertactivity->execute())
                throw new Exception('Could not set activity information');
        }
        
        if($intransaction) {
            $dbh->commit();
            $intransaction = false;
        }
        
    } catch(Exception $e) {
        if($intransaction) {
            $dbh->rollback();
            $intransaction = false;
        }
        echoError($xmldoc, $errorid, $e->getMessage());
        die();
    }
    
    
     
    echo $xmldoc->saveXML();    
    
    $dbh = null;

?>