<?php
    session_start();
    include_once('connect-database-read.php');
    include_once("../session.php");
    include_once('save-xml.php');
    
    header('Content-Type:  text/xml');
    
    $xmldoc = createXmlDocument();
       
    $errorid = 'get-cell-line-names-error';
    
    $resultset = null;
    try {
        $querystring ="SELECT 	CellLineName AS Name,
				PassageTechnique,
				t.Tissue AS TissueName,
				s.Species AS SpeciesName,
				Description,
				m.Morphology,
				GrowthMode,
				Kayrotype,
				NegativeMycoplasmaTestDate,
				Document
			FROM CellLine c
			    LEFT JOIN Tissue t ON c.Tissue = t.ID
			    LEFT JOIN Species s ON c.Species = s.ID
			    LEFT JOIN Morphology m ON c.Morphology = m.ID";
        $resultset = $dbh->query($querystring);
    } catch(PDOException $e) {
        echoError($xmldoc, $errorid, "Error running query: " . $e->getMessage());
        die();
    }
    
       
    echo saveRecordSetToXML($xmldoc, $resultset);
    
     /* close the database connection */
     $dbh = null;
?>