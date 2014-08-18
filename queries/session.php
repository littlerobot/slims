<?php

    $slimsidindex = 'slims-session-id';
    
    function beginSession() {
        if(!isset($_SESSION['id']))
            $_SESSION['id'] = 0;
    }
    
    function endSession() {
        if(isset($_SESSION['id']))
            unset($_SESSION['id']);
    }
    
    /* check that the user has successfully logged in through raven */
    function isRavenSession() {
        global $slimsidindex;
        if(!isset($_SESSION[$slimsidindex]))
           return false;
        return true;
    }
    
    /* use raven id to lookup slims user information */
    function isValidUser() {
        if(!isRavenSession()) {
            return false;
        }
        global $slimsidindex;
        $id = $_SESSION[$slimsidindex];
        
        global $dbh;
        if(!$dbh) {
            // connect
        }
        
        $querystring = sprintf( "SELECT
                                    s.ID,
                                    s.StaffName AS Staff,
                                    r.ID AS ResearchGroupID,
                                    false AS IsAdmin
                                FROM
                                    Staff s
                                        INNER JOIN ResearchGroup r
                                            ON s.ResearchGroup = r.ID
                                WHERE
                                    s.ID LIKE '%s'
                                LIMIT 1", $id);
        
        try {
            $results = $dbh->query($querystring);
            if(!$results)
                return false;
        } catch(Exception $e) {
            return false;
        }
        populateSession($results->fetch(PDO::FETCH_ASSOC));
        return true;
    }
    
    /* set the query results to the session variable */
    function populateSession($results) {
        global $slimsidindex;
        $_SESSION['Name'] = $results['Staff'];
        $_SESSION['Group'] = $results['ResearchGroupID'];
        $_SESSION['IsAdmin'] = $results['IsAdmin'];
    }
    
    /* send the user to the raven login screen */
    function redirect() {
        header('Location: login.php');
    }

?>