<?php
    $dbh = null;
    $user = "slims";
    $pass = "slims";
    try {
	$dbh = new PDO('mysql:host=localhost;dbname=slims', $user, $pass, array(PDO::ATTR_PERSISTENT => true));
    } catch(PDOException $e) {
        echo "error connecting to database: " . $e->getMessage();
        die();
    }
?>