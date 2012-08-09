<?php
/*
$Id: checkout_shipping_express.php,v 1.16 2003/06/09 23:03:53 hpdl Exp $
redone by nana
osCommerce, Open Source E-Commerce Solutions
http://www.oscommerce.com

Copyright (c) 2003 osCommerce

Released under the GNU General Public License

*/
require('includes/application_top.php');
require('includes/classes/http_client.php');

require(DIR_WS_LANGUAGES . $language . '/' . 'fast_account.php');



// if no shipping destination address was selected, use the customers own address as default
if (!tep_session_is_registered('sendto')) {
tep_session_register('sendto');
$sendto = $customer_default_address_id;
} else {
// verify the selected shipping address
$check_address_query = tep_db_query("select count(*) as total from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$customer_id . "' and address_book_id = '" . (int)$sendto . "'");
$check_address = tep_db_fetch_array($check_address_query);

if ($check_address['total'] != '1') {
$sendto = $customer_default_address_id;
if (tep_session_is_registered('shipping')) tep_session_unregister('shipping');
}
}
// if no billing destination address was selected, use the customers own address as default
if (!tep_session_is_registered('billto')) {
tep_session_register('billto');
$billto = $customer_default_address_id;
} else {
// verify the selected billing address
$check_address_query = tep_db_query("select count(*) as total from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$customer_id . "' and address_book_id = '" . (int)$billto . "'");
$check_address = tep_db_fetch_array($check_address_query);

if ($check_address['total'] != '1') {
$billto = $customer_default_address_id;
if (tep_session_is_registered('payment')) tep_session_unregister('payment');
}
}


//the next 4 lines are for ccgv
 require(DIR_WS_CLASSES . 'order_total.php');

$order_total_modules = new order_total;
/*$order_total_modules->collect_posts();
$order_total_modules->pre_confirmation_check(); */
// if the customer is not logged on, redirect them to the login page
if (!tep_session_is_registered('customer_id')) {
$navigation->set_snapshot();
// tep_redirect(tep_href_link('create_account1.php', '', 'SSL'));
// tep_redirect(tep_href_link('create_account2.php', '', 'SSL'));
// tep_redirect(tep_href_link('create_account3.php', '', 'SSL'));
tep_redirect(tep_href_link(FILENAME_CREATE_ACCOUNT, '', 'SSL'));
}
require(DIR_WS_CLASSES . 'order.php');
$order = new order;
require(DIR_WS_CLASSES . 'payment.php');
$payment_modules = new payment;


$total_weight = $cart->show_weight();
$total_count = $cart->count_contents();


require(DIR_WS_CLASSES . 'shipping.php');
$shipping_modules = new shipping;
// if there is nothing in the customers cart, redirect them to the shopping cart page
if ($cart->count_contents() < 1) {
tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
}

// register a random ID in the session to check throughout the checkout procedure
// against alterations in the shopping cart contents
if (!tep_session_is_registered('cartID')) tep_session_register('cartID');
$cartID = $cart->cartID;

// if the order contains only virtual products, forward the customer to the billing page as
// a shipping address is not needed
if ($order->content_type == 'virtual') {
if (!tep_session_is_registered('shipping')) tep_session_register('shipping');
$shipping = false;
$sendto = false;
tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'));
}
tep_session_unregister('billing');
tep_session_unregister('payment');
if (isset($HTTP_POST_VARS['payment'])) $payment = $HTTP_POST_VARS['payment'];
if (!tep_session_is_registered('payment')) tep_session_register('payment');



if($n==1){

if (isset($_POST['save_x'])){
$paynow=3;
}
if (isset($_POST['preview_x'])){
$paynow=5;
}


//i commented this out so payment is not required in this page and total can be accessed
/*if ( ( is_array($payment_modules->modules) && (sizeof($payment_modules->modules) > 1) && !is_object($$payment) ) || (is_object($$payment) && ($$payment->enabled == false)) ) {
tep_redirect(tep_href_link(FILENAME_CHECKOUT_SHIPPING, 'error_message=' . urlencode(ERROR_NO_PAYMENT_MODULE_SELECTED), 'SSL'));
*/
tep_session_unregister('payment');
$payment_modules->update_status();
}
if (is_array($payment_modules->modules)) {
$payment_modules->pre_confirmation_check();
}
//}
while (list($key, $value) = each($_POST))
{
tep_session_register($key);
}
if ( defined('MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING') && (MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING == 'true') ) {
$pass = false;

switch (MODULE_ORDER_TOTAL_SHIPPING_DESTINATION) {
case 'national':
if ($order->delivery['country_id'] == STORE_COUNTRY) {
$pass = true;
}
break;
case 'international':
if ($order->delivery['country_id'] != STORE_COUNTRY) {
$pass = true;
}
break;
case 'both':
$pass = true;
break;
}

$free_shipping = false;
if ( ($pass == true) && ($order->info['total'] >= MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER) ) {
$free_shipping = true;

include(DIR_WS_LANGUAGES . $language . '/modules/order_total/ot_shipping.php');
}
} else {
$free_shipping = false;
}

// process the selected shipping method
if ( isset($HTTP_POST_VARS['action']) && ($HTTP_POST_VARS['action'] == 'process') ) {
if (!tep_session_is_registered('comments')) tep_session_register('comments');
if (tep_not_null($HTTP_POST_VARS['comments'])) {
$comments = tep_db_prepare_input($HTTP_POST_VARS['comments']);
}

if (!tep_session_is_registered('shipping')) tep_session_register('shipping');

if ( (tep_count_shipping_modules() > 0) || ($free_shipping == true) ) {
if ( (isset($HTTP_POST_VARS['shipping'])) && (strpos($HTTP_POST_VARS['shipping'], '_')) ) {
$shipping = $HTTP_POST_VARS['shipping'];

list($module, $method) = explode('_', $shipping);
if ( is_object($$module) || ($shipping == 'free_free') ) {
if ($shipping == 'free_free') {
$quote[0]['methods'][0]['title'] = FREE_SHIPPING_TITLE;
$quote[0]['methods'][0]['cost'] = '0';
} else {
$quote = $shipping_modules->quote($method, $module);
}
if (isset($quote['error'])) {
tep_session_unregister('shipping');
} else {
if ( (isset($quote[0]['methods'][0]['title'])) && (isset($quote[0]['methods'][0]['cost'])) ) {
$shipping = array('id' => $shipping,
'title' => (($free_shipping == true) ? $quote[0]['methods'][0]['title'] : $quote[0]['module'] . ' (' . $quote[0]['methods'][0]['title'] . ')'),
'cost' => $quote[0]['methods'][0]['cost']);

tep_redirect(tep_href_link(FILENAME_CHECKOUT_CONFIRMATION,'paynow='.$paynow, 'SSL'));
}
}
} else {
tep_session_unregister('shipping');
}
}
} else {
$shipping = false;

tep_redirect(tep_href_link(FILENAME_CHECKOUT_CONFIRMATION, 'paynow='.$paynow, 'SSL'));
}
}

// get all available shipping quotes
$quotes = $shipping_modules->quote();

// if no shipping method has been selected, automatically select the cheapest method.
// if the modules status was changed when none were available, to save on implementing
// a javascript force-selection method, also automatically select the cheapest shipping
// method if more than one module is now enabled
if ( !tep_session_is_registered('shipping') || ( tep_session_is_registered('shipping') && ($shipping == false) && (tep_count_shipping_modules() > 1) ) ) $shipping = $shipping_modules->cheapest();
require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CHECKOUT_SHIPPING);
require(DIR_WS_LANGUAGES . $language . '/' . 'checkout_payment.php');


$breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
$breadcrumb->add(NAVBAR_TITLE_2, tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));

