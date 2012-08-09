<?php

/*

  $Id: infobox_skin_mapping.php,v 1.0 2003/08/18 

  osCommerce, Open Source E-Commerce Solutions

  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

*/



  require('includes/application_top.php');

	//check that destination directories exist and are writeable


	    if (is_dir(DIR_FS_CATALOG . 'includes/boxes/')) {
      if (!is_writeable(DIR_FS_CATALOG . 'includes/boxes/')) $messageStack->add('Error  - ' . DIR_FS_CATALOG . 'includes/boxes/ is not writeable', 'error');
      } else {
        $messageStack->add('Error - ' . DIR_FS_CATALOG . 'includes/boxes/ does not exist', 'error');
      }
      


  if ($HTTP_GET_VARS['action']) {
    switch ($HTTP_GET_VARS['action']) {
    case 'save':
	   $skin_map = ($HTTP_POST_VARS['skin_map']);

		 //first empty all entries from the database as we are now to re-establish them!

		 $query = "truncate table " . TABLE_INFOBOX_SKIN_MAPPING;
		 tep_db_query ($query);
	   foreach ($skin_map as $key => $val){
		  $boxes_string = '<?php' . "\n";
		  $boxes_string .= '$skin_slice_set = ' . $val . ";\n";
      $boxes_string .= 'include (DIR_WS_INCLUDES . \'boxes_content/' . $key . '\');' . "\n";
			$boxes_string .= '?>';
			$query = "insert into " . TABLE_INFOBOX_SKIN_MAPPING . " (filename, slice_set_id) values ('" . $key . "', '" . $val . "')";
  		tep_db_query ($query);      							  
			$fp = fopen (DIR_FS_CATALOG . 'includes/boxes/' . $key, 'w');
			fwrite ($fp, $boxes_string);
			fclose($fp);
		 }
		 
		 
		 $header_set = $HTTP_POST_VARS['header_set'];
		 $header_height = getimagesize(DIR_FS_CATALOG_IMAGES . 'slice_sets/' . $header_set . '/top_background.jpg');
		 $header_height = $header_height[1];
		 $query = "update " . TABLE_CONFIGURATION . " set configuration_value = '" . $header_set . "' where configuration_key = 'INFOBOX_HEADERNAV_SLICE_SET'";
 		 tep_db_query ($query);
 		 $query = "update " . TABLE_CONFIGURATION . " set configuration_value = '" . $header_height . "' where configuration_key = 'INFOBOX_HEADERNAV_HEIGHT'";
		 tep_db_query ($query); 

		 
		 $footer_set = $HTTP_POST_VARS['footer_set'];
 		 $footer_height = getimagesize(DIR_FS_CATALOG_IMAGES . 'slice_sets/' . $footer_set . '/top_background.jpg');
		 $footer_height = $footer_height[1];
		 $query = "update " . TABLE_CONFIGURATION . " set configuration_value = '" . $footer_set . "' where configuration_key = 'INFOBOX_FOOTER_SLICE_SET'";
 		 tep_db_query ($query);
 		 $query = "update " . TABLE_CONFIGURATION . " set configuration_value = '" . $footer_height . "' where configuration_key = 'INFOBOX_FOOTER_HEIGHT'";
		 tep_db_query ($query);
		 
 		 $col_left_set = $HTTP_POST_VARS['col_left_set'];
		 $query = "update " . TABLE_CONFIGURATION . " set configuration_value = '" . $col_left_set . "' where configuration_key = 'COLUMN_LEFT_SLICE_SET'";
 		 tep_db_query ($query);
		 
 		 $col_right_set = $HTTP_POST_VARS['col_right_set'];
		 $query = "update " . TABLE_CONFIGURATION . " set configuration_value = '" . $col_right_set . "' where configuration_key = 'COLUMN_RIGHT_SLICE_SET'";
 		 tep_db_query ($query);
		 
 		 $main_set = $HTTP_POST_VARS['main_set'];
		 $query = "update " . TABLE_CONFIGURATION . " set configuration_value = '" . $main_set . "' where configuration_key = 'MAIN_CONTENT_SLICE_SET'";
 		 tep_db_query ($query);
		 
	 break;
   			
  }
 }

?>

<!doctype html>
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/general.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onload="SetFocus();">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
		      <tr>
        <td colspan="2"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo 'Infobox Skin Mapping'; ?></td>
          </tr>
<?php
			 echo '<tr><td align="center">';
			 echo '<table border="0" cellspacing="0" cellpadding="0">';
			 echo tep_draw_form('skin_map', FILENAME_INFOBOX_SKIN_MAPPING, 'action=save', 'post');
			 
