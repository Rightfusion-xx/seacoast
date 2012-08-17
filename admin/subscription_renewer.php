<?php

 $system_login=true;
  set_time_limit(1200);
  $_SERVER['DOCUMENT_ROOT']='..';
  require('includes/application_top.php');

  require(DIR_WS_CLASSES . 'currencies.php');
  require(DIR_WS_CLASSES . 'authnet.php');
  include(DIR_WS_CLASSES . 'order.php');
  
  $subscription_query=tep_db_query('select *, zone_code as entry_state, countries_iso_code_2 as entry_country from customers_info ci join customers c on c.customers_id=ci.customers_info_id join address_book ab on ab.address_book_id=c.customers_default_address_id left outer join zones z on ab.entry_zone_id=z.zone_id join countries zc on zc.countries_id=ab.entry_country_id where cm_expiration<=curdate() and cm_renew=1');
  $num_subscriptions_found=0;
  $num_subscriptions_renewed=0;

//Cycle through subscriptions and renew them, if found.
  while($subscriptions=tep_db_fetch_array($subscription_query))
  {
	echo '.';
ob_flush();
flush();
  	$num_subscriptions_found+=1;
//Okay, create the new order
  
  $yearoffset=0;
  $expiryyear=substr($subscriptions['cc_expires'],2,2);

  if(date('y')==$expiryyear && date('m')>substr($subscriptions['cc_expires'],0,2))
  {
    $yearoffset=1;
  }
  elseif(date('y')>$expiryyear)
  {
    $yearoffset=ceil((date('y')-$expiryyear)/3);
  }
  
  $expiryyear='0' . strval($expiryyear+($yearoffset*3));

  $sql_data_array = array('customers_id' => $subscriptions['customers_id'],
                          'customers_name' => $subscriptions['customers_firstname'] . ' ' . $subscriptions['customers_lastname'],
                          'customers_company' => $subscriptions['entry_company'],
                          'customers_street_address' => $subscriptions['entry_street_address'],
                          'customers_city' => $subscriptions['entry_city'],
                          'customers_postcode' => $subscriptions['entry_postcode'], 
                          'customers_state' => $subscriptions['entry_state'], 
                          'customers_country' => $subscriptions['entry_country'], 
                          'customers_telephone' => $subscriptions['customers_telephone'], 
                          'customers_email_address' => $subscriptions['customers_email_address'],
                          'customers_address_format_id' => '2', 
                          'delivery_name' => $subscriptions['entry_firstname'] . ' ' . $subscriptions['entry_lastname'], 
                          'delivery_company' => $subscriptions['entry_company'],
                          'delivery_street_address' => $subscriptions['entry_street_address'], 
                          'delivery_suburb' => $subscriptions['entry_suburb'], 
                          'delivery_city' => $subscriptions['entry_city'], 
                          'delivery_postcode' => $subscriptions['entry_postcode'], 
                          'delivery_state' => $subscriptions['entry_state'], 
                          'delivery_country' => $subscriptions['entry_country'], 
                          'delivery_address_format_id' => '2', 
                          'billing_name' => $subscriptions['entry_firstname'] . ' ' . $subscriptions['entry_lastname'], 
                          'billing_company' => $subscriptions['entry_company'],
                          'billing_street_address' => $subscriptions['entry_street_address'], 
                          'billing_suburb' => $subscriptions['entry_suburb'], 
                          'billing_city' => $subscriptions['entry_city'], 
                          'billing_postcode' => $subscriptions['entry_postcode'], 
                          'billing_state' => $subscriptions['entry_state'], 
                          'billing_country' => $subscriptions['entry_country'], 
                          'billing_address_format_id' => '2', 
                          'payment_method' => $subscriptions['payment_method'], 

                          'cc_type' => $subscriptions['cc_type'], 
                          'cc_owner' => $subscriptions['cc_owner'], 
                          'cc_number' => $subscriptions['cc_number'],
                          'cc_expires' => substr($subscriptions['cc_expires'],0,2). substr($expiryyear,-2,2),
                          'cvvnumber' => ($yearoffset>0) ? '' : $subscriptions['cvv_number'],

                          'date_purchased' => 'now()', 
                          'orders_status' => '8', 
                          'currency' => 'USD', 
                          'currency_value' => '1.0');

  tep_db_perform(TABLE_ORDERS, $sql_data_array);
  $insert_id = tep_db_insert_id();
  
  
    $sql_data_array = array('orders_id' => $insert_id,
                            'title' => 'Membership Total',
                            'text' => '$'.$subscriptions['cm_price'].'',
                            'value' => number_format($subscriptions['cm_price'],2), 
                            'class' => 'ot_total', 
                            'sort_order' => '1');
    tep_db_perform(TABLE_ORDERS_TOTAL, $sql_data_array);


  $sql_data_array = array('orders_id' => $insert_id, 
                          'orders_status_id' => '8', 
                          'date_added' => 'now()', 
                          'customer_notified' => '0',
                          'comments' => 'Seacoast Vitamins-Direct Membership Renewal');
  tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);


