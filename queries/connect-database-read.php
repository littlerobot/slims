<?php
    $dbh = null;
    $user = "read-user";
    $pass = "";
    try {
	$dbh = new PDO('mysql:host=localhost;dbname=slims', $user, $pass, array(PDO::ATTR_PERSISTENT => true));
    } catch(PDOException $e) {
        echo "error connecting to database: " . $e->getMessage();
        die();
    }
?>