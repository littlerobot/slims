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
    
    $key = 'new-cellline-name';
    if(!isset($_REQUEST[$key])) {
        echoError($xmldoc, $key, "Cell Line Name parameter not set");
        die();
    }
       
    $celllinename = trim($_REQUEST[$key]);
    if(strlen($celllinename) == 0) {
        echoError($xmldoc, $key, "Cell Line Name parameter is empty");
        die();
    }
    
    $resultset = null;
    try {
        $querystring = sprintf("SELECT c.CellLineName
                                FROM CellLine c
                                WHERE c.CellLineName LIKE '%s' LIMIT 1;", $celllinename);
        $resultset = $dbh->query($querystring);
    } catch(PDOException $e) {
        echoError($xmldoc, "select-query-error", $e->getMessage());
        die();
    }
    if(!$resultset || $resultset->rowCount() != 0) {
        echoError($xmldoc, $key, "Cell line with name '$celllinename' already exists");
        die();
    }
    
    
    $cellstatement = $dbh->prepare("INSERT INTO CellLine (
                                            CellLineName,
                                            PassageTechnique,
                                            Tissue,
                                            Species,
                                            GrowthMode,
                                            Description,
                                            Morphology,
                                            Kayrotype,
                                            NegativeMycoplasmaTestDate
                                        ) VALUES (
                                                    :celllinename,
                                                    :Passagetechnique,
                                                    :TissueID,
                                                    :SpeciesID,
                                                    :growthmodename,
                                                    :descriptiontext,
                                                    :MorphologyID,
                                                    :kayrotypeID,
                                                    :datetimestamp
                                                    );");
    
    $cellstatement->bindValue(':celllinename', $celllinename, PDO::PARAM_STR);
    $cellstatement->bindValue(':Passagetechnique', isset($_REQUEST['new-cellline-passage-technique']) ? $_REQUEST['new-cellline-passage-technique'] : null, PDO::PARAM_STR);
    $cellstatement->bindValue(':TissueID', isset($_REQUEST['new-cellline-tissue']) ? $_REQUEST['new-cellline-tissue'] : null, PDO::PARAM_INT);
    $cellstatement->bindValue(':SpeciesID', isset($_REQUEST['new-cellline-species']) ? $_REQUEST['new-cellline-species'] : null, PDO::PARAM_INT);
    $cellstatement->bindValue(':growthmodename', isset($_REQUEST['new-cellline-growth-mode']) ? $_REQUEST['new-cellline-growth-mode'] : null, PDO::PARAM_STR);
    $cellstatement->bindValue(':descriptiontext', isset($_REQUEST['new-cellline-description']) ? $_REQUEST['new-cellline-description'] : null, PDO::PARAM_STR);
    $cellstatement->bindValue(':MorphologyID', isset($_REQUEST['new-cellline-morphology']) ? $_REQUEST['new-cellline-morphology'] : null, PDO::PARAM_INT);
    $cellstatement->bindValue(':kayrotypeID', isset($_REQUEST['new-cellline-kayrotype']) ? $_REQUEST['new-cellline-kayrotype'] : null, PDO::PARAM_INT);
    
    $d = null;
    if(isset($_REQUEST['test-time'])) {
        $d = $_REQUEST['test-time'];
        if(strlen(trim($d)) !== 0 && is_numeric($d)) {
           $d = date("Y-m-d H:i:s", $d);
        }
    }
    $cellstatement->bindValue(':datetimestamp', $d, PDO::PARAM_STR);
    
    $documentval = isset($_REQUEST['new-cellline-documents']) ? $_REQUEST['new-cellline-documents'] : null;
    $documents = null;
    $docstatement = null;
    if($documentval) {
        $documents = explode('+', $documentval);
        if(count($documents) != 0) {
            $docstatement = $dbh->prepare("INSERT INTO Documents (
                                                CellLineName,
                                                Document
                                          ) VALUES (
                                                :celllinename,
                                                :document
                                          );");
            $docstatement->bindValue(':celllinename', $celllinename, PDO::PARAM_STR);
        }
    } else {
        $documents = Array();
    }
    
    try {
        $intransaction = false;
        $intransaction = $dbh->beginTransaction();
        $cellstatement->execute();
        
        foreach($documents as $doc) {
            $doc = trim($doc);
            if(strlen($doc) != 0) {
                $docstatement->bindValue(':document', $doc, PDO::PARAM_STR);
                $docstatement->execute();
            }
        }
        
        if($dbh->commit())
            $intransaction = false;
    } catch(PDOException $e) {
        echoError($xmldoc, $key, $e->getMessage());
        if($intransaction)
            $dbh->rollBack();
        die();
    }
   
    echo $xmldoc->saveXML();
    
    $dbh = null;
  
?>