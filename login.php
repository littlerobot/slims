<html>
    <head>
            <style type="text/css">
                div.error {
                    color: red;
                    font-size: 32px;
                    font-weight: bold;
                    padding: 10px;
                }
            </style>
    </head>
    <body>
        <?php
        
            // script for the mockup Raven login
         
            $reason = 0;
            if(isset($_GET['r']))
                $reason = $_GET['r'];
            if($reason == 1) {
                ?><div class="error">Invalid username/password!</div><?php
            }
        ?>
        <form method="post" action="validate.php">
            Username: <input type="text" name="slims-id"><br/>
            Password: <input type="password" name="slims-password"><br/>
            <input type="submit" value="Submit">
        </form>
    </body>
</html>