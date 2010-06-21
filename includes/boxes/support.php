<?php



  $manufacturers_query = tep_db_query("select manufacturers_id, manufacturers_name from " . TABLE_MANUFACTURERS . " order by manufacturers_name");



  if ($number_of_rows = tep_db_num_rows($manufacturers_query)) {



?>

          <tr>



            <td>



<?php



    require(DIR_WS_INCLUDES . '/boxes/boxTop.php');



    echo BOX_HEADING_SUPPORT;



	require(DIR_WS_INCLUDES . '/boxes/boxMiddle.php');

echo 

'<a href="/help/live/main.php" target="_blank"><img src="http://seacoastvitamins.com/help/live/icon.php" alt="Click here for Live Help - Powered by Help Center Live" /></a>' ;


    require(DIR_WS_INCLUDES . '/boxes/boxBottom.php');



?>



            </td>



          </tr>
<?php



  }



?>
