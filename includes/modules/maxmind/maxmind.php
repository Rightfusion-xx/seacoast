<?php
/*
  $Id: maxmind.php,v 1.5 2004/09/09 22:50:51 hpdl Exp $
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2004 osCommerce
  Released under the GNU General Public License
  Noel Latsha, www.nabcomdiamonds.com/www.devosc.com

  2004/07/18 - amended for cc.php by Stuart Owens
  with assistance from Acheron and stevel
  Tested on osc2.2 (Dec 2002 version)
*/

// If you have a liscense key, enter it here and uncomment the line
$h["license_key"] = "xkI2TBBJow01";

// *************************************DO NOT MODIFY BELOW THIS LINE (Unless you know what you are doing **********************************	   

$check_country_query = tep_db_query("select countries_iso_code_2 from " . TABLE_COUNTRIES . " where countries_name = '" . $order->billing['country']['title'] . "'");
$check_country = tep_db_fetch_array($check_country_query);
		
$check_state_query = tep_db_query("select zone_code from " . TABLE_ZONES . " where zone_name = '" . $order->billing['state'] . "'");
$check_state = tep_db_fetch_array($check_state_query);

require(DIR_WS_MODULES . 'maxmind/CreditCardFraudDetection.php');
$ccfs = new CreditCardFraudDetection;

//Modify a few variables to match what MaxMind is expecting.
$string = $order->info['cc_number'];
$cc = substr($string, 0, 6); 

$str = $order->customer['email_address'];
list ($addy, $domain) = split ('[@]', $str);

$phstr = preg_replace( '/[^0123456789]/', '', $order->customer['telephone']);
$phone = substr($phstr, 0, 6);

//next we set inputs and store them in a hash
$h["i"] = $_SERVER['REMOTE_ADDR'];      								 // set the client ip address
$h["domain"] = $domain;      							     // set the Email domain 
$h["city"] = $order->billing['city'];      				     // set the billing city
$h["region"] = $check_state['zone_code'];      				 // set the billing state
$h["postal"] = $order->billing['postcode'];     			 // set the billing zip code
$h["country"] = $check_country['countries_iso_code_2'];      // set the billing country
$h["bin"] = $cc;      										 // set bank identification number
$h["custPhone"] = $phone;     								 //set customer phone number

// If you have cURL and an SSL connection available, leave the next line uncommented
// Otherwise comment it out by adding "//" in front of it.
$ccfs->isSecure = 0;

$ccfs->input($h);
$ccfs->query();
$h = $ccfs->output();
$outputkeys = array_keys($h);
$sql_data_array = array(                       
                         'order_id' => $insert_id,
                         'score' => $h['score'],
                                     'distance' => $h['distance'],
                                     'country_match' => $h['countryMatch'],
                                     'country_code' => $h['countryCode'],
                                     'free_mail' => $h['freeMail'],
                                     'anonymous_proxy' => $h['anonymousProxy'],
                                     'proxy_score' => $h['proxyScore'],
                                     'spam_score' => $h['spamScore'],
                                     'bin_match' => $h['binMatch'],
                                     'bin_country' => $h['binCountry'],
                                     'bin_name' => $h['binName'],
                                     'err' => $h['err'],
                                     'ip_isp' => $h['ip_isp'],
                                     'ip_org' => $h['ip_org'],
                                     'hi_risk' => $h['highRiskCountry'],
                                     'cust_phone' => $h['custPhoneInBillingLoc'],
                                     'ip_city' => $h['ip_city'],
                                     'ip_region' => $h['ip_region'],
                                     'ip_latitude' => $h['ip_latitude'],
                                     'ip_longitude' => $h['ip_longitude']);

tep_db_perform(TABLE_ORDERS_MAXMIND, $sql_data_array);
?>