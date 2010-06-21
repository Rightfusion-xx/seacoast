<?php







  $manufacturers_query = tep_db_query("select manufacturers_id, manufacturers_name from " . TABLE_MANUFACTURERS . " order by manufacturers_name");







  if ($number_of_rows = tep_db_num_rows($manufacturers_query)) {







?>







<!-- manufacturers //-->







          <tr>







            <td>







<?php







    require(DIR_WS_INCLUDES . '/boxes/boxTop.php');







    echo BOX_HEADING_SPONSORED_LINKS;







	require(DIR_WS_INCLUDES . '/boxes/boxMiddle.php');





 echo  '<a href="http://www.prostasolonline.com/">Prostasol Online</a><br>'.

 '<a href="http://www.ip-6online.com/">IP6 Online</a><br>'.

 '<a href="http://www.fishoilsonline.com/">Fish Oils Online</a><br>'.

 '<a href="http://efalexfocus.com/">Learning Disorders</a><br>'.

 '<a href="http://equiguardonline.com/">Equiguard Online</a><br>'.

 '<a href="http://www.wobenzymonline.com/">Wobenzym Online</a><br>' .

 

    require(DIR_WS_INCLUDES . '/boxes/boxBottom.php');







?>







            </td>







          </tr>







<!-- manufacturers_eof //-->







<?php







  }







?>







