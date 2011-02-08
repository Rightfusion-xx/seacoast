<?php


  require('includes/application_top.php');

// if the customer is not logged on, redirect them to the shopping cart page
  if (!tep_session_is_registered('customer_id')) {
    tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
  }
  
  refresh_user_info();

  if (isset($HTTP_GET_VARS['action']) && ($HTTP_GET_VARS['action'] == 'update')) {
    $notify_string = 'action=notify&';
    $notify = $HTTP_POST_VARS['notify'];
    if (!is_array($notify)) $notify = array($notify);
    for ($i=0, $n=sizeof($notify); $i<$n; $i++) {
      $notify_string .= 'notify[]=' . $notify[$i] . '&';
    }
    if (strlen($notify_string) > 0) $notify_string = substr($notify_string, 0, -1);

    tep_redirect(tep_href_link(FILENAME_DEFAULT, $notify_string));
// Added a check for a Guest checkout and cleared the session - 030411 
if (tep_session_is_registered('noaccount')) { 
tep_session_destroy();
tep_redirect(tep_href_link(FILENAME_DEFAULT, '', 'NONSSL')); 
} 
else { 
tep_redirect(tep_href_link(FILENAME_DEFAULT, $notify_string, 'SSL')); 
}
  }

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CHECKOUT_SUCCESS);

  $breadcrumb->add(NAVBAR_TITLE_1);
  $breadcrumb->add(NAVBAR_TITLE_2);

  $global_query = tep_db_query("select global_product_notifications from " . TABLE_CUSTOMERS_INFO . " where customers_info_id = '" . (int)$customer_id . "'");
  $global = tep_db_fetch_array($global_query);

  if ($global['global_product_notifications'] != '1') {
    $orders_query = tep_db_query("select o.orders_id, ot.value from " . TABLE_ORDERS . " o JOIN " . TABLE_ORDERS_TOTAL . " ot on ot.orders_id=o.orders_id where ot.class='ot_subtotal' and o.customers_id = '" . (int)$customer_id . "' order by date_purchased desc limit 1");
    $orders = tep_db_fetch_array($orders_query);

    $products_array = array();
    $products_query = tep_db_query("select products_id, products_name, final_price, products_quantity from " . TABLE_ORDERS_PRODUCTS . " where orders_id = '" . (int)$orders['orders_id'] . "' order by products_name");
    $pipe=false;
    while ($products = tep_db_fetch_array($products_query)) {
      $products_array[] = array('id' => $products['products_id'],
                                'text' => $products['products_name'],
      							'price'=> $products['final_price'],
      							'qty' => $products['products_quantity']);
      if($pipe){$hpoutput.='|';}
      $hpoutput.=$products['products_id'].'~'.urlencode($products['products_name']).'~'.$products['products_quantity'].'~'.number_format($products['final_price'],2);
      $pipe=true;
    }
  }
  
// PWA:  Added a check for a Guest checkout and cleared the session - 030411 v0.71
if (tep_session_is_registered('noaccount')) {
 $order_update = array('purchased_without_account' => '1');
 tep_db_perform(TABLE_ORDERS, $order_update, 'update', "orders_id = '".$orders['orders_id']."'");
//  tep_db_query("insert into " . TABLE_ORDERS . " (purchased_without_account) values ('1') where orders_id = '" . (int)$orders['orders_id'] . "'");
 tep_db_query("delete from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . tep_db_input($customer_id) . "'");
 tep_db_query("delete from " . TABLE_CUSTOMERS . " where customers_id = '" . tep_db_input($customer_id) . "'");
 tep_db_query("delete from " . TABLE_CUSTOMERS_INFO . " where customers_info_id = '" . tep_db_input($customer_id) . "'");
 tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET . " where customers_id = '" . tep_db_input($customer_id) . "'");
 tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " where customers_id = '" . tep_db_input($customer_id) . "'");
 tep_db_query("delete from " . TABLE_WHOS_ONLINE . " where customer_id = '" . tep_db_input($customer_id) . "'");
 tep_session_destroy();
 

}
   require(DIR_WS_CLASSES . 'order.php');
  $o_info = new order($orders['orders_id']);
  
  tep_session_unregister('cc_number');
  
  tep_session_unregister('cc_owner');
  
  tep_session_unregister('cvvnumber');
  
  tep_session_unregister('cc_expires_month');
  
  tep_session_unregister('cc_expires_year');

?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>

