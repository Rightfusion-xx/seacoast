<?php

  require('includes/application_top.php');
//fast easy checkout start
tep_session_unregister('payment');

//fast easy checkout end 
// if the customer is not logged on, redirect them to the login page
  if (!tep_session_is_registered('customer_id')) {
    $navigation->set_snapshot();
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }

// if there is nothing in the customers cart, redirect them to the shopping cart page
  if ($cart->count_contents() < 1) {
    tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
  }

// if no shipping method has been selected, redirect the customer to the shipping method selection page
  if (!tep_session_is_registered('shipping')) {
    tep_redirect(tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
  }

// avoid hack attempts during the checkout procedure by checking the internal cartID
  if (isset($cart->cartID) && tep_session_is_registered('cartID')) {
    if ($cart->cartID != $cartID) {
      tep_redirect(tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
    }
  }

// Stock Check
  if ( (STOCK_CHECK == 'true') && (STOCK_ALLOW_CHECKOUT != 'true') ) {
    $products = $cart->get_products();
    for ($i=0, $n=sizeof($products); $i<$n; $i++) {
      if (tep_check_stock($products[$i]['id'], $products[$i]['quantity'])) {
        tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
        break;
      }
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

  require(DIR_WS_CLASSES . 'order.php');
  $order = new order;

  if (!tep_session_is_registered('comments')) tep_session_register('comments');

  $total_weight = $cart->show_weight();
  $total_count = $cart->count_contents();

// load all enabled payment modules
  require(DIR_WS_CLASSES . 'payment.php');
  $payment_modules = new payment;

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CHECKOUT_PAYMENT);

  $breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
  $breadcrumb->add(NAVBAR_TITLE_2, tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'));
?>
<!doctype html>
<html <?php echo HTML_PARAMS; ?>>
<head>
    <link rel="stylesheet" href="/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/bootstrap/css/bootstrap-responsive.min.css">
    <link href="/css/main.css" rel="stylesheet">
    <link href="/font/fonts.css" rel="stylesheet">
    <!--[if lt IE 9]>
    <script type="text/javascript" src="/jquery/respond.src.js"></script>
    <![endif]-->
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo 'Enter Billing Information - Seacoast Vitamins'; ?></title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
<script language="javascript"><!--
var selected;

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
//begin cvv contribution
function popupWindow(url) {
window.open(url,'popupWindow','toolbar=no,location=no,directories=no status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no,width=450,height=450,screenX=150,screenY=150,top=150,left=150')
}
//end cvv contribution
//--></script>
<?php echo $payment_modules->javascript_validation(); ?>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">

 

<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->

    <div class="container">
    <div class="row">
    <div class="span12">
    
    
	<?php echo tep_draw_form('checkout_payment', tep_href_link(FILENAME_CHECKOUT_SHIPPING, 'action=process', 'SSL'), 'post', 'onsubmit="return check_form();"'); ?>
	<h1>Update Payment Information</h1>
                            
                    <?php
  if (isset($HTTP_GET_VARS['payment_error']) && is_object(${$HTTP_GET_VARS['payment_error']}) && ($error = ${$HTTP_GET_VARS['payment_error']}->get_error())) {    
?>
        <div class="alert alert-error">
            <p><b>
                    <?php echo tep_output_string_protected($error['title']); ?>
                </b>
            </p>
            <p class="quote">
                <?php echo $_SESSION['payment_error_text']; ?>
            </p>
        </div>
                                    
                                  
                    <?php
  }
?>
                   <p>
                     <?php	require('includes/fec/payment_box.php');?>
                   </p>
                   <p>
                        <table border="0" cellspacing="1" cellpadding="2" class="infoBox">
                          <tr class="infoBoxContents"> 
                            <td> 
                              <table border="0" width="100%" cellspacing="0" cellpadding="2">
                                <tr> 
                                  <td width="10"> 
                                    <?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?>
                                  </td>
                                  <td class="main"><b> 
                                    <?php echo TITLE_CONTINUE_CHECKOUT_PROCEDURE . '</b><br>' . TEXT_CONTINUE_CHECKOUT_PROCEDURE; ?>
                                  </td>
                                  <td class="main" align="right"> 
                                    <?php echo tep_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?>
                                    <BR>
                                    <FONT SIZE="-2">(Information is encrypted 
                                    for your privacy and security).</FONT></td>
                                  <td width="10"> 
                                    <?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?>
                                  </td>
                                </tr>
                              </table>
                             </td>
                            </tr>
                            
                           </table>
                           
                          
                      
                           <table width="100%"><tr>
                            <td width="25%"> 
                              <table border="0" width="100%" cellspacing="0" cellpadding="0">
                                <tr> 
                                  <td width="50%"> 
                                    <?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?>
                                  </td>
                                  <td> 
                                    <?php echo tep_image(DIR_WS_IMAGES . 'checkout_bullet.gif'); ?>
                                  </td>
                                  <td width="50%"> 
                                    <?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?>
                                  </td>
                                </tr>
                              </table>
                            </td>
                            <td width="25%"> 
                              <?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?>
                            </td>
                            <td width="25%"> 
                              <?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?>
                            </td>
                            <td width="25%"> 
                              <table border="0" width="100%" cellspacing="0" cellpadding="0">
                                <tr> 
                                  <td width="50%"> 
                                    <?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?>
                                  </td>
                                  <td width="50%"> 
                                    <?php echo tep_draw_separator('pixel_silver.gif', '1', '5'); ?>
                                  </td>
                                </tr>
                              </table>
                            </td>
                          </tr>
                          <tr> 
                            <td align="center" width="25%" class="checkoutBarFrom"> 
                              <?php echo '<a href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL') . '" class="checkoutBarFrom">' . CHECKOUT_BAR_DELIVERY . '</a>'; ?>
                            </td>
                            <td align="center" width="25%" class="checkoutBarCurrent"> 
                              <?php echo CHECKOUT_BAR_PAYMENT; ?>
                            </td>
                            <td align="center" width="25%" class="checkoutBarTo"> 
                              <?php echo CHECKOUT_BAR_CONFIRMATION; ?>
                            </td>
                            <td align="center" width="25%" class="checkoutBarTo"> 
                              <?php echo CHECKOUT_BAR_FINISHED; ?>
                            </td>
                          </tr>
                        </table>
                       
                         </p>
                </form>
                </div></div></div><br/>
           
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
