<?php
/*
  $Id: orders.php,v 1.106 2002/11/23 13:58:03 thomasamoulton Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  include(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ORDERS);
  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();
  
function quickship($oID, $status=''){
      $order_updated = false;
	  
	    $check_status_query = tep_db_query("select customers_name, customers_email_address, orders_status, date_purchased from " . TABLE_ORDERS . " where orders_id = '" . $oID . "'");
  $check_status = tep_db_fetch_array($check_status_query);
  
  $orders_statuses = array();
  $orders_status_array = array();
  $orders_status_query = tep_db_query("select orders_status_id, orders_status_name from " . TABLE_ORDERS_STATUS . " where language_id = 1");
  while ($orders_status = tep_db_fetch_array($orders_status_query)) {
    $orders_statuses[] = array('id' => $orders_status['orders_status_id'],
                               'text' => $orders_status['orders_status_name']);
    $orders_status_array[$orders_status['orders_status_id']] = $orders_status['orders_status_name'];
  }


	  if ($status!==''){	  
	    if ($check_status['orders_status'] !== $status) {
        tep_db_query("update " . TABLE_ORDERS . " set orders_status = '". $status . "', last_modified = now() where orders_id = '" . $oID . "'");
        $customer_notified = '0';
		
        $notify_comments = '';
          $email = STORE_NAME . "\n" . EMAIL_SEPARATOR . "\n" . EMAIL_TEXT_ORDER_NUMBER . ' ' . $oID . "\n" . EMAIL_TEXT_INVOICE_URL . ' ' . tep_catalog_href_link(FILENAME_CATALOG_ACCOUNT_HISTORY_INFO, 'order_id=' . $oID, 'SSL') . "\n" . EMAIL_TEXT_DATE_ORDERED . ' ' . tep_date_long($check_status['date_purchased']) . "\n\n" . $notify_comments . sprintf(EMAIL_TEXT_STATUS_UPDATE, $orders_status_array[$status]);
          tep_mail($check_status['customers_name'], $check_status['customers_email_address'], EMAIL_TEXT_SUBJECT, nl2br($email), STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
        $customer_notified = '1';
        
		tep_db_query("insert into " . TABLE_ORDERS_STATUS_HISTORY . " (orders_id, orders_status_id, date_added, customer_notified, comments) values ('" . (int)$oID . "', '" . tep_db_input($status) . "', now(), '" . tep_db_input($customer_notified) . "', '" . tep_db_input($comments)  . "')");

        $order_updated = true;
      	}
	  }
	  
  }
  
if ($_POST['update']=='1') {
echo '<div class="dataTableContent" align="left">';
foreach ($_POST as $i => $v){
	//echo "$i - $v<br>";
	list($o, $f) = explode("_", $i);
    if (is_numeric($o)){	
	if ($f=="check")
		{
			quickship($o, '3');
			echo "Order# $o, <b>$f</b> = $v (DATABASE UPDATED)<br>";
		}
	//End is_numeric
	}
}
//If HTTP_POST end
echo '</div>';
}

include(DIR_WS_CLASSES . 'order.php');
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?php
  require(DIR_WS_INCLUDES . 'header.php');
?>
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
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
        <tr>
          <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td class="pageHeading">Quick Ship</td>
                <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', 1, HEADING_IMAGE_HEIGHT); ?></td>
              </tr>
            </table></td>
        </tr>
        <tr>
          <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                    <tr class="dataTableHeadingRow">
                      <td class="dataTableHeadingContent" align="center">Ship</td>
                      <td class="dataTableHeadingContent" align="center">Customer</td>
                      <td class="dataTableHeadingContent" align="center">Order #</td>
                      <td class="dataTableHeadingContent" align="center">Order Total</td>
                      <td class="dataTableHeadingContent" align="center">Date Purchased</td>
                      <td class="dataTableHeadingContent" align="center">Status</td>
                      <td class="dataTableHeadingContent" align="center">Invoice</td>
                    </tr>
                    <?php
    if ($HTTP_GET_VARS['cID']) {
      $cID = tep_db_prepare_input($HTTP_GET_VARS['cID']);
      $orders_query_raw = "select o.orders_id, o.customers_name, o.customers_id, o.payment_method, o.date_purchased, o.last_modified, o.currency, o.currency_value, s.orders_status_name, ot.text as order_total from " . TABLE_ORDERS . " o left join " . TABLE_ORDERS_TOTAL . " ot on (o.orders_id = ot.orders_id), " . TABLE_ORDERS_STATUS . " s where o.customers_id = '" . tep_db_input($cID) . "' and o.orders_status = s.orders_status_id and s.language_id = '" . $languages_id . "' and ot.class = 'ot_total' order by o.customers_name ASC";
    } elseif ($HTTP_GET_VARS['status']) {
      $status = tep_db_prepare_input($HTTP_GET_VARS['status']);
      $orders_query_raw = "select o.orders_id, o.customers_name, o.payment_method, o.date_purchased, o.last_modified, o.currency, o.currency_value, s.orders_status_name, ot.text as order_total from " . TABLE_ORDERS . " o left join " . TABLE_ORDERS_TOTAL . " ot on (o.orders_id = ot.orders_id), " . TABLE_ORDERS_STATUS . " s where o.orders_status = s.orders_status_id and s.language_id = '" . $languages_id . "' and s.orders_status_id = '" . tep_db_input($status) . "' and ot.class = 'ot_total' order by o.customers_name ASC";
    } elseif ($HTTP_POST_VARS['status']) {
      $status = tep_db_prepare_input($HTTP_POST_VARS['status']);
      $orders_query_raw = "select o.orders_id, o.customers_name, o.payment_method, o.date_purchased, o.last_modified, o.currency, o.currency_value, s.orders_status_name, ot.text as order_total from " . TABLE_ORDERS . " o left join " . TABLE_ORDERS_TOTAL . " ot on (o.orders_id = ot.orders_id), " . TABLE_ORDERS_STATUS . " s where o.orders_status = s.orders_status_id and s.language_id = '" . $languages_id . "' and s.orders_status_id = '" . tep_db_input($status) . "' and ot.class = 'ot_total' order by o.customers_name ASC";
	} else {
      $orders_query_raw = "select o.orders_id, o.customers_name, o.payment_method, o.date_purchased, o.last_modified, o.currency, o.currency_value, s.orders_status_name, ot.text as order_total from " . TABLE_ORDERS . " o left join " . TABLE_ORDERS_TOTAL . " ot on (o.orders_id = ot.orders_id), " . TABLE_ORDERS_STATUS . " s where o.orders_status = s.orders_status_id and s.language_id = '" . $languages_id . "' and s.orders_status_id = '1' and ot.class = 'ot_total' order by o.customers_name ASC";
    }

    $orders_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $orders_query_raw, $orders_query_numrows);
    $orders_query = tep_db_query($orders_query_raw);
	echo tep_draw_form('quickship', 'quickship.php', '', 'post');
	while ($orders = tep_db_fetch_array($orders_query)) {
      if (((!$HTTP_GET_VARS['oID']) || ($HTTP_GET_VARS['oID'] == $orders['orders_id'])) && (!$oInfo)) {
        $oInfo = new objectInfo($orders);
      }
	  $id = $orders['orders_id']; 

	  if ($orders['orders_status_name'] == "Shipped") {$checkedlogic=false;} else {$checkedlogic=false;}     
?>
                    <tr class="dataTableRow">
                      <td class="dataTableContent" align="center"><?php echo tep_draw_checkbox_field($id."_".'check', '', $checkedlogic); ?></td>
                      <td class="dataTableContent" align="center"><?php echo $orders['customers_name']; ?></td>
                      <td class="dataTableContent" align="center"><?php echo $orders['orders_id'] ?></td>
                      <td class="dataTableContent" align="center"><?php echo strip_tags($orders['order_total']); ?></td>
                      <td class="dataTableContent" align="center"><?php echo tep_datetime_short($orders['date_purchased']); ?></td>
                      <td class="dataTableContent" align="center"><?php echo $orders['orders_status_name']; ?></td>
                      <td class="dataTableContent" align="center"><?php echo '<a href="' . tep_href_link(FILENAME_ORDERS_INVOICE, 'oID=' . $orders['orders_id']) . '" TARGET="_blank">' . tep_image_button('button_invoice.gif', IMAGE_ORDERS_INVOICE) . '</a>' ?></td>
                    </tr>
                    <?php
    }
?>
                    <input type="hidden" name="status" value="1">
                    <input type="hidden" name="update" value="1">
                    <?php echo tep_image_submit('button_update.gif', IMAGE_UPDATE, ''); ?>
                    </form>
                    
                    <tr>
                      <td colspan="5"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                          <tr>
                            <td class="smallText" valign="top"><?php echo $orders_split->display_count($orders_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_ORDERS); ?></td>
                            <td class="smallText" align="right"><?php echo $orders_split->display_links($orders_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page']); ?></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table></td>
              </tr>
            </table></td>
        </tr>
      </table></td>
    <!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->
<!-- footer //-->
<?php
    require(DIR_WS_INCLUDES . 'footer.php');
?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
