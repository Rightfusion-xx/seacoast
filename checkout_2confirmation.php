<?php
/*
  $Id: checkout_confirmation.php,v 1.139 2003/06/11 17:34:53 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  define('AJAX_NOW', 'yes');

$HTTP_POST_VARS['gv_redeem_code']=$gc;

if($cb==1){
tep_session_register('cot_gv');
}else{tep_session_unregister('cot_gv');}
$HTTP_POST_VARS['i']=2;
 $shipping['title']=$zship.'';




 
 
 if (tep_not_null($HTTP_POST_VARS['tip'])) {
    $tip = tep_db_prepare_input($HTTP_POST_VARS['tip']);

  }

// load the selected payment module
  require(DIR_WS_CLASSES . 'payment.php');
  if ($credit_covers) $payment=''; //rmh M-S_ccgv
  $payment_modules = new payment($payment);
  require(DIR_WS_CLASSES . 'order_total.php'); //rmh M-S_ccgv
$shipping['cost']=$tip;

  require(DIR_WS_CLASSES . 'order.php');
  $order = new order;

  $payment_modules->update_status();
  $order_total_modules = new order_total; //rmh M-S_ccgv
 
 
  if (is_array($payment_modules->modules)) {
    $payment_modules->pre_confirmation_check();
  }




// load the selected shipping module
  require(DIR_WS_CLASSES . 'shipping.php');
  $shipping_modules = new shipping($shipping);




 if (MODULE_ORDER_TOTAL_INSTALLED) {
    $order_total_modules->process();

    echo $order_total_modules->output() ;

  }

?>