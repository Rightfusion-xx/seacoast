<?php
include(DIR_WS_LANGUAGES . $language . '/' . FILENAME_EASY_COUPONS);
// easy discount installed

// configuration details extraction
$ec_config  = preg_split('/d/',EASY_COUPONS); // split table from config
$ec_table   = $ec_config[1];
$ec_config  = preg_split(';',$ec_config[0]); // split config values
$ec_active  = $ec_config[0]; // EC active
$ec_auto    = $ec_config[1]; // EC Automatic
$ec_email   = $ec_config[4]; // Print on email
$ec_expire  = $ec_config[5]; // Expires
$ec_days    = $ec_config[6]; // days to expiration
$ec_dtype   = $ec_config[7]; // discount table type (money/percent)
$ec_mf      = $ec_config[8]; // discount table value (max/fixed)
$ec_clth    = $ec_config[9]; // length of coupon codes in characters

if (MODULE_EASY_DISCOUNT_STATUS == 'true') {
  // coupons enabled
  if ($ec_active) {
    // cart not empty
    if ($cart->count_contents() > 0) {
      // coupon code input given
      if ($_POST['coupon_code1'] != '') {
        // concatenate the input fields
        $inputcouponcode = strtoupper($_POST['coupon_code1']);
        // coupon reset code
        if ($inputcouponcode != 'RESET') {
          // fetch the coupon code from the database
          $coupon_query = tep_db_query("select code, discount, type, enddate 
                                        from coupons 
                                        where code = '" . $inputcouponcode . "'
                                          and (enddate > now() || enddate is null) 
                                          and used = 0 ");
          $coupon = tep_db_fetch_array($coupon_query);
          // valid and available coupon code found
          if ($coupon['code']) {
            $couponcode = array('code' => $coupon['code'], 'discount' => $coupon['discount'], 'type' => $coupon['type']);
            if (!tep_session_is_registered('couponcode')) tep_session_register('couponcode');
            // give message
            $messageStack->add_session('cart',EC_PROCESSED,'success');

          } else {
            // give message
            $messageStack->add_session('cart',EC_UNKNOWN,'error');
          }
        } elseif (tep_session_is_registered('couponcode')) {
          $messageStack->add_session('cart',EC_RESET,'success');
          tep_session_unregister('couponcode');
          // clear discount if cart goes empty
          $easy_discount->clear('COUPON');
        } else {
          // give message
          $messageStack->add_session('cart',EC_UNKNOWN.$inputcouponcode ,'error');
        }
      } elseif ((isset($_POST['coupon_code1']))) {
        $messageStack->add_session('cart',EC_EMPTY,'error');
      }
      // process coupon discount using easy discount
      if (tep_session_is_registered('couponcode')) {
        if ($couponcode['type'] == 'p') {
          $easy_discount->set('COUPON',$couponcode['code'].' Coupon Discount ('.round($couponcode['discount'],0).'%)', $cart_total*$couponcode['discount']/100);
        } elseif ($cart_total > $couponcode['discount']) {
          $easy_discount->set('COUPON',$couponcode['code'].' Coupon Discount ('.$currencies->format($couponcode['discount']).')', $couponcode['discount']);
        } else {
          $easy_discount->set('COUPON',$couponcode['code'].' Coupon Discount ('.$currencies->format($cart_total).')', $cart_total);
          $messageStack->add_session('cart','Your Coupon Discount ('.$currencies->format($couponcode['discount']).') has been limited to '.$currencies->format($cart_total).', add more to your basket to use your full discount.','error');
        }
      }
    } else {
      // clear discount if cart goes empty
      $easy_discount->clear('COUPON');
    }
    // clear the input fields
    $coupon_code1 = '';
  }
}
?>