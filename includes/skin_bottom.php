<?php

if ($slice_set!=0) {
  echo '</td><td background = "' . DIR_WS_IMAGES . 'slice_sets/' . $slice_set . '/right_background.jpg"></td></tr>';
  echo '<tr><td><img src="images/slice_sets/' . $slice_set . '/bottom_left.jpg"></td>';
  echo '<td background = "' . DIR_WS_IMAGES . 'slice_sets/' . $slice_set . '/bottom_background.jpg"></td>';
  echo '<td><img src="images/slice_sets/' . $slice_set . '/bottom_right.jpg"></td></tr></table>';
}
?>