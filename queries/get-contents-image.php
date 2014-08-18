<?php
    session_start();
    include_once("../session.php");
    include_once('connect-database-read.php');
    include_once("save-xml.php");
        
    
    
    $width = 1000;
    $height = 1000;
    if(isset($_REQUEST['width']) && $_REQUEST['width']) {
        $width = $_REQUEST['width'];
    }
    if(isset($_REQUEST['height']) && $_REQUEST['height']) {
        $height = $_REQUEST['height'];
    }
    
    $dewar = isset($_REQUEST['dewar']) ? $_REQUEST['dewar'] : null;
    $stack = isset($_REQUEST['stack']) ? $_REQUEST['stack'] : null;
    $box = isset($_REQUEST['box']) ? $_REQUEST['box'] : null;
          
    $positions = isset($_REQUEST['positions']) ? explode('+', $_REQUEST['positions']) : null;
    if(count($positions) == 0)
        $positions = null;
        
    $resultset = null;
    try {
        $querystring = sprintf("SELECT  l.Position,
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
                                        ao.Operation
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
                                                Species,
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
                                        ) cl ON cl.CellLineName = c.CellLine
                                    LEFT JOIN
                                        (SELECT a.Sample,
                                                a.ActivityDate,
                                                o.Operation
                                        FROM Activity a
                                            LEFT JOIN Operation o ON a.Operation = o.ID) ao ON ao.Sample = l.Sample
                                WHERE
                                    l.Dewar = :d AND
                                    l.Stack = :s AND
                                    l.Box = :b AND
                                    l.Position IN (%s)
                                ORDER BY l.Position;", implode(',', $positions));
       
        $statement = $dbh->prepare($querystring);
        $statement->bindValue(':d', $dewar, PDO::PARAM_STR);
        $statement->bindValue(':s', $stack, PDO::PARAM_INT);
        $statement->bindValue(':b', $box, PDO::PARAM_INT);
               
        $statement->execute();        
        $resultset = $statement->fetchAll();
    } catch(PDOException $e) {
        header('Content-type: text/xml');
        $xmldoc = createXmlDocument();
        echoError($xmldoc, "get-content-error", "Could not retreive content: Error running query '" . $e->getMessage(). "'");
        die();
    }
    
    $image = imagecreate($width + 1, $height + 1);
    
    $white = imagecolorallocate($image, 255, 255, 255);
    $black = imagecolorallocate($image, 0, 0, 0);
    $grey = imagecolorallocate($image, 192, 192, 192);
    
    $poswidth = $width/10;
    $posheight = $height/10;
    
    for($i = 1, $row = $resultset[0], $x = 0, $j = 0; $i <= 100; $i++){
        if(($i % 10) == 1){
            $y = 0;
            if($i != 1)
                $x += $poswidth;
        } else
            $y += $posheight;
               
        
        $colour = $grey;
        $cellnametext = $activitytext = "";
        if($i == $row['Position']) {
            $colour = $white;
            $cellnametext = $row['CellLine'];
            $activitytext = $row['ActivityDate'] ?: 'No date recorded';
            $row = $resultset[++$j];
        }
            
        imagefilledrectangle($image, $x, $y, $x + $poswidth, $y + $posheight, $colour);
        imagerectangle($image, $x, $y, $x + $poswidth, $y + $posheight, $black);
        imagestring($image, 4, $x + 1, $y + 1, $cellnametext, $black);
        imagestring($image, 4, $x + 1, $y + imagefontheight(4), $activitytext, $black);
    }
    
    header('Content-type: image/png');
    
    imagepng($image);
    imagedestroy($image);
    
    /* close the database connection */
     $dbh = null;
    
     
?>