<?php
    session_start();
    
    include_once('connect-database-read.php');
    include_once('session.php');
      
    error_reporting(E_ERROR);
    ini_set("display_errors", 1);
    
    beginSession();    
    
    $slimsidindex = 'slims-session-id';
    
    $_SESSION['id']++;
    $_SESSION[$slimsidindex] = $_REQUEST['slims-id'];
    
    echo print_r($_SESSION[$slimsidindex]);
    //exit();
    /*
    echo print_r($_SESSION);
    foreach($_SESSION as $sesh)
        echo print_r($sesh) . '<br/>';
     exit();
    */
    
    if(!isValidUser()) {
        redirect();
        endSession();
        exit();
    }
    header('Location: ../test3.html');

?>