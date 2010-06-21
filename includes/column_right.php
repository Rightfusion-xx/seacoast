<?php

if(!strpos($_SERVER['PHP_SELF'],'product_reviews_write.php')) { 
if (COLUMN_RIGHT_SLICE_SET != 0){
$slice_set = COLUMN_RIGHT_SLICE_SET;
$bgcol = constant('INFOBOX_SKIN_BGCOL' . $slice_set);
echo '<td width="' . BOX_WIDTH . '" valign="top" height="100%">';
echo '<table cellspacing="0" cellpadding="0" border="0" height="100%" valign="top"><tr><td><img src="images/slice_sets/' . $slice_set . '/top_left.jpg"></td>' .
'<td background = "' . DIR_WS_IMAGES . 'slice_sets/' . $slice_set . '/top_background.jpg" class="newinfobox_top' . $slice_set . '" valign="middle" align="center" width="100%">';
echo '</td><td><img src="images/slice_sets/' . $slice_set . '/top_right.jpg"></td></tr>';
echo '<tr><td background = "' . DIR_WS_IMAGES . 'slice_sets/' . $slice_set . '/left_background.jpg"></td>';
echo '<td bgcolor = "' . $bgcol . '" valign="top" width="100%" height="100%">' ;
echo '<table cellspacing="0" cellpadding="0" border="0">';
}
else {
?>
<td valign="top"><div id="rightnav" style="margin-left:5%;margin-right:10%;margin-top:10%;width:15em;">
<?php
}

 if($_SERVER['HTTPS']=='off')
            {
?>
<div id="nav_manufacturers" class="nav_box">
  
  <?php 

include(DIR_WS_BOXES . 'related_searches.php');



}

if (COLUMN_RIGHT_SLICE_SET != 0){
echo '</table>';
echo '</td><td background = "' . DIR_WS_IMAGES . 'slice_sets/' . $slice_set . '/right_background.jpg"></td></tr>';
echo '<tr><td><img src="images/slice_sets/' . $slice_set . '/bottom_left.jpg"></td>';
echo '<td background = "' . DIR_WS_IMAGES . 'slice_sets/' . $slice_set . '/bottom_background.jpg"></td>';
echo '<td><img src="images/slice_sets/' . $slice_set . '/bottom_right.jpg"></td></tr>';
echo '</table>';
}
else echo '</td></tr></table>';
?>
</div>
<?php } 

 ?>