//			 echo '<tr><td colspan="4" align="center"><b><u>Infobox Skins Currently in the Library</b></u><br><br></td></tr>';
       $dirhandle = opendir (DIR_FS_CATALOG . 'includes/boxes_content/');
			 while ($file = readdir($dirhandle)) {
		 	   if (($file != '.') && ($file != '..')) $boxes_avail[] = $file;
			 } 

			 echo '<tr><td></td><td colspan="9" align="center"><b>Skin Slice Set Allocation</b></td></tr>';
			 echo '<tr><td><b>Box Filename</b></td>';
			 for ($i=1; $i<=10; $i++){
			  if (file_exists (DIR_FS_CATALOG_IMAGES . 'slice_sets/' . $i . '/top_background.jpg')){
          echo '<td align="center"><table cellspacing="0" cellpadding="0"><tr><td><img src="' . DIR_WS_CATALOG_IMAGES . 'slice_sets/' . $i . '/top_left.jpg"></td>';
				  echo '<td background="' . DIR_WS_CATALOG_IMAGES . 'slice_sets/' . $i . '/top_background.jpg"></td>';
				  echo '<td><img src="' . DIR_WS_CATALOG_IMAGES . 'slice_sets/' . $i . '/top_right.jpg"></td></tr>';
				  echo '<tr><td background="' . DIR_WS_CATALOG_IMAGES . 'slice_sets/' . $i . '/left_background.jpg"></td>';
				  echo '<td>' . $i . '</td>';			 
				  echo '<td background="' . DIR_WS_CATALOG_IMAGES . 'slice_sets/' . $i . '/right_background.jpg"></td></tr>';
          echo '<tr><td><img src="' . DIR_WS_CATALOG_IMAGES . 'slice_sets/' . $i . '/bottom_left.jpg"></td>';
				  echo '<td background="' . DIR_WS_CATALOG_IMAGES . 'slice_sets/' . $i . '/bottom_background.jpg"></td>';
				  echo '<td><img src="' . DIR_WS_CATALOG_IMAGES . 'slice_sets/' . $i . '/bottom_right.jpg"></td></tr></table>';
			    echo '</td>';
				}
				else echo '<td>Empty</td>';
			 }
			 echo '<td>No Skin</td></tr>';
			 
			 foreach ($boxes_avail as $b){
			  echo '<tr><td>' . $b;
				 for ($i=1; $i<=10; $i++){
				 //if we just updated, use those settings
				 if (isset($skin_map[$b])) $selected = $skin_map[$b]; 
				 else {
				 //otherwise look in the database for the current mapping settings in use
				 $query = "select * from " . TABLE_INFOBOX_SKIN_MAPPING . " where filename ='" . $b . "'";
				 $result = tep_db_query($query);
				 if (tep_db_num_rows($result)!=0) {
				   $row = tep_db_fetch_array($result);
				   $selected = $row['slice_set_id']; 
				  }
					//otherwise default to slice set 1 for everything!
				  else $selected = 1;
				 }
				  echo '<td align="center"><input type="radio" name="skin_map[' . $b . ']" value="' . $i . '"';
					if ($i == $selected) echo 'checked';
					echo '></td>';
				 }
				 
				echo '</td></tr>';
			 }
			 
 			 if (isset($header_set)) $selected = $header_set;
 			 else $selected = INFOBOX_HEADERNAV_SLICE_SET;
       echo '<tr><td>Header Navigation</td>';
			 for ($i=1; $i<=10; $i++) {
			  echo '<td align="center"><input type="radio" name="header_set" value="' . $i . '"';
				if ($i == $selected) echo ' checked ';
  				echo '></td>';
			 }
 			 echo '</tr>';
			 
			 if (isset($footer_set)) $selected = $footer_set;
			 else $selected = INFOBOX_FOOTER_SLICE_SET;
			 echo '<tr><td>Footer</td>';
			 for ($i=1; $i<=10; $i++) {
			  echo '<td align="center"><input type="radio" name="footer_set" value="' . $i . '"';
				if ($i == $selected) echo ' checked ';
	  			echo '></td>';
			 }
 			 echo '</tr>';
			 
			 
 			 if (isset($col_left_set)) $selected = $col_left_set;
			 else $selected = COLUMN_LEFT_SLICE_SET;
			 echo '<tr><td>Left Column</td>';
			 for ($i=1; $i<=10; $i++) {
			  echo '<td align="center"><input type="radio" name="col_left_set" value="' . $i . '"';
				if ($i == $selected) echo ' checked ';
	  			echo '></td>';
			 }
	 				echo '<td><input type="radio" name="col_left_set" value="0"';
   				if ($selected == 0) echo 'checked';
					echo '></td>'; 
 			 echo '</tr>';
			 
			 if (isset($col_right_set)) $selected = $col_right_set;
			 else $selected = COLUMN_RIGHT_SLICE_SET;
			 echo '<tr><td>Right Column</td>';
			 for ($i=1; $i<=10; $i++) {
			  echo '<td align="center"><input type="radio" name="col_right_set" value="' . $i . '"';
				if ($i == $selected) echo ' checked ';
	  			echo '></td>';
			 }
	 				echo '<td><input type="radio" name="col_right_set" value="0"';
   				if ($selected == 0) echo 'checked';
					echo '></td>'; 
 			 echo '</tr>';
			 
			 if (isset($main_set)) $selected = $main_set;
			 else $selected = MAIN_CONTENT_SLICE_SET;
			 echo '<tr><td>Main Page Content</td>';
			 for ($i=1; $i<=10; $i++) {
			  echo '<td align="center"><input type="radio" name="main_set" value="' . $i . '"';
				if ($i == $selected) echo ' checked ';
	  			echo '></td>';
			 }
	 				echo '<td><input type="radio" name="main_set" value="0"';
   				if ($selected == 0) echo 'checked';
					echo '></td>'; 
 			 echo '</tr>';
			 
       echo '<tr><td align="center" colspan="10">' .  tep_image_submit('button_confirm.gif', IMAGE_CONFIRM);
			 echo '</form>';
 
			 
			 
			 echo '</table>'; 
			 
			 echo '</td></tr>';
?>
						
    </table></td>

<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>