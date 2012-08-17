<?php



  $manufacturers_query = tep_db_query("select manufacturers_id, manufacturers_name from " . TABLE_MANUFACTURERS . " order by manufacturers_name");



  if ($number_of_rows = tep_db_num_rows($manufacturers_query)) {



?>



<!-- manufacturers //-->



          <tr>



            <td>



<?php



    require(DIR_WS_INCLUDES . '/boxes/boxTop.php');



    echo BOX_HEADING_LIVEHELP;



	require(DIR_WS_INCLUDES . '/boxes/boxMiddle.php');

echo 
?>

<div id="div_initiate" style="position:absolute; z-index:1; top: 40%; left:40%; visibility: hidden;"><a href="javascript:Live.initiate_accept();"><img src="http://seacoastvitamins.com/livehelp/templates/Bliss/images/initiate.gif" border="0"></a><br><a href="javascript:Live.initiate_decline();"><img src="http://seacoastvitamins.com/livehelp/templates/Bliss/images/initiate_close.gif" border="0"></a></div>
<script type="text/javascript" language="javascript" src="http://seacoastvitamins.com/livehelp/class/js/include.php?live&cobrowse&departmentid=1"></script>

<?php

    require(DIR_WS_INCLUDES . '/boxes/boxBottom.php');



?>



            </td>



          </tr>
<?php



  }



?>