?>
<!doctype html>
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">

<?php //echo $payment_modules->javascript_validation(); ?>
<script language="javascript"><!--
function ajaxLoader(url,id) {

  if (document.getElementById) {
    var x = (window.ActiveXObject) ? new ActiveXObject("Microsoft.XMLHTTP") : new XMLHttpRequest();
  }
  if (x) {

    x.onreadystatechange = function() {
	document.getElementById("contentLYR").innerHTML ='<img style="vertical-align:middle" src="images/loading.gif">Loading, please wait...' ;
      if (x.readyState == 4 && x.status == 200) {
        el = document.getElementById(id);
   el.innerHTML ="";
        el.innerHTML = x.responseText;

      }
    }
    x.open("GET", url, true);

    x.send(null);

  }
}
var selected;

var zhipper='<?php echo $shipping['title']; ?>';
var Csid='<?php echo $osCsid; ?>';
var zprice='<?php echo $shipping['cost']; ?>';
var selected;

function selectRowEffect2(object, buttonSelect) {
  if (!selected) {
    if (document.getElementById) {
      selected = document.getElementById('defaultSelected');
    } else {
      selected = document.all['defaultSelected'];
    }
  }

  if (selected) selected.className = 'moduleRow';
  object.className = 'moduleRowSelected2';
  selected = object;

// one button is not an array
  if (document.checkout_payment.shipping[0]) {
    document.checkout_payment.shipping[buttonSelect].checked=true;
  } else {
    document.checkout_payment.shipping.checked=true;
  }
}

function rowOverEffect(object) {
  if (object.className == 'moduleRow') object.className = 'moduleRowOver';
}

function rowOutEffect(object) {
  if (object.className == 'moduleRowOver') object.className = 'moduleRow';
}
//--></script>
<script language="javascript"><!--
var selected;
<?php//rmh M-S_ccgv begin ?>
var submitter = null;
function submitFunction() {
   submitter = 1;
   }
