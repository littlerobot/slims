<?php

    /* session.php
    *   
    *   php script that defines functions associated with the user and their SLIMS login status
    *   anyone can access SLIMS, but if not in the SLIMS Users table, they'll only be able to use the
    *   Contents tab (ie see the contents of the database)
    *   non-admin can add/remove samples from their own stacks, add new cell lines etc
    *   admin can do the same as non-admin, but can update anyone's stacks. admin also has access to the User, Stack and History tables
    */
 
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
    
    function beginSession($id) {
        $_SERVER['REMOTE_USER'] = $id;
    }
    
    // end session by unsetting all the appropriate session variables
    // REMOTE_USER is used for testing (where $_SERVER['REMOTE_USER'] is unavailable)
    function endSession() {
        unset($_SESSION['id']);
        unset($_SESSION['Name']);
        unset($_SESSION['Group']);
        unset($_SESSION['IsAdmin']);
        unset($_SESSION['REMOTE_USER']);
    }
      
    /* use raven id to lookup slims user information */
    function isValidUser() {
        
        // the user's id should always be in $_SERVER['REMOTE_USER'] if Raven
        // is working. for testing, it will be in the $_SESSION['REMOTE_USER']
        if($_SERVER["SERVER_ADDR"] != "192.168.1.55") {
            $id = $_SERVER['REMOTE_USER'];
        } else {
            if(!isset($_SESSION['REMOTE_USER']))
                return false;
            $id = $_SESSION['REMOTE_USER'];
            $_SERVER['REMOTE_USER'] = $id;
        }
        
        // check if we're already logged in
        if(isset($_SESSION['id']) && $id == $_SESSION['id'])
            return true;
        endSession();
        
        // import the database handle
        global $dbh;
        
        // lookup on the Staff table with the Raven ID,
        // we're not valid if the id is not found
        $resultset = null;
        try {
            $statement = $dbh->prepare( "SELECT
                                            s.ID,
                                            s.StaffName AS Staff,
                                            r.ID AS ResearchGroupID,
                                            r.IsAdmin
                                        FROM
                                            Staff s
                                                INNER JOIN ResearchGroup r
                                                    ON s.ResearchGroup = r.ID
                                        WHERE
                                            s.ID LIKE :s
                                        LIMIT 1");
            
       
            $results = $statement->execute(array(':s' => $id));
            $resultset = $statement->fetch(PDO::FETCH_ASSOC);
            if(!$results || $resultset === false)
                return false;
        } catch(Exception $e) {
            return false;
        }
        // insert the returned data into the session array
        populateSession($resultset);
        return true;
    }
    
    /* set the query results to the session variable */
    function populateSession(&$results) {
        $_SESSION['id'] = $_SERVER['REMOTE_USER'];
        $_SESSION['Name'] = $results['Staff'];
        $_SESSION['Group'] = $results['ResearchGroupID'];
        $_SESSION['IsAdmin'] = $results['IsAdmin'];
        $_SESSION['REMOTE_USER'] = $_SERVER['REMOTE_USER'];
    }
    
    function isAdminUser() {
        if(!isset($_SESSION['IsAdmin']))
            return 0;
        return $_SESSION['IsAdmin']; // 0 or 1
    }

    // again, use $_SESSION if testing, otherwise $_SERVER
    function getRemoteUser() {
        if($_SERVER["SERVER_ADDR"] != "192.168.1.55") 
            return $_SERVER['REMOTE_USER'];
        else {
            if(isset($_SESSION['REMOTE_USER']))
               return $_SESSION['REMOTE_USER'];
            return null;
        }
    }
    
    function getGroup() {
        if(isset($_SESSION['Group']))
            return $_SESSION['Group'];
        return null;
    }
    
    // if we're valid the user's name will be stored in the session,
    // if not, return the user's Raven ID
    function getUserName() {
        if(!isset($_SESSION['Name'])) {
            return getRemoteUser();
        }
        return $_SESSION['Name'];
    }
    
    /* send the user to the mockup raven login screen */
    function redirect() {
        header('Location: login.php');
    }
    
    // redirect with a flag to show error message
    function redirectInvalid() {
        header('Location: login.php?r=1');
    }

?>