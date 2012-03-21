<?php
define('HEADING_TITLE', 'Coupons');
define('SUB_HEADING_TITLE_1', 'Information');
define('SUB_HEADING_TITLE_2', 'How does it work');
define('SUB_HEADING_TITLE_3', 'Note:');
define('SUB_HEADING_TEXT_1', 'Coupon codes are printed on your packing slip and proforma invoice which you received with your previous order.<br>If you have such a coupon code, please enter it here to receive an <span style="font-size:14px;color:blue;">additional discount</span>.');
if ($ec_mf == 'm') {
  define('SUB_HEADING_TEXT_2', 'As of May 2006 each packing slip and proforma invoice we send along with your products contains a coupon code. These coupon codes are 16 digit codes in the form of <span style="color:blue;">XXXX-XXXX-XXXX-XXXX</span>. Each coupon code has a discount value for your next purchase. The discount amount is semi-random in that it is subject to chance yet the higher your current order value, the higher the chance of obtaining a higher Coupon discount for your next purchase.<br>Once you have entered your valid coupon code and press the '.tep_image_submit('button_cash_in.gif', 'Cash in Coupon'). ' Button you will immediately see your discount in the basket totals.');
} else {
  define('SUB_HEADING_TEXT_2', 'As of May 2006 each packing slip and proforma invoice we send along with your products contains a coupon code. These coupon codes are 16 digit codes in the form of <span style="color:blue;">XXXX-XXXX-XXXX-XXXX</span>. Each coupon code has a discount value for your next purchase. The higher your current order value, the higher the Coupon discount for your next purchase.<br>Once you have entered your valid coupon code and press the '.tep_image_submit('button_cash_in.gif', 'Cash in Coupon'). ' Button you will immediately see your discount in the basket totals.');
}
if ($ec_expire) {
  define('SUB_HEADING_TEXT_3', 'Coupon codes are unique and can only be used once. Your coupon code remains valid for '.$ec_days.' days and you can only use one coupon code per purchase. Anyone with a valid coupon code can use it as they are not registered by name. So keep you coupon code safe unless you wish to give it to a friend as a gift ofcourse.');
} else {
  define('SUB_HEADING_TEXT_3', 'Coupon codes are unique and can only be used once. Your coupon code remains valid until you use it but you can only use one coupon code per purchase. Anyone with a valid coupon code can use it as they are not registered by name. So keep you coupon code safe unless you wish to give it to a friend as a gift ofcourse.');
}
define('TEXT_CLOSE_WINDOW', '<img src="images/buttons/buttonsmall_close.gif" border="0">');
?>