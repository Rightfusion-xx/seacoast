<?php
/*
$Id: account_password_new.php,v 1.1 2003/05/19 19:55:45 hpdl Exp $

osCommerce, Open Source E-Commerce Solutions
http://www.oscommerce.com

Copyright (c) 2003 osCommerce

Released under the GNU General Public License
*/

require('includes/application_top.php');
// needs to be included earlier to set the success message in the messageStack
 require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CHECKOUT_SUCCESS);
require(DIR_WS_LANGUAGES . $language . '/' . 'create_account.php');
require(DIR_WS_LANGUAGES . $language . '/' . 'fast_account.php');

if (($HTTP_GET_VARS['customers_id']) || ($HTTP_POST_VARS['customers_id'])) {
if ($HTTP_GET_VARS['customers_id']){ $customers_id = $HTTP_GET_VARS['customers_id'];}
if ($HTTP_POST_VARS['customers_id']) {$customers_id = $HTTP_POST_VARS['customers_id'];}
} else{
if (tep_session_is_registered('customer_id')){$customers_id=$HTTP_SESSION_VARS['customer_id'];
}
}
$check_customer_query2 = tep_db_query("select customers_password,customers_firstname,customers_lastname,customers_gender,confirmation_key,customers_email_address from " . TABLE_CUSTOMERS . " where customers_id = '" . $customers_id . "'");
$check_customer2 = tep_db_fetch_array($check_customer_query2);
if (($HTTP_GET_VARS['confirmation_password']) || ($HTTP_POST_VARS['confirmation_password'])) {
if ($HTTP_GET_VARS['confirmation_password']){ $password_current = $HTTP_GET_VARS['confirmation_password'];}
if ($HTTP_POST_VARS['confirmation_password']) {$password_current = $HTTP_POST_VARS['confirmation_password'];}
}else{
if (tep_session_is_registered('customer_id')) $password_current=$check_customer2['confirmation_key'];
if (tep_session_is_registered('confirmation')) $password_current=$HTTP_SESSION_VARS['confirmation'];
if (!tep_session_is_registered('customer_id'))$password_current = tep_db_prepare_input($HTTP_POST_VARS['password_current']);
}
 if (isset($HTTP_POST_VARS['action']) && ($HTTP_POST_VARS['action'] == 'process')) {
    $password_new = tep_db_prepare_input($HTTP_POST_VARS['password_new']);
    $password_confirmation = tep_db_prepare_input($HTTP_POST_VARS['password_confirmation']);

    $error = false;

    if
	//(strlen($password_current) < ENTRY_PASSWORD_MIN_LENGTH) {
     // $error = true;

     // $messageStack->add('account_password', ENTRY_PASSWORD_CURRENT_ERROR);
  //  } elseif
	(strlen($password_new) < ENTRY_PASSWORD_MIN_LENGTH) {
      $error = true;

      $messageStack->add('account_password', ENTRY_PASSWORD_NEW_ERROR);
    } elseif ($password_new != $password_confirmation) {
      $error = true;

      $messageStack->add('account_password', ENTRY_PASSWORD_NEW_ERROR_NOT_MATCHING);
    }

    if ($error == false) {
      $check_customer_query = tep_db_query("select customers_password,customers_firstname,customers_lastname,customers_gender,customers_email_address from " . TABLE_CUSTOMERS . " where customers_id = '" . $customers_id . "'");
      $check_customer = tep_db_fetch_array($check_customer_query);

    if (tep_validate_password($password_current, $check_customer['customers_password'])) {
         tep_db_query("update " . TABLE_CUSTOMERS . " set createaccount  = 'Y' where customers_id = '" . $customers_id . "'");
        tep_db_query("update " . TABLE_CUSTOMERS . " set customers_password= '" . tep_encrypt_password($password_new) . "' where customers_id = '" . $customers_id . "'");
        tep_db_query("update " . TABLE_CUSTOMERS_INFO . " set customers_info_date_account_last_modified = now() where customers_info_id = '" . $customers_id . "'");
if (tep_session_is_registered('createaccount')) tep_session_unregister('createaccount');
if (tep_session_is_registered('registered_now')) tep_session_unregister('registered_now');
if (tep_session_is_registered('confirmation')) tep_session_unregister('confirmation');
      $messageStack->add_session('account', SUCCESS_PASSWORD_UPDATED, 'success');

       // build the message content
	   $gender= $check_customer['customers_gender'];
	   $firstname= $check_customer['customers_firstname'];
	    $lastname= $check_customer['customers_lastname'];
	   $email_address= $check_customer['customers_email_address'];
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
    tep_mail($name, $email_address, EMAIL_SUBJECT, $email_text, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);


   if (tep_session_is_registered('customer_id')){
   if (sizeof($navigation->snapshot) > 0) {
          $origin_href = tep_href_link($navigation->snapshot['page'], tep_array_to_string($navigation->snapshot['get'], array(tep_session_name())), $navigation->snapshot['mode']);
          $navigation->clear_snapshot();
          tep_redirect($origin_href);
        } else {
          tep_redirect(tep_href_link(FILENAME_DEFAULT));
        }}
   tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));

    } else {
        $error = true;

        $messageStack->add('account_password', ERROR_CURRENT_PASSWORD_NOT_MATCHING);
      }
    }
  }

  $breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_ACCOUNT, '', 'SSL'));
  $breadcrumb->add(NAVBAR_TITLE_2, tep_href_link(FILENAME_ACCOUNT_PASSWORD, '', 'SSL'));
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
    <td width="100%" valign="top"><?php echo tep_draw_form('account_password', tep_href_link('account_password_new.php', 'customers_id='.$customers_id.'&confirmation_password='.$password_current, 'SSL'), 'post', 'onSubmit="return check_form(account_password);"') . tep_draw_hidden_field('action', 'process'); ?><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php  if ($thx==1) {echo HEADING_TITLE2;}else{echo HEADING_TITLE_2;}
