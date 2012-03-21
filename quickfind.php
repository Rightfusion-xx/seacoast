<?php
/*
  $Id: quickfind.php,v 1.10 2005/08/04 23:25:46 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright © 2003 osCommerce

  Released under the GNU General Public License

*/

require('includes/application_top.php');
$results = array();
$q = '';
$name = '';
$id = '';
$url = '';
$q = addslashes(preg_replace("/%[^0-9a-zA-Z ]%/", "", $_GET['keywords']) );
$limit = 100;

  if ( isset($q) && tep_not_null($q) ) {

  $searchwords = explode(" ",$q);
  $nosearchwords = sizeof($searchwords);
  foreach($searchwords as $key => $value) {
    if ($value == '')
      unset($searchwords[$key]);
  }
  $searchwords = array_values($searchwords);
  $nosearchwords = sizeof($searchwords);
  foreach($searchwords as $key => $value) {
    $booltje = '+' . $searchwords[$key] . '*';
    $searchwords[$key] = $booltje;
  }
  $q = implode(" ",$searchwords);

   $query = "SELECT pd.products_id, pd.products_name, p.products_model 
    FROM " . TABLE_PRODUCTS_DESCRIPTION . " pd 
    INNER JOIN " . TABLE_PRODUCTS . " p 
    ON (p.products_id = pd.products_id) 
    WHERE (MATCH (p.products_model) AGAINST ('" .$q."' IN BOOLEAN MODE)
    OR MATCH (pd.products_name) AGAINST ('".$q."' IN BOOLEAN MODE))
    AND p.products_status = '1' 
    AND pd.language_id = '" . (int)$languages_id . "'
    ORDER BY pd.products_name ASC
    LIMIT " . $limit;

   $query = tep_db_query($query);

    if ( tep_db_num_rows($query) ) {
      while ( $row = tep_db_fetch_array($query) ) {
          $name = $row['products_name'];
          $id = $row['products_id'];
          $url = tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $id);
          $results[] = '<a href="' .  $url . '">' .  $name . '</a>' . $model . "\n";
        }
    } else {
      $results[] = 'Search Results';
    }
    echo implode('<br><br>' . "\n", $results);
    // To use <DOCTYPE> XHTML 1.0 or higher
	// echo implode('<br />' . "\n", $results); 
  } else {
    echo "Type for results...";
  }
?>