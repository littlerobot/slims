<?php

    session_start();
    
    include_once('connect-database-read.php');
    include_once("../session.php");
    include_once("save-xml.php");
    
    error_reporting(E_ERROR);
    ini_set("display_errors", 1);
    
    function getCoordinate($position) {
        $position--; // must be 0-indexed
        return Chr(floor($position / 10) + ord('A')) . (($position % 10) + 1);    
    }
    $all = (isset($_REQUEST["all"]) && $_REQUEST["all"] == 1);
    $selection = isset($_REQUEST["selection"]) ? $_REQUEST["selection"] : null; 
    $type = isset($_REQUEST["export-type"]) ? $_REQUEST["export-type"] : 'list'; // defaults to a list over grid
    $xml = isset($_REQUEST['positions']) ? $_REQUEST['positions'] : null;
    
    $query = "SELECT
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
                                        AND l.Stack = st.Stack";
    $parameters = Array();
    
    if(!$all) {
        $xml = urldecode($xml);
        // echo $xml;
        $inputdoc = new DOMDocument();
        $inputdoc->loadXML($xml);
        $rows = $inputdoc->getElementsByTagName('row');
        $length = $rows->length;
        $clause = null;
        
        for($r = 0; $r < $length; $r++) {
            
            $clause .= " OR (l.Dewar = :dewar{$r}";
            
            $row = $rows->item($r);
            $dewar = $row->getElementsByTagName("Dewar")->item(0);
            $parameters[":dewar{$r}"] = $dewar->nodeValue;
            if($selection != "Dewar(s)") {
                $stack = $row->getElementsByTagName("Stack")->item(0);
                $parameters[":stack{$r}"] = $stack->nodeValue;
                
                $clause .= " AND l.Stack = :stack{$r}";
            }
            if($selection != "Dewar(s)" && $selection != "Stack(s)") {
                $box = $row->getElementsByTagName("Box")->item(0);
                $parameters[":box{$r}"] = $box->nodeValue;
                $clause .= " AND l.Box = :box{$r}";
            }
            if($selection != "Dewar(s)" && $selection != "Stack(s)" && $selection != "Box(es)") {
                $position = $row->getElementsByTagName("Position")->item(0);
                $parameters[":position{$r}"] = $position->nodeValue;
                $clause .= " AND l.Position = :position{$r}";
            }
            
            $clause .= ")";
        }
        if($clause != null) {
            $clause = substr($clause, strlen(" OR ") - 1);
            $query .= " WHERE " . $clause;
        }
    }
    
    $query .= " ORDER BY l.Dewar, l.Stack, l.Box, l.Position";
    // echo $query;
    // die();
    $statement = $dbh->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    try {
        $statement->execute($parameters);
    } catch(Exception $e) {
        echo $e->message;
        die();
    }
    
    $timestamp = date("Ymd-His");
    header("Content-type: application/vnd.ms-excel; charset=utf-8");
    header("Content-disposition: attachment; filename=export{$timestamp}.xml");
    /*
        <?xml version="1.0"?>
        <?mso-application progid="Excel.Sheet"?>
        <Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"
                xmlns:o="urn:schemas-microsoft-com:office:office"
                xmlns:x="urn:schemas-microsoft-com:office:excel"
                xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"
                xmlns:html="http://www.w3.org/TR/REC-html40">
            <Worksheet ss:Name="Sheet1">
		<Table>
			<Row>
				<Cell ss:Index="2">
					<Data ss:Type="String">A</Data>
				</Cell>
                        </Row>
		</Table>
	    </Worksheet>
	</Workbook>
      
    */
    $workbook = new DOMDocument("1.0", "utf-8");
    $workbook->loadXML("<?mso-application progid=\"Excel.Sheet\"?>
                            <Workbook   xmlns=\"urn:schemas-microsoft-com:office:spreadsheet\"
                                        xmlns:o=\"urn:schemas-microsoft-com:office:office\"
                                        xmlns:x=\"urn:schemas-microsoft-com:office:excel\"
                                        xmlns:ss=\"urn:schemas-microsoft-com:office:spreadsheet\"
                                        xmlns:html=\"http://www.w3.org/TR/REC-html40\"/>");
    $root = $workbook->getElementsByTagName("Workbook")->item(0);
    $sheet = $workbook->createElement("Worksheet");
    $sheet->setAttribute("ss:Name", "Sheet1");
    $root->appendChild($sheet);
    $table = $sheet->appendChild($workbook->createElement("Table"));
    $row = $table->appendChild($workbook->createElement("Row"));
    $row2 = $workbook->createElement("Row");
    $resultset = $statement->fetch();
    $i = 0;
    foreach ($resultset as $key => $val){
        if(!is_numeric($key)) {
            $cell = $row->appendChild($workbook->createElement("Cell"));
            $cell->setAttribute("ss:Index", $i + 1);
            $data = $cell->appendChild($workbook->createElement("Data", $key));
            $data->setAttribute("ss:Type", "String");
            $cell = $row2->appendChild($workbook->createElement("Cell"));
            $cell->setAttribute("ss:Index", $i + 1);
            if($key == 'Position') {
                $val = getCoordinate($val);
            }
            $data = $cell->appendChild($workbook->createElement("Data", $val));
            $data->setAttribute("ss:Type", is_numeric($val) ? "Number" : "String");
            $i++;
        }
    }
    $table->appendChild($row2);
    $i = 0;
    while($resultset = $statement->fetch()) {
        $row = $table->appendChild($workbook->createElement("Row"));
        foreach ($resultset as $key => $val){
            if(!is_numeric($key)) {
                if($key == 'Position') {
                    $val = getCoordinate($val);
                }
                $cell = $row->appendChild($workbook->createElement("Cell"));
                $cell->setAttribute("ss:Index", $i + 1);
                $data = $cell->appendChild($workbook->createElement("Data", $val));
                $data->setAttribute("ss:Type", is_numeric($val) ? "Number" : "String");
                $i++;
            }
        }
        $i = 0;
    }
        
    $dbh = null;
    echo $workbook->saveXML();
?>