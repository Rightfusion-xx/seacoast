<?php

/*

  $Id: column_left.php,v 1.15 2003/07/01 14:34:54 hpdl Exp $
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2003 osCommerce
  Released under the GNU General Public License

*/ 



  if (COLUMN_LEFT_SLICE_SET != 0){
	$slice_set = COLUMN_LEFT_SLICE_SET;
	$bgcol = constant('INFOBOX_SKIN_BGCOL' . $slice_set);
	
   echo '<table border="0" width="100%" cellspacing="0" cellpadding="0"';
   echo '<tr height="100%">';
 	 echo '<td width="' . BOX_WIDTH . '" valign="top" height="100%">';
   echo '<table cellspacing="0" cellpadding="0" border="0" height="100%"><tr><td><img src="images/slice_sets/' . $slice_set . '/top_left.jpg"></td>' .

     '<td background = "' . DIR_WS_IMAGES . 'slice_sets/' . $slice_set . '/top_background.jpg" class="newinfobox_top' . $slice_set . '" valign="middle" align="center" width="100%">' . $header_text;

    echo '</td><td><img src="images/slice_sets/' . $slice_set . '/top_right.jpg"></td></tr>';
    echo '<tr><td background = "' . DIR_WS_IMAGES . 'slice_sets/' . $slice_set . '/left_background.jpg" height="100%"></td>';
    echo '<td bgcolor = "' . $bgcol . '" valign="top" width="100%" height="100%">' ;
  	echo '<table cellspacing="0" cellpadding="0" border="0">';
}																				 

else {
?>

<table border="0" width="100%" cellspacing="3" cellpadding="3">
  <tr>
  <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
<?php
}
  require(DIR_WS_BOXES . 'search.php');
  require(DIR_WS_BOXES . 'healthnotes_articles.php');
  
    if ((USE_CACHE == 'true') && empty($SID)) {
    echo tep_cache_manufacturers_box();
  } else {
   // include(DIR_WS_BOXES . 'manufacturers.php');
  }

  if ((USE_CACHE == 'true') && empty($SID)) {

    echo tep_cache_categories_box();
  } else {
  //  include(DIR_WS_BOXES . 'coolmenu.php');
  }




  if (COLUMN_LEFT_SLICE_SET != 0){
	  echo '</table>';
	  echo '</td><td background = "' . DIR_WS_IMAGES . 'slice_sets/' . $slice_set . '/right_background.jpg"></td></tr>';
    echo '<tr><td><img src="images/slice_sets/' . $slice_set . '/bottom_left.jpg"></td>';
    echo '<td background = "' . DIR_WS_IMAGES . 'slice_sets/' . $slice_set . '/bottom_background.jpg"></td>';
    echo '<td><img src="images/slice_sets/' . $slice_set . '/bottom_right.jpg"></td></tr></table></td>';
  }

	else echo '</table></td>';

?>