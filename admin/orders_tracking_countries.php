<?php
/*
  $Id: orders_tracking_zones.php,v 2.8 21 August 2005 01:30:00 

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Orders Tracking originally developed by Kieth Waldorf
  Orders Tracking Countries added to package by David Radford
  Released under the GNU General Public License
*/
 // Note: all orders assumed to be in the default currency
  require('includes/application_top.php');
  $o_min_status =1; //schoose minimum order status

// get default currency symbol for this store
  $currency_query = tep_db_query("select symbol_left from " . TABLE_CURRENCIES . " where  code = '".DEFAULT_CURRENCY."' ");
  $currency_symbol_results = tep_db_fetch_array($currency_query);
  $store_currency_symbol = tep_db_output($currency_symbol_results['symbol_left']);
?>

<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/general.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
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
    <td width="100%" valign="top">
     <table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td>
         <table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr> 
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="dataTableContent"><br><br><?php
                 echo tep_draw_form('search', FILENAME_STATS_ORDERS_TRACKING_ZONES, '', 'get');
                 echo HEADING_SELECT_YEAR . ' ' . tep_draw_input_field('year', '', 'size="4"');
                 echo '  ' . HEADING_SELECT_MONTH . ' ' . tep_draw_input_field('month', '', 'size="4"');                 
                 echo '&nbsp;&nbsp;&nbsp;<input type="submit" value="'. HEADING_TITLE_RECALCULATE .'">';
                 echo '</form>';
                ?><br><br>
            </td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
       </tr>
       <tr>
        <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">


<CENTER>
<TABLE BORDER=1 CELLPADDING=5 CELLSPACING=1>
<TR class="dataTableHeadingRow" bgcolor=silver><th class="dataTableHeadingContent"><?php echo HEADING_COUNTRY;?><th class="dataTableHeadingContent"><?php echo HEADING_ORDER_COUNT;?><th class="dataTableHeadingContent"><?php echo HEADING_ORDER_VALUE;?><th class="dataTableHeadingContent"><?php echo HEADING_PCT_BY_VALUE;?></TR>

<?php

// if no year has been selected, use current year
  if (isset($HTTP_GET_VARS['year'])) {
	  $year=$HTTP_GET_VARS['year'];
  }
  else {
	  $year = date('Y'); #current year
  }

// if selected a month but not year, assume current year    
if ($HTTP_GET_VARS['year'] == '') {
	  $year = date('Y'); #current year
  }      

// if a month has been selected, set the date range for just that month
  if (isset($HTTP_GET_VARS['month'])) {
	  $startmonth=$HTTP_GET_VARS['month'];
	  $endmonth=$startmonth;
  }

// if no month has been selected, we want entire year of data
  if ($HTTP_GET_VARS['month'] == '') {
	  $startmonth=01;
	  $endmonth=12;
  } 
 $total_query = tep_db_query("select sum(value) as amount, count(value) as count from " . TABLE_ORDERS . " LEFT JOIN " . TABLE_ORDERS_TOTAL . " ON orders.orders_id = orders_total.orders_id  where class = \"ot_total\" AND date_purchased >= '$year-$startmonth-01 00:00:00' AND date_purchased <= '$year-$endmonth-31 11:59:59' AND orders_status >= $o_min_status");
  while  ($thetotal = tep_db_fetch_array($total_query)) {
    if ( $thetotal['count'] != 0 ) //if there are orders for this period find the totals for calculating the percentages later
    {
     $total_orders = $thetotal['count'];
   	 $total_amount = $thetotal['amount'];
	   }
  }
  $pcttot=0;
  $location_query = tep_db_query("select customers_country, currency, sum(value) as amount, count(*) as count from " . TABLE_ORDERS . " LEFT JOIN " . TABLE_ORDERS_TOTAL . " ON orders.orders_id = orders_total.orders_id  where class = \"ot_total\" AND date_purchased >= '$year-$startmonth-01 00:00:00' AND date_purchased <= '$year-$endmonth-31 11:59:59' AND orders_status >= $o_min_status group by customers_country order by customers_country");
  while  ($location = tep_db_fetch_array($location_query)) {
    if ( $location['count'] != 0 ) //if there are orders for this country, print the country,count, amount and percentage of total
    {
   	   $pct = $location['amount'] * 100 / $total_amount ;
   	   $amount = number_format($location['amount'],2,'.',',');
       $pcttot += $pct;
    	 $pct = number_format($pct,1,'.',',');
       $location_contents .= '<tr class="dataTableRow"><td class="dataTableContent">' . $location['customers_country'] . '</td><td class="dataTableContent">' . $location['count']  . '</td><td class="dataTableContent"  align=right>' . $store_currency_symbol. $amount . '</td><td class="dataTableContent"  align=right>' . $pct . '%</td></tr>';
   	}
  }
  echo $location_contents;
  $total_amount = number_format($total_amount,2,'.',',');
  $pcttot = number_format($pcttot,2,'.',',');
  echo '<tr class="dataTableRow"><td class="dataTableContent">'. HEADING_TOTALS. '</td><td class="dataTableContent">' . $total_orders. '</td><td class="dataTableContent"  align=right>' . $store_currency_symbol . $total_amount. '</td><td class="dataTableContent" align=right>' .$pcttot. '%</td></tr>';

?>

</TABLE>

            </td>
          </tr>
        </table></td>
      </tr>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>

<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
</body>
</html>
