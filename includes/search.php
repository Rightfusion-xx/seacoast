<?php
// Sample Remote Integration of Healthnotes search
//
// This should work for PHP > 4.0.3 with the 'allow_url_fopen' configuration 
// directive enabled. For details, see 
// http://us4.php.net/manual/en/features.remote-files.php

// Your Healthnotes-assigned org ID and URL
$org = 'seacoast';
$searchUrl = 'http://www.healthnotes.info/http/search.cfm';

if (empty($_POST))
{
  // 'Next' and 'Previous' links pass some GET parameters
  $criteria = empty($_GET['criteria']) ? '' : rawurlencode($_GET['criteria']);
  $rowstart = empty($_GET['rowstart']) ? 1 : $_GET['rowstart'];
  $numrows = empty($_GET['numrows']) ? 25 : $_GET['numrows'];
}
else
{
  // Search form should post a field named 'criteria' and optionally 'rowstart' and 'numrows'
  $criteria = empty($_POST['criteria']) ? '' : rawurlencode($_POST['criteria']);
  $rowstart = empty($_POST['rowstart']) ? 1 : $_POST['rowstart'];
  $numrows = empty($_POST['numrows']) ? 25 : $_POST['numrows'];
}


if (!empty($criteria))
{
  // Access Healthnotes search service with specified criteria
  $url = sprintf('%s?org=%s&criteria=%s&rowstart=%d&numrows=%d', 
                 $searchUrl, $org, $criteria, $rowstart, $numrows);
    $hn=file_get_contents($url);
    $hn=str_replace('healthnotes.php','health_library.php',$hn);
    $hn=str_replace('org=seacoast&ContentID','article',$hn);
    $hn=str_replace('org=seacoast&contentid','article',$hn);
    $hn=str_replace('org=seacoast&','',$hn);
    $results = $hn;
}
else
{
  $results = 'Try entering some search terms ';
}

echo '<!-- Begin Healthnotes search results -->';
echo $results;
echo '<!-- End Healthnotes search results -->';
?>
