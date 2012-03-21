<?php
$system_login=true;
  set_time_limit(1200);
  $_SERVER['DOCUMENT_ROOT']='..';
  require('includes/application_top.php');
  
  //delete zero transaction records.
  tep_db_query('delete from stamps_postback where length(transaction_id)<1 or tracking_number is null');

  $new_shipments_q=tep_db_query('select sp.*, o.date_purchased, o.customers_name, o.customers_email_address from stamps_postback sp 
                                        join orders o on o.orders_id=sp.orders_id order by date_created asc');
  
  while($shipment=tep_db_fetch_array($new_shipments_q))
  {
    $order_q=tep_db_query('select ant.results, ot.value as total, o.payment_method from authnet_transactions ant join orders_total ot on ot.orders_id=ant.orders_id and ot.class="ot_total"
                                  join orders o on o.orders_id=ant.orders_id where ant.orders_id=' . (int)$shipment['orders_id'] );

    $order_details['Invoice Amt']=number_format($shipment['total'],2);
    $order_details['Amt Charged']=0;

    while($order=tep_db_fetch_array($order_q))
    {

     $tran=unserialize(gzuncompress($order['results']));
      if($tran[0]=='1')
      {
        //take in to account transaction because the transaction was a success
        if($tran[11]=='auth_capture')
        {
          $order_details['Amt Charged']+=$tran[9];
        }
        else
        {
          $order_details['Amt Charged']-=$tran[9];
        }
     
      }
      
    }
    
    if($shipment['payment_method']=='Credit Card')
    {
      if($shipment['total']-$order_details['Amt Charged']>1)
      {
        //Set to backorder
      }
      else
      {
        //Set to shipped
      }
    }

    //Set to shipped regardless, for right now.
    $comments='Your order has shipped! Tracking #: '.$shipment['tracking_number'].'';
    $notify_comments = sprintf(EMAIL_TEXT_COMMENTS_UPDATE, $comments) . "\n\n";

    $email='';
    $email .= STORE_NAME . "\n" . EMAIL_SEPARATOR . "\n" . EMAIL_TEXT_ORDER_NUMBER . ' ' . $shipment['orders_id'] . "\n" . EMAIL_TEXT_INVOICE_URL . ' ' . tep_catalog_href_link(FILENAME_CATALOG_ACCOUNT_HISTORY_INFO, 'order_id=' . $shipment['orders_id'], 'SSL') . "\n" . EMAIL_TEXT_DATE_ORDERED . ' ' . tep_date_long($shipment['date_purchased']) . "\n\n" . $notify_comments . sprintf(EMAIL_TEXT_STATUS_UPDATE, $orders_status_array[$status]);
    //echo $email; //exit();//Break!
    tep_mail($shipment['customers_name'], $shipment['customers_email_address'], EMAIL_TEXT_SUBJECT, $email, STORE_NAME, STORE_OWNER_EMAIL_ADDRESS);

    tep_db_query("update orders set orders_status = '3', last_modified = now() where orders_id = '" . (int)$shipment['orders_id'] . "'");
    tep_db_query("insert into orders_status_history (orders_id, orders_status_id, date_added, customer_notified, comments) values ('" . (int)$shipment['orders_id'] . "', '3', now(), '1', '" . tep_db_input($comments)  . "')");

    tep_db_query('insert into orders_shipping_info(orders_id,tracking_number,transaction_id,postage_amount,insured_value,insurance_fee,weight_oz,date_created) values ("'.(int)$shipment['orders_id'].'","'.tep_db_input($shipment['tracking_number']) .'","'.tep_db_input($shipment['transaction_id']).'","'.tep_db_input($shipment['postage_amount']).'","'.tep_db_input((float)$shipment['insured_value']).'","'.tep_db_input((float)$shipment['insurance_fee']).'","'.tep_db_input((float)$shipment['weight_oz']).'","'.tep_db_input($shipment['date_created']).'")');
    tep_db_query('delete from stamps_postback where transaction_id="'.  tep_db_input($shipment['transaction_id']) .'"');
    //exit();
  }
?>