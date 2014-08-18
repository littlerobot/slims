<?php

    function createXmlDocument() {
	/* create a blank document */
	$xmldoc = new DOMDocument("1.0", "ISO-8859-15");
	$root = $xmldoc->appendChild($xmldoc->createElement("root"));
	$root->setAttribute('success', 'true');
	return $xmldoc;
    }
   
    function saveRecordSetToXML(&$xmldoc, &$resultset) {
	$root = $xmldoc->documentElement;
        $root->setAttribute("success", "true");	
	/* simply loop through each record in result and apply an element to each field in the record
	    the $row array has both string and numeric indices so skip the numerics */
	 foreach($resultset as $row) {
		 $rownode = $root->appendChild($xmldoc->createElement('row'));
		 foreach($row as $field => $value) {
			 /* skip any field starting with a digit */
			 if(preg_match('/^\d/', $field) == 0) {
				 $rownode->appendChild($xmldoc->createElement($field, $value));
			 }
		 }
	 }
	 /* write the XML to the return */
	 return $xmldoc->saveXML();
    }
    
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