<?php  require('includes/application_top.php');

// if the customer is not logged on, redirect them to the login page
  if (!tep_session_is_registered('customer_id')) {
    $navigation->set_snapshot(array('mode' => 'SSL', 'page' => FILENAME_CHECKOUT_PAYMENT));
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }// if there is nothing in the customers cart, redirect them to the shopping cart page
  if ($cart->count_contents() < 1) {
    tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
  }

  /// fec for get total
 $paynow = tep_db_prepare_input($HTTP_GET_VARS['paynow']);
if ($paynow ==3) {
 tep_redirect(tep_href_link(FILENAME_CHECKOUT_SHIPPING, 'show_total=1&error_message=' . urlencode(ERROR_TOTAL_NOW), 'SSL'));
  }
// avoid hack attempts during the checkout procedure by checking the internal cartID
  if (isset($cart->cartID) && tep_session_is_registered('cartID')) {
    if ($cart->cartID != $cartID) {
      tep_redirect(tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
    }
  }// if no shipping method has been selected, redirect the customer to the shipping method selection page
  if (!tep_session_is_registered('shipping')) {
    tep_redirect(tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
  }  if (!tep_session_is_registered('payment')) tep_session_register('payment');
  if (isset($HTTP_POST_VARS['payment'])) $payment = $HTTP_POST_VARS['payment'];  if (!tep_session_is_registered('comments')) tep_session_register('comments');
  if (tep_not_null($HTTP_POST_VARS['comments'])) {
    $comments = tep_db_prepare_input($HTTP_POST_VARS['comments']);
  }// load the selected payment module

  require(DIR_WS_CLASSES . 'payment.php');
  $payment_modules = new payment($payment);  
  
  require(DIR_WS_CLASSES . 'order.php');
  $order = new order;  
  $payment_modules->update_status();  
  if ( ( is_array($payment_modules->modules) && (sizeof($payment_modules->modules) > 1) && !is_object($$payment) ) || (is_object($$payment) && ($$payment->enabled == false)) ) {

    tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . urlencode(ERROR_NO_PAYMENT_MODULE_SELECTED), 'SSL'));
  }  if (is_array($payment_modules->modules)) {
    $payment_modules->pre_confirmation_check();
  }// load the selected shipping module

  require(DIR_WS_CLASSES . 'shipping.php');
  $shipping_modules = new shipping($shipping);  require(DIR_WS_CLASSES . 'order_total.php');
  $order_total_modules = new order_total;// Stock Check
  $any_out_of_stock = false;
  if (STOCK_CHECK == 'true') {
    for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
      if (tep_check_stock($order->products[$i]['id'], $order->products[$i]['qty'])) {
        $any_out_of_stock = true;
      }
    }

    // Out of Stock
    if ( (STOCK_ALLOW_CHECKOUT != 'true') && ($any_out_of_stock == true) ) {
      tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
    }
  }  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CHECKOUT_CONFIRMATION);  $breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));

 /////////////BEGIN MERGE OF checkout_process CODE////////////////////
  require('includes/checkout_guts.php');


//////////////END OF checkout_proccess.php MERGE/////////////////



  $breadcrumb->add(NAVBAR_TITLE_2);

?>

<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">

<html <?php echo HTML_PARAMS; ?>>

<head>

<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">

<title><?php echo TITLE; ?></title>

<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">

<link rel="stylesheet" type="text/css" href="stylesheet.css">

</head>

<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0"> <!-- header //-->

<?php require(DIR_WS_INCLUDES . 'header.php'); ?>

<!-- header_eof //--><!-- body //-->

<TABLE BORDER="0" WIDTH="100%" CELLSPACING="0" CELLPADDING="0">

  <TR>

    <TD WIDTH="<?php echo BOX_WIDTH; ?>" VALIGN="top" rowspan="2">

	  <TABLE BORDER="0" WIDTH="<?php echo BOX_WIDTH; ?>" CELLSPACING="2" CELLPADDING="0">

<!-- left_navigation //-->

<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>

