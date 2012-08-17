<?php
/*
  $Id: meta_tags.php,v 3.0 2004/04/19 11:15:00 robw Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License
  Original script idea credited to Tamim - www.TMPanime.com Added: Category Meta Tag Generator v1.0

*/

////////////////////////////
// User Definable Section //
////////////////////////////

// First Define any Common Meta Elements used for all Manufacturers / Categories

// Common Meta Title appears BEFORE individual Category/Manufacturer Meta Titles
$common_meta_title = "osCommerce - ";

// Common Meta Keywords appears AFTER individual Category/Manufacturer Meta Keywords
$common_meta_keywords = "all, category, manufacturer, keywords, end, with, these, keywords";

// Common Meta Description appears AFTER individual Category/Manufacturer Meta Descriptions
$common_meta_description = "This common description appears at the end of every Category / Manufacturer meta description";


// Now define the default Meta Data. These are used if a category/manufacturer has a blank item of Meta Data and for Non Category/Manufacturer Sections of the main page.

$meta_title = "osCommerce";

$meta_keywords = "insert, your, keywords, here";

$meta_description = "Insert your standard description here";


////////////////////////////////////////
// No Edits required below this point //
////////////////////////////////////////

if (isset($cPath) && tep_not_null($cPath)) {

$metaCategoryArray = explode ("_",$cPath);
if (strpos($cPath, '_')) { $metaCategoryArray  = array_reverse($metaCategoryArray); }
$metaCategory = $metaCategoryArray[0];

$category_query = tep_db_query ("select title, keywords, description from " . TABLE_META_TAGS . " where categories_id = '" . $metaCategory . "'");

$metaData = tep_db_fetch_array ($category_query);

if ($metaData ['title']) $meta_title = $common_meta_title . " " . $metaData ['title'];
if ($metaData ['keywords']) $meta_keywords = $metaData ['keywords'] . ", " . $common_meta_keywords;
if ($metaData ['description']) $meta_description = $metaData ['description'] . " " . $common_meta_description;

}

if (isset($manufacturers_id) && tep_not_null($manufacturers_id)) {

$manufacturers_query = tep_db_query ("select title, keywords, description from " . TABLE_META_TAGS . " where manufacturers_id = '" . $manufacturers_id . "'");

$metaData = tep_db_fetch_array ($manufacturers_query);

if ($metaData ['title']) $meta_title = $common_meta_title . " " . $metaData ['title'];
if ($metaData ['keywords']) $meta_keywords = $metaData ['keywords'] . ", " . $common_meta_keywords;
if ($metaData ['description']) $meta_description = $metaData ['description'] . " " . $common_meta_description;

}


echo '<title>' . $meta_title . '</title>' . "\n";
echo '<meta name="keywords" content="' . $meta_keywords . '">' . "\n";
echo '<meta name="description" content="' . $meta_description . '">' . "\n";

?>