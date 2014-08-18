<?php
    session_start();
    
    include_once('connect-database-read.php');
    include_once("../session.php");
    include_once("save-xml.php");
    
    error_reporting(E_ERROR);
    ini_set("display_errors", 1);
       
    /* if we've got this far, it's safe to output as XML */
    header('Content-Type:  text/xml');
    
    $xmldoc = createXmlDocument();
       
    $group = $_SESSION['Group'];
        
    
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    
    $querystart = "SELECT
                            l.Dewar,
                            l.Stack,
                            l.Box,
                            l.Position,
                            c.CellLine,
                            c.BasalMedia,
                            c.PassageNumber,
                            c.Comment,
                            cl.PassageTechnique,
                            cl.Tissue,
                            cl.Species,
                            cl.GrowthMode,
                            cl.Description,
                            cl.Morphology,
                            cl.Kayrotype,
                            cl.NegativeMycoplasmaTestDate,
                            ao.ActivityDate,
                            ao.Operation,
                            st.ResearchGroup
                    FROM Location l
                        INNER JOIN
                            (SELECT cs.ID,
                                    CellLine,
                                    b.BasalMedia,
                                    PassageNumber,
                                    Comment
                            FROM CellSample cs 
                                    LEFT JOIN BasalMedia b ON cs.BasalMedia = b.ID
                            ) c ON c.ID = l.Sample
                        INNER JOIN
                            (SELECT CellLineName,
                                    PassageTechnique,
                                    t.Tissue,
                                    sp.Species,
                                    GrowthMode,
                                    Description,
                                    m.Morphology,
                                    Kayrotype,
                                    NegativeMycoplasmaTestDate
                            FROM  CellLine cc
                                    LEFT JOIN
                                        Tissue t ON t.ID = cc.Tissue
                                    LEFT JOIN
                                        Morphology m ON m.ID = cc.Morphology
                                    LEFT JOIN
                                        Species sp ON sp.ID = cc.Species
                            ) cl ON cl.CellLineName = c.CellLine
                        LEFT JOIN
                            (SELECT a.Sample,
                                    a.ActivityDate,
                                    o.Operation
                            FROM Activity a
                            LEFT JOIN Operation o ON a.Operation = o.ID
                            WHERE o.Operation = 'Freeze') ao ON ao.Sample = l.Sample
                        LEFT JOIN
                            Stack st ON l.Dewar = st.Dewar
                                        AND l.Stack = st.Stack
                    WHERE";
                                
    $search = isset($_REQUEST['search']) ? $_REQUEST['search'] : 0;
    if($search == 0) {
               
        $dewar = isset($_REQUEST['dewar']) ? $_REQUEST['dewar'] : null;
        $stack = isset($_REQUEST['stack']) ? $_REQUEST['stack'] : null;
        $box = isset($_REQUEST['box']) ? $_REQUEST['box'] : null;
              
        $positions = isset($_REQUEST['positions']) ? array_filter(explode('+', $_REQUEST['positions'])) : Array(-1);
        
            
    
        
        $resultset = null;
        try {
            $querystring = sprintf($querystart . " l.Dewar = :d AND
                                                    l.Stack = :s AND
                                                    l.Box = :b AND
                                                    l.Position IN (%s);", implode(',', $positions));
           
            $statement = $dbh->prepare($querystring);
            $statement->bindValue(':d', $dewar, PDO::PARAM_STR);
            $statement->bindValue(':s', $stack, PDO::PARAM_INT);
            $statement->bindValue(':b', $box, PDO::PARAM_INT);
                   
            $statement->execute();        
            $resultset = $statement->fetchAll();
        } catch(PDOException $e) {
            echoError($xmldoc, "get-content-error", "Could not retreive content: Error running query '" . $e->getMessage(). "'");
            die();
        }
        
    } else { // $search == 1
        
        $fields = isset($_REQUEST['fields']) ? array_filter(explode('+', $_REQUEST['fields'])) : Array();
        if(count($fields) == 0) {
            echoError($xmldoc, $errorid, 'no fields to search');
            die();
        }
        
        $term = isset($_REQUEST['term']) ? $_REQUEST['term'] : null;
        if(!$term || strlen($term) === 0) {
            echoError($xmldoc, $errorid, 'empty search term');
            die();
        }
        $singlerecord = isset($_REQUEST['single-record']) ? $_REQUEST['single-record'] : 0;
        $maxrecords = isset($_REQUEST['max-records']) ? $_REQUEST['max-records'] : 100;
        $grouprecords = isset($_REQUEST['group-records']) ? $_REQUEST['group-records'] : 0;
        
        
        function callback($item) {
            return sprintf("CAST(`%s` AS CHAR(255)) LIKE '%%%%%%%s%%%%'", $item, 's');
        }
        
        $f = array_map("callback", $fields);
        
        $t = array_fill(0, count($fields), $term);
        $a = join(' OR ', $f);
        $where = "(" . vsprintf($a, $t) . ")";
        $where .= ($grouprecords != 0) ? sprintf(" AND st.ResearchGroup = %d", $group) : "";
        $limit = sprintf('LIMIT %d', $maxrecords);
        
        $querystring = sprintf($querystart . " %s %s", $where, $limit);
        
        if($singlerecord == 1) {
            $querystring = "SELECT  Dewar,
                                    Stack,
                                    Box,
                                    MIN(Position) AS Position,
                                    MIN(CellLine) AS CellLine,
                                    MIN(Comment) AS Comment,
                                    MIN(Description) AS Description,
                                    MIN(Tissue) AS Tissue,
                                    MIN(Species) AS Species,
                                    MIN(Morphology) AS Morphology,
                                    MIN(BasalMedia) AS BasalMedia,
                                    MIN(PassageNumber) AS PassageNumber,
                                    MIN(PassageTechnique) AS PassageTechnique,
                                    MIN(GrowthMode) AS GrowthMode,
                                    MIN(Kayrotype) AS Kayrotype
                            FROM ((" . $querystring . ") t1)
                            GROUP BY
                                Dewar,
                                Stack,
                                Box";
        }
        $querystring .= ';';
                
        try {                        
            $resultset = $dbh->query($querystring);
            if(!$resultset) {
                echoError($xmldoc, $errorid, 'no records');
                die();
            }
        } catch (Exception $e) {
            echoError($xmldoc, "get-content-error", "Could not retreive content: Error running query '" .$querystring . "' '" . $e->getMessage(). "'");
            die();
        }
    
    }
       
    echo saveRecordSetToXML($xmldoc, $resultset);
    
     /* close the database connection */
     $dbh = null;
?>