<script type="text/javascript">
  var pageTracker = _gat._getTracker("UA-207538-1");
  pageTracker._initData();
  pageTracker._trackPageview();
  
   pageTracker._addTrans(
    "<?php echo $orders['orders_id'] ?>",                                     // Order ID
    "",                            // Affiliation
    "<?php echo $o_info->info['total']; ?>",                                    // Total
    "",                                     // Tax
    "<?php echo $o_info->info['shipping_cost'] ?>",                                        // Shipping
    "<?php echo $o_info->delivery['city']?>",                                 // City
    "<?php echo $o_info->delivery['state']?>",                               // State
    "<?php echo $o_info->delivery['country']?>"                                       // Country
  );
</script>
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
    <td width="100%" valign="top"><?php echo tep_draw_form('order', tep_href_link('/')); ?><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="4" cellpadding="2">
          <tr>
            
            <td valign="top" class="main"><?php //echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?><div align="center" class="pageHeading"><?php echo HEADING_TITLE; ?></div><br>
            Thank you for shopping! Seacoast Vitamins ships orders FAST!
            <br><br>
            
            
<?php 
switch (rand(0,2)){
case 0:

?>
<!-- BEGIN: BizRate Survey Invitation HTML -->

<script language="JavaScript" src="https://eval.bizrate.com/js/pos_162489.js" type="text/javascript"></script>

<!-- END: BizRate Survey Invitation HTML -->
<?php break; 

case 1: ?>
<!-- BEGIN: Shopping.com Survey -->

<script type="text/javascript" language="JavaScript" src="https://www.shopping.com/xMSJ?pt=js&direct=1&mid=406477&lid=1"></script>
<script type="text/javascript" language="JavaScript" src="https://www.shopping.com/xMSJ?pt=js&mid=406477&lid=1"></script>

<!-- END: Shopping.com Survey -->
<?php break;
case 2: ?>

<link rel="stylesheet" href="https://merchants.nextag.com/serv/main/buyer/dhtmlpopup/dhtmlwindow.css" type="text/css" />
<script type="text/javascript"> seller_id = 3768833;
document.write('<'+ 'script type="text/javascript" src="https://merchants.nextag.com/seller/review/popup_include.js"><'+'\/script>'); 
</script>

<?php  break; }?>

<?php
  if ($global['global_product_notifications'] != '1') {
    echo 'We\'ll send you email updates for the following products as they are prepared and shipped to you.<br><p class="productsNotifications">';

    $products_displayed = array();
    for ($i=0, $n=sizeof($products_array); $i<$n; $i++) {
      if (!in_array($products_array[$i]['id'], $products_displayed)) {
      	?>
      	
			 <script type="text/javascript">
			
			  pageTracker._addItem(
			    "<?php echo $orders['orders_id']?>",                                     // Order ID
			    "<?php echo $products_array[$i]['id']?>",                                     // SKU
			    "<?php echo $products_array[$i]['text']?>",                                  // Product Name 
			    "",                             // Category
			    "<?php echo $products_array[$i]['price']?>",                                    // Price
			    "<?php echo $products_array[$i]['qty']?>"                                         // Quantity
			  );
			
			  pageTracker._trackTrans();
			</script>
			
			<!--Merchant Advantage-->
			<img src="zmam=56141451&zmas=1&zmaq=N&quantity=<?php echo $products_array[$i]['qty']?>&pcode=<?php echo $products_array[$i]['id']?>&zman=<?php echo $orders['orders_id']?>&zmat=<?php echo $products_array[$i]['price']*$products_array[$i]['qty']?>" width=0 height=0 border=0>
      	    <!--End Merchant Advantage-->

      	<?php
      	
        echo ' ' . $products_array[$i]['text'] . '<br>';
        $products_displayed[] = $products_array[$i]['id'];
      }
    }

    echo '</p>';
  } else {
    echo TEXT_SEE_ORDERS . '<br><br>' . TEXT_CONTACT_STORE_NAME;
  }
?>
            <h3><?php echo TEXT_THANKS_FOR_SHOPPING; ?></h3></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td align="right" class="main"><?php echo tep_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?></td>
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
            <td width="25%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
            <td width="25%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
                <td width="50%"><?php echo tep_image(DIR_WS_IMAGES . 'checkout_bullet.gif'); ?></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td align="center" width="25%" class="checkoutBarFrom"><?php echo CHECKOUT_BAR_DELIVERY; ?></td>
            <td align="center" width="25%" class="checkoutBarFrom"><?php echo CHECKOUT_BAR_PAYMENT; ?></td>
            <td align="center" width="25%" class="checkoutBarFrom"><?php echo CHECKOUT_BAR_CONFIRMATION; ?></td>
            <td align="center" width="25%" class="checkoutBarCurrent"><?php echo CHECKOUT_BAR_FINISHED; ?></td>
          </tr>
        </table></td>
      </tr>
