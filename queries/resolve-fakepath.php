<?php
    header('Content-type: application/json');
    $path = print_r($_FILES);
    
    /* echo '<?xml version="1.0 encoding="ISO-8859-15"?>
            <root success="true">
                <result>
                    <path>'.$path.'</path>
                </result>
            </root>'; */
    echo '{ "success": "true", "records" : { "path": "'.$path.'"} }';
?>