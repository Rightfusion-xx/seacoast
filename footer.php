<?php

/*

  $Id: footer.php,v 1.26 2003/02/10 22:30:54 hpdl Exp $



  osCommerce, Open Source E-Commerce Solutions

  http://www.oscommerce.com



  Copyright (c) 2003 osCommerce



  Released under the GNU General Public License

*/



  require(DIR_WS_INCLUDES . 'counter.php');

?>



<table border="0" width="100%" cellspacing="0" cellpadding="1">

  <tr>

  <?php	echo '<TD COLSPAN="2" BGCOLOR="#FFCC99" CLASS="headernavigation" ALIGN="center"><IMG SRC="/images/spacer.gif" height="1" width="1"></TD></TR><TR>' . 

              '<td colspan="2" align="center" class="footer" BGCOLOR="#336699">&nbsp;&nbsp; Order Online 24 Hours a Day, 7 Days a Week &nbsp;&nbsp;&nbsp; Call Toll-Free 800-555-6792 &nbsp;&nbsp;&nbsp; Office Hours: Mon - Fri, 9am - 6pm Eastern Time &nbsp;&nbsp;<br>&copy; 2005, Seacoast Natural Foods, Inc.</td></tr>';

  //echo '<tr><td class="footer" align="right" background="' . DIR_WS_IMAGES . 'slice_sets/' . INFOBOX_FOOTER_SLICE_SET . '/top_background.jpg" height="' . INFOBOX_FOOTER_HEIGHT . '">&nbsp;&nbsp; Office Hours: Mon - Fri, 9am - 6pm Eastern Time &nbsp;&nbsp;</td>';

  ?>

  </tr>

</table>



<?php

 // if ($banner = tep_banner_exists('dynamic', '468x50')) {

?>



<table border="0" width="100%" cellspacing="0" cellpadding="12">

  <tr>

    <td class="smallText">

      <?php //echo tep_display_banner('static', $banner); 

echo '<b>Disclaimer:</b> Statements made, or products sold through this web site,have not been evaluated by the United States Food and Drug Administration. They are not intended to diagnose, treat, cure, or prevent any disease.' .<br>

' The information presented on this site is for educational purposes only and is not intended to replace advise from your physician or other health care professional or any information found on any product label or packaging.' .

' You should always consult with a qualified health care professional before starting any exercise, diet or supplementation program, especially if you are taking prescription medications. <b>Prices may change with out notice.</b>' .

' <b>Note:</b> coupon discounts are not reflected on your invoice but will be manually applied when we process your order. All items (marked with an* ) are exempt.' .

' ( ie. Coromega*, Prostasol*)' .					  

?>
    </td>

  </tr>

</table>

<?php

 // }

?>













