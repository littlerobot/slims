<?php
    $dbh = null;
    $user = "read-write";
    $pass = "";
    try {
	$dbh = new PDO('mysql:host=localhost;dbname=slims', $user, $pass, array(PDO::ATTR_PERSISTENT => true));
	$dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e) {
        echo "error connecting to database: " . $e->getMessage();
        die();
    }
?>