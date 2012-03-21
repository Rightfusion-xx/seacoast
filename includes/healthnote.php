<?php

$org = 'seacoast';
$contentUrl = 'http://www.healthnotes.info/http/healthnotes.cfm'; 
{
$url = "{$contentUrl}?org={$org}&page=ltdus.cfm";
}

echo '<!-- Begin Healthnotes content -->';
echo file_get_contents($url);  
echo '<!-- End Healthnotes content -->';

?>