<!-- left_navigation_eof //-->

      </TABLE></TD>

<td valign="top" colspan="2" valign="top"><?php require(DIR_WS_INCLUDES . 'titlebar.php'); ?></td></tr><tr><!-- body_text //-->

    <td width="100%" valign="top"><div id="content">

		

		<table border="0" cellspacing="0" cellpadding="12">

      <tr>

        <td><TABLE WIDTH="100%" BORDER="0" CELLPADDING="1" CELLSPACING="0" ><TR><TD>

<TABLE WIDTH="100%" BORDER="0" CELLPADDING="7" CELLSPACING="0" BGCOLOR="#FFFFFF"><TR><TD><table border="0" width="100%" cellspacing="0" cellpadding="0">

          <tr>

            <td><h1>Confirm Your Order</h1></td>

            <td class="pageHeading" align="right"><?php //echo tep_image(DIR_WS_IMAGES . 'table_background_confirmation.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>

          </tr>

        </table></td>

      </tr>

      <tr>

        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?>

                        <table border="0" width="100%" cellspacing="0" cellpadding="0">

                          <tr> 

                            <td align="left" class="main" height="19"> 

                              <?php

  if (isset($$payment->form_action_url)) {

    $form_action_url = $$payment->form_action_url;

  } else {

    $form_action_url = tep_href_link(FILENAME_CHECKOUT_PROCESS, '', 'SSL');

  }  echo tep_draw_form('checkout_confirmation', $form_action_url, 'post');  if (is_array($payment_modules->modules)) {

    echo $payment_modules->process_button();

  }  echo tep_image_submit('button_confirm_order.gif', IMAGE_BUTTON_CONFIRM_ORDER) . '<BR><FONT SIZE="-2">(Information is encrypted for your privacy and security).</FONT>' . '</form>' . "\n";

?><!-- START SCANALERT CODE --><br/>
<img width="94" height="54" border="0" src="https://images.scanalert.com/meter/www.seacoastvitamins.com/13.gif" alt="HACKER SAFE certified sites prevent over 99.9% of hacker crime." oncontextmenu="alert('Copying Prohibited by Law - HACKER SAFE is a Trademark of ScanAlert'); return false;">
<!-- END SCANALERT CODE -->

                            </td>

                          </tr>

                        </table>

                      </td>

      </tr>

      <tr>

        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">

          <tr class="infoBoxContents">

<?php

  if ($sendto != false) {

?>

            <td width="30%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">

              <tr>

                <td class="main"><?php echo '<b>' . HEADING_DELIVERY_ADDRESS . '</b> <a href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING_ADDRESS, '', 'SSL') . '"><span class="orderEdit">(' . TEXT_EDIT . ')</span></a>'; ?></td>

              </tr>

              <tr>

                <td class="main"><?php echo tep_address_format($order->delivery['format_id'], $order->delivery, 1, ' ', '<br>'); ?></td>

              </tr>

<?php

    if ($order->info['shipping_method']) {

?>

              <tr>

                <td class="main"><?php echo '<b>' . HEADING_SHIPPING_METHOD . '</b> <a href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL') . '"><span class="orderEdit">(' . TEXT_EDIT . ')</span></a>'; ?></td>

              </tr>

              <tr>

                <td class="main"><?php echo $order->info['shipping_method']; ?></td>

              </tr>

<?php

    }

?>

            </table></td>

<?php

  }

?>

            <td width="<?php echo (($sendto != false) ? '70%' : '100%'); ?>" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">

              <tr>

                <td><table border="0" width="100%" cellspacing="0" cellpadding="2">

