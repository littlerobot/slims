<?php
    session_start();
    
    // script for the mockup Raven login, populating $_SESSION
     
    include_once('queries/connect-database-read.php');
    include_once('session.php');
      
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
       
    $_SESSION['REMOTE_USER'] = $_REQUEST["slims-id"];
     
    
    echo print_r($_SESSION);
    header('Location: slims.php');

?>