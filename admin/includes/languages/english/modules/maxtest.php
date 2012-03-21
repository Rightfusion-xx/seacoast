<?php
/*
  $Id: testmax.php,v 1.43 2004/05/21 22:50:51 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License
  
  Noel Latsha, www.nabcomdiamonds.com/www.devosc.com
*/

// A couple of countries have to ruin it for everyone...

// Testing area - since this is not a live transaction *****************************************************

require('includes/application_top.php');

// Set this to an order number from the database that paid with a CC
$oID = XX; 
        
//Enter your license key here
//$h["license_key"] = "xkI2TBBJow01";


// Set the first 6 CC numbers here for testing
$cc = XXXXXX;

		$check_status_query = tep_db_query("select customers_name, customers_street_address, customers_city, customers_postcode, customers_state, customers_country, customers_email_address, customers_telephone from " . TABLE_ORDERS . " where orders_id = '" . (int)$oID . "'");
       	$check_status = tep_db_fetch_array($check_status_query);
		
// End Testing Area *******************************************************************************
		
		
// *************************************DO NOT MODIFY BELOW THIS LINE (Unless you know what you are doing **********************************	   
$check_country_query = tep_db_query("select countries_iso_code_2 from " . TABLE_COUNTRIES . " where countries_name = '" . $check_status['customers_country'] . "'");
$check_country = tep_db_fetch_array($check_country_query);
		
$check_state_query = tep_db_query("select zone_code from " . TABLE_ZONES . " where zone_name = '" . $check_status['customers_state'] . "'");
$check_state = tep_db_fetch_array($check_state_query);

require(DIR_WS_MODULES . 'maxmind/CreditCardFraudDetection.php');
$ccfs = new CreditCardFraudDetection;

$str = $check_status['customers_email_address'];
list ($addy, $domain) = split ('[@]', $str);

$phstr = preg_replace( '/[^0123456789]/', '', $check_status['customers_telephone']);
$phone = substr($phstr, 0, 6);

//next we set inputs and store them in a hash
$h["i"] = $REMOTE_ADDR;   									// set the client ip address
$h["domain"] = $domain; 									// set the Email domain 
$h["city"] = $check_status['customers_city'];    			// set the billing city
$h["region"] = $check_state['zone_code'];       			// set the billing state
$h["postal"] = $check_status['customers_postcode'];    		// set the billing zip code
$h["country"] = $check_country['countries_iso_code_2'];  	// set the billing country
$h["bin"] = $cc;       										// set bank identification number
$h["custPhone"] = $phone;		// Area-code and local prefix of customer phone number

// If you have cURL and an SSL connection available, leave the next line uncommented
// Otherwise comment it our by adding "//" in front of it.
// $ccfs->isSecure = 0;

//set the time out to be five seconds
$ccfs->timeout = 5;

//uncomment to turn on debugging
// $ccfs->debug = 1;

$ccfs->input($h);
$ccfs->query();
$h = $ccfs->output();
$outputkeys = array_keys($h);
$sql_data_array = array( 'order_id' => $oID,
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
	
print_r($sql_data_array);
echo '<br><br>';
	
$numoutputkeys = count($h);
for ($i = 0; $i < $numoutputkeys; $i++) {
$key = $outputkeys[$i];
$value = $h[$key];
print $key . " = " . $value . "\n";
echo '<br>';
}
?>