//echo $password_current; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_image(DIR_WS_IMAGES . 'table_background_account.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
<td><?php //echo $password_current; ?></td>
<td><?php //echo $HTTP_SESSION_VARS['new_password']; ?></td>
      </tr>
<?php
  if ($messageStack->size('account_password') > 0) {
?>
      <tr>
        <td><?php echo $messageStack->output('account_password'); ?></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
  }
?><?php
  if ($messageStack->size('account') > 0) {
?>
      <tr>
        <td><?php echo $messageStack->output('account'); ?></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
  }
if ($thx==1) {
?>
 <tr><td class="main" colspan="2"><b>
<?php
echo FEC_TEXT_SUCCESS;
echo FEC_TEXT_SEE_ORDERS;
echo '<br><br>' .FEC_TEXT_CONTACT_STORE_OWNER;
echo FEC_TEXT_THANKS_FOR_SHOPPING;
//}
?>
</b></td></tr>
<?php
    }
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
			<tr>
                <td class="main"><?php// echo CATEGORY_CREATE_ACCOUNT ?></td>
				 </tr>
              <tr>
                <td class="inputRequirement"><?php echo ENTRY_CREATEACCOUNT; ?></td>
              </tr>
              <tr>
                <td class="main"><b><?php echo MY_PASSWORD_TITLE; ?></b></td>
                <td class="inputRequirement" align="right"><?php echo FORM_REQUIRED_INFORMATION; ?></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
              <tr class="infoBoxContents">
                <td><table border="0" cellspacing="2" cellpadding="2"><?php if (($HTTP_GET_VARS['confirmation_password']) || ($HTTP_POST_VARS['confirmation_password']) || ($password_current==$password_current)|| ($password_current==$HTTP_SESSION_VARS['confirmation'])) {}else{ ?>
                  <tr>
                    <td class="main"><?php echo ENTRY_PASSWORD_CURRENT2; ?></td>
                    <td class="main"><?php echo tep_draw_password_field('password_current') . '&nbsp;' . (tep_not_null(ENTRY_PASSWORD_CURRENT_TEXT) ? '<span class="inputRequirement">' . ENTRY_PASSWORD_CURRENT_TEXT . '</span>': ''); ?></td>
                  </tr><?php  } ?>
                  <tr>
                    <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
                  </tr>
                  <tr>
                    <td class="main"><?php echo ENTRY_PASSWORD_NEW2; ?></td>
                    <td class="main"><?php echo tep_draw_password_field('password_new') . '&nbsp;' . (tep_not_null(ENTRY_PASSWORD_NEW_TEXT) ? '<span class="inputRequirement">' . ENTRY_PASSWORD_NEW_TEXT . '</span>': ''); ?></td>
                  </tr>
                  <tr>
                    <td class="main"><?php echo ENTRY_PASSWORD_CONFIRMATION; ?></td>
                    <td class="main"><?php echo tep_draw_password_field('password_confirmation') . '&nbsp;' . (tep_not_null(ENTRY_PASSWORD_CONFIRMATION_TEXT) ? '<span class="inputRequirement">' . ENTRY_PASSWORD_CONFIRMATION_TEXT . '</span>': ''); ?></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
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
                <td><?php if (tep_session_is_registered('customer_id')){ echo '<a href="' . tep_href_link(FILENAME_LOGOFF, '', 'SSL') . '">' . tep_image_button('button_end_shopping.gif', IMAGE_BUTTON_BACK) . '</a>';}else{echo '<a href="' . tep_href_link(FILENAME_DEFAULT, '', 'SSL') . '">' . tep_image_button('button_continue.gif', IMAGE_BUTTON_BACK) . '</a>';} ?></td>
                <td align="right"><?php echo tep_image_submit('button_create_account.gif', IMAGE_BUTTON_CONTINUE); ?></td>
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
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>