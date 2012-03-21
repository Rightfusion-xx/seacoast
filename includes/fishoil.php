 
<p><b><font face="Arial, Helvetica, sans-serif" size="2">Any Combination of Nordic 
  Natural's Receive Additional 15% OFF! </font></b></p>
<p align="center"><a href="/Store/index.php?manufacturers_id=60"><img src="images/nordicnaturalss.GIF" alt="Nordic Naturals Pure and Great Tasting Omega Oils" border="0"></a> 
  <br>
</p>
<div align="center"> 

  <table width="61%" border="0" cellpadding="0">

    <tr> 

      <td width="28%"><font face="Arial, Helvetica, sans-serif" size="2"><img src="http://www.seacoastvitamins.com/Store/images/fishr.JPG" width="106" height="64"></font></td>

      <td width="46%"> 

        <div align="center"><font face="Arial, Helvetica, sans-serif" size="2">Fish 

          Oil Coupon Code:<b><br>
          FishOil15</b></font></div>

      </td>

      <td width="26%"><font face="Arial, Helvetica, sans-serif" size="2"><img src="http://www.seacoastvitamins.com/Store/images/fishl.JPG" width="106" height="64"></font></td>

    </tr>

  </table>

  <table width="0%" border="0" cellpadding="0">

    <tr> 

      <td> 

        <div align="center"><font face="Arial, Helvetica, sans-serif" size="2"><img src="http://www.seacoastvitamins.com/Store/images/fishoil.bmp"></font></div>

      </td>

    </tr>

  </table>

<?php

			  $heading = '';

    if (isset($HTTP_GET_VARS['manufacturers_id=60'])) {

      $heading = tep_db_query("select manufacturers_name from " . TABLE_MANUFACTURERS . " where manufacturers_id = '" . (int)$HTTP_GET_VARS['manufacturers_id'] . "'");

	  $heading = tep_db_fetch_array($heading);

      $heading = $heading['manufacturers_name'];

    } elseif ($current_category_id) {

	  $heading = tep_db_query("select categories_name from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . (int)$current_category_id . "'");

      $heading = tep_db_fetch_array($heading);

      $heading = $heading['categories_name'];

    }

	?><table width="100%" border="0" cellpadding="0">

    <tr> 

      <td> 

        <p><font face="Arial, Helvetica, sans-serif" size="2"><b>Enter coupon 

          code in comments box:<br>

          Note: coupon discounts are not reflected on your invoice but will be 

          manually applied when we process your order. All items (marked with 

          an * ) are exempt. ( ie. Coromega* ) </b></font></p>

        <p><font face="Arial, Helvetica, sans-serif" size="2"><b>This Coupon only 

          applies to Fish Oils<br>

          You may only use one promotional coupon per order.<br>

          Any other questions or comments please contact us:</b></font></p>

        <p><font face="Arial, Helvetica, sans-serif" size="2"><b><a href="mailto:webmaster@seacoastvitamins.com">webmaster@seacoastvitamins.com</a><br>

          <a href="mailto:order">orders@seacoastvitamins.com</a></b></font></p>

        <p><font face="Arial, Helvetica, sans-serif" size="2"><b>Toll Free: 1-800-555-6792 

          / 1-877-229-1779</b></font></p>

      </td>

    </tr>

  </table>

</div>