<?php if (DOWNLOAD_ENABLED == 'true') include(DIR_WS_MODULES . 'downloads.php'); ?>
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

<!-- Pricegrabber -->
<img src="https://www.pricegrabber.com/conversion.php?retid=11556" width=1 height=1>
 <!-- End Pricegrabber -->
   
<script type="text/javascript">
<!--
    /* NexTag ROI Optimizer Data */
    var id = '3768833';
    var rev = '<?php echo $orders['value'];?>';
    var order = '<?php echo $orders['orders_id'];?>';
//-->
</script>
<script type="text/javascript" src="https://imgsrv.nextag.com/imagefiles/includes/roitrack.js"></script>


<script type="text/javascript" src="https://imgsrv.nextag.com/imagefiles/includes/roitrack.js"></script>
<!--Shopping.com-->
<script language="JavaScript">
var merchant_id = '406477'
var order_id = '<?php echo $orders['orders_id'];?>'
var order_amt = '<?php echo $orders['value'];?>'
var category_id = '0000'
var category_name = 'SeacoastVitamins'
var product_id = '0'
var product_name = '<?php echo $hpoutput;?>'
</script>
<script language="JavaScript" src="https://stat.DealTime.com/ROI/ROI.js?mid=406477"></script>

<!--Shopzilla/BizRate-->
<script language="javascript">
<!--
	/* Performance Tracking Data */
	var mid            = '162489';
	var cust_type      = '1';
	var order_value    = '<?php echo $orders['value'];?>';
	var order_id       = '<?php echo $orders['orders_id'];?>';
	var units_ordered  = '1';
//-->
</script>
<script language="javascript" src="https://www.shopzilla.com/css/roi_tracker.js"></script>

<?php  /*

  //--------------------------------------------------------------------------
   // PostAffiliate Pro integration code
   //--------------------------------------------------------------------------
   // get order id
   $sql = "select orders_id from ".TABLE_ORDERS.
          " where customers_id='".(int)$customer_id.
          "' order by date_purchased desc limit 1";
   $pap_orders_query = tep_db_query($sql);
   $pap_orders = tep_db_fetch_array($pap_orders_query);
   $pap_order_id = $pap_orders['orders_id'];
 
   // get total amount of order
   $sql = "select value from ".TABLE_ORDERS_TOTAL.
          " where orders_id='".(int)$pap_order_id.
          "' and class='ot_subtotal'";
   $pap_orders_total_query = tep_db_query($sql);
   $pap_orders_total = tep_db_fetch_array($pap_orders_total_query);
   $pap_total_value = $pap_orders_total['value'];
 
   // draw invisible image to register sale
   if($pap_total_value != "" && $pap_order_id != "")
   {
     $img = '<script id="pap_x2s6df8d" src="http://www.seacoastvitamins.com/affiliates/scripts/sale.js" type="text/javascript"></script>
<script type="text/javascript"><!--

var TotalCost="'.$pap_total_value.'";
var OrderID="'.$pap_order_id.'";
var ProductID="";
papSale();
--></script>';
     print $img;
   }
   //--------------------------------------------------------------------------
   // END of integration code
   //--------------------------------------------------------------------------
            */
?>
            
<!--Merchant Advantage-->
<script language="JavaScript" src="https://secure.merchantadvantage.com/inChannel/ma2q.js"></script>
<!--End Merchant Advantage-->

<!-- Google Code for Product Purchase Conversion Page -->
<script language="JavaScript" type="text/javascript">
<!--
var google_conversion_id = 1064963330;
var google_conversion_language = "en_US";
var google_conversion_format = "1";
var google_conversion_color = "ffffff";
var google_conversion_label = "flYbCNyKYRCCmuj7Aw";
//-->
</script>
<script language="JavaScript" src="https://www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<img height="1" width="1" border="0" src="https://www.googleadservices.com/pagead/conversion/1064963330/?label=flYbCNyKYRCCmuj7Aw&amp;script=0"/>
</noscript>


<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
