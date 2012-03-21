
<!-- shopping_cart //-->
<?php if($cart->count_contents()>0){?>
<div style="border:1px solid #999999;margin-top:20px;margin-bottom:20px;">
  <div id="nav_manufacturers" class="nav_box">
    <div class="nav_header">
      <?php  echo '<a href="' . tep_href_link(FILENAME_SHOPPING_CART, '', 'SSL') . '">' . BOX_HEADING_SHOPPING_CART . '</a>';?>
    </div>


    <?php


  $cart_contents_string = '';
  if ($cart->count_contents() > 0) {
    $cart_contents_string = '<table border="0" cellspacing="0" cellpadding="0">';
    $products = $cart->get_products();
    for ($i=0, $n=sizeof($products); $i<$n; $i++) {
      $cart_contents_string .= '<tr><td align="right" valign="top" class="infoBoxContents">';

      if ((tep_session_is_registered('new_products_id_in_cart')) && ($new_products_id_in_cart == $products[$i]['id'])) {
        $cart_contents_string .= '<span class="newItemInCart">';
      } else {
        $cart_contents_string .= '<span class="infoBoxContents">';
      }

     if ($products[$i]['carrot'] == "1")
      {
        $cart_contents_string .= $products[$i]['quantity'] . '&nbsp;x&nbsp;</span></td><td valign="top" class="infoBoxContents">';
      } else {
        $cart_contents_string .= $products[$i]['quantity'] . '&nbsp;x&nbsp;</span></td><td valign="top" class="infoBoxContents"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products[$i]['id']) . '">';
      }

      if ((tep_session_is_registered('new_products_id_in_cart')) && ($new_products_id_in_cart == $products[$i]['id'])) {
        $cart_contents_string .= '<span class="newItemInCart">';
      } else {
        $cart_contents_string .= '';
      }

       if ($products[$i]['carrot'] == "1")
      {    
        $cart_contents_string .= $products[$i]['name'] . '</span></td></tr>';
      } else {
        $cart_contents_string .= $products[$i]['name'] . '</a></td></tr>';
      }
      if ((tep_session_is_registered('new_products_id_in_cart')) && ($new_products_id_in_cart == $products[$i]['id'])) {
        tep_session_unregister('new_products_id_in_cart');
      }
    }
    $cart_contents_string .= '</table>';
  } else {
    $cart_contents_string .= BOX_SHOPPING_CART_EMPTY;
  }

  echo $cart_contents_string;

  if ($cart->count_contents() > 0) {
    echo tep_draw_separator();
    echo '<b>'.$currencies->format($cart->show_total()) . ' Total</b>';
  }
//ICW ADDED FOR CREDIT CLASS GV
  if (tep_session_is_registered('customer_id')) {
    $gv_query = tep_db_query("select amount from " . TABLE_COUPON_GV_CUSTOMER . " where customer_id = '" . $customer_id . "'");
	$gv_result = tep_db_fetch_array($gv_query);
	if ($gv_result['amount'] > 0) {
	  echo tep_draw_separator();
	  echo '<table cellpadding="0" cellspacing="0" border="0"><tr><td class="smalltext">' . VOUCHER_BALANCE . '</td><td class="smalltext" align="right" valign="bottom">' . $currencies->format($gv_result['amount']) . '</td></tr></table>';
	  echo '<table cellpadding="0" cellspacing="0" border="0"><tr><td class="smalltext"><a href="' . tep_href_link(FILENAME_GV_SEND) . '">' . BOX_SEND_TO_FRIEND . '</a></td></tr></table>';
	  }
	}
	if (tep_session_is_registered('gv_id')) {
	  $gv_query = tep_db_query("select coupon_amount from " . TABLE_COUPONS . "where coupon_id = '" . $gv_id . "'");
	  $coupon = tep_db_fetch_array($gv_query);
	  echo tep_draw_separator();
	  echo '<table cellpadding="0" cellspacing="0" border="0"><tr><td class="smalltext">' . VOUCHER_REDEEMED . '</td><td class="smalltext" align="right" valign="bottom">' . $currencies->format($coupon['coupon_amount']) . '</td></tr></table>';
	  }
	if (tep_session_is_registered('cc_id') && $cc_id) {
	  echo tep_draw_separator();
	  echo '<table cellpadding="0" wiidth="100%" cellspacing="0" border="0"><tr><td class="smalltext">' . CART_COUPON . '</td><td class="smalltext" align="right" valign="bottom">' . '<a href="javascript:couonpopupWindow(' .  tep_href_link(FILENAME_POPUP_COUPON_HELP, 'cID=' . $cc_id) . '\')">' . CART_COUPON_INFO . '</a>' . '</td></tr></table>';
	  }
//ADDED FOR CREDIT CLASS GV END ADDITION

?>
  </div>
</div>
<!-- shopping_cart_eof //-->
<?php }?>