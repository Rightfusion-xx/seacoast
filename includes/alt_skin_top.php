<?php



$slice_set = MAIN_CONTENT_SLICE_SET;



if ($slice_set!=0) {



  $bgcol = constant('INFOBOX_SKIN_BGCOL' . $slice_set);

  $header_height = INFOBOX_HEADERNAV_HEIGHT;

 

  if (defined('HEADING_TITLE')) $header_text = HEADING_TITLE;

  if (defined('HEADING_TITLE_MODIFY_ENTRY')) $header_text = HEADING_TITLE_MODIFY_ENTRY;

  if (defined('HEADING_TITLE_DELETE_ENTRY')) $header_text = HEADING_TITLE_DELETE_ENTRY; 

  if (defined('HEADING_TITLE_ADD_ENTRY')) $header_text = HEADING_TITLE_ADD_ENTRY;





  if(isset($HTTP_GET_VARS['products_id'])){

   $prod_name_query = tep_db_query("select * from " . TABLE_PRODUCTS . " as p, " . TABLE_PRODUCTS_DESCRIPTION . " as pd where p.products_id = pd.products_id and pd.products_id = " . $HTTP_GET_VARS['products_id']);

   $prod_name = tep_db_fetch_array($prod_name_query);

   $header_text = $prod_name['products_name']; 

  }



  //else $header_text ="Test";



  echo '<table cellspacing="0" cellpadding="0" border="0"><tr><td><img src="images/slice_sets/' . $slice_set . '/top_left.jpg"></td>' .

       '<td background = "' . DIR_WS_IMAGES . 'slice_sets/' . $slice_set . '/top_background.jpg" class="newinfobox_top' . $slice_set . '" valign="middle" align="center" width="100%">';

  echo '</td><td><img src="images/slice_sets/' . $slice_set . '/top_right.jpg"></td></tr>';

  echo '<tr><td background = "' . DIR_WS_IMAGES . 'slice_sets/' . $slice_set . '/left_background.jpg" height="100%"></td>';

  echo '<td bgcolor = "#' . $bgcol . '" valign="top" width="100%" height="100%">' ;																				 



}

?>