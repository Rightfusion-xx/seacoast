<?php
/*
  $Id: orders_tracking.php, v2.6 July 09, 2005

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 - 2004 osCommerce

  Orders Tracking originally developed by Kieth Waldorf
  v2.1, v2.3, v2.4, v2.5, updates by Jared Call with suggestions from the forums
  v2.2 updates by Robert Hellemans
  Localization work for English and Brazilian Portugese added by alan
  Released under the GNU General Public License
*/

  require('includes/application_top.php');

// get main currency symbol for this store
  $currency_query = tep_db_query("select symbol_left from " . TABLE_CURRENCIES . " where currencies_id = '1' ");
  $currency_symbol_results = tep_db_fetch_array($currency_query);
  $store_currency_symbol = tep_db_output($currency_symbol_results['symbol_left']);
  
setlocale(LC_MONETARY, 'en_US');

?>

<!doctype html>
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
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr> 
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">

<TABLE BORDER=1 CELLPADDING=5 CELLSPACING=1>
<TR class="dataTableHeadingRow" bgcolor=silver><th class="dataTableHeadingContent"><?php echo HEADING_TITLE_DESCRIPTION; ?><th class="dataTableHeadingContent"><?php echo HEADING_TITLE_ORDER_COUNT; ?><th class="dataTableHeadingContent"><?php echo HEADING_TITLE_VALOR; ?></TR>

<?php
//  $orders_status_total = 0;
  $orders_status_query = tep_db_query("select orders_status_name, orders_status_id from " . TABLE_ORDERS_STATUS . " where language_id = '" . $languages_id . "'");
  while ($orders_status = tep_db_fetch_array($orders_status_query)) {
    $orders_pending_query = tep_db_query("select count(*) as count from " . TABLE_ORDERS . " where orders_status = '" . $orders_status['orders_status_id'] . "'");
    $orders_pending = tep_db_fetch_array($orders_pending_query);
    
    $current_status = $orders_status['orders_status_id'];

//    if ($current_status != "4") {
	    $orders_total_this_status_query_raw = "select sum(ot.value) as total FROM " . TABLE_ORDERS . " o, " . TABLE_ORDERS_TOTAL . " ot WHERE ot.orders_id = o.orders_id AND  ot.class = 'ot_total' AND o.orders_status = $current_status";
	    $orders_total_this_status_query = tep_db_query($orders_total_this_status_query_raw);
	    $orders_total_this_status = tep_db_fetch_array($orders_total_this_status_query);
//	    $order_status_total = $orders_status_total_this_status['total'];
//    }
  
    $orders_contents .= '<tr class="dataTableRow"><td class="dataTableContent"><a href="' . tep_href_link(FILENAME_ORDERS, 'selected_box=orders&status=' . $orders_status['orders_status_id']) . '">' . $orders_status['orders_status_name'] . '</font></a></td><td class="dataTableContent">' . $orders_pending['count'] . '</td><td class="dataTableContent" align="right">' . $store_currency_symbol . number_format($orders_total_this_status['total'],2) . '</td></tr>';
    
  }
//  $orders_contents = substr($orders_contents, 0, -5);
echo $orders_contents;
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
