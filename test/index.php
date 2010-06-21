<?php

// Load the XML source
$xml = new DOMDocument;
$xml->load('docbook.xml');

$xsl = new DOMDocument;
$xsl->load('docbook.xsl');

// Configure the transformer
$proc = new XSLTProcessor;
$proc->importStyleSheet($xsl); // attach the xsl rules

//echo trim($proc->transformToDoc($xml)->firstChild->wholeText);

?> 