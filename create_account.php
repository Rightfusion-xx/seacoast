<?php
require('includes/application_top.php');
// needs to be included earlier to set the success message in the messageStack
require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CREATE_ACCOUNT);
$process = false;
// +Country-State Selector
if(isset($HTTP_POST_VARS['action']) && (($HTTP_POST_VARS['action'] == 'process') || ($HTTP_POST_VARS['action'] == 'refresh')))
{
    if($HTTP_POST_VARS['action'] == 'process')
        $process = true;
    // -Country-State Selector
    if(ACCOUNT_GENDER == 'true')
    {
        if(isset($HTTP_POST_VARS['gender']))
        {
            $gender = tep_db_prepare_input($HTTP_POST_VARS['gender']);
        }
        else
        {
            $gender = false;
        }
    }

    $firstname = tep_db_prepare_input($HTTP_POST_VARS['firstname']);

    $lastname = tep_db_prepare_input($HTTP_POST_VARS['lastname']);

    if(ACCOUNT_DOB == 'true')
        $dob = tep_db_prepare_input($HTTP_POST_VARS['dob']);

    $email_address = tep_db_prepare_input($HTTP_POST_VARS['email_address']);

    if(ACCOUNT_COMPANY == 'true')
        $company = tep_db_prepare_input($HTTP_POST_VARS['company']);

    $street_address = tep_db_prepare_input($HTTP_POST_VARS['street_address']);

    if(ACCOUNT_SUBURB == 'true')
        $suburb = tep_db_prepare_input($HTTP_POST_VARS['suburb']);

    $postcode = tep_db_prepare_input($HTTP_POST_VARS['postcode']);

    $city = tep_db_prepare_input($HTTP_POST_VARS['city']);

    if(ACCOUNT_STATE == 'true')
    {

        $state = tep_db_prepare_input($HTTP_POST_VARS['state']);

        if(isset($HTTP_POST_VARS['zone_id']))
        {

            $zone_id = tep_db_prepare_input($HTTP_POST_VARS['zone_id']);
        }
        else
        {

            $zone_id = false;
        }
    }

    $country = tep_db_prepare_input($HTTP_POST_VARS['country']);

    $telephone = tep_db_prepare_input($HTTP_POST_VARS['telephone']);

    $fax = tep_db_prepare_input($HTTP_POST_VARS['fax']);

    if(isset($HTTP_POST_VARS['newsletter']))
    {

        $newsletter = tep_db_prepare_input($HTTP_POST_VARS['newsletter']);
    }
    else
    {

        $newsletter = false;
    }

    $password = tep_db_prepare_input($HTTP_POST_VARS['password']);

    $confirmation = tep_db_prepare_input($HTTP_POST_VARS['confirmation']);

    // +Country-State Selector

    if($process)
    {

        // -Country-State Selector

        $error = false;

        if(ACCOUNT_GENDER == 'true')
        {

            if(($gender != 'm') && ($gender != 'f'))
            {

                $error = true;

                $messageStack->add('create_account', ENTRY_GENDER_ERROR);
            }
        }

        if(strlen($firstname) < ENTRY_FIRST_NAME_MIN_LENGTH)
        {

            $error = true;

            $messageStack->add('create_account', ENTRY_FIRST_NAME_ERROR);
        }

        if(strlen($lastname) < ENTRY_LAST_NAME_MIN_LENGTH)
        {

            $error = true;

            $messageStack->add('create_account', ENTRY_LAST_NAME_ERROR);
        }

        if(ACCOUNT_DOB == 'true')
        {

            if(checkdate(substr(tep_date_raw($dob), 4, 2), substr(tep_date_raw($dob), 6, 2), substr(tep_date_raw($dob), 0, 4)) == false)
            {

                $error = true;

                $messageStack->add('create_account', ENTRY_DATE_OF_BIRTH_ERROR);
            }
        }

        if(strlen($email_address) < ENTRY_EMAIL_ADDRESS_MIN_LENGTH)
        {

            $error = true;

            $messageStack->add('create_account', ENTRY_EMAIL_ADDRESS_ERROR);
        }
        elseif(tep_validate_email($email_address) == false)
        {

            $error = true;

            $messageStack->add('create_account', ENTRY_EMAIL_ADDRESS_CHECK_ERROR);
        }
        else
        {

            $check_email_query = tep_db_query("select count(*) as total from " . TABLE_CUSTOMERS . " where customers_email_address = '" . tep_db_input($email_address) . "'");

            $check_email = tep_db_fetch_array($check_email_query);

            // DDB - 040616 - PWA

            //      if ($check_email['total'] > 0) {

            //        $error = true;

            //        $messageStack->add('create_account', ENTRY_EMAIL_ADDRESS_ERROR_EXISTS);

            //      }

            if($check_email['total'] > 0)

            { //PWA delete account

                $get_customer_info = tep_db_query("select customers_id, customers_email_address, purchased_without_account from " . TABLE_CUSTOMERS . " where customers_email_address = '" . tep_db_input($email_address) . "'");

                $customer_info = tep_db_fetch_array($get_customer_info);

                $customer_id = $customer_info['customers_id'];

                $customer_email_address = $customer_info['customers_email_address'];

                $customer_pwa = $customer_info['purchased_without_account'];

                if($customer_pwa != '1')

                {

                    $error = true;

                    $messageStack->add('create_account', ENTRY_EMAIL_ADDRESS_ERROR_EXISTS);
                }
                else
                {

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

        if(strlen($street_address) < ENTRY_STREET_ADDRESS_MIN_LENGTH)
        {

            $error = true;

            $messageStack->add('create_account', ENTRY_STREET_ADDRESS_ERROR);
        }

        if(strlen($postcode) < ENTRY_POSTCODE_MIN_LENGTH)
        {

            $error = true;

            $messageStack->add('create_account', ENTRY_POST_CODE_ERROR);
        }

        if(strlen($city) < ENTRY_CITY_MIN_LENGTH)
        {

            $error = true;

            $messageStack->add('create_account', ENTRY_CITY_ERROR);
        }

        if(is_numeric($country) == false)
        {

            $error = true;

            $messageStack->add('create_account', ENTRY_COUNTRY_ERROR);
        }

        if(ACCOUNT_STATE == 'true')
        {

            $zone_id = 0;

            $check_query = tep_db_query("select count(*) as total from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country . "'");

            $check = tep_db_fetch_array($check_query);

            $entry_state_has_zones = ($check['total'] > 0);

            if($entry_state_has_zones == true)
            {

                $zone_query = tep_db_query("select distinct zone_id from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country . "' and (zone_name like '" . tep_db_input($state) . "%' or zone_code like '%" . tep_db_input($state) . "%')");

                if(tep_db_num_rows($zone_query) == 1)
                {

                    $zone = tep_db_fetch_array($zone_query);

                    $zone_id = $zone['zone_id'];
                }
                else
                {

                    $error = true;

                    $messageStack->add('create_account', ENTRY_STATE_ERROR_SELECT);
                }
            }
            else
            {

                if(strlen($state) < ENTRY_STATE_MIN_LENGTH)
                {

                    $error = true;

                    $messageStack->add('create_account', ENTRY_STATE_ERROR);
                }
            }
        }

        if(strlen($telephone) < ENTRY_TELEPHONE_MIN_LENGTH)
        {

            $error = true;

            $messageStack->add('create_account', ENTRY_TELEPHONE_NUMBER_ERROR);
        }

        if(strlen($password) < ENTRY_PASSWORD_MIN_LENGTH)
        {

            $error = true;

            $messageStack->add('create_account', ENTRY_PASSWORD_ERROR);
        }
        elseif($password != $confirmation)
        {

            $error = true;

            $messageStack->add('create_account', ENTRY_PASSWORD_ERROR_NOT_MATCHING);
        }

        if($error == false)
        {

            $sql_data_array = array(
                'customers_firstname'     => $firstname,
                'customers_lastname'      => $lastname,
                'customers_email_address' => $email_address,
                'customers_telephone'     => $telephone,
                'customers_fax'           => $fax,
                'customers_newsletter'    => $newsletter,
                'customers_password'      => tep_encrypt_password($password)
            );

            if(ACCOUNT_GENDER == 'true')
                $sql_data_array['customers_gender'] = $gender;

            if(ACCOUNT_DOB == 'true')
                $sql_data_array['customers_dob'] = tep_date_raw($dob);

            tep_db_perform(TABLE_CUSTOMERS, $sql_data_array);

            $customer_id = tep_db_insert_id();

            $sql_data_array = array(
                'customers_id'         => $customer_id,
                'entry_firstname'      => $firstname,
                'entry_lastname'       => $lastname,
                'entry_street_address' => $street_address,
                'entry_postcode'       => $postcode,
                'entry_city'           => $city,
                'entry_country_id'     => $country
            );

            if(ACCOUNT_GENDER == 'true')
                $sql_data_array['entry_gender'] = $gender;

            if(ACCOUNT_COMPANY == 'true')
                $sql_data_array['entry_company'] = $company;

            if(ACCOUNT_SUBURB == 'true')
                $sql_data_array['entry_suburb'] = $suburb;

            if(ACCOUNT_STATE == 'true')
            {

                if($zone_id > 0)
                {
                    $sql_data_array['entry_zone_id'] = $zone_id;
                    $sql_data_array['entry_state'] = '';
                }
                else
                {
                    $sql_data_array['entry_zone_id'] = '0';
                    $sql_data_array['entry_state'] = $state;
                }
            }

            tep_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array);

            $address_id = tep_db_insert_id();

            tep_db_query("update " . TABLE_CUSTOMERS . " set customers_default_address_id = '" . (int)$address_id . "' where customers_id = '" . (int)$customer_id . "'");

            tep_db_query("insert into " . TABLE_CUSTOMERS_INFO . " (customers_info_id, customers_info_number_of_logons, customers_info_date_account_created) values ('" . (int)$customer_id . "', '0', now())");

            if(SESSION_RECREATE == 'True')
            {

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

            // restore cart contents

            $cart->restore_contents();

            // build the message content

            $name = $firstname . ' ' . $lastname;

            if(ACCOUNT_GENDER == 'true')
            {

                if($gender == 'm')
                {

                    $email_text = sprintf(EMAIL_GREET_MR, $lastname);
                }
                else
                {

                    $email_text = sprintf(EMAIL_GREET_MS, $lastname);
                }
            }
            else
            {

                $email_text = sprintf(EMAIL_GREET_NONE, $firstname);
            }

            $email_text .= EMAIL_WELCOME . EMAIL_TEXT . EMAIL_CONTACT . EMAIL_WARNING;

            //   tep_mail($name, $email_address, EMAIL_SUBJECT, $email_text, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);

            if($_REQUEST['preventionmag'] == '1')
            {
                tep_db_query("insert into leads (customers_id, leads_code, leads_date) values ('" . (int)$customer_id . "', 'PREVENTIONMAG', now())");
            }

            tep_redirect(tep_href_link(FILENAME_CREATE_ACCOUNT_SUCCESS, '', 'SSL'));
        }
    }
    // +Country-State Selector

}

if(!isset($country))
    $country = DEFAULT_COUNTRY;

// -Country-State Selector

$breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_CREATE_ACCOUNT, '', 'SSL'));

?>

<!doctype html>

<html <?php echo HTML_PARAMS; ?>>

<head>

    <meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">

    <meta name="robots" content="noindex, nofollow">

    <title><?php echo TITLE; ?></title>

    <base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">

    <link rel="stylesheet" type="text/css" href="stylesheet.css">

    <?php require('includes/form_check.js.php'); ?>

</head>

<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">


<!-- header //-->

<?php require(DIR_WS_INCLUDES . 'header.php'); ?>

<!-- header_eof //-->


<div class="container">
<div class="row">
<div class="span12">


<h1>Shipping Information</h1>

<p><b>Already a member?</b><br/><a href="/login.php"><img
    src="/includes/languages/english/images/buttons/button_login.gif" border="0"></a></p>


<?php

if($messageStack->size('create_account') > 0)
{

    ?>

<div class="alert alert-error"><?php echo $messageStack->output('create_account'); ?></div>

    <?php
}

?>
<form action="create_account.php" method="post" class="form-horizontal" id="create_account">
<?php echo tep_draw_hidden_field('action', 'process'); ?>


<?php echo FORM_REQUIRED_INFORMATION; ?><br/>
<fieldset>

<legend>
    <?php echo CATEGORY_PERSONAL; ?>
</legend>


<div class="control-group">
    <label class="control-label" for="country">
        <?php echo ENTRY_COUNTRY; ?>
    </label>

    <div class="controls">

        <?php // +Country-State Selector ?>

        <?php echo tep_get_country_list('country', $country,

        'onChange="return refresh_form(create_account);"') . '&nbsp;' .

        (tep_not_null(ENTRY_COUNTRY_TEXT) ? '<span class="inputRequirement">' . ENTRY_COUNTRY_TEXT .

            '</span>' : ''); ?>

        <?php // -Country-State Selector ?>
    </div>
</div>

<div class="control-group">
    <label class="control-label">
        <?php echo ENTRY_FIRST_NAME; ?>
    </label>

    <div class="controls">
        <?php echo tep_draw_input_field('firstname') . '&nbsp;' . (tep_not_null(ENTRY_FIRST_NAME_TEXT) ? '<span class="inputRequirement">' . ENTRY_FIRST_NAME_TEXT . '</span>' : ''); ?>
    </div>
</div>

<div class="control-group">
    <label class="control-label">
        <?php echo ENTRY_LAST_NAME; ?>
    </label>

    <div class="controls">
        <?php echo tep_draw_input_field('lastname') . '&nbsp;' . (tep_not_null(ENTRY_LAST_NAME_TEXT) ? '<span class="inputRequirement">' . ENTRY_LAST_NAME_TEXT . '</span>' : ''); ?>
    </div>
</div>


<div class="control-group">
    <label class="control-label">
        <?php echo ENTRY_EMAIL_ADDRESS; ?>
    </label>

    <div class="controls">
        <?php echo tep_draw_input_field('email_address') . '&nbsp;' . (tep_not_null(ENTRY_EMAIL_ADDRESS_TEXT) ? '<span class="inputRequirement">' . ENTRY_EMAIL_ADDRESS_TEXT . '</span>' : ''); ?>
    </div>
</div>

<legend><?php echo CATEGORY_PASSWORD; ?></legend>

<div class="control-group">
    <label class="control-label">
        <?php echo ENTRY_PASSWORD; ?>
    </label>

    <div class="controls">
        <?php echo tep_draw_password_field('password') . '&nbsp;' . (tep_not_null(ENTRY_PASSWORD_TEXT) ? '<span class="inputRequirement">' . ENTRY_PASSWORD_TEXT . '</span>' : ''); ?>
    </div>
</div>

<div class="control-group">
    <label class="control-label">
        <?php echo ENTRY_PASSWORD_CONFIRMATION; ?>
    </label>

    <div class="controls">
        <?php echo tep_draw_password_field('confirmation') . '&nbsp;' . (tep_not_null(ENTRY_PASSWORD_CONFIRMATION_TEXT) ? '<span class="inputRequirement">' . ENTRY_PASSWORD_CONFIRMATION_TEXT . '</span>' : ''); ?>
    </div>
</div>


<?php

if(ACCOUNT_COMPANY == 'true')
{

    ?>
<legend>
    <?php echo CATEGORY_COMPANY; ?>
</legend>

<div class="control-group">
    <label class="control-label">
        <?php echo ENTRY_COMPANY; ?>
    </label>

    <div class="controls">
        <?php echo tep_draw_input_field('company') . '&nbsp;' . (tep_not_null(ENTRY_COMPANY_TEXT) ? '<span class="inputRequirement">' . ENTRY_COMPANY_TEXT . '</span>' : ''); ?>
    </div>
</div>

    <?php
}

?>


<legend><?php echo CATEGORY_ADDRESS; ?></legend>


<div class="control-group">
    <label class="control-label">
        <?php echo ENTRY_STREET_ADDRESS; ?>
    </label>

    <div class="controls">
        <?php echo tep_draw_input_field('street_address') . '&nbsp;' . (tep_not_null(ENTRY_STREET_ADDRESS_TEXT) ? '<span class="inputRequirement">' . ENTRY_STREET_ADDRESS_TEXT . '</span>' : ''); ?>
    </div>
</div>

<div class="control-group">
    <label class="control-label">
        <?php echo ENTRY_CITY; ?>
    </label>

    <div class="controls">
        <?php echo tep_draw_input_field('city') . '&nbsp;' . (tep_not_null(ENTRY_CITY_TEXT) ? '<span class="inputRequirement">' . ENTRY_CITY_TEXT . '</span>' : ''); ?>
    </div>
</div>

<div class="control-group">
    <label class="control-label">
        <?php echo ENTRY_POST_CODE; ?>
    </label>

    <div class="controls">
        <?php echo tep_draw_input_field('postcode') . '&nbsp;' . (tep_not_null(ENTRY_POST_CODE_TEXT) ? '<span class="inputRequirement">' . ENTRY_POST_CODE_TEXT . '</span>' : ''); ?>
    </div>
</div>


<?php

if(ACCOUNT_STATE == 'true')
{

    ?>

 <div class="control-group">
    <label class="control-label">
        <?php echo ENTRY_STATE; ?>
    </label>
    <div class="controls">

         <?php

    // +Country-State Selector

    $zones_array = array();

    $zones_query = tep_db_query("select zone_name from " . TABLE_ZONES . " where zone_country_id = " .

        (int)$country . " order by zone_name");

    while($zones_values = tep_db_fetch_array($zones_query))
    {

        $zones_array[] = array('id'   => $zones_values['zone_name'],
                               'text' => $zones_values['zone_name']
        );
    }

    if(count($zones_array) > 0)
    {

        echo tep_draw_pull_down_menu('state', $zones_array);
    }
    else
    {

        echo tep_draw_input_field('state');
    }
    // -Country-State Selector

}
else
{

    echo tep_draw_input_field('state');
}



if(tep_not_null(ENTRY_STATE_TEXT))
    echo '&nbsp;<span class="inputRequirement">' . ENTRY_STATE_TEXT;

?>
</div>
</div>

<?php

// }

?>


<legend><?php echo CATEGORY_CONTACT; ?></legend>


<div class="control-group">
    <label class="control-label">
        <?php echo ENTRY_TELEPHONE_NUMBER; ?>
    </label>

    <div class="controls">
        <?php echo tep_draw_input_field('telephone') . '&nbsp;' . (tep_not_null(ENTRY_TELEPHONE_NUMBER_TEXT) ? '<span class="inputRequirement">' . ENTRY_TELEPHONE_NUMBER_TEXT . '</span>' : ''); ?>
    </div>
</div>

<legend>
    <b><?php echo CATEGORY_OPTIONS; ?></b>
</legend>

<div class="control-group">
    <label class="control-label">
        <?php echo ENTRY_NEWSLETTER; ?>  <?php echo tep_draw_checkbox_field('newsletter', '1', 'true') . '&nbsp;' . (tep_not_null(ENTRY_NEWSLETTER_TEXT) ? '<span class="inputRequirement">' . ENTRY_NEWSLETTER_TEXT . '</span>' : ''); ?>
    </label>

    <div class="controls">
        <p>Seacoast Natural Health Newsletter

            contains New Product Information, Natural Health Articles, and

            Valuable Discount Notifications. We will never share your information with anyone.</p>

        <a href="/privacy.php" target="_blank">Policy/Spam

            Notice </a>
    </div>
</div>


<?php echo tep_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?>
<BR><FONT SIZE="-1">(Information is encrypted for your privacy and security).</FONT>


</fieldset>
</form>


</div>
</div>
</div>


<!-- body_eof //-->


<!-- footer //-->

<?php include(DIR_WS_INCLUDES . 'footer.php'); ?>

<!-- footer_eof //-->

<br>

</body>

</html>

<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>

