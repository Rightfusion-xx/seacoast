 
 
<table width="100%" border="0">
  <tr> 
      
    <td width="193" height="36"> 
      <p><img src="https://www.seacoastvitamins.com/images/hni_logo_trans.gif" width="135" height="35"> 
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
  $results = file_get_contents($url);
}
else
{
  $results = 'Try entering some search terms ';
}

?>
      <div><font face="Arial, Helvetica, sans-serif" size="2">Product Information 
        </font> 
        <form method="post" action="/search.php">
<input type="text" name="criteria">
<input type="submit" value="Search">
</form>
</div>
    </td>
      
    <td colspan="2" width="800" height="36">
      <p><font face="Arial, Helvetica, sans-serif" size="2">Healthnotes has natural 
        answers to your everday health questions ranging from Vitamin Guide, Herbal 
        Remedies, Homeopathy, Sports &amp; Fitness, Women's &amp; Men's Health 
        much more!</font></p>
      </td>
    </tr>
  </table>
  
