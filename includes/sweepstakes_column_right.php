<?php







/*







  $Id: column_right.php,v 1.17 2003/06/09 22:06:41 hpdl Exp $







  osCommerce, Open Source E-Commerce Solutions







  http://www.oscommerce.com







  Copyright (c) 2003 osCommerce







  Released under the GNU General Public License







*/







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



   <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">



<?php



 }				







//  Show the Similar Products box if we are on a product page



  if (isset($_GET['products_id'])) include(DIR_WS_BOXES . 'similar_products.php');



  



   // require(DIR_WS_BOXES . 'shopping_cart.php');

  if (isset($HTTP_GET_VARS['products_id'])) {



    if (basename($PHP_SELF) != FILENAME_TELL_A_FRIEND) include(DIR_WS_BOXES . 'tell_a_friend.php');



  } else {



    //include(DIR_WS_BOXES . 'specials.php');
	
	//include(DIR_WS_BOXES . 'helpcenter.php');
	
  //  include(DIR_WS_BOXES . 'health.php');
	
	include(DIR_WS_BOXES . 'sweepstakes.php');

//	include(DIR_WS_BOXES . 'specials_scroll.php');


	



	



  }



 // require(DIR_WS_BOXES . 'healthnotes.php');







  



 if (isset($HTTP_GET_VARS['products_id'])) include(DIR_WS_BOXES . 'manufacturer_info.php');



 if (tep_session_is_registered('customer_id')) include(DIR_WS_BOXES . 'order_history.php');







if (isset($HTTP_GET_VARS['products_id'])) {



  if (tep_session_is_registered('customer_id')) {



      $check_query = tep_db_query("select count(*) as count from " . TABLE_CUSTOMERS_INFO . " where customers_info_id = '" . (int)$customer_id . "' and global_product_notifications = '1'");



      $check = tep_db_fetch_array($check_query);



      if ($check['count'] > 0) {


     include(DIR_WS_BOXES . 'best_sellers.php');



      } else {



     include(DIR_WS_BOXES . 'product_notifications.php');



      }



    } else {


     include(DIR_WS_BOXES . 'product_notifications.php');



    }



  } else {



//    include(DIR_WS_BOXES . 'best_sellers.php');



  }











//  require(DIR_WS_BOXES . 'whats_new.php');



 //   require(DIR_WS_BOXES . 'reviews.php');



//  require(DIR_WS_BOXES . 'information.php');



  



/*  if (substr(basename($PHP_SELF), 0, 8) != 'checkout') {


    include(DIR_WS_BOXES . 'languages.php');



    include(DIR_WS_BOXES . 'currencies.php');



  }



	*/



  if (COLUMN_RIGHT_SLICE_SET != 0){	



	  echo '</table>';



	  echo '</td><td background = "' . DIR_WS_IMAGES . 'slice_sets/' . $slice_set . '/right_background.jpg"></td></tr>';



    echo '<tr><td><img src="images/slice_sets/' . $slice_set . '/bottom_left.jpg"></td>';



    echo '<td background = "' . DIR_WS_IMAGES . 'slice_sets/' . $slice_set . '/bottom_background.jpg"></td>';



    echo '<td><img src="images/slice_sets/' . $slice_set . '/bottom_right.jpg"></td></tr>';



		echo '</table></td></tr></table>';



  }	







	else echo '</table></td></tr></table>';







?>



