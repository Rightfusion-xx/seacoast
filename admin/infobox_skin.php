<?php
/*
  $Id: options_images.php,v 1.0 2003/08/18 

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
  $language_id = '1';
  require('includes/application_top.php');
	
	//check that destination directories exist and are writeable
	
    for ($i=1; $i<=10; $i++){
  	    if (is_dir(DIR_FS_CATALOG_IMAGES . 'slice_sets/' . $i)) {
        if (!is_writeable(DIR_FS_CATALOG_IMAGES . 'slice_sets/' . $i)) $messageStack->add('Error  - ' . DIR_FS_CATALOG_IMAGES . 'slice_sets/' . $i . ' directory not writeable', 'error');
      } else {
        $messageStack->add('Error - ' . DIR_FS_CATALOG_IMAGES . 'slice_sets/' . $i . ' does not exist', 'error');
      }
		}
		
	  if (is_dir(DIR_FS_CATALOG_IMAGES . 'skins/')) {
    if (!is_writeable(DIR_FS_CATALOG_IMAGES . 'skins/')) $messageStack->add('Error  - ' . DIR_FS_CATALOG_IMAGES . 'skins/ is not writeable', 'error');
  } else {
    $messageStack->add('Error - ' . DIR_FS_CATALOG_IMAGES . 'skins/ directory does not exist', 'error');
  }

  if ($HTTP_GET_VARS['action']) {
    switch ($HTTP_GET_VARS['action']) {

case 'save':
        $image_source = $HTTP_POST_FILES['image_input']['tmp_name'];
        $image_filename = $HTTP_POST_FILES['image_input']['name'];
				$image_destination = DIR_FS_CATALOG_IMAGES . 'skins/' . $image_filename;
        $skin_name = $HTTP_POST_VARS['name_input'];
  		  copy($image_source , $image_destination)	or die("unable to copy $image_source to location $image_destination");
        
				$query = "insert into " . TABLE_SKINS . " (skin_name, skin_filename) values ('" . $skin_name . "', '". $image_filename . "')";
				$result = tep_db_query ($query);

        break;

case 'slice':				
        $left_margin = $HTTP_POST_VARS['left_margin'];
        $right_margin = $HTTP_POST_VARS['right_margin'];
        $top_margin = $HTTP_POST_VARS['top_margin'];
        $bottom_margin = $HTTP_POST_VARS['bottom_margin'];
				$slice_set = $HTTP_POST_VARS['slice_set'];
				
				$image_id = $HTTP_POST_VARS['slice_select'];
				
				$query = "select skin_filename from " . TABLE_SKINS . " where skin_id='" . $image_id . "'";
				$result = tep_db_query($query);
				$row = tep_db_fetch_array($result);
				$image_filename = $row['skin_filename'];
				
				$image = @getimagesize(DIR_FS_CATALOG_IMAGES . 'skins/' . $image_filename);

				$image_width = $image[0];
				$image_height = $image[1];
				
				$slice_coords[] = array('filename' => 'top_left.jpg', 
				                        'x1' => '0',
																'x2' => $left_margin,
																'y1' => '0',
																'y2' => $top_margin);
																
        $slice_coords[] = array('filename' => 'top_right.jpg', 
				                        'x1' => $image_width - $right_margin,
																'x2' => $image_width,
																'y1' => '0',
																'y2' => $top_margin);
																
        $slice_coords[] = array('filename' => 'bottom_left.jpg', 
				                        'x1' => '0',
																'x2' => $left_margin,
																'y1' => $image_height - $bottom_margin,
																'y2' => $image_height);																 			 
			 
        $slice_coords[] = array('filename' => 'bottom_right.jpg', 
				                        'x1' => $image_width - $right_margin,
																'x2' => $image_width,
																'y1' => $image_height - $bottom_margin,
																'y2' => $image_height);
																
        $slice_coords[] = array('filename' => 'top_background.jpg', 
				                        'x1' => ($image_width/2),
																'x2' => ($image_width/2)+1,
																'y1' => '0',
																'y2' => $top_margin);
																
        $slice_coords[] = array('filename' => 'bottom_background.jpg', 
				                        'x1' => ($image_width/2),
																'x2' => ($image_width/2)+1,
																'y1' => $image_height - $bottom_margin,
																'y2' => $image_height);

        $slice_coords[] = array('filename' => 'left_background.jpg', 
				                        'x1' => '0',
																'x2' => $left_margin,
																'y1' => ($image_height/2),
																'y2' => ($image_height/2)+1);
																
        $slice_coords[] = array('filename' => 'right_background.jpg', 
				                        'x1' => $image_width - $right_margin,
																'x2' => $image_width,
																'y1' => ($image_height/2),
																'y2' => ($image_height/2+1));																																
																																																 			 
																																 			 


			 $srcim = @imagecreatefromjpeg(DIR_FS_CATALOG_IMAGES . 'skins/' . $image_filename);
			 
			 //Get the colour from the centre of the image in order to set the colour
			 //Of the middle cell, which will contain the old infobox contents
			 $colorindex = ImageColorAt($srcim, ($image_width/2), ($image_height/2));
//       $rgb = imagecolorsforindex($srcim, $colorindex);
//			 $rgbcol = $rgb['alpha'];
//			 echo $rgb['red'] . ' ' . $rgb ['green'] . ' ' . $rgb['blue'];
//	     $rgbcol = $rgb['red'] . ',' . $rgb ['green'] . ',' . $rgb['blue'];
         $rgbcol = dechex($colorindex);     		 
			 $query = "update " . TABLE_CONFIGURATION . " set configuration_value = '" . addslashes($rgbcol) . "' where configuration_key = 'INFOBOX_SKIN_BGCOL" . $slice_set . "'";
			 tep_db_query ($query); 
			 
       foreach ($slice_coords as $v1){
			 $slice_width = $v1['x2'] - $v1['x1'];
			 $slice_height = $v1['y2'] - $v1['y1'];
			 $dstim = imagecreatetruecolor ($slice_width, $slice_height);
			 $dst = DIR_FS_CATALOG_IMAGES . 'slice_sets/' . $slice_set . '/' . $v1['filename'];
			 imagecopy ($dstim, $srcim, 0,0, $v1['x1'], $v1['y1'], $slice_width, $slice_height);
			 imagejpeg($dstim, $dst, 90);
			 imagedestroy($dstim);
 			 }																
			 imagedestroy($srcim);
			 
			 //Now update slice co-ords used for this skin in the database
			 $query = "update " . TABLE_SKINS . " set left_margin = " . $left_margin . ",right_margin = " . $right_margin . ", top_margin = " . $top_margin . ", bottom_margin = " . $bottom_margin . " where skin_id = " . $image_id;
       $result = tep_db_query($query);
			 
			 tep_redirect(tep_href_link(FILENAME_INFOBOX_SKIN, '&sid=' . $image_id . '&slice_set=' . $slice_set						));															
								
				break;
			
			
case 'delete': 
       $skin_id = $HTTP_GET_VARS['sid'];
			 tep_db_query("delete from " . TABLE_SKINS . " where skin_id = '" . $skin_id . "'");
			 tep_redirect(tep_href_link(FILENAME_INFOBOX_SKIN));
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
            <td class="pageHeading"><?php echo 'Infobox Skin Manager'; ?></td>

          </tr>
<?php
			 echo '<tr><td align="center">';
			 echo '<table border="0" cellspacing="0" cellpadding="0">';
			 echo tep_draw_form('image_input', FILENAME_INFOBOX_SKIN, '&action=slice', 'post');
 			 
			 
			 $query = "select * from " . TABLE_SKINS;
			 $result = tep_db_query($query);
			 if (isset($HTTP_GET_VARS['sid'])) $sid = $HTTP_GET_VARS['sid'];
			 if (isset($HTTP_GET_VARS['slice_set'])) $slice_set = $HTTP_GET_VARS['slice_set'];
			 else $slice_set = 1;
			 
			 echo '<tr><td colspan="4" align="center"><b><u>Infobox Skins Currently in the Library</b></u><br><br></td></tr>';
			  
			 echo '<table border="0" cellspacing="0" cellpadding="3"><tr>';
			 $cell_count=0;
			 while ($row = tep_db_fetch_array($result)){
			 //Limit Library display to 4 entries per row...
			   if ($cell_count > 3) {
				   echo '</tr><tr>';
					 $cell_count=0;   
				 }
			   echo '<td align="center"><table border="0" cellspacing="0" cellpadding="0">';
			   echo '<tr><td align="center">' . tep_image(DIR_WS_CATALOG_IMAGES . 'skins/' . $row['skin_filename'], $row['skin_name'], 100, 100) . '</td></tr>';
			   echo '<tr><td align="center"><input type= "radio" name="slice_select" value="' . $row['skin_id'] . '"';
			   if ($row['skin_id'] == $sid) echo ' checked ';
			     echo "onclick = document.location.href='" . FILENAME_INFOBOX_SKIN . "?sid=" . $row['skin_id'] . "'";
			   echo '></td></tr>';
         echo '<tr><td align="center">' . $row['skin_name'] . '</td></tr>';
         echo '<tr><td align="center"><a href="' . FILENAME_INFOBOX_SKIN . '?action=delete&sid=' . $row['skin_id'] . '">' .  tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a></td></tr>';
			   echo '</table></td>';
				 $cell_count++;
			 }
			 echo '</tr></table>';
			 echo '</tr>';
			 
       echo '<tr><td align="center"><br><br><table border="1" cellspacing="0" cellpadding="0"><tr>';
			 echo '<td align="center" colspan="2"><b><u>Enter margins to use for slicing selected image and click Confirm to skin!</b></u></td></tr>';
			 echo '<tr><td>';
			 
       if (isset($sid)){

  			 $result = tep_db_query("select * from " . TABLE_SKINS . " where skin_id = '" . $sid . "'");
				 $row = tep_db_fetch_array ($result);
				 $left_margin = $row['left_margin'];
 				 $right_margin = $row['right_margin'];
 				 $top_margin = $row['top_margin'];
 				 $bottom_margin = $row['bottom_margin'];
       }			 

       for ($i=1;$i<=10;$i++){			 			 
			  $slice_sets[] = array ('id' =>$i, 'text' => $i);
			 }
			 
			 echo 'Left margin: ' . '</td><td>' . tep_draw_input_field('left_margin', $left_margin, '', true) . '</td></tr>';
 			 echo '<tr><td>' . 'Right margin: ' . '</td><td>' . tep_draw_input_field('right_margin', $right_margin, '', true) . '</td></tr>';
			 echo '<tr><td>' . 'Top margin: ' . '</td><td>' . tep_draw_input_field('top_margin', $top_margin, '', true) . '</td></tr>';
			 echo '<tr><td>' . 'Bottom margin: ' . '</td><td>' . tep_draw_input_field('bottom_margin', $bottom_margin, '', true) . '</td></tr>';
 			 echo '<tr><td>' . 'Which slice Set to store as:  ' . '</td><td>' . tep_draw_pull_down_menu('slice_set', $slice_sets) . '</td></tr>';
       echo '<tr><td align="center" colspan="2">' .  tep_image_submit('button_confirm.gif', IMAGE_CONFIRM);
			 echo '</form></td></tr></table></td></tr>';
				
?>
          </td></tr>
        </table></td>
      </tr>
			

			<tr><td align="center"><br><br>
					  
<?php
		 
       echo '<table border="1" cellspacing="0" cellpadding="0"><tr><td align="center">';
       echo '<table border="0" cellspacing="0" cellpadding="0">';
			 echo '<tr><td colspan="2" align="center" valign="top"><b><u>Add a skin to the library</b></u></td></tr>';
			 echo '<tr><td>' . tep_draw_form('image_input', FILENAME_INFOBOX_SKIN, '&action=save', 'post', 'enctype="multipart/form-data"');
			 echo 'Select File to Upload: ' . '</td><td>' . tep_draw_file_field('image_input') . '</td></tr>';
			 echo '<tr><td>Skin Name: </td><td>' . tep_draw_input_field('name_input') . '</td></tr>';
       echo '<tr><td colspan="2" align="center"><br>' .  tep_image_submit('button_upload.gif', IMAGE_UPLOAD);
			 echo '</form><br><br></td></tr></table></td>';
			 echo '<td valign="top">';
			 
			 $bgcol = constant('INFOBOX_SKIN_BGCOL' . $slice_set);
			 
			 echo '<table cellspacing="0" cellpadding="0" border="0"><tr><td colspan="3" align="center"><b><u>Preview of your skin slice set ' . $slice_set . '</b></u><br><br></td></tr>' . 
			       '<tr><td><img src="' . DIR_WS_CATALOG_IMAGES . 'slice_sets/' . $slice_set . '/top_left.jpg"></td>' . 
	 					 '<td background = "' . DIR_WS_CATALOG_IMAGES . 'slice_sets/' . $slice_set . '/top_background.jpg" valign="middle" align="center" width="100%">Header Text</td>' .
						 '<td><img src="' . DIR_WS_CATALOG_IMAGES . 'slice_sets/' . $slice_set . '/top_right.jpg"></td></tr>' .
						 '<tr><td background ="' . DIR_WS_CATALOG_IMAGES . 'slice_sets/' . $slice_set. ' /left_background.jpg"></td><td bgcolor ="#' . $bgcol . '">' .																				 
    			   'Infobox Content Here...!' . 
             '</td><td background ="' . DIR_WS_CATALOG_IMAGES . 'slice_sets/' . $slice_set . ' /right_background.jpg"></td></tr>' .
	 				   '<tr><td><img src="' . DIR_WS_CATALOG_IMAGES . 'slice_sets/' . $slice_set . '/bottom_left.jpg"></td>' . 
	 					 '<td background ="' . DIR_WS_CATALOG_IMAGES . 'slice_sets/' . $slice_set . '/bottom_background.jpg"></td>' .
						 '<td><img src="' . DIR_WS_CATALOG_IMAGES . 'slice_sets/' . $slice_set . '/bottom_right.jpg"></td></tr></table>'; 
			 
			 
			 echo '</td></tr></table>'; 
			 
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