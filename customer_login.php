<?php                        

require($_SERVER['DOCUMENT_ROOT'].'/includes/application_top.php');
require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_PRODUCT_INFO);

tep_session_register('do_admin');
$do_admin=true;
tep_session_register('ordered_by');
$ordered_by='phone';


$error = false;

$email_address = tep_db_prepare_input($_REQUEST['email_address']);
echo $email_address;
$check_customer_query = tep_db_query("select customers_id, customers_firstname, customers_password, customers_email_address, customers_default_address_id from " . TABLE_CUSTOMERS . " where customers_email_address = '" . tep_db_input($email_address) . "'");
if (!tep_db_num_rows($check_customer_query)) {
$error = true;
} else {
$check_customer = tep_db_fetch_array($check_customer_query);

tep_session_recreate();

$check_country_query = tep_db_query("select entry_country_id, entry_zone_id from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$check_customer['customers_id'] . "' and address_book_id = '" . (int)$check_customer['customers_default_address_id'] . "'");
$check_country = tep_db_fetch_array($check_country_query);
$customer_id = $check_customer['customers_id'];
$customer_default_address_id = $check_customer['customers_default_address_id'];
$customer_first_name = $check_customer['customers_firstname'];
$customer_country_id = $check_country['entry_country_id'];
$customer_zone_id = $check_country['entry_zone_id'];
tep_session_register('customer_id');
tep_session_register('customer_default_address_id');
tep_session_register('customer_first_name');
tep_session_register('customer_country_id');
tep_session_register('customer_zone_id');
tep_db_query("update " . TABLE_CUSTOMERS_INFO . " set customers_info_date_of_last_logon = now(), customers_info_number_of_logons = customers_info_number_of_logons+1 where customers_info_id = '" . (int)$customer_id . "'");
// restore cart contents
$cart->restore_contents();

refresh_user_info();   

tep_redirect('/account.php');
}tep_redirect('customers.php');
?>
