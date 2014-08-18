<?php 
	
	include_once("connect-database-read.php");
	include_once("save-xml.php");
		
	$resultset = null;
	try {
		$resultset = $dbh->query("SELECT * FROM ViewContents");
	} catch (PDOException $e) { 
		echo "Error!: " . $e->getMessage() . "<br/>";
		die();
	}
	
	header("Content-type: text/xml");
	
	echo saveRecordSetToXML($resultset);
	
	/* close the database connection */
	$dbh = null;	
	
?>