<?php//rmh M-S_ccgv end ?>
function selectRowEffect(object, buttonSelect) {
  if (!selected) {
    if (document.getElementById) {
      selected = document.getElementById('defaultSelected');
    } else {
      selected = document.all['defaultSelected'];
    }
  }

  if (selected) selected.className = 'moduleRow';
  object.className = 'moduleRowSelected';
  selected = object;

// one button is not an array
  if (document.checkout_payment.payment[0]) {
    document.checkout_payment.payment[buttonSelect].checked=true;
  } else {
    document.checkout_payment.payment.checked=true;
  }
}

function rowOverEffect(object) {
  if (object.className == 'moduleRow') object.className = 'moduleRowOver';
}

function rowOutEffect(object) {
  if (object.className == 'moduleRowOver') object.className = 'moduleRow';
}
//--></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="3" cellpadding="3">
  <tr>
    <TD WIDTH="<?php echo BOX_WIDTH; ?>" VALIGN="top" rowspan="2"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<td valign="top" colspan="2" valign="top"><?php require(DIR_WS_INCLUDES . 'titlebar.php'); ?></td></tr><tr><!-- body_text //-->
   <td valign="top" colspan="2" valign="top"><?php require(DIR_WS_INCLUDES . 'titlebar.php'); ?></td></tr><tr><!-- body_text //-->
    <td width="100%" valign="top"><?php echo tep_draw_form('checkout_payment', tep_href_link(FILENAME_CHECKOUT_SHIPPING, 'n=1', 'SSL'), 'post', 'onsubmit="return check_form();"') . tep_draw_hidden_field('action', 'process'); ?><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE;
//echo $ZETA; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_image(DIR_WS_IMAGES . 'table_background_payment.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
  if (isset($HTTP_GET_VARS['payment_error']) && is_object(${$HTTP_GET_VARS['payment_error']}) && ($error = ${$HTTP_GET_VARS['payment_error']}->get_error())) {
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><b><?php echo tep_output_string_protected($error['title']); ?></b></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td>
        <table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBoxNotice">
          <tr class="infoBoxNoticeContents">
            <td>
            <table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td class="main" width="100%" valign="top"><?php echo tep_output_string_protected($error['error']); ?></td>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
            </table></td>
          </tr>
        </table>
       </td>
          </tr>
        </table>
<?php
  }
?>
      </td>
        <td>
        <table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
               </tr>
        </table></td>
      </tr>
 <?php	require('includes/fec/products_box.php');?>
  <?php	require('includes/fec/comment_box.php');?>
<?php	//require('includes/fec/shipping_box.php');?>
<?php	require('includes/fec/ajax_shipping2.php');?>
<?php
 $show_total = tep_db_prepare_input($HTTP_GET_VARS['show_total']);	
  if ($show_total ==1)          require('includes/fec/total_box2.php');?>
<?php
 // echo $order_total_modules->credit_selection();//rmh M-S_ccgv
?>
<tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr><tr  cellpadding="2">
<td  class="infoBox"><div id="contentLYR" align="right"  style="margin-right: 10px;">
</div></td></tr><noscript>
<?php	  if ($show_total ==1)   require('total_box.php');?></noscript>
<?php	//require('includes/fec/address_box.php');?>
  <?php	require('includes/fec/payment_box.php');?>

      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td class="main"><b><?php echo TITLE_CONTINUE_CHECKOUT_PROCEDURE . '</b><br>' . TEXT_CONTINUE_CHECKOUT_PROCEDURE; ?></td>
                <td class="main" align="right"><?php echo tep_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE,'name="preview" value="preview data"');  ?></td>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td width="25%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td width="50%" align="right"><?php echo tep_draw_separator('pixel_silver.gif', '1', '5'); ?></td>
                <td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
              </tr>
            </table></td>
            <td width="25%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
                <td><?php echo tep_image(DIR_WS_IMAGES . 'checkout_bullet.gif'); ?></td>
                <td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
              </tr>
            </table></td>
            <td width="25%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
            <td width="25%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
                <td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '1', '5'); ?></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td align="center" width="25%" class="checkoutBarFrom"><?php echo '<a href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL') . '" class="checkoutBarFrom">' . CHECKOUT_BAR_DELIVERY . '</a>'; ?></td>
            <td align="center" width="25%" class="checkoutBarCurrent"><?php echo CHECKOUT_BAR_PAYMENT; ?></td>
            <td align="center" width="25%" class="checkoutBarTo"><?php echo CHECKOUT_BAR_CONFIRMATION; ?></td>
            <td align="center" width="25%" class="checkoutBarTo"><?php echo CHECKOUT_BAR_FINISHED; ?></td>
          </tr>
        </table></td>
      </tr>
    </table></form></td>
<!-- body_text_eof //-->

    <TD WIDTH="<?php echo BOX_WIDTH; ?>" VALIGN="top" rowspan="2"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
<!-- right_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_right.php'); ?>
<!-- right_navigation_eof //-->
    </table></td>
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
