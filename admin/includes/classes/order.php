<?php
/*
  $Id: order.php,v 1.7 2003/06/20 16:23:08 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  class order {
    var $info, $totals, $products, $customer, $delivery;

    function order($order_id) {
      $this->info = array();
      $this->totals = array();
      $this->products = array();
      $this->customer = array();
      $this->delivery = array();

      $this->query($order_id);
    }

    function query($order_id) {

// begin cvv contribution        

      $order_query = tep_db_query("select *, ot.value as ot_total, case when pnref is null then '0' else '1' end as paid
                                    from " . TABLE_ORDERS . " o 
                                    join orders_total ot on ot.orders_id=o.orders_id and ot.class='ot_total' 
                                    left outer join orders_pnref op on op.orders_id=o.orders_id
                                    where o.orders_id = '" . (int)$order_id . "'");

      $order = tep_db_fetch_array($order_query);

// end cvv contribution
      $totals_query = tep_db_query("select * from " . TABLE_ORDERS_TOTAL . " where orders_id = '" . (int)$order_id . "' order by sort_order");
      while ($totals = tep_db_fetch_array($totals_query)) {
      if($totals['class']=='ot_shipping'){

                $freightamt=$totals['value'];}
        $this->totals[] = array(
	  'title' => $totals['title'], 
	  'text' => $totals['text'], 
	  'class' => $totals['class'], 
	  'value' => $totals['value'],
	  'sort_order' => $totals['sort_order'], 
	  'orders_total_id' => $totals['orders_total_id']);
      }

$this->info = array('currency' => $order['currency'],
                          'currency_value' => $order['currency_value'],
                          'payment_method' => $order['payment_method'],
                          'cc_type' => $order['cc_type'],
                          'cc_owner' => $order['cc_owner'],
                          'cc_number' => $order['cc_number'],
                          'cc_expires' => $order['cc_expires'],
						  'cvvnumber' => $order['cvvnumber'],
						  'shipping_tax' => $order['shipping_tax'],
                          'date_purchased' => $order['date_purchased'],
                          'orders_status' => $order['orders_status'],
                          'last_modified' => $order['last_modified'],
                          'orders_id' => $order_id,
                          'paid' => $order['paid'],
                          'ot_total' => number_format($order['ot_total'],2),
                          'ot_shipping'=>$freightamt);

      $this->customer = array('id' => $order['customers_id'],
      							'name' => $order['customers_name'],
                              'company' => $order['customers_company'],
                              'street_address' => $order['customers_street_address'],
                              'suburb' => $order['customers_suburb'],
                              'city' => $order['customers_city'],
                              'postcode' => $order['customers_postcode'],
                              'state' => $order['customers_state'],
                              'country' => $order['customers_country'],
                              'format_id' => $order['customers_address_format_id'],
                              'telephone' => $order['customers_telephone'],
                              'email_address' => $order['customers_email_address']);

      $this->delivery = array('name' => $order['delivery_name'],
                              'company' => $order['delivery_company'],
                              'street_address' => $order['delivery_street_address'],
                              'suburb' => $order['delivery_suburb'],
                              'city' => $order['delivery_city'],
                              'postcode' => $order['delivery_postcode'],
                              'state' => $order['delivery_state'],
                              'country' => $order['delivery_country'],
                              'format_id' => $order['delivery_address_format_id']);

      $this->billing = array('name' => $order['billing_name'],
                             'company' => $order['billing_company'],
                             'street_address' => $order['billing_street_address'],
                             'suburb' => $order['billing_suburb'],
                             'city' => $order['billing_city'],
                             'postcode' => $order['billing_postcode'],
                             'state' => $order['billing_state'],
                             'country' => $order['billing_country'],
                             'format_id' => $order['billing_address_format_id']);

      $countryid = tep_get_country_id($this->delivery["country"]);
  $zoneid = tep_get_zone_id($countryid, $this->delivery["state"]);
	
 $index = 0;
     $orders_products_query = tep_db_query("
   SELECT 
          (SELECT location from products_location where products_id=op.products_id order by time_created desc limit 0,1) as location,
	 op.orders_products_id, op.products_id,
   m.manufacturers_name,
	 op.products_name, 
	 op.products_model, 
	 op.products_price,
	 op.products_tax, 
	 op.products_quantity, 
	 op.final_price,
	 p.products_tax_class_id,
	 p.products_weight,
	 p.products_available
  FROM " . TABLE_ORDERS_PRODUCTS . " op
  LEFT JOIN " . TABLE_PRODUCTS . " p 
  ON op.products_id = p.products_id  join manufacturers m on m.manufacturers_id=p.manufacturers_id
  WHERE orders_id = '" . (int)$order_id . "'");
 
       while ($orders_products = tep_db_fetch_array($orders_products_query)) {
            
         $this->products[$index] = array('qty' => $orders_products['products_quantity'],
                                         'location' => $orders_products['location'],
                                         'name' => $orders_products['products_name'],
                                         'model' => $orders_products['products_model'],
                                         'manufacturer' => $orders_products['manufacturers_name'],
                                         'tax' => $orders_products['products_tax'],
        'tax_description' => tep_get_tax_description($orders_products['products_tax_class_id'], $countryid, $zoneid),
                                         'price' => $orders_products['products_price'],
                                         'final_price' => $orders_products['final_price'],
										 'weight' => $orders_products['products_weight'],
										 'products_id' => $orders_products['products_id'],
										 'qty_avail' => $orders_products['products_available'],
                                   'orders_products_id' => $orders_products['orders_products_id']);
 
        $subindex = 0;
        $attributes_query = tep_db_query("select * from " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . " where orders_id = '" . (int)$order_id . "' and orders_products_id = '" . (int)$orders_products['orders_products_id'] . "'");
        if (tep_db_num_rows($attributes_query)) {
          while ($attributes = tep_db_fetch_array($attributes_query)) {
            $this->products[$index]['attributes'][$subindex] = 
			array('option' => $attributes['products_options'],
                  'value' => $attributes['products_options_values'],
                  'prefix' => $attributes['price_prefix'],
                 'price' => $attributes['options_values_price'],
		  'orders_products_attributes_id' => $attributes['orders_products_attributes_id']);

            $subindex++;
          }
        }
        $index++;
      }
    }
    
    function deductInventory()
    {
        foreach($this->products as $item)
        {
            tep_db_query('update products set products_available=products_available-'.$item['qty'].' where products_id='. $item['products_id']);
        }
    
    }
    function restockInventory()
    {
        foreach($this->products as $item)
        {
            tep_db_query('update products set products_available=products_available+'.$item['qty'].' where products_id='. $item['products_id']);
        }
    }
  }
?>