<?php

  if (sizeof($order->info['tax_groups']) > 1) {

?>

                  <tr>

                    <td class="main" colspan="2"><?php echo '<b>' . HEADING_PRODUCTS . '</b> <a href="' . tep_href_link(FILENAME_SHOPPING_CART) . '"><span class="orderEdit">(' . TEXT_EDIT . ')</span></a>'; ?></td>

                    <td class="smallText" align="right"><b><?php echo HEADING_TAX; ?></b></td>

                    <td class="smallText" align="right"><b><?php echo HEADING_TOTAL; ?></b></td>

                  </tr>

<?php

  } else {

?>

                  <tr>

                    <td class="main" colspan="3"><?php echo '<b>' . HEADING_PRODUCTS . '</b> <a href="' . tep_href_link(FILENAME_SHOPPING_CART) . '"><span class="orderEdit">(' . TEXT_EDIT . ')</span></a>'; ?></td>

                  </tr>

<?php

  }  for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {

    echo '          <tr>' . "\n" .

         '            <td class="main" align="right" valign="top" width="30">' . $order->products[$i]['qty'] . '&nbsp;x</td>' . "\n" .

         '            <td class="main" valign="top">' . $order->products[$i]['name'];    if (STOCK_CHECK == 'true') {

      echo tep_check_stock($order->products[$i]['id'], $order->products[$i]['qty']);

    }    if ( (isset($order->products[$i]['attributes'])) && (sizeof($order->products[$i]['attributes']) > 0) ) {

      for ($j=0, $n2=sizeof($order->products[$i]['attributes']); $j<$n2; $j++) {

        echo '<br><nobr><small>&nbsp;<i> - ' . $order->products[$i]['attributes'][$j]['option'] . ': ' . $order->products[$i]['attributes'][$j]['value'] . '</i></small></nobr>';

      }

    }    echo '</td>' . "\n";    if (sizeof($order->info['tax_groups']) > 1) echo '            <td class="main" valign="top" align="right">' . tep_display_tax_value($order->products[$i]['tax']) . '%</td>' . "\n";    echo '            <td class="main" align="right" valign="top">' . $currencies->display_price($order->products[$i]['final_price'], $order->products[$i]['tax'], $order->products[$i]['qty']) . '</td>' . "\n" .

         '          </tr>' . "\n";

  }

?>

                </table></td>

              </tr>

            </table></td>

          </tr>

        </table></td>

      </tr>

      <tr>

        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>

      </tr>

      <tr>

        <td class="main"><b><?php echo HEADING_BILLING_INFORMATION; ?></b></td>

      </tr>

      <tr>

        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>

      </tr>

      <tr>

        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">

          <tr class="infoBoxContents">

            <td width="30%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">

              <tr>

                <td class="main"><?php echo '<b>' . HEADING_BILLING_ADDRESS . '</b> <a href="' . tep_href_link(FILENAME_CHECKOUT_PAYMENT_ADDRESS, '', 'SSL') . '"><span class="orderEdit">(' . TEXT_EDIT . ')</span></a>'; ?></td>

              </tr>

              <tr>

                <td class="main"><?php echo tep_address_format($order->billing['format_id'], $order->billing, 1, ' ', '<br>'); ?></td>

              </tr>

              <tr>

                <td class="main"><?php echo '<b>' . HEADING_PAYMENT_METHOD . '</b> <a href="' . tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL') . '"><span class="orderEdit">(' . TEXT_EDIT . ')</span></a>'; ?></td>

              </tr>

              <tr>

                <td class="main"><?php echo $order->info['payment_method']; ?></td>

              </tr>

            </table></td>

            <td width="70%" valign="top" align="right"><table border="0" cellspacing="0" cellpadding="2">

<?php

  if (MODULE_ORDER_TOTAL_INSTALLED) {

    $order_total_modules->process();

    echo $order_total_modules->output();

  }

?>

            </table></td>

          </tr>

        </table></td>

      </tr>

<?php

  if (is_array($payment_modules->modules)) {

    if ($confirmation = $payment_modules->confirmation()) {

?>

      <tr>

        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>

      </tr>

      <tr>

        <td class="main"><b><?php echo HEADING_PAYMENT_INFORMATION; ?></b></td>

      </tr>

      <tr>

        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>

      </tr>

      <tr>

        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">

          <tr class="infoBoxContents">

            <td><table border="0" cellspacing="0" cellpadding="2">

              <tr>

                <td class="main" colspan="4"><?php echo $confirmation['title']; ?></td>

              </tr>

<?php

      for ($i=0, $n=sizeof($confirmation['fields']); $i<$n; $i++) {

?>

              <tr>

                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>

                <td class="main"><?php echo $confirmation['fields'][$i]['title']; ?></td>

                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>

                <td class="main"><?php echo $confirmation['fields'][$i]['field']; ?></td>

              </tr>

<?php

      }

?>

            </table></td>

          </tr>

        </table></td>

      </tr>

<?php

    }

  }

