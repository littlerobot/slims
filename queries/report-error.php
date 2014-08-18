<?php
     function echoError(&$xmldoc, $name, $value) {
        $rootnode = $xmldoc->documentElement;
        $rootnode->setAttribute("success", "false");
        $result = $rootnode->appendChild($xmldoc->createElement("result"));
        $id = $result->appendChild($xmldoc->createElement("id"));
        $id->appendChild($xmldoc->createTextNode($name));
        $message = $result->appendChild($xmldoc->createElement("message"));
        $message->appendChild($xmldoc->createTextNode($value));
        echo $xmldoc->saveXML();
    }
?>