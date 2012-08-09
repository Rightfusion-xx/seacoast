<?php
/*
$Id: create+account3.php,v 2.00 2004/01/05 23:28:24 hpdl Exp $
osCommerce, Open Source E-Commerce Solutions
http://www.oscommerce.com
Copyright (c) 2003 osCommerce
Released under the GNU General Public License
*/
$show_account_box = 1;
//0 no create account box
//1 javascript create account box
//2 normal create account box
$create_password =0;// set to 1 to create an account with random password
$show_login=2;
//0 no create login box
//1 javascript login account box
//2 normal create login box
////////////////////////////////////////////////
////////////////////////////////////////////////
require('includes/application_top.php');
require(DIR_WS_LANGUAGES . $language . '/' . 'create_account.php');
require(DIR_WS_LANGUAGES . $language . '/' . 'fast_account.php');
require(DIR_WS_LANGUAGES . $language . '/' . 'login.php');
// if we have been here before and are coming back get rid of the credit covers variable
if(tep_session_is_registered('credit_covers')) tep_session_unregister('credit_covers'); //rmh M-S_ccgv
//line 21-23 sends the customer ti index.php if he is logedin
if ((tep_session_is_registered('customer_id'))&&(tep_session_is_registered('createaccount'))) { tep_redirect(tep_href_link('account_password_new.php', '', 'SSL'));
}
if (tep_session_is_registered('customer_id')) {
	tep_redirect(tep_href_link(FILENAME_DEFAULT, '', 'SSL'));
}
// redirect the customer to a friendly cookie-must-be-enabled page if cookies are disabled (or the session has not started)
if ($session_started == false) {
	tep_redirect(tep_href_link(FILENAME_COOKIE_USAGE));
}
$error = false;
if (isset($HTTP_POST_VARS['action']) && ($HTTP_POST_VARS['action'] == 'process')) {
$fromlogin=tep_db_prepare_input($HTTP_POST_VARS['fromlogin']);
	if (ACCOUNT_GENDER == 'true') {
		if (isset($HTTP_POST_VARS['gender'])) {
			$gender = tep_db_prepare_input($HTTP_POST_VARS['gender']);
			} else {
			$gender = false;
		}
	}
	//START REGISTRATION CODE
	$createaccount='N';
	//next two lines gives you a temporary fixed password you can change to what you like
	//start type one create assount
	if ($create_password == 1) {
		$createaccount = tep_db_prepare_input($HTTP_POST_VARS['createmyaccount']);
		if ($createaccount!='Y')$createaccount='N';
		$password = tep_create_random_value(15);
		$confirmation = $password;
	}
	//start type two create account
	if ($show_account_box >= 1) {
		$createaccount = tep_db_prepare_input($HTTP_POST_VARS['createaccount']);
		if ($createaccount!='Y')$createaccount='N';
		if ($createaccount=='Y') {
			$password = tep_db_prepare_input($HTTP_POST_VARS['password']);
			$confirmation = tep_db_prepare_input($HTTP_POST_VARS['confirmation']);
		}
	}
if ($fromlogin == 1) {
		$createaccount = 'Y';
			$password = tep_db_prepare_input($HTTP_POST_VARS['password']);
			$confirmation = tep_db_prepare_input($HTTP_POST_VARS['confirmation']);
	}
	$firstname = tep_db_prepare_input($HTTP_POST_VARS['firstname']);
	$lastname = tep_db_prepare_input($HTTP_POST_VARS['lastname']);
	if (ACCOUNT_DOB == 'true') $dob = tep_db_prepare_input($HTTP_POST_VARS['dob']);
	$email_address = tep_db_prepare_input($HTTP_POST_VARS['email']);
	if (ACCOUNT_COMPANY == 'true') $company = tep_db_prepare_input($HTTP_POST_VARS['company']);
	$street_address = tep_db_prepare_input($HTTP_POST_VARS['street_address']);
	if (ACCOUNT_SUBURB == 'true') $suburb = tep_db_prepare_input($HTTP_POST_VARS['suburb']);
	$postcode = tep_db_prepare_input($HTTP_POST_VARS['postcode']);
	$city = tep_db_prepare_input($HTTP_POST_VARS['City']);
	if (ACCOUNT_STATE == 'true') {
		$state = tep_db_prepare_input($HTTP_POST_VARS['state']);
		if (isset($HTTP_POST_VARS['zone_id'])) {
			$zone_id = tep_db_prepare_input($HTTP_POST_VARS['zone_id']);
			} else {
			$zone_id = false;
		}
	}
	$country = tep_db_prepare_input($HTTP_POST_VARS['country']);
	$telephone = tep_db_prepare_input($HTTP_POST_VARS['telephone']);
	$fax = tep_db_prepare_input($HTTP_POST_VARS['fax']);
	if (isset($HTTP_POST_VARS['newsletter'])) {
		$newsletter = tep_db_prepare_input($HTTP_POST_VARS['newsletter']);
		} else {
		$newsletter = false;
	}
	//rmh M-S_referral begin
	$source = tep_db_prepare_input($HTTP_POST_VARS['source']);
	if (isset($HTTP_POST_VARS['source_other'])) $source_other = tep_db_prepare_input($HTTP_POST_VARS['source_other']);
	//rmh M-S_referral end
	if (tep_not_null($HTTP_POST_VARS['ShipAddress'])) {
		$process = true;
		$shipping_firstname = tep_db_prepare_input($HTTP_POST_VARS['ShipFirstName']);
		$shipping_lastname = tep_db_prepare_input($HTTP_POST_VARS['ShipLastName']);
		$shipping_street_address = tep_db_prepare_input($HTTP_POST_VARS['ShipAddress']);
		if (ACCOUNT_SUBURB == 'true') $shipping_suburb = tep_db_prepare_input($HTTP_POST_VARS['shipsuburb']);
		$shipping_postcode = tep_db_prepare_input($HTTP_POST_VARS['shippostcode']);
		$shipping_city = tep_db_prepare_input($HTTP_POST_VARS['ShipCity']);
		$shipping_company = tep_db_prepare_input($HTTP_POST_VARS['shipcompany']);
		$shipping_country = tep_db_prepare_input($HTTP_POST_VARS['shipcountry']);
		if (ACCOUNT_STATE == 'true') {
			if (isset($HTTP_POST_VARS['zone_id'])) {
				$shipping_zone_id = tep_db_prepare_input($HTTP_POST_VARS['shipping_zone_id']);
				} else {
				$shipping_zone_id = false;
			}
			$shipping_state = tep_db_prepare_input($HTTP_POST_VARS['shippingstate']);
		}
	}
	$error = false;
	if (ACCOUNT_GENDER == 'true') {
		if ( ($gender != 'm') && ($gender != 'f') ) {
			$error = true;
			$messageStack->add('create_account', ENTRY_GENDER_ERROR);
		}
	}
	if (strlen($firstname) < ENTRY_FIRST_NAME_MIN_LENGTH) {
		$error = true;
		$messageStack->add('create_account', ENTRY_FIRST_NAME_ERROR);
	}
	if (strlen($lastname) < ENTRY_LAST_NAME_MIN_LENGTH) {
		$error = true;
		$messageStack->add('create_account', ENTRY_LAST_NAME_ERROR);
	}
	//rmh M-S_addr-enhancer begin
	if (ACCOUNT_DOB == 'true' && REQUIRE_DOB == 'true') {
		if (checkdate(substr(tep_date_raw($dob), 4, 2), substr(tep_date_raw($dob), 6, 2), substr(tep_date_raw($dob), 0, 4)) == false) {
			$error = true;
			$messageStack->add('create_account', ENTRY_DATE_OF_BIRTH_ERROR);
		}
	}
	if (strlen($email_address) < ENTRY_EMAIL_ADDRESS_MIN_LENGTH) {
		$error = true;
		$messageStack->add('create_account', ENTRY_EMAIL_ADDRESS_ERROR);
		} elseif (tep_validate_email($email_address) == false) {
		$error = true;
		$messageStack->add('create_account', ENTRY_EMAIL_ADDRESS_CHECK_ERROR);
		} else {
		$check_email_query = tep_db_query("select count(*) as total from " . TABLE_CUSTOMERS . " where customers_email_address = '" . tep_db_input($email_address) . "'");
		$check_email = tep_db_fetch_array($check_email_query);
		/* if ($check_email['total'] > 0) {
			$error = true;
			$messageStack->add('create_account', ENTRY_EMAIL_ADDRESS_ERROR_EXISTS);
		}*/
		if ($check_email['total'] > 0)
		{ //PWA delete account
			$get_customer_info = tep_db_query("select customers_id, customers_email_address, createaccount from " . TABLE_CUSTOMERS . " where customers_email_address = '" . tep_db_input($email_address) . "'");
			$customer_info = tep_db_fetch_array($get_customer_info);
			$customer_id = $customer_info['customers_id'];
			$customer_email_address = $customer_info['customers_email_address'];
			$customer_pwa = $customer_info['createaccount'];
			if ($customer_pwa =='Y')
			{
				$error = true;
				$messageStack->add('create_account', ENTRY_EMAIL_ADDRESS_ERROR_EXISTS);
				} else {
				// here i am adding another line just incase they change to an account immediatly i do not know if it is necessary or not
				if (tep_session_is_registered('registered_now')) tep_session_unregister('registered_now');
				if (tep_session_is_registered('createaccount')) tep_session_unregister('createaccount');
				tep_db_query("delete from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . $customer_id . "'");
				tep_db_query("delete from " . TABLE_CUSTOMERS . " where customers_id = '" . $customer_id . "'");
				tep_db_query("delete from " . TABLE_CUSTOMERS_INFO . " where customers_info_id = '" . $customer_id . "'");
				tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET . " where customers_id = '" . $customer_id . "'");
				tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " where customers_id = '" . $customer_id . "'");
				tep_db_query("delete from " . TABLE_WHOS_ONLINE . " where customer_id = '" . $customer_id . "'");
			}
		}
		// END
	}
	if ($createaccount == 'Y') {
		if (strlen($password) < ENTRY_PASSWORD_MIN_LENGTH) {
			$error = true;
			$messageStack->add('create_account', ENTRY_PASSWORD_ERROR);
			} elseif ($password != $confirmation) {
			$error = true;
			$messageStack->add('create_account', ENTRY_PASSWORD_ERROR_NOT_MATCHING);
		}
	}
	if (strlen($street_address) < ENTRY_STREET_ADDRESS_MIN_LENGTH) {
		$error = true;
		$messageStack->add('create_account', ENTRY_STREET_ADDRESS_ERROR);
	}
	if (strlen($street_address) < ENTRY_STREET_ADDRESS_MIN_LENGTH) {
		$error = true;
		$messageStack->add('create_account', ENTRY_STREET_ADDRESS_ERROR);
	}
	if (strlen($postcode) < ENTRY_POSTCODE_MIN_LENGTH) {
		$error = true;
		$messageStack->add('create_account', ENTRY_POST_CODE_ERROR);
	}
	if (strlen($city) < ENTRY_CITY_MIN_LENGTH) {
		$error = true;
		$messageStack->add('create_account', ENTRY_CITY_ERROR);
	}
	//rmh M-S_referral begin
	/* if ((tep_not_null(tep_get_sources())) && (REFERRAL_REQUIRED == 'true') && (is_numeric($source) == false)) {
		$error = true;
		$messageStack->add('create_account', ENTRY_SOURCE_ERROR);
	}
	if ((REFERRAL_REQUIRED == 'true') && (DISPLAY_REFERRAL_OTHER == 'true') &&($source == '9999') && (!tep_not_null($source_other)) ) {
		$error = true;
		$messageStack->add('create_account', ENTRY_SOURCE_OTHER_ERROR);
	}*/
	//rmh M-S_referral end
	if (is_numeric($country) == false) {
		$error = true;
		$messageStack->add('create_account', ENTRY_COUNTRY_ERROR);
	}
	if (ACCOUNT_STATE == 'true') {
		$zone_id = 0;
		$check_query = tep_db_query("select count(*) as total from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country . "'");
		$check = tep_db_fetch_array($check_query);
		$entry_state_has_zones = ($check['total'] > 0);
		if ($entry_state_has_zones == true) {
			$zone_query = tep_db_query("select distinct zone_id from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country . "' and (zone_name like '" . tep_db_input($state) . "%' or zone_code like '%" . tep_db_input($state) . "%')");
			if (tep_db_num_rows($zone_query) == 1) {
				$zone = tep_db_fetch_array($zone_query);
				$zone_id = $zone['zone_id'];
				} else {
				$error = true;
				$messageStack->add('create_account', ENTRY_STATE_ERROR_SELECT);
			}
			} else {
			if (strlen($state) < ENTRY_STATE_MIN_LENGTH) {
				$error = true;
				$messageStack->add('create_account', ENTRY_STATE_ERROR);
			}
		}
	}
	if (strlen($telephone) < ENTRY_TELEPHONE_MIN_LENGTH) {
		$error = true;
		$messageStack->add('create_account', ENTRY_TELEPHONE_NUMBER_ERROR);
	}
	if (tep_not_null($HTTP_POST_VARS['ShipAddress'])) {
		if (strlen($shipping_firstname) < ENTRY_FIRST_NAME_MIN_LENGTH) {
			$error = true;
			$messageStack->add('create_account', ENTRY_SHIPPING_FIRST_NAME_ERROR);
		}
		if (strlen($shipping_lastname) < ENTRY_LAST_NAME_MIN_LENGTH) {
			$error = true;
			$messageStack->add('create_account', ENTRY_SHIPPING_LAST_NAME_ERROR);
		}
		if (strlen($shipping_street_address) < ENTRY_STREET_ADDRESS_MIN_LENGTH) {
			$error = true;
			$messageStack->add('create_account', ENTRY_SHIPPING_STREET_ADDRESS_ERROR);
		}
		if (strlen($shipping_postcode) < ENTRY_POSTCODE_MIN_LENGTH) {
			$error = true;
			$messageStack->add('create_account', ENTRY_SHIPPING_POST_CODE_ERROR);
		}
		if (strlen($shipping_city) < ENTRY_CITY_MIN_LENGTH) {
			$error = true;
			$messageStack->add('create_account', ENTRY_SHIPPING_CITY_ERROR);
		}
		if (ACCOUNT_STATE == 'true') {
			$shipping_zone_id = 0;
			$shipping_check_query = tep_db_query("select count(*) as total from " . TABLE_ZONES . " where zone_country_id = '" . (int)$shipping_country . "'");
			$shipping_check = tep_db_fetch_array($shipping_check_query);
			$entry_state_has_zones = ($shipping_check['total'] > 0);
			if ($entry_state_has_zones == true) {
				$shipping_zone_query = tep_db_query("select distinct zone_id from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country . "' and (zone_name like '" . tep_db_input($shipping_state) . "%' or zone_code like '%" . tep_db_input($shipping_state) . "%')");
				if (tep_db_num_rows($shipping_zone_query) == 1) {
					$shipping_zone = tep_db_fetch_array($shipping_zone_query);
					$shipping_zone_id = $shipping_zone['zone_id'];
					} else {
					$error = true;
					$messageStack->add('create_account', ENTRY_SHIPPING_STATE_ERROR_SELECT);
				}
				} else {
				if (strlen($shipping_state) < ENTRY_SHIPPING_STATE_MIN_LENGTH) {
					$error = true;
					$messageStack->add('create_account', ENTRY_SHIPPING_STATE_ERROR);
				}
			}
		}
		if ( (is_numeric($shipping_country) == false) || ($shipping_country < 1) ) {
			$error = true;
			$messageStack->add('create_account', ENTRY_SHIPPING_COUNTRY_ERROR);
		}
	}
	if ($error == false) {
		if ($createaccount=='N') {
			$password = tep_create_random_value(15);
			$confirmation = $password;
		}
		if ($createaccount == 'Y') {$confirmation='completeaccount';}
		$sql_data_array = array('customers_firstname' => $firstname,
		'customers_lastname' => $lastname,
		'customers_email_address' => $email_address,
		'customers_telephone' => $telephone,
		'customers_fax' => $fax,
		'createaccount' => $createaccount,
		'customers_newsletter' => $newsletter,
		'confirmation_key' => $confirmation,
		'customers_password' => tep_encrypt_password($password));
		if (ACCOUNT_GENDER == 'true') $sql_data_array['customers_gender'] = $gender;
		if (ACCOUNT_DOB == 'true') $sql_data_array['customers_dob'] = tep_date_raw($dob);
		tep_db_perform(TABLE_CUSTOMERS, $sql_data_array);
		$customer_id = tep_db_insert_id();
		$sql_data_array = array('customers_id' => $customer_id,
		'entry_firstname' => $firstname,
		'entry_lastname' => $lastname,
		'entry_street_address' => $street_address,
		'entry_postcode' => $postcode,
		'entry_city' => $city,
		'entry_country_id' => $country);
		if (ACCOUNT_GENDER == 'true') $sql_data_array['entry_gender'] = $gender;
		if (ACCOUNT_COMPANY == 'true') $sql_data_array['entry_company'] = $company;
		if (ACCOUNT_SUBURB == 'true') $sql_data_array['entry_suburb'] = $suburb;
		if (ACCOUNT_STATE == 'true') {
			if ($zone_id > 0) {
				$sql_data_array['entry_zone_id'] = $zone_id;
				$sql_data_array['entry_state'] = '';
				} else {
				$sql_data_array['entry_zone_id'] = '0';
				$sql_data_array['entry_state'] = $state;
			}
		}
		tep_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array);
		$address_id = tep_db_insert_id();
		tep_db_query("update " . TABLE_CUSTOMERS . " set customers_default_address_id = '" . (int)$address_id . "' where customers_id = '" . (int)$customer_id . "'");
		//rmh M-S_referral begin
		//rmh M-S_multi-stores edited next line
		// tep_db_query("insert into " . TABLE_CUSTOMERS_INFO . " (customers_info_id, customers_info_date_of_last_logon, customers_info_number_of_logons, customers_info_date_account_created, customers_info_date_account_last_modified, customers_info_source_id) values ('" . (int)$customer_id . "', now(), '1', now(), now(), '". (int)$source . "')");
		/* tep_db_query("insert into " . TABLE_CUSTOMERS_INFO . " (customers_info_id, customers_info_date_of_last_logon, customers_info_number_of_logons, customers_info_date_account_created, customers_info_date_account_last_modified) values ('" . (int)$customer_id . "', now(), '1', now(), now() )");
		if ($source == '9999') {
			tep_db_query("insert into " . TABLE_SOURCES_OTHER . " (customers_id, sources_other_name) values ('" . (int)$customer_id . "', '". tep_db_input($source_other) . "')");
		}*/
		//rmh M-S_referral end
		// rmh M-S_referral comment out the next line
		tep_db_query("insert into " . TABLE_CUSTOMERS_INFO . " (customers_info_id, customers_info_number_of_logons, customers_info_date_account_created) values ('" . (int)$customer_id . "', '0', now())");
		if (SESSION_RECREATE == 'True') {
			tep_session_recreate();
		}
		$customer_first_name = $firstname;
		$customer_default_address_id = $address_id;
		$customer_country_id = $country;
		$customer_zone_id = $zone_id;
		tep_session_register('customer_id');
		tep_session_register('customer_first_name');
		tep_session_register('customer_default_address_id');
		tep_session_register('customer_country_id');
		tep_session_register('customer_zone_id');
		$registered_now=1;
		tep_session_register('registered_now');
		if ($createaccount == 'N') tep_session_register('createaccount');
		$shipping_address_query = tep_db_query("select address_book_id from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$customer_id . "'");
		$shipping_address = tep_db_fetch_array($shipping_address_query);
		$billto = $shipping_address['address_book_id'];
		$sendto = $shipping_address['address_book_id'];
		tep_session_register('billto');
		tep_session_register('sendto');
		$billto = $shipping_address['address_book_id'];
		$sendto = $shipping_address['address_book_id'];
		// restore cart contents
		$cart->restore_contents();
		//END REGISTRATION CODE
		//START DIFFERENT SHIPPING CODE
		if (tep_not_null($HTTP_POST_VARS['ShipAddress'])) {
		$sql_data_array = array('customers_id' => $customer_id,
		'entry_firstname' => $shipping_firstname,
		'entry_lastname' => $shipping_lastname,
		'entry_street_address' => $shipping_street_address,
		'entry_postcode' => $shipping_postcode,
		'entry_city' => $shipping_city,
		'entry_country_id' => $shipping_country);
		if (ACCOUNT_GENDER == 'true') $sql_data_array['entry_gender'] = $gender;
		if (ACCOUNT_COMPANY == 'true') $sql_data_array['entry_company'] = $shipping_company;
		if (ACCOUNT_SUBURB == 'true') $sql_data_array['entry_suburb'] = $shipping_suburb;
		if (ACCOUNT_STATE == 'true') {
			if ($zone_id > 0) {
				$sql_data_array['entry_zone_id'] = $shipping_zone_id;
				$sql_data_array['entry_state'] = '';
				} else {
				$sql_data_array['entry_zone_id'] = '0';
				$sql_data_array['entry_state'] = $shipping_state;
			}
		}
		tep_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array);
		$address_id = tep_db_insert_id();
		$sendto =$address_id;
		tep_session_unregister('sendto');
		tep_session_register('sendto');
		$sendto = tep_db_insert_id();
		}
		//END DIFFERENT SHIPPING CODE
		// build the message content
		$name = $firstname . ' ' . $lastname;
		if (ACCOUNT_GENDER == 'true') {
			if ($gender == 'm') {
				$email_text = sprintf(EMAIL_GREET_MR, $lastname);
				} else {
				$email_text = sprintf(EMAIL_GREET_MS, $lastname);
			}
			} else {
			$email_text = sprintf(EMAIL_GREET_NONE, $firstname);
		}
		$email_text .= EMAIL_WELCOME . EMAIL_TEXT . EMAIL_CONTACT . EMAIL_WARNING;
		if ($createaccount == 'Y')
	{if($create_password ==1){$email_text .=ASSWORD_CREATED.': '.$password;}
		tep_mail($name, $email_address, EMAIL_SUBJECT, $email_text, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);}
		/////////END EMAIL
		if ($createaccount == 'Y') {
			if ($cart->count_contents() > 0) {
				tep_redirect(tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
				} else {
				tep_redirect(tep_href_link(FILENAME_CREATE_ACCOUNT_SUCCESS, '', 'SSL'));
		}  }
		else {
			tep_redirect(tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
		}
	}
}
if ($error == true) {
	// $messageStack->add('create_account', TEXT_CREATE_ACCOUNT_ERROR);
}
$breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_CREATE_ACCOUNT, '', 'SSL'));
?>
<!doctype html>
<html <?php echo HTML_PARAMS; ?>>
<head>
    <link rel="stylesheet" href="/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/bootstrap/css/bootstrap-responsive.min.css">
    <link href="/css/main.css" rel="stylesheet">
    <link href="/font/fonts.css" rel="stylesheet">
    <!--[if lt IE 9]>
    <script type="text/javascript" src="/jquery/respond.src.js"></script>
    <![endif]-->
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
<SCRIPT src="global.js" type=text/javascript></SCRIPT>
<?php require('includes/form_check.js.php'); ?>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="3" rightmargin="3" >
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->
<!-- body //-->
<table border="0" width="100%" cellspacing="3" cellpadding="3" align="center">
<tr>
<TD WIDTH="<?php echo BOX_WIDTH; ?>" VALIGN="top" rowspan="2"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
</table></td>
<td valign="top" colspan="2" valign="top"><?php require(DIR_WS_INCLUDES . 'titlebar.php'); ?></td></tr><tr><!-- body_text //-->
<td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr>
<td><table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr>
<td class="pageHeading"><?php echo HEADING_TITLES; ?></td>
<td class="pageHeading" align="right"><?php echo tep_image(DIR_WS_IMAGES . 'table_background_login.gif', HEADING_TITLES, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
</tr>
</table></td>
<tr>
<td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
</tr>
<tr>
<td>
<?php  if ($show_login ==1) { require('includes/fec/login_box2.php');}
if ($show_login ==2) { require('includes/fec/login_box.php');}
if ($show_login ==0) {echo PRIMARY_ADDRESS_DESCRIPTION; } ?></td>
</tr>
<tr>
<td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
</tr>
<tr>
<td><table border="0" width="100%" cellspacing="0" cellpadding="2">
<tr>
<td class="main"><?php echo tep_draw_form('checkout', tep_href_link('create_account3.php', '', 'SSL'), 'post','onSubmit="return check_form(checkout);"') . tep_draw_hidden_field('action', 'process').tep_draw_hidden_field('fromlogin', $fromlogin); ?><b><?php //echo TITLE_FORM; ?></b></td>
</tr>
</table></td>
</tr>
<tr>
<td><br><table border="0" width="100%" cellspacing="0" cellpadding="2">
<?php
if ($messageStack->size('create_account') > 0) {
	?>
	<tr>
	<td><?php echo $messageStack->output('create_account'); ?></td>
	</tr>
	<?php
}
?>  <tr><td><table width="100%" ><tr><td class="main">
<b><?php echo TITLE_PAYMENT_ADDRESS; ?></b></td><td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '100px', '10'); ?></td>
<td class="inputRequirement" align="right"><?php echo FORM_REQUIRED_INFORMATION; ?></td>
</tr>
</table></td>
</tr>
</table></td>
</tr>
<tr>
<td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
<tr class="infoBoxContents">
<td><table border="0" cellspacing="2" cellpadding="2" align="right">
<tr>
<td class = "infoBoxContents"><?php echo PAYMENT_SHIPMENT; ?></td>
<td class = "infoBoxContents"><input type="image" src="images/collapse_tcat.gif" name="row" value="1" onclick="return toggle_collapse('forumbit_1')"></td>
</tr>
</table></td>
</tr>
</table></td>
</tr>
<tr>
<td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
<tr class="infoBoxContents">
<td><table border="0" cellspacing="2" cellpadding="2">
<tr><?php
if (ACCOUNT_GENDER == 'true') {
	?>
	<tr>
	<td class="infoBoxContents"><?php echo ENTRY_GENDER; ?></td>
	<td class="infoBoxContents"><?php echo tep_draw_radio_field('gender', 'm') . '&nbsp;&nbsp;' . MALE . '&nbsp;&nbsp;' . tep_draw_radio_field('gender', 'f') . '&nbsp;&nbsp;' . FEMALE . '&nbsp;' . (tep_not_null(ENTRY_GENDER_TEXT) ? '<span class="inputRequirement">' . ENTRY_GENDER_TEXT . '</span>': ''); ?></td>
	</tr> </tr>
	<?php
}
?>
<td class="infoBoxContents"><?php echo ENTRY_FIRST_NAME; ?></td>
<td class="infoBoxContents"><?php echo tep_draw_input_field('firstname','','') . '&nbsp;' . (tep_not_null(ENTRY_FIRST_NAME_TEXT) ? '<span class="inputRequirement">' . ENTRY_FIRST_NAME_TEXT . '</span>': ''); ?></td>
</tr> <tr>
<td class="infoBoxContents"><?php echo ENTRY_LAST_NAME; ?></td>
<td class="infoBoxContents"><?php echo tep_draw_input_field('lastname','','') . '&nbsp;' . (tep_not_null(ENTRY_LAST_NAME_TEXT) ? '<span class="inputRequirement">' . ENTRY_LAST_NAME_TEXT . '</span>': ''); ?></td>
<?php
if (ACCOUNT_DOB == 'true') {
	?>
	</tr> <tr>
	<td class="infoBoxContents"><?php echo ENTRY_DATE_OF_BIRTH; ?></td>
	<td class="infoBoxContents"><?php echo tep_draw_input_field('dob') . '&nbsp;' . (tep_not_null(ENTRY_DATE_OF_BIRTH_TEXT) ? '<span class="inputRequirement">' . ENTRY_DATE_OF_BIRTH_TEXT . '</span>': ''); ?></td> </tr>
	<?php
}
?>
<?php
if (ACCOUNT_COMPANY == 'true') {
	?>
	<tr>
	<td class="infoBoxContents"><?php echo ENTRY_COMPANY; ?></td>
	<td class="infoBoxContents"><?php echo tep_draw_input_field('company') . '&nbsp;' . (tep_not_null(ENTRY_COMPANY_TEXT) ? '<span class="inputRequirement">' . ENTRY_COMPANY_TEXT . '</span>': ''); ?></td>
	</tr>
	<?php
}
?>
<tr>
<td class="infoBoxContents"><?php echo ENTRY_STREET_ADDRESS; ?></td>
<td class="infoBoxContents"><?php echo tep_draw_input_field('street_address','','') . '&nbsp;' . (tep_not_null(ENTRY_STREET_ADDRESS_TEXT) ? '<span class="inputRequirement">' . ENTRY_STREET_ADDRESS_TEXT . '</span>': ''); ?></td>
</tr><tr>
<td class="infoBoxContents"><?php echo ENTRY_POST_CODE; ?></td>
<td class="infoBoxContents"><?php echo tep_draw_input_field('postcode') . '&nbsp;' . (tep_not_null(ENTRY_POST_CODE_TEXT) ? '<span class="inputRequirement">' . ENTRY_POST_CODE_TEXT . '</span>': ''); ?></td>
</tr>
<?php
if (ACCOUNT_SUBURB == 'true') {
	?>
	<tr>
	<td class="infoBoxContents"><?php echo ENTRY_SUBURB; ?></td>
	<td class="infoBoxContents"><?php echo tep_draw_input_field('suburb') . '&nbsp;' . (tep_not_null(ENTRY_SUBURB_TEXT) ? '<span class="inputRequirement">' . ENTRY_SUBURB_TEXT . '</span>': ''); ?></td>
	</tr>
	<?php
}
?>  <tr>
<td class="infoBoxContents"><?php echo ENTRY_CITY; ?></td>
<td class="infoBoxContents"><?php echo tep_draw_input_field('City') . '&nbsp;' . (tep_not_null(ENTRY_CITY_TEXT) ? '<span class="inputRequirement">' . ENTRY_CITY_TEXT . '</span>': ''); ?></td>
</tr>
<?php
if (ACCOUNT_STATE == 'true') {
	?>
	<tr>
	<td class="infoBoxContents"><?php echo ENTRY_STATE; ?></td>
	<td class="infoBoxContents">
	<?php
	if ($HTTP_POST_VARS['action'] == 'process') {
		if ($entry_state_has_zones == true) {
			$zones_array = array();
			$zones_query = tep_db_query("select zone_name from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country . "' order by zone_name");
			while ($zones_values = tep_db_fetch_array($zones_query)) {
				$zones_array[] = array('id' => $zones_values['zone_name'], 'text' => $zones_values['zone_name']);
			}
			echo tep_draw_pull_down_menu('state', $zones_array);
			} else {
			echo tep_draw_input_field('state');
		}
		} else {
		echo tep_draw_input_field('state');
	}
	if (tep_not_null(ENTRY_STATE_TEXT)) echo '&nbsp;<span class="inputRequirement">' . ENTRY_STATE_TEXT;
	?>
	</td>
	</tr>
	<?php
}
?>
<tr>
<td class="infoBoxContents"><?php echo ENTRY_COUNTRY; ?></td>
<td class="infoBoxContents"><?php echo tep_get_country_list('country') . '&nbsp;' . (tep_not_null(ENTRY_COUNTRY_TEXT) ? '<span class="inputRequirement">' . ENTRY_COUNTRY_TEXT . '</span>': ''); ?></td>
</tr><tr>
<td class="infoBoxContents"><?php echo ENTRY_TELEPHONE_NUMBER; ?></td>
<td class="infoBoxContents"><?php echo tep_draw_input_field('telephone','','') . '&nbsp;' . (tep_not_null(ENTRY_TELEPHONE_NUMBER_TEXT) ? '<span class="inputRequirement">' . ENTRY_TELEPHONE_NUMBER_TEXT . '</span>': ''); ?></td>
</tr><tr>
<td class="infoBoxContents"><?php echo ENTRY_EMAIL_ADDRESS; ?></td>
<td class="infoBoxContents"><?php echo tep_draw_input_field('email') . '&nbsp;' . (tep_not_null(ENTRY_EMAIL_ADDRESS_TEXT) ? '<span class="inputRequirement">' . ENTRY_EMAIL_ADDRESS_TEXT . '</span>': ''); ?></td>
</tr>
<tr>
<td class="main"><?php echo ENTRY_NEWSLETTER; ?></td>
<td class="main"><?php echo tep_draw_checkbox_field('newsletter', '1') . '&nbsp;' . (tep_not_null(ENTRY_NEWSLETTER_TEXT) ? '<span class="inputRequirement">' . ENTRY_NEWSLETTER_TEXT . '</span>': '');?></td>
</tr>
<?php
if ($create_password == 1) {
	?>
	<tr>
	<td class="main"><?php echo YES_ACCOUNT; ?></td>
	<td class="main"><?php echo tep_draw_checkbox_field('createmyaccount', 'Y') . '&nbsp;' . (tep_not_null(ENTRY_NEWSLETTER_TEXT) ? '<span class="inputRequirement">' . ENTRY_NEWSLETTER_TEXT . '</span>': '');?></td>
	</tr>
	<?php
}
?>
</table></td>
</tr>
</table></td>
</tr>
<tr>
<td><table border="0" width="100%" cellspacing="1" cellpadding="2">
<TBODY id=collapseobj_forumbit_1>
<TD class=alt2 noWrap>
<table border="0" width="100%" cellspacing="1" cellpadding="2">
<TBODY>
<tr>
<td><table border="0" width="100%" cellspacing="0" cellpadding="2">
<tr>
<td class="main"><b><?php echo TITLE_SHIPPING_ADDRESS; ?></b></td>
</tr>
</table></td>
</tr>
<tr>
<td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
<tr class="infoBoxContents">
<td><table border="0" width="100%" cellspacing="0" cellpadding="2">
<tr>
<td class="infoBoxContents"><?php echo ENTRY_FIRST_NAME; ?></td>
<td class = "infoBoxContents"><input type="text" name="ShipFirstName" value="<? echo $FirstName; ?>" size="20"><?php echo '&nbsp;<span class="inputRequirement">' . ENTRY_FIRST_NAME_TEXT . '</span>'; ?></td>
</tr> <tr>
<td class="infoBoxContents"><?php echo ENTRY_LAST_NAME; ?></td>
<td class = "infoBoxContents"><input name="ShipLastName" value="<? echo $LastName; ?>" size="20"><?php echo '&nbsp;<span class="inputRequirement">' . ENTRY_FIRST_NAME_TEXT . '</span>'; ?></td>
</tr>
<?php
if (ACCOUNT_COMPANY == 'true') {
	?>
	<tr>
	<td class="infoBoxContents"><?php echo ENTRY_COMPANY; ?></td>
	<td class="infoBoxContents"><?php echo tep_draw_input_field('shipcompany') . '&nbsp;' . (tep_not_null(ENTRY_COMPANY_TEXT) ? '<span class="inputRequirement">' . ENTRY_COMPANY_TEXT . '</span>': ''); ?></td>
	</tr>
	<?php
}
?>
<tr>
<td class="infoBoxContents"><?php echo ENTRY_STREET_ADDRESS; ?></td>
<td class = "infoBoxContents"><tt><font size="2"><input name="ShipAddress" value="<? echo $ShipAddress; ?>" size="20"></font></tt><?php echo '&nbsp;<span class="inputRequirement">' . ENTRY_FIRST_NAME_TEXT . '</span>'; ?></td>
</tr><tr>
<td class="infoBoxContents"><?php echo ENTRY_POST_CODE; ?></td>
<td class="infoBoxContents"><?php echo tep_draw_input_field('shippostcode') ; ?><?php echo '&nbsp;<span class="inputRequirement">' . ENTRY_FIRST_NAME_TEXT . '</span>'; ?></td>
</tr>
</tr> <tr>
<td class="infoBoxContents"><?php echo ENTRY_CITY; ?></td>
<td class="infoBoxContents"><?php echo tep_draw_input_field('ShipCity') . '&nbsp;' . (tep_not_null(ENTRY_CITY_TEXT) ? '<span class="inputRequirement">' . ENTRY_CITY_TEXT . '</span>': ''); ?></td>
</tr>
<?php
if (ACCOUNT_SUBURB == 'true') {
	?>
	<tr>
	<td class="infoBoxContents"><?php echo ENTRY_SUBURB; ?></td>
	<td class="infoBoxContents"><?php echo tep_draw_input_field('shipsuburb') . '&nbsp;' . (tep_not_null(ENTRY_SUBURB_TEXT) ? '<span class="inputRequirement">' . ENTRY_SUBURB_TEXT . '</span>': ''); ?></td>
	</tr>
	<?php
}
if (ACCOUNT_STATE == 'true') {
	?>
	<tr>
	<td class="infoBoxContents"><?php echo ENTRY_STATE; ?></td>
	<td class="infoBoxContents">
	<?php
	if ($HTTP_POST_VARS['action'] == 'process') {
		if ($entry_state_has_zones == true) {
			$zones_array = array();
			$zones_query = tep_db_query("select zone_name from " . TABLE_ZONES . " where zone_country_id = '" . (int)$shipcountry . "' order by zone_name");
			while ($zones_values = tep_db_fetch_array($zones_query)) {
				$zones_array[] = array('id' => $zones_values['zone_name'], 'text' => $zones_values['zone_name']);
			}
			echo tep_draw_pull_down_menu('shippingstate', $zones_array);
			} else {
			echo tep_draw_input_field('shippingstate');
		}
		} else {
		echo tep_draw_input_field('shippingstate');
	}
	if (tep_not_null(ENTRY_STATE_TEXT)) echo '&nbsp;<span class="inputRequirement">' . ENTRY_STATE_TEXT;
	?>
	</td>
	</tr>
	<?php
}
?>
<tr>
<td class="infoBoxContents"><?php echo ENTRY_COUNTRY; ?></td>
<td class="infoBoxContents"><?php echo tep_get_country_list('shipcountry') . '&nbsp;' . (tep_not_null(ENTRY_COUNTRY_TEXT) ? '<span class="inputRequirement">' . ENTRY_COUNTRY_TEXT . '</span>': ''); ?></td>
</tr> <tr>
<td class="infoBoxContents"><?php //echo ENTRY_TELEPHONE_NUMBER; ?></td>
<td class="infoBoxContents"><?php// echo tep_draw_input_field('shiptelephone') . '&nbsp;' . (tep_not_null(ENTRY_TELEPHONE_NUMBER_TEXT) ? '<span class="inputRequirement">' . ENTRY_TELEPHONE_NUMBER_TEXT . '</span>': ''); ?></td>
</tr>
</table></td>
</tr>
</table></td>
</tr>
</td></tr>
</TBODY> </table></td>
</tr>
</table></td>
</tr>
<script>toggle_collapse('forumbit_1')</script>
<?php
if ($show_account_box == 1) {require('includes/fec/account_box2.php');}
if ($show_account_box == 2){ require('includes/fec/account_box.php');}
?>
<table border="0" width="100%" cellspacing="0" cellpadding="2" valign="top">
</td><td width="100%" border="1">&nbsp;</td><td width="100%" border="1" valign="top"><center>
<?php
echo tep_image_submit('button_continue.gif', IMAGE_BUTTON_CONFIRM_ORDER) . '</form></td>';
?>
</tr></table>
<table border="0" width="100%" cellspacing="0" cellpadding="2" valign="top">
<tr>
<td><table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr>
<td width="25%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr>
<td width="50%" align="right"><?php echo tep_image(DIR_WS_IMAGES . 'checkout_bullet.gif'); ?></td>
<td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
</tr>
</table></td>
<td width="25%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
<td width="25%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
<td width="25%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr>
<td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
<td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '1', '5'); ?></td>
</tr>
</table></td>
</tr>
<tr>
<td align="center" width="25%" class="checkoutBarCurrent"><?php echo CHECKOUT_BAR_DELIVERY; ?></td>
<td align="center" width="25%" class="checkoutBarTo"><?php echo CHECKOUT_BAR_PAYMENT; ?></td>
<td align="center" width="25%" class="checkoutBarTo"><?php echo CHECKOUT_BAR_CONFIRMATION; ?></td>
<td align="center" width="25%" class="checkoutBarTo"><?php echo CHECKOUT_BAR_FINISHED; ?></td>
</tr>
</table></td>
</tr>
</table></TD>
<!-- body_text_eof //-->
<TD WIDTH="<?php echo BOX_WIDTH; ?>" VALIGN="top" rowspan="2"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
<!-- right_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_right.php'); ?>
<!-- right_navigation_eof //-->
</table></td>
</tr>
</table>
<!-- body_eof //-->
<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>