// Update products_ordered (for bestsellers list)
    tep_db_query("update " . TABLE_PRODUCTS . " set products_ordered = products_ordered + " . sprintf('%d', '1') . ", products_dieqty = products_dieqty - " . sprintf('%d', '1') . " where products_id = '" . CM_PID . "'");

    $sql_data_array = array('orders_id' => $insert_id, 
                            'products_id' => CM_PID, 
                            'products_model' => '', 
                            'products_name' => 'Seacoast Vitamins-Direct Full Subscription',
                            'products_price' => ''.$subscriptions['cm_price'].'', 
                            'products_savings' => '0',
                            'final_price' => ''.$subscriptions['cm_price'].'', 
                            'products_tax' => '0', 
                            'products_quantity' => 1);
    tep_db_perform(TABLE_ORDERS_PRODUCTS, $sql_data_array);
    $order_products_id = tep_db_insert_id();
    
    
//Run payment through
	$order=new order($insert_id);
	$order->is_subscription=true;
	$order->info['description']='Subscription Renewal';
        $pay_result=runPayment($order);
        
        if($pay_result[0]<>'1' && $pay_result[38]<>'M' && strlen($order->info['cvvnumber'])>1)
        {
          $order->info['cvvnumber']='';
          $pay_result=runPayment($order);
        }


        if($pay_result[0]=='1')
        { //succesfull

            //Update status
                    tep_db_query("UPDATE " . TABLE_ORDERS . " SET
					  orders_status = '9', 
                      last_modified = now()
                      WHERE orders_id = '" . (int)$insert_id . "'");
		
			  
          		
			    tep_db_query("INSERT into " . TABLE_ORDERS_STATUS_HISTORY . " 
			    (orders_id, orders_status_id, date_added, customer_notified, comments) 
			    values ('" . $insert_id . "', 
				    '9', 
				    now(), 
				    0, 
				    'Paid - " . tep_db_input(number_format($subscriptions['cm_price']))  . "')");
			    
//Update customer with full subscription
   			tep_db_query('update customers_info set cm_expiration=date_add(cm_expiration, interval 1 year) where customers_info_id='.(int)$subscriptions['customers_id']);
			$num_subscriptions_renewed+=1;    
        }
        else
        {
//Payment failed. Put it on hold            
   			tep_db_query('update customers_info set cm_renew=0 where customers_info_id='.(int)$subscriptions['customers_id']);
        }

      

  	
  	
  }
//Log findings
	syslog(LOG_INFO,'Subscriptions needing renewal: ' . $num_subscriptions_found);
	syslog(LOG_INFO,'Subscriptions successfully renewed: ' . $num_subscriptions_renewed);
	syslog(LOG_INFO,'Subscription payments declined: ' . $num_subscriptions_found-$num_subscriptions_renewed);	
?>