?>

      <tr>

        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>

      </tr>

<?php

  if (tep_not_null($order->info['comments'])) {

?>

      <tr>

        <td class="main"><?php echo '<b>' . HEADING_ORDER_COMMENTS . '</b> <a href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL') . '"><span class="orderEdit">(' . TEXT_EDIT . ')</span></a>'; ?></td>

      </tr>

      <tr>

        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>

      </tr>

      <tr>

        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">

          <tr class="infoBoxContents">

            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">

              <tr>

                <td class="main"><?php echo nl2br(tep_output_string_protected($order->info['comments'])) . tep_draw_hidden_field('comments', $order->info['comments']); ?></td>

              </tr>

            </table></td>

          </tr>

        </table></td>

      </tr>

      <tr>

        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>

      </tr>

<?php

  }

?>

      <tr>

        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">

          <tr>

            <td align="left" class="main">

<?php

  if (isset($$payment->form_action_url)) {

    $form_action_url = $$payment->form_action_url;

  } else {

    $form_action_url = tep_href_link(FILENAME_CHECKOUT_PROCESS, '', 'SSL');

  }  echo tep_draw_form('checkout_confirmation', $form_action_url, 'post');  if (is_array($payment_modules->modules)) {

    echo $payment_modules->process_button();

  }  echo tep_image_submit('button_confirm_order.gif', IMAGE_BUTTON_CONFIRM_ORDER) . '<BR><FONT SIZE="-2">(Information is encrypted for your privacy and security).</FONT>' . '</form>' . "\n";

?>

            </td>

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

            <td width="25%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>

            <td width="25%"><table border="0" width="100%" cellspacing="0" cellpadding="0">

              <tr>

                <td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>

                <td><?php echo tep_image(DIR_WS_IMAGES . 'checkout_bullet.gif'); ?></td>

                <td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>

              </tr>

            </table></td>

            <td width="25%"><table border="0" width="100%" cellspacing="0" cellpadding="0">

              <tr>

                <td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>

                <td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '1', '5'); ?></td>

              </tr>

            </table></td>

          </tr>

          <tr>

            <td align="center" width="25%" class="checkoutBarFrom"><?php echo '<a href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL') . '" class="checkoutBarFrom">' . CHECKOUT_BAR_DELIVERY . '</a>'; ?></td>

            <td align="center" width="25%" class="checkoutBarFrom"><?php echo '<a href="' . tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL') . '" class="checkoutBarFrom">' . CHECKOUT_BAR_PAYMENT . '</a>'; ?></td>

            <td align="center" width="25%" class="checkoutBarCurrent"><?php echo CHECKOUT_BAR_CONFIRMATION; ?> </td>

            <td align="center" width="25%" class="checkoutBarTo"><?php echo CHECKOUT_BAR_FINISHED; ?></td>

          </tr>

        </table></td>

      </tr>

    </table></TD></TR></TABLE></TD></TR></TABLE></div></td>

		

<!-- body_text_eof //-->

   <TD WIDTH="<?php echo BOX_WIDTH; ?>" VALIGN="top" rowspan="2">

     <TABLE BORDER="0" WIDTH="<?php echo BOX_WIDTH; ?>" CELLSPACING="2" CELLPADDING="0">

<!-- right_navigation //--><!-- right_navigation_eof //-->

     </TABLE></TD></TR></TABLE>

<!-- body_eof //--><!-- footer //-->

<?php require(DIR_WS_INCLUDES . 'secure_footer.php'); ?>

<!-- footer_eof //-->

<br>

</body>

</html>

<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>

