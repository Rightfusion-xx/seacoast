<?php
/*
  $Id: orders.php,v 1.112 2003/06/29 22:50:52 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_CLASSES . 'currencies.php');
  require(DIR_WS_CLASSES . 'authnet.php');
  include(DIR_WS_CLASSES . 'order.php');
  $currencies = new currencies();

  $orders_statuses = array();
  $orders_status_array = array();
  $orders_status_query = tep_db_query("select orders_status_id, orders_status_name from " . TABLE_ORDERS_STATUS . " where language_id = '" . (int)$languages_id . "'");
  while ($orders_status = tep_db_fetch_array($orders_status_query)) {
    $orders_statuses[] = array('id' => $orders_status['orders_status_id'],
                               'text' => $orders_status['orders_status_name']);
    $orders_status_array[$orders_status['orders_status_id']] = $orders_status['orders_status_name'];
  }
  

  
  $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');

  if (tep_not_null($action)) {
        $oID = tep_db_prepare_input($HTTP_GET_VARS['oID']);
        $status = tep_db_prepare_input($HTTP_POST_VARS['status']);
        $comments = tep_db_prepare_input($HTTP_POST_VARS['comments']);
        $order=new order($oID);
        $payhistory=paytools($order);
        

   
    switch ($action) {
      case 'makepayment':
      if($_REQUEST['amount']<=0.00)
      {
       $messageStack->add_session('A valid amount is required and may not be a negative (-) amount.','warning');

      }
      else
      {
      if($_REQUEST['type']=='refund')
      {
        foreach($payhistory as $ph)
        {
          if($ph[6]== $_REQUEST['transaction_id'])
          {
            break;
          }
        }

        if($_REQUEST['amount']<1 || $_REQUEST['amount']>$ph[9])
        {
          $messageStack->add_session('The amount of refund cannot be less than 1 or more than the original transaction.','warning');
        }
        else
        {
          if($_REQUEST['memo']=='' && $_REQUEST['amount']<$ph[9])
          {
            $ph['description']='Partial customer refund';
          }
          elseif($_REQUEST['memo']=='' && $_REQUEST['amount']==$ph[9])
          {
            $ph['description']='Full customer refund';
          }
          else
          {
            $ph['description']=$_REQUEST['memo'];
          }

          $pay_result=runRefund($order, $ph,$_REQUEST['amount']);

          if($pay_result[0]=='1')
          {
            //Update status
            $status=($_REQUEST['status']=='')?7:$_REQUEST['status'];
            $comments=$ph['description']."\n\rRefund - " . tep_db_input($pay_result[9]);
          }
          else
          {
  
              $messageStack->add_session($pay_result[0],'warning');
              $messageStack->add_session($pay_result[3],'warning');
              tep_redirect(tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('action')) . 'action=edit'));

          }
        }

        
      }
      elseif($_REQUEST['type']=='payment')
      {
        if($_REQUEST['amount']+$payhistory['total_paid']>$order->info['ot_total'])
        {
          $messageStack->add_session('The amount to pay cannot be more than the total invoice amount, including other authorized transactions.','warning');
          tep_redirect(tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('action')) . 'action=edit'));

        }
        else
        {
          //check to see if amount is less than original amount.
          if($_REQUEST['amount']<$order->info['ot_total'])
          {
            $order->info['ot_total']=$_REQUEST['amount'];
            
            if($_REQUEST['memo']!='')
            {
              $order->info['description']=$_REQUEST['memo'];
              
            }
            else
            {
               $order->info['description']='Customer Order - Partial Payment';
            }
          }

          if($_REQUEST['memo']=='')
          {
            //transaction is full transaction
            $order->info['description']='Customer Order';
          }
          else
          {
            $order->info['description']=$_REQUEST['memo'];
          }

          $pay_result=runPayment($order);

          $order->info['transid']=tep_db_prepare_input($_REQUEST['transid']);


        if($pay_result[0]=='1')
        {
          //Update status
          $status=7;
          $comments=$order->info['description']."\n\rPaid - " . tep_db_input($pay_result[9]);
        }
        else
        {

            $messageStack->add_session($pay_result[0],'warning');
            $messageStack->add_session($pay_result[3],'warning');
            tep_redirect(tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('action')) . 'action=edit'));
        }
        
       
      }
      }

    }


            

      case 'update_order':

        $order_updated = false;
        $check_status_query = tep_db_query("select customers_name, customers_email_address, orders_status, date_purchased from " . TABLE_ORDERS . " where orders_id = '" . (int)$oID . "'");
        $check_status = tep_db_fetch_array($check_status_query);

        if ( ($check_status['orders_status'] != $status) || tep_not_null($comments)) {
          tep_db_query("update " . TABLE_ORDERS . " set orders_status = '" . tep_db_input($status) . "', last_modified = now() where orders_id = '" . (int)$oID . "'");
            
            if(($check_status['orders_status']=='3' || $check_status['orders_status']=='7' || $check_status['orders_status']=='4' || $check_status['orders_status']=='11') && ($status!='3' && $status!='7' && $status!='4' && $status!='11'))
            {
                $order->restockInventory();
            }
            elseif(($check_status['orders_status']!='3' && $check_status['orders_status']!='7' && $check_status['orders_status']!='4' && $check_status['orders_status']!='11') && ($status=='3' || $status=='7' || $status=='4'|| $status=='11'))
            {
                $order->deductInventory();
            }
            
          $customer_notified = '0';
          if (isset($HTTP_POST_VARS['notify']) && ($HTTP_POST_VARS['notify'] == 'on')) {
            $notify_comments = '';
            if (isset($HTTP_POST_VARS['notify_comments']) && ($HTTP_POST_VARS['notify_comments'] == 'on')) {
              $notify_comments = sprintf(EMAIL_TEXT_COMMENTS_UPDATE, $comments) . "\n\n";
            }

$email='';
            $email .= STORE_NAME . "\n" . EMAIL_SEPARATOR . "\n" . EMAIL_TEXT_ORDER_NUMBER . ' ' . $oID . "\n" . EMAIL_TEXT_INVOICE_URL . ' ' . tep_catalog_href_link(FILENAME_CATALOG_ACCOUNT_HISTORY_INFO, 'order_id=' . $oID, 'SSL') . "\n" . EMAIL_TEXT_DATE_ORDERED . ' ' . tep_date_long($check_status['date_purchased']) . "\n\n" . $notify_comments . sprintf(EMAIL_TEXT_STATUS_UPDATE, $orders_status_array[$status]);

            tep_mail($check_status['customers_name'], $check_status['customers_email_address'], EMAIL_TEXT_SUBJECT, $email, STORE_NAME, STORE_OWNER_EMAIL_ADDRESS);

            $customer_notified = '1';
          }

          tep_db_query("insert into " . TABLE_ORDERS_STATUS_HISTORY . " (orders_id, orders_status_id, date_added, customer_notified, comments) values ('" . (int)$oID . "', '" . tep_db_input($status) . "', now(), '" . tep_db_input($customer_notified) . "', '" . tep_db_input($comments)  . "')");

          $order_updated = true;
        }

        if ($order_updated == true) {
         $messageStack->add_session(SUCCESS_ORDER_UPDATED, 'success');
        } else {
          $messageStack->add_session(WARNING_ORDER_NOT_UPDATED, 'warning');
        }

        tep_redirect(tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('action')) . 'action=edit'));
        break;
      case 'deleteconfirm':
        $oID = tep_db_prepare_input($HTTP_GET_VARS['oID']);

        tep_remove_order($oID, $HTTP_POST_VARS['restock']);

        tep_redirect(tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID', 'action'))));
        break;
// begin cvv contribution
    case 'deletecvv':
      $oID = tep_db_prepare_input($HTTP_GET_VARS['oID']);
      $cvvnumber = tep_db_prepare_input ($HTTP_POST_VARS['cvvnumber']);

      tep_db_query("update " . TABLE_ORDERS . " set cvvnumber  = null " . tep_db_input($cvvnumber) . " where orders_id = '" . tep_db_input($oID) . "'");
      $order_updated = true;

      if ($order_updated) {
       $messageStack->add_session(SUCCESS_ORDER_UPDATED, 'success');
      } else {
        $messageStack->add_session(WARNING_ORDER_NOT_UPDATED, 'warning');
      }

      tep_redirect(tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('action')) . 'action=edit'));
      break;		
    }
  }

  if (($action == 'edit') && isset($HTTP_GET_VARS['oID'])) {
    $oID = tep_db_prepare_input($HTTP_GET_VARS['oID']);

    $orders_query = tep_db_query("select orders_id from " . TABLE_ORDERS . " where orders_id = '" . (int)$oID . "'");
    $order_exists = true;
    if (!tep_db_num_rows($orders_query)) {
      $order_exists = false;
      $messageStack->add(sprintf(ERROR_ORDER_DOES_NOT_EXIST, $oID), 'error');
    }
  }
  
function paytools(&$order)
{
    //Paytools
  $pt=tep_db_query('select * from authnet_transactions where orders_id="'.$order->info['orders_id'].'"');
  $pay_history=Array();
  while($ph=tep_db_fetch_array($pt))
  {
    $ph['results']=unserialize(gzuncompress($ph['results']));
    $ph['results']['transaction_time']=$ph['time_created'];
    array_push($pay_history,$ph['results']);

  }
  
  //If old PayFlow Pro transaction exists, allow a refund from the manager.
  if($order->info['paid']=='1' && count($pay_history)<1)
  {
    array_push($pay_history, array(6=>' ',9=>$order->info['ot_total'], 11=>'auth_capture', 0=>'1'));

  }
  ////////////////////

  
  foreach($pay_history as $ph)
  {
    if($ph[0]=='1')
    {
      //take in to account transaction because the transaction was a success
      if($ph[11]=='auth_capture')
      {
        $total_paid+=$ph[9];
      }
      else
      {
        $total_paid-=$ph[9];
      }

    }

  }

  if(number_format($total_paid,2)>=number_format($order->info['ot_total'],2))
  {
    $order->info['paid']='1';
  }
  $pay_history['total_paid']=$total_paid;

  return($pay_history);
}  

    paytools($order);
    


?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/general.js"></script>


<script language="javascript">
function printInvoice(auto)
{
  var invoice_only=true;
  if(!auto)
  {
    invoice_only=confirm('Only print customer invoice? Press cancel to print all details.') ;
  }
  if(invoice_only)
  {
   document.getElementById('all_info').setAttribute('class','hide-when-print');
  }
  window.print();
  document.getElementById('all_info').setAttribute('class','');
  return false;
}
</script>


</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" <?php if($order->info['paid']=='1' && isset($_REQUEST['transid'])){?> onload="printInvoice(true);" <?php }?> >
<div id="all_info">
<!-- header //-->
<?php
  require(DIR_WS_INCLUDES . 'header.php');
?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top" class="hide-when-print"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
<?php

  if (($action == 'edit') && ($order_exists == true)) {
    $order = new order($oID);
    $payhistory=paytools($order);
?>

      <tr>
        <td width="100%" colspan="2"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo 'Order Number: ' . $oID; ?><?php if($order->info['paid']=='1'){?><br/><span style="font-size:18pt;color:#ff0000;">PAID</span>&nbsp;[<a href="#" onclick="printInvoice(false);">print</a>]<?php }?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', 1, HEADING_IMAGE_HEIGHT); ?></td>
            <td class="pageHeading" align="right"><?php echo ' <a href="' . tep_href_link(FILENAME_ORDERS_EDIT, 'oID=' . $_GET['oID']) . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_ORDERS_INVOICE, 'oID=' . $_GET['oID']) . '" TARGET="_blank">' . tep_image_button('button_invoice.gif', IMAGE_ORDERS_INVOICE) . '</a> <a href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('action'))) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a> '; ?></td>
          </tr>
        </table>
        
        <table width="100%" border="0">
          
          
      <tr>
        <td class="main" width="50%" valign="top"><table border="1" cellspacing="0" cellpadding="5" width="100%">
          <tr>
            <td class="smallText" align="center" nowrap><b><?php echo TABLE_HEADING_DATE_ADDED; ?></b></td>
            <td class="smallText" align="center" nowrap><b><?php echo TABLE_HEADING_CUSTOMER_NOTIFIED; ?></b></td>
            <td class="smallText" align="center" nowrap><b><?php echo TABLE_HEADING_STATUS; ?></b></td>
            <td class="smallText" align="center" width="100%"><b><?php echo TABLE_HEADING_COMMENTS; ?></b></td>
          </tr>
<?php
    $orders_history_query = tep_db_query("select orders_status_id, date_added, customer_notified, comments from " . TABLE_ORDERS_STATUS_HISTORY . " where orders_id = '" . tep_db_input($oID) . "' order by date_added desc");
    if (tep_db_num_rows($orders_history_query)) {
      while ($orders_history = tep_db_fetch_array($orders_history_query)) {
        echo '          <tr>' . "\n" .
             '            <td class="smallText" align="center">' . tep_datetime_short($orders_history['date_added']) . '</td>' . "\n" .
             '            <td class="smallText" align="center">';
        if ($orders_history['customer_notified'] == '1') {
          echo tep_image(DIR_WS_ICONS . 'tick.gif', ICON_TICK) . "</td>\n";
        } else {
          echo tep_image(DIR_WS_ICONS . 'cross.gif', ICON_CROSS) . "</td>\n";
        }
        echo '            <td class="smallText">' . $orders_status_array[$orders_history['orders_status_id']] . '</td>' . "\n" .
             '            <td class="smallText">' . nl2br(tep_db_output($orders_history['comments'])) . '&nbsp;</td>' . "\n" .
             '          </tr>' . "\n";
      }
    } else {
        echo '          <tr>' . "\n" .
             '            <td class="smallText" colspan="5">' . TEXT_NO_ORDER_HISTORY . '</td>' . "\n" .
             '          </tr>' . "\n";
    }
?>
        </table></td>
      <td valign="top">

      <table border="0" width="100%" cellspacing="0">
      <tr><?php echo tep_draw_form('status', FILENAME_ORDERS, tep_get_all_get_params(array('action')) . 'action=update_order'); ?>
        <td class="main" align="center"><?php echo tep_draw_textarea_field('comments', 'soft', '60', '5'); ?></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td align="center"><table border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td><table border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main"><b><?php echo ENTRY_STATUS; ?></b> <?php echo tep_draw_pull_down_menu('status', $orders_statuses, $order->info['orders_status']); ?></td>
              </tr>
              <tr>
                <td class="main"><b><?php echo ENTRY_NOTIFY_CUSTOMER; ?></b> <?php echo tep_draw_checkbox_field('notify', '', true); ?></td>
                <td class="main"><b><?php echo ENTRY_NOTIFY_COMMENTS; ?></b> <?php echo tep_draw_checkbox_field('notify_comments', '', true); ?></td>
              </tr>
            </table></td>
            <td valign="top"><?php echo tep_image_submit('button_update.gif', IMAGE_UPDATE); ?></td>
          </tr>
        </table></td>
      </form></tr>
         </td></tr></table>
         
         </td></tr></table>




        <!--MaxMind-->
        <?php // Addition for MaxMind CC check Noel Latsha
$check_maxmind_query = tep_db_query("select distance, country_match, country_code, free_mail, anonymous_proxy, score, bin_match, bin_country, err, proxy_score, spam_score, bin_name, cust_phone, ip_city, ip_latitude, ip_longitude, ip_region, ip_isp, ip_org, hi_risk from " . TABLE_ORDERS_MAXMIND . " where order_id = '" . (int)$oID . "'");
$maxmind_query = tep_db_fetch_array($check_maxmind_query);
$max_score = round($maxmind_query['score']);
switch ($max_score) {
case 0: $max_comment = '(Extremely Low risk)'; break;
case 1: $max_comment = '(Very Low risk)'; break;
case 2: $max_comment = '(Low risk)'; break;
case 3: $max_comment = '(Low risk)'; break;
case 4: $max_comment = '(Low-Medium risk)'; break;
case 5: $max_comment = '(Medium risk)'; break;
case 6: $max_comment = '(Medium-high risk)'; break;
case 7: $max_comment = '(High risk)'; break;
case 8: $max_comment = '(Very High risk)'; break;
case 9: $max_comment = '(Extremely High risk)'; break;
case 10: $max_comment = '(I can smell the fraud from here)'; break;
}
?>

<table width="100%" cellpadding="2" cellspacing="0" border="0">
<tr>
<td colspan="7" class="main"><?php echo '<br><b>' . MAXMIND_SCORE . '&nbsp;&nbsp;&nbsp;<font color="red">' . $maxmind_query['score'] . '</font> ' . $max_comment . '</b>'; ?></td>
</tr>
<tr class="dataTableRow">
<td width="14%" class="dataTableContent"><?php echo MAXMIND_COUNTRY; ?></td>
<td width="18%" class="dataTableContent"><?php echo '<b>' . $maxmind_query['country_match'] . '</b>'; ?></td>
<td width="14%" class="dataTableContent"><?php echo MAXMIND_CODE; ?></td>
<td width="18%" class="dataTableContent"><?php echo '<b>' . $maxmind_query['country_code'] . '</b>'; ?></td>
<td width="14%" class="dataTableContent"><?php echo MAXMIND_HI_RISK; ?></td>
<td width="18%" class="dataTableContent"><?php echo '<b>' . $maxmind_query['hi_risk'] . '</b>'; ?></td>
</tr>
<tr>
<td width="14%" class="dataTableContent"><?php echo MAXMIND_BIN_MATCH; ?></td>
<td width="18%" class="dataTableContent"><?php echo '<b>' . $maxmind_query['bin_match'] . '</b>'; ?></td>
<td width="14%" class="dataTableContent"><?php echo MAXMIND_BIN_COUNTRY; ?></td>
<td width="18%" class="dataTableContent"><?php echo '<b>' . $maxmind_query['bin_country'] . '</b>'; ?></td>
<td width="14%" class="dataTableContent"><?php echo MAXMIND_BIN_NAME; ?></td>
<td width="18%" class="dataTableContent"><?php echo '<b>' . $maxmind_query['bin_name'] . '</b>'; ?></td>
</tr>
<tr class="dataTableRow">
<td width="14%" class="dataTableContent"><?php echo MAXMIND_IP_ISP; ?></td>
<td width="18%" class="dataTableContent"><?php echo '<b>' . $maxmind_query['ip_isp'] . '</b>'; ?></td>
<td width="14%" class="dataTableContent"><?php echo MAXMIND_IP_ISP_ORG; ?></td>
<td width="18%" class="dataTableContent"><?php echo '<b>' . $maxmind_query['ip_org'] . '</b>'; ?></td>
<td width="14%" class="dataTableContent"><?php echo MAXMIND_DISTANCE; ?></td>
<td width="18%" class="dataTableContent"><?php echo '<b>' . $maxmind_query['distance'] . '</b>'; ?></td>
</tr>
<tr>
<td width="14%" class="dataTableContent"><?php echo MAXMIND_ANONYMOUS; ?></td>
<td width="18%" class="dataTableContent"><?php echo '<b>' . $maxmind_query['anonymous_proxy'] . '</b>'; ?></td>
<td width="14%" class="dataTableContent"><?php echo MAXMIND_PROXY_SCORE; ?></td>
<td width="18%" class="dataTableContent"><?php echo '<b>' . $maxmind_query['proxy_score'] . '</b>'; ?></td>
<td width="14%" class="dataTableContent"><?php echo MAXMIND_SPAM; ?></td>
<td width="18%" class="dataTableContent"><?php echo '<b>' . $maxmind_query['spam_score'] . '</b>'; ?></td>
</tr>
<tr class="dataTableRow">
<td width="14%" class="dataTableContent"><?php echo MAXMIND_FREE_EMAIL; ?></td>
<td width="18%" class="dataTableContent"><?php echo '<b>' . $maxmind_query['free_mail'] . '</b>'; ?></td>
<td width="14%" class="dataTableContent"><?php echo MAXMIND_CUST_PHONE; ?></td>
<td width="18%" class="dataTableContent"><a href="http://www.whitepages.com/search/Reverse_Phone?phone=<?php echo $order->customer['telephone']; ?>" target="_blank"><?php echo '<b>' . $maxmind_query['cust_phone'] . '</b>'; ?></td>
<td width="14%" class="dataTableContent"><?php echo MAXMIND_ERR; ?></td>
<td width="18%" class="dataTableContent"><?php echo '<b>' . $maxmind_query['err'] . '</b>'; ?></td>
</tr>
</table>
<table width="100%" cellpadding="2" cellspacing="0" border="0">
<tr>&nbsp;</tr>
<tr>
<td colspan="4" class="dataTableContent" width="75%" align="center"><?php echo MAXMIND_DETAILS . '&nbsp;&nbsp;&nbsp;' . MAXMIND_MAXMIND; ?></td>
</tr>
<tr class="dataTableRow">
<td width="25%" class="dataTableContent"><?php echo MAXMIND_IP_CITY . '<b>' . $maxmind_query['ip_city'] . '</b>'; ?></td>
<td width="25%" class="dataTableContent"><?php echo MAXMIND_IP_REGION . '<b>' . $maxmind_query['ip_region'] . '</b>'; ?></td>
<td width="25%" class="dataTableContent"><?php echo MAXMIND_IP_LATITUDE . '<b>' . $maxmind_query['ip_latitude'] . '</b>'; ?></td>
<td width="25%" class="dataTableContent"><?php echo MAXMIND_IP_LONGITUDE . '<b>' . $maxmind_query['ip_longitude'] . '</b>'; ?></td>
</tr>


<?php // End addition for MaxMind Noel Latsha ?>
          <tr>
            <td class="main"><?php echo ENTRY_CREDIT_CARD_CVVNUMBER; ?></td>
            <td class="main"><?php echo $order->info['cvvnumber']; ?></td>
          </tr>
<?php // end cvv contribution ?>		  
 </table>

        <hr/>


        <table width="100%">
      <tr>
        <td><table width="100%" border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main" valign="top"><b><?php echo ENTRY_CUSTOMER; ?></b><br/><a href="customers.php?cID=<?php echo $order->customer['id']  ?>&action=edit">[view profile]</td>
                <td class="main"><?php echo tep_address_format($order->customer['format_id'], $order->customer, 1, '', '<br>'); ?></td>
              </tr>
              <tr>
                <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
              </tr>
              <tr>
                <td class="main"><b><?php echo ENTRY_TELEPHONE_NUMBER; ?></b></td>
                <td class="main"><?php echo $order->customer['telephone']; ?></td>
              </tr>
              <tr>
                <td class="main"><b><?php echo ENTRY_EMAIL_ADDRESS; ?></b></td>
                <td class="main"><?php echo '<a href="mailto:' . $order->customer['email_address'] . '"><u>' . $order->customer['email_address'] . '</u></a>'; ?></td>
              </tr>
            </table></td>
            <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main" valign="top"><b><?php echo ENTRY_SHIPPING_ADDRESS; ?></b></td>
                <td class="main"><?php echo tep_address_format($order->delivery['format_id'], $order->delivery, 1, '', '<br>'); ?></td>
<?php if ($order->delivery !== $order->billing){ print("<script language = 'javascript'>alert('WARNING: The customers address is different from the shipping address please make sure you send the order to the correct address Click ok to go to the order page');</script>");
				echo '<h3><font color="#FF0000">Shipping Address Different From Billing Address</font>';
} ?>
              </tr>
            </table></td>
            <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main" valign="top"><b><?php echo ENTRY_BILLING_ADDRESS; ?></b></td>
                <td class="main"><?php echo tep_address_format($order->billing['format_id'], $order->billing, 1, '', '<br>'); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>

      </table>











</td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr class="dataTableHeadingRow">
            <td class="dataTableHeadingContent" colspan="3"><?php echo TABLE_HEADING_PRODUCTS; ?></td>
            <td class="dataTableHeadingContent"><?php echo 'Brand'; ?></td>
            <td class="dataTableHeadingContent" align="right"><?php echo 'Qty Avail.'; ?></td>
            <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_TAX; ?></td>
            <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_PRICE_EXCLUDING_TAX; ?></td>
            <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_PRICE_INCLUDING_TAX; ?></td>
            <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_TOTAL_EXCLUDING_TAX; ?></td>
            <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_TOTAL_INCLUDING_TAX; ?></td>
          </tr>
<?php
    for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
      echo '          <tr class="dataTableRow">' . "\n" .
           '            <td class="dataTableContent" valign="top" align="left">['.$order->products[$i]['location'].']' .
           '            <td class="dataTableContent" valign="top" align="right">' . $order->products[$i]['qty'] . '&nbsp;x</td>' . "\n" .
           '            <td class="dataTableContent" valign="top">' . $order->products[$i]['name'];

      if (isset($order->products[$i]['attributes']) && (sizeof($order->products[$i]['attributes']) > 0)) {
        for ($j = 0, $k = sizeof($order->products[$i]['attr
        
        
        ibutes']); $j < $k; $j++) {
          echo '<br><nobr><small>&nbsp;<i> - ' . $order->products[$i]['attributes'][$j]['option'] . ': ' . $order->products[$i]['attributes'][$j]['value'];
          if ($order->products[$i]['attributes'][$j]['price'] != '0') echo ' (' . $order->products[$i]['attributes'][$j]['prefix'] . $currencies->format($order->products[$i]['attributes'][$j]['price'] * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . ')';
          echo '</i></small></nobr>';
        }
      }

      echo '            </td>' . "\n" .
           '            <td class="dataTableContent" valign="top">' . $order->products[$i]['manufacturer'] . '</td>' . "\n" .
           '            <td class="dataTableContent" valign="top" align="right">' . $order->products[$i]['qty_avail'] . '</td>' . "\n" .
           '            <td class="dataTableContent" align="right" valign="top">' . tep_display_tax_value($order->products[$i]['tax']) . '%</td>' . "\n" .
           '            <td class="dataTableContent" align="right" valign="top"><b>' . $currencies->format($order->products[$i]['final_price'], true, $order->info['currency'], $order->info['currency_value']) . '</b></td>' . "\n" .
           '            <td class="dataTableContent" align="right" valign="top"><b>' . $currencies->format(tep_add_tax($order->products[$i]['final_price'], $order->products[$i]['tax']), true, $order->info['currency'], $order->info['currency_value']) . '</b></td>' . "\n" .
           '            <td class="dataTableContent" align="right" valign="top"><b>' . $currencies->format($order->products[$i]['final_price'] * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . '</b></td>' . "\n" .
           '            <td class="dataTableContent" align="right" valign="top"><b>' . $currencies->format(tep_add_tax($order->products[$i]['final_price'], $order->products[$i]['tax']) * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . '</b></td>' . "\n";
      echo '          </tr>' . "\n";
    }
?>
          <tr>
            <td align="right" colspan="9"><table border="0" cellspacing="0" cellpadding="2">
<?php
    for ($i = 0, $n = sizeof($order->totals); $i < $n; $i++) {
      echo '              <tr>' . "\n" .
           '                <td align="right" class="smallText">' . $order->totals[$i]['title'] . '</td>' . "\n" .
           '                <td align="right" class="smallText">' . $order->totals[$i]['text'] . '</td>' . "\n" .
           '              </tr>' . "\n";
    }
?>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
          <td align="right" style="padding-right:5em;padding-top:1em;">


        <table border="0" cellspacing="0" cellpadding="2">

          <tr>
            <td class="main"><b><?php echo ENTRY_PAYMENT_METHOD; ?></b></td>
            <td class="main"><?php echo $order->info['payment_method']; ?></td>
          </tr>
<?php
//    if (tep_not_null($order->info['cc_type']) || tep_not_null($order->info['cc_owner']) || tep_not_null($order->info['cc_number'])) {
// begin cvv contribution
      if (tep_not_null($order->info['cc_type']) || tep_not_null($order->info['cc_owner']) || tep_not_null($order->info['cc_number']) || tep_not_null($order->info['cvvnumber']))  {
// end cvv contribution
?>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_CREDIT_CARD_TYPE; ?></td>
            <td class="main"><?php echo $order->info['cc_type']; ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_CREDIT_CARD_OWNER; ?></td>
            <td class="main"><?php echo $order->info['cc_owner']; ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_CREDIT_CARD_NUMBER; ?></td>
            <td class="main"><?php echo 'XXXX...'.substr($order->info['cc_number'],-4); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_CREDIT_CARD_EXPIRES; ?></td>
            <td class="main"><?php echo $order->info['cc_expires']; ?></td>
          </tr>
          </table>
          
          
          
          
<!--Payment info-->
        <form action="<?php echo FILENAME_ORDERS?>?action=makepayment&transid=1111&oID=<?php echo $oID?>" method="post" name="paytools">
        <input name="action" value="makepayment" type="hidden"/>
        <input name="type" value="payment" type="hidden"/>
        <input name="status" value="<?php echo $order->info['orders_status']?>" type="hidden"/>
        <input name="oID" value="<?php echo $oID?>" type="hidden"/>
        <?php if(strlen($payhistory[0][0])>0)
            { ?>
            <p>
               <b class="main">Transaction History</b>
               <table cellspacing="2" style="font-size:10pt;" width="100%">
                      <tr>
                          <td>TransactionID</td>
                          <td>Type</td>
                          <td>Amount</td>
                          <td>Result</td>
                          <td>AVS</td>
                          <td>CVV</td>
                          <td>Message</td>
                          <td>Time</td>
                      <?php
                      foreach($payhistory as $ph)
                      {
                        if(strlen($ph[6])>0)
                        {
                        echo '<tr>' ;
                        echo '<td>'; if($ph[11]=='auth_capture' && $ph[0]=='1') echo '<input type="radio" name="transaction_id" value="'.$ph[6].'" onclick="javascript:update_payment_type(\'refund\',\''.$ph[9].'\')">';
                        echo $ph[6].'</td>';
                        echo '<td>'.$ph[11].'</td>';
                        echo '<td>$'.$ph[9].'</td>';
                        echo '<td>'.$ph[0].'</td>';
                        echo '<td>'.$ph[5].'</td>';
                        echo '<td>'.$ph[38].'</td>';
                        echo '<td>'.$ph[3].'</td>';
                        echo '<td>'.$ph['transaction_time'].'</td>';
                        echo '</tr>';
                        }

                      }

                      ?>
                      <tr><td colspan="8"><input type="radio" name="transaction_id" value="" checked onclick="javascript:update_payment_type('payment','<?php echo number_format($order->info['ot_total']-$payhistory['total_paid'],2)?>')"> New Payment

               </table>
            </p>
            <?php echo '<p><b>Total Paid: $'.$payhistory['total_paid'].'</b></p>';
            
            ?>

            <?php }  ?>
             <div class="hide-when-print main" style="font-size:10pt;padding-top:1em;">
             <b><span id='prompt_heading'>Make Payment</span></b>
             <p>

                <table cellspacing="0" cellpaddin="0" style="font-size:10pt;">
                <tr><td>
                <span id="prompt_payamt">Payment Amount</span>:</td><td><input type="text" name="amount" value="<?php echo number_format($order->info['ot_total']-$payhistory['total_paid'],2)?>"/>
                </td></tr><td>

                Memo</td><td><input type="text" name="memo" value=""/>
                </td><td></tr><tr><td>
                <input type="submit" />
                </form>
                </td></tr>
                </table>

             </p>
             </div>

             <script type="text/javascript">  
                     function update_payment_type(paytype,amt)
                     { 
                       document.paytools.amount.value=amt;

                       if(paytype=='refund')
                       {
                         document.paytools.type.value='refund';
                         document.getElementById('prompt_payamt').innerHTML='Refund Amount';
                         document.getElementById('prompt_heading').innerHTML='Issue Refund';
                         
                       }
                       else
                       {
                         document.paytools.type.value='payment';
                         document.getElementById('prompt_payamt').innerHTML='Payment Amount';
                         document.getElementById('prompt_heading').innerHTML='Make Payment';
                       }



                     }


             </script>







          </td>
      </tr>

      <tr>
        <td colspan="2" align="right"><?php echo ' <a href="' . tep_href_link(FILENAME_ORDERS_EDIT, 'oID=' . $_GET['oID']) . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_ORDERS_INVOICE, 'oID=' . $_GET['oID']) . '" TARGET="_blank">' . tep_image_button('button_invoice.gif', IMAGE_ORDERS_INVOICE) . '</a>  <a href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('action'))) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a> '; ?></td>
<?php
    }
?>
<?php
  } else {
?>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', 1, HEADING_IMAGE_HEIGHT); ?></td>
            <td align="right"><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr><?php echo tep_draw_form('orders', FILENAME_ORDERS, '', 'get'); ?>
                <td class="smallText" align="right"><?php echo HEADING_TITLE_SEARCH . ' ' . tep_draw_input_field('oID', '', 'size="12"') . tep_draw_hidden_field('action', 'edit'); ?></td>
              </form></tr>
              <tr><?php echo tep_draw_form('status', FILENAME_ORDERS, '', 'get'); ?>
                <td class="smallText" align="right"><?php echo HEADING_TITLE_STATUS . ' ' . tep_draw_pull_down_menu('status', array_merge(array(array('id' => '', 'text' => TEXT_ALL_ORDERS)), $orders_statuses), '', 'onChange="this.form.submit();"'); ?></td>
              </form></tr>            
            </table></td>
          </tr>

        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_CUSTOMERS; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ORDER_TOTAL; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_DATE_PURCHASED; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_STATUS; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
    if (isset($HTTP_GET_VARS['cID'])) {
      $cID = tep_db_prepare_input($HTTP_GET_VARS['cID']);
      $orders_query_raw = "select o.orders_id, o.customers_name, o.customers_id, o.payment_method, o.date_purchased, o.last_modified, o.currency, o.currency_value, s.orders_status_name, ot.text as order_total from " . TABLE_ORDERS . " o left join " . TABLE_ORDERS_TOTAL . " ot on (o.orders_id = ot.orders_id), " . TABLE_ORDERS_STATUS . " s where o.customers_id = '" . (int)$cID . "' and o.orders_status = s.orders_status_id and s.language_id = '" . (int)$languages_id . "' and ot.class = 'ot_total' order by orders_id DESC --";
    } elseif (isset($HTTP_GET_VARS['status'])) {
      $status = tep_db_prepare_input($HTTP_GET_VARS['status']);
      $orders_query_raw = "select o.orders_id, o.customers_name, o.payment_method, o.date_purchased, o.last_modified, o.currency, o.currency_value, s.orders_status_name, ot.text as order_total from " . TABLE_ORDERS . " o left join " . TABLE_ORDERS_TOTAL . " ot on (o.orders_id = ot.orders_id), " . TABLE_ORDERS_STATUS . " s where o.orders_status = s.orders_status_id and s.language_id = '" . (int)$languages_id . "' and s.orders_status_id = '" . (int)$status . "' and ot.class = 'ot_total' order by o.orders_id DESC";
    } else {
      $orders_query_raw = "select o.orders_id, o.customers_name, o.payment_method, o.date_purchased, o.last_modified, o.currency, o.currency_value, s.orders_status_name, ot.text as order_total from " . TABLE_ORDERS . " o left join " . TABLE_ORDERS_TOTAL . " ot on (o.orders_id = ot.orders_id), " . TABLE_ORDERS_STATUS . " s where o.date_purchased>=DATE_ADD(CURDATE(), INTERVAL -7 DAY) and o.orders_status = s.orders_status_id and s.language_id = '" . (int)$languages_id . "' and ot.class = 'ot_total' order by o.orders_id DESC";
    }
    $orders_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $orders_query_raw, $orders_query_numrows);
    $orders_query = tep_db_query($orders_query_raw);
    while ($orders = tep_db_fetch_array($orders_query)) {
    if ((!isset($HTTP_GET_VARS['oID']) || (isset($HTTP_GET_VARS['oID']) && ($HTTP_GET_VARS['oID'] == $orders['orders_id']))) && !isset($oInfo)) {
        $oInfo = new objectInfo($orders);
      }

      if (isset($oInfo) && is_object($oInfo) && ($orders['orders_id'] == $oInfo->orders_id)) {
        echo '              <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $oInfo->orders_id . '&action=edit') . '\'">' . "\n";
      } else {
        echo '              <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID')) . 'oID=' . $orders['orders_id']) . '\'">' . "\n";
      }
?>
                <td class="dataTableContent"><?php echo '<a href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $orders['orders_id'] . '&action=edit') . '">' . tep_image(DIR_WS_ICONS . 'preview.gif', ICON_PREVIEW) . '</a>&nbsp;' . $orders['customers_name']; ?></td>
                <td class="dataTableContent" align="right"><?php echo strip_tags($orders['order_total']); ?></td>
                <td class="dataTableContent" align="center"><?php echo tep_datetime_short($orders['date_purchased']); ?></td>
                <td class="dataTableContent" align="right"><?php echo $orders['orders_status_name']; ?></td>
                <td class="dataTableContent" align="right"><?php if (isset($oInfo) && is_object($oInfo) && ($orders['orders_id'] == $oInfo->orders_id)) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID')) . 'oID=' . $orders['orders_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
    }
?>
              <tr>
                <td colspan="5"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $orders_split->display_count($orders_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_ORDERS); ?></td>
                    <td class="smallText" align="right"><?php echo $orders_split->display_links($orders_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page'], tep_get_all_get_params(array('page', 'oID', 'action'))); ?></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
<?php
  $heading = array();
  $contents = array();

  switch ($action) {
    case 'delete':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_ORDER . '</b>');

      $contents = array('form' => tep_draw_form('orders', FILENAME_ORDERS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $oInfo->orders_id . '&action=deleteconfirm'));
      $contents[] = array('text' => TEXT_INFO_DELETE_INTRO . '<br><br><b>' . $cInfo->customers_firstname . ' ' . $cInfo->customers_lastname . '</b>');
      $contents[] = array('text' => '<br>' . tep_draw_checkbox_field('restock') . ' ' . TEXT_INFO_RESTOCK_PRODUCT_QUANTITY);
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $oInfo->orders_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    default:
      if (isset($oInfo) && is_object($oInfo)) {
        $heading[] = array('text' => '<b>[' . $oInfo->orders_id . ']&nbsp;&nbsp;' . tep_datetime_short($oInfo->date_purchased) . '</b>');

        $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $oInfo->orders_id . '&action=edit') . '">' . tep_image_button('button_details.gif', IMAGE_DETAILS) . '</a> <a href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $oInfo->orders_id . '&action=delete') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a>');
$contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_ORDERS_INVOICE, 'oID=' . $oInfo->orders_id) . '" TARGET="_blank">' . tep_image_button('button_invoice.gif', IMAGE_ORDERS_INVOICE) . '</a> <a href="' . tep_href_link(FILENAME_ORDERS_PACKINGSLIP, 'oID=' . $oInfo->orders_id) . '" TARGET="_blank">' . tep_image_button('button_packingslip.gif', IMAGE_ORDERS_PACKINGSLIP) . '</a> <a href="' . tep_href_link(FILENAME_ORDERS_EDIT, 'oID=' . $oInfo->orders_id) . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a>');
$contents[] = array('text' => '<br>' . TEXT_DATE_ORDER_CREATED . ' ' . tep_date_short($oInfo->date_purchased));
        $contents[] = array('text' => '<br>' . TEXT_INFO_PAYMENT_METHOD . ' '  . $oInfo->payment_method);
      }
      break;
  }

  if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
    echo '            <td width="25%" valign="top">' . "\n";

    $box = new box;
    echo $box->infoBox($heading, $contents);

    echo '            </td>' . "\n";
  }
?>
          </tr>
        </table></td>
      </tr>
<?php
  }
?>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</div>
<?php if(is_numeric($oID) && $oID>0 && $order->info['paid']=='1'){echo '<div class="page-break"></div><div class="hide-till-print" style="width:100%">';require ('includes/invoice.php');echo '</div>';}?>

</body>
</html>


<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); 

?>


<?php

function CapturePayment($order)
{
/*
    $pfpXml='<?xml version="1.0" encoding="UTF-8"?><XMLPayRequest Timeout="30" version = "2.0" xmlns="http://www.paypal.com/XMLPay"> 
                <RequestData>
                    <Vendor>'.PFP_VENDOR.'</Vendor>
                    <Partner>'.PFP_PARTNER.'</Partner>';
                    
    $pfpXml.='<Transactions>
                <Transaction>
                    <Authorization>
                        <PayData>
                            <Invoice>
                                <TotalAmt>'.$orders['order_total'].'</TotalAmt>
                            </Invoice>
                            <Tender>
                                <Card>
                                    <CardType>'.$order->info['cc_type'].'</CardType>
                                    <CardNum>'.$order->info['cc_number'].'</CardNum>
                                    <ExpDate>20'.right($order->info['cc_expires']),2).left($order->info['cc_expires']),2)</ExpDate>
                                    <CVNum>'.$order->info['cvvnum']).'</CVNum>
                                    <NameOnCard>'.$order->info['cc_owner']).'<NameOnCard/>
                                </Card>
                            </Tender>
                        </PayData>
                    </Authorization>
                </Transaction>
                </Transactions>';          
    
    $pfpXml.='</RequestData>
                <RequestAuth>
                    <UserPass>
                        <User>'.PFP_USER.'</User>
                        <Password>'.PFP_PASSWORD.'</Password>
                    </UserPass>
                </RequestAuth>
            </XMLPayRequest>';
    
    $remote=curl_init();
    $headers = array(
            "Content-Length: ".strlen($pfpXml),
            "Content-Type: text/xml",
            "Host: ".PFP_HOST",
            "X-VPS-REQUEST-ID: ".time(),
            "X-VPS-CLIENTTIMEOUT: 45"); 
       
    curl_setopt($remote, CURLOPT_HTTPHEADER, $headers);    
    curl_setopt($remote, CURLOPT_URL,'https://'.PFP_HOST.'/transaction');
	curl_setopt($remote, CURLOPT_VERBOSE, 1);

	//turning off the server and peer verification(TrustManager Concept).
	curl_setopt($remote, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($remote, CURLOPT_SSL_VERIFYHOST, FALSE);

    curl_setopt($remote, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($remote, CURLOPT_POST, 1);
    curl_setopt($remote, CURLOPT_FORBID_REUSE, TRUE);


	curl_setopt($remote,CURLOPT_POSTFIELDS,$pfpXml);

	//getting response from server
	echo $pfpXml.'<br/>';
	$response = curl_exec($remote);
	
	echo $response;
	echo curl_getinfo($remote);
*/
}

?>