<?php
/*
  $Id: Order_Info_Process.php,v 0.56 2003/03/08 hpdl Exp $
	by Richy C.
  
        OSCommerce v2.2MS1
  
   Modified versions of create_account.php and related
  files.  Allowing 'purchase without account'.        

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CREATE_ACCOUNT);
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_LOGIN);

  if (!@$HTTP_POST_VARS['action']) {
    tep_redirect(tep_href_link(FILENAME_ORDER_INFO, '', 'NONSSL'));
  }

  $gender = tep_db_prepare_input($HTTP_POST_VARS['gender']);
  $firstname = tep_db_prepare_input($HTTP_POST_VARS['firstname']);
  $lastname = tep_db_prepare_input($HTTP_POST_VARS['lastname']);
  if (ACCOUNT_DOB == 'true') $dob = tep_db_prepare_input($HTTP_POST_VARS['dob']);
  $email_address = tep_db_prepare_input($HTTP_POST_VARS['email_address']);
  $telephone = tep_db_prepare_input($HTTP_POST_VARS['telephone']);
  $fax = tep_db_prepare_input($HTTP_POST_VARS['fax']);
  $newsletter = tep_db_prepare_input($HTTP_POST_VARS['newsletter']);
//  $password = tep_db_prepare_input($HTTP_POST_VARS['password']);
        $password = tep_db_prepare_input('');
  $confirmation = tep_db_prepare_input($HTTP_POST_VARS['confirmation']);
  $street_address = tep_db_prepare_input($HTTP_POST_VARS['street_address']);
  if (ACCOUNT_COMPANY == 'true') $company = tep_db_prepare_input($HTTP_POST_VARS['company']);
  if (ACCOUNT_SUBURB == 'true') $suburb = tep_db_prepare_input($HTTP_POST_VARS['suburb']);
  $postcode = tep_db_prepare_input($HTTP_POST_VARS['postcode']);
  $city = tep_db_prepare_input($HTTP_POST_VARS['city']);
  $zone_id = tep_db_prepare_input($HTTP_POST_VARS['zone_id']);
  $state = tep_db_prepare_input($HTTP_POST_VARS['state']);
  $country = tep_db_prepare_input($HTTP_POST_VARS['country']);

  $error = false; // reset error flag

  if (ACCOUNT_GENDER == 'true') {
    if (($gender == 'm') || ($gender == 'f')) {
      $entry_gender_error = false;
    } else {
      $error = true;
      $entry_gender_error = true;
    }
  }

  if (strlen($firstname) < ENTRY_FIRST_NAME_MIN_LENGTH) {
    $error = true;
    $entry_firstname_error = true;
  } else {
    $entry_firstname_error = false;
  }

  if (strlen($lastname) < ENTRY_LAST_NAME_MIN_LENGTH) {
    $error = true;
    $entry_lastname_error = true;
  } else {
    $entry_lastname_error = false;
  }

  if (ACCOUNT_DOB == 'true') {
    if (checkdate(substr(tep_date_raw($dob), 4, 2), substr(tep_date_raw($dob), 6, 2), substr(tep_date_raw($dob), 0, 4))) {
      $entry_date_of_birth_error = false;
    } else {
      $error = true;
      $entry_date_of_birth_error = true;
    }
  }

  if (strlen($email_address) < ENTRY_EMAIL_ADDRESS_MIN_LENGTH) {
    $error = true;
    $entry_email_address_error = true;
  } else {
    $entry_email_address_error = false;
  }

  if (!tep_validate_email($email_address)) {
    $error = true;
    $entry_email_address_check_error = true;
  } else {
    $entry_email_address_check_error = false;
  }

  if (strlen($street_address) < ENTRY_STREET_ADDRESS_MIN_LENGTH) {
    $error = true;
    $entry_street_address_error = true;
  } else {
    $entry_street_address_error = false;
  }

  if (strlen($postcode) < ENTRY_POSTCODE_MIN_LENGTH) {
    $error = true;
    $entry_post_code_error = true;
  } else {
    $entry_post_code_error = false;
  }

  if (strlen($city) < ENTRY_CITY_MIN_LENGTH) {
    $error = true;
    $entry_city_error = true;
  } else {
    $entry_city_error = false;
  }

  if (!$country) {
    $error = true;
    $entry_country_error = true;
  } else {
    $entry_country_error = false;
  }

  if (ACCOUNT_STATE == 'true') {
    if ($entry_country_error) {
      $entry_state_error = true;
    } else {
      $zone_id = 0;
      $entry_state_error = false;
      $check_query = tep_db_query("select count(*) as total from " . TABLE_ZONES . " where zone_country_id = '" . tep_db_input($country) . "'");
      $check_value = tep_db_fetch_array($check_query);
      $entry_state_has_zones = ($check_value['total'] > 0);
      if ($entry_state_has_zones) {
        $zone_query = tep_db_query("select zone_id from " . TABLE_ZONES . " where zone_country_id = '" . tep_db_input($country) . "' and zone_name = '" . tep_db_input($state) . "'");
        if (tep_db_num_rows($zone_query) == 1) {
          $zone_values = tep_db_fetch_array($zone_query);
          $zone_id = $zone_values['zone_id'];
        } else {
          $zone_query = tep_db_query("select zone_id from " . TABLE_ZONES . " where zone_country_id = '" . tep_db_input($country) . "' and zone_code = '" . tep_db_input($state) . "'");
          if (tep_db_num_rows($zone_query) == 1) {
            $zone_values = tep_db_fetch_array($zone_query);
            $zone_id = $zone_values['zone_id'];
          } else {
            $error = true;
            $entry_state_error = true;
          }
        }
      } else {
        if (!$state) {
          $error = true;
          $entry_state_error = true;
        }
      }
    }
  }

  if (strlen($telephone) < ENTRY_TELEPHONE_MIN_LENGTH) {
    $error = true;
    $entry_telephone_error = true;
  } else {
    $entry_telephone_error = false;
  }

	$entry_password_error = false;
	$entry_email_address_exists = false;

  if ($error == true) {
    $processed = true;

    $breadcrumb->add(NAV_ORDER_INFO, tep_href_link(FILENAME_ORDER_INFO, '', 'NONSSL'));
// DDB - 040622 - no need    $breadcrumb->add(NAVBAR_TITLE_2);
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
<?php require('includes/form_check.js.php'); ?>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="3" cellpadding="3">
  <tr>
    <TD WIDTH="<?php echo BOX_WIDTH; ?>" VALIGN="top" rowspan="2"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<td valign="top" colspan="2" valign="top"><?php require(DIR_WS_INCLUDES . 'titlebar.php'); ?></td></tr><tr><!-- body_text //-->
    <td width="100%" valign="top"><?php echo tep_draw_form('account_edit', tep_href_link(FILENAME_ORDER_INFO_PROCESS, '', 
'SSL'), 'post', 
'onSubmit="return check_form(this);"') . tep_draw_hidden_field('action', 'process'); ?><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <!--tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_image(DIR_WS_IMAGES . 'table_background_account.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr//-->
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><?php include(DIR_WS_MODULES . 'Order_Info_Check.php'); ?></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td align="right"><?php echo tep_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?></td>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
    </table></form></td>
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
<?php include(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<?php
  } else {

		// PWA 0.70 : SELECT using new method of determining a customer has purchased without account:
    $check_customer_query = tep_db_query("select customers_id, purchased_without_account, 
			customers_firstname, customers_password, customers_email_address,
			customers_default_address_id from " . TABLE_CUSTOMERS . "
			where upper(customers_email_address) = '" . strtoupper($HTTP_POST_VARS['email_address']) . "' and
        upper(customers_firstname) = '" . strtoupper($HTTP_POST_VARS['firstname']) . "' and
        upper(customers_lastname) = '" . strtoupper($HTTP_POST_VARS['lastname']) . "'");
   
// if password is EMPTY (null) and e-mail address is same then we just load up their account information.
// could be security flaw -- might want to setup password = somestring and have it recheck here (during the first initial
// creation

			$check_customer = tep_db_fetch_array($check_customer_query);
        
      if (tep_db_num_rows($check_customer_query)) {
				
				// PWA 0.70 added this for backwards compatibility with older versions of PWA
				// that made a blank password, causing logins to fail:
				if(!$check_customer['purchased_without_account']) {
					list($md5hash, $salt) = explode(':',$check_customer['customers_password']);
					if(md5($salt) == $md5hash) {
						// password was blank; customer purchased without account using a previous version of PWA code
						$check_customer['purchased_without_account'] = 1;
					}
				}
		
        if ($check_customer['purchased_without_account'] != 1) {
          tep_redirect(tep_href_link(FILENAME_LOGIN, 
          	'login=fail&reason=' . urlencode(
							str_replace('{EMAIL_ADDRESS}',$check_customer['customers_email_address'],PWA_FAIL_ACCOUNT_EXISTS)), 'SSL'));

        } else {
          $customer_id = $check_customer['customers_id'];
          // now get latest address book entry:
          $get_default_address = tep_db_query("select address_book_id, entry_country_id, entry_zone_id from " . TABLE_ADDRESS_BOOK . "
                where customers_id = '" . $customer_id . "' ORDER BY address_book_id DESC LIMIT 1");
          $default_address = tep_db_fetch_array($get_default_address);
          $customer_default_address_id = $default_address['address_book_id'];
          $customer_first_name = $check_customer['customers_firstname'];
          $customer_country_id = $default_address['entry_country_id'];
          $customer_zone_id = $default_address['entry_zone_id'];
          tep_session_register('customer_id');
          tep_session_register('customer_default_address_id');
          tep_session_register('customer_first_name');
          tep_session_register('customer_country_id');
          tep_session_register('customer_zone_id');
          // PWA 0.71 update returning customer's address book:
         $customer_update = array('customers_firstname' => $firstname,
                             'customers_lastname' => $lastname,
                             'customers_telephone' => $telephone,
                             'customers_fax' => $fax);
   if (ACCOUNT_GENDER == 'true') $customer_update['customers_gender'] = $gender;
      tep_db_perform(TABLE_CUSTOMERS, $customer_update, 'update', "customers_id = '".$customer_id."'");
   
   $address_book_update = array('customers_id' => $customer_id,
         'entry_firstname' => $firstname,
                 'entry_lastname' => $lastname,
                 'entry_street_address' => $street_address,
                 'entry_postcode' => $postcode,
                 'entry_city' => $city,
                 'entry_country_id' => $country);
                   if (ACCOUNT_GENDER == 'true') $address_book_update['entry_gender'] = $gender;
    if (ACCOUNT_COMPANY == 'true') $address_book_update['entry_company'] = $company;
    if (ACCOUNT_SUBURB == 'true') $address_book_update['entry_suburb'] = $suburb;
    if (ACCOUNT_STATE == 'true') {
     if ($zone_id > 0) {
      $address_book_update['entry_zone_id'] = $zone_id;
      $address_book_update['entry_state'] = '';
     } else {
      $address_book_update['entry_zone_id'] = '0';
      $address_book_update['entry_state'] = $state;
     }
    }
         tep_db_perform(TABLE_ADDRESS_BOOK, $address_book_update, 'update', "address_book_id = '".$customer_default_address_id."'");
   } // if-else $pass_ok

          if ($HTTP_POST_VARS['setcookie'] == '1') {
            setcookie('email_address', $HTTP_POST_VARS['email_address'], time()+2592000);
            setcookie('password', $HTTP_POST_VARS['password'], time()+2592000);
            setcookie('first_name', $customer_first_name, time()+2592000);
          } elseif ( ($HTTP_COOKIE_VARS['email_address']) && ($HTTP_COOKIE_VARS['password']) ) {
            setcookie('email_address', '');
            setcookie('password', '');
            setcookie('first_name', '');
          } // if cookies

          $date_now = date('Ymd');
          tep_db_query("update " . TABLE_CUSTOMERS_INFO . " set customers_info_date_of_last_logon = now(),
        customers_info_number_of_logons = customers_info_number_of_logons+1 where customers_info_id = '" . $customer_id . "'");

        } else {
        // if customer_exist = NO

		// PWA 0.70 : new way of determining a customer purchased without an account : just say so!
    $sql_data_array = array('purchased_without_account' => 1,
    												'customers_firstname' => $firstname,
                            'customers_lastname' => $lastname,
                            'customers_email_address' => $email_address,
                            'customers_telephone' => $telephone,
                            'customers_fax' => $fax,
                            'customers_newsletter' => $newsletter,
                            'customers_password' => tep_encrypt_password($password));
//                            'customers_default_address_id' => 1);

    if (ACCOUNT_GENDER == 'true') $sql_data_array['customers_gender'] = $gender;
    if (ACCOUNT_DOB == 'true') $sql_data_array['customers_dob'] = tep_date_raw($dob);

    tep_db_perform(TABLE_CUSTOMERS, $sql_data_array);

    $customer_id = tep_db_insert_id();

    $sql_data_array = array('customers_id' => $customer_id,
                            'address_book_id' => $address_id,
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

      tep_db_query("insert into " . TABLE_CUSTOMERS_INFO . " (customers_info_id, customers_info_number_of_logons, customers_info_date_account_created) values ('" . (int)$customer_id . "', '0', now())");	

    $customer_first_name = $firstname;
    $customer_default_address_id = $address_id;
    $customer_country_id = $country;
    $customer_zone_id = $zone_id;
    tep_session_register('customer_id');
    tep_session_register('customer_first_name');
    tep_session_register('customer_default_address_id');
    tep_session_register('customer_country_id');
    tep_session_register('customer_zone_id');

	} // ELSE CUSTOMER=NO

// restore cart contents
    $cart->restore_contents();

    // build the message content
// DDB - 040622 - no mail will be sent
//    $name = $firstname . " " . $lastname;
//
//    if (ACCOUNT_GENDER == 'true') {
//       if ($HTTP_POST_VARS['gender'] == 'm') {
//         $email_text = EMAIL_GREET_MR;
//       } else {
//         $email_text = EMAIL_GREET_MS;
//       }
//    } else {
//      $email_text = EMAIL_GREET_NONE;
//    }
//
//    $email_text .= EMAIL_WELCOME . EMAIL_TEXT . EMAIL_CONTACT . EMAIL_WARNING;
//    tep_mail($name, $email_address, EMAIL_SUBJECT, nl2br($email_text), STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, '');

//    tep_redirect(tep_href_link(FILENAME_CREATE_ACCOUNT_SUCCESS, '', 'SSL'));

	tep_session_register('noaccount');

    tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'));
  }

  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
