<?php
require('includes/application_top.php');
// redirect the customer to a friendly cookie-must-be-enabled page if cookies are disabled (or the session has not started)
if($session_started == false)
{
    tep_redirect(tep_href_link(FILENAME_COOKIE_USAGE));
}
require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_LOGIN);
$error = false;
// PWA 0.70 :
if($HTTP_GET_VARS['login'] == 'fail')
{
    $fail_reason = (!empty($HTTP_GET_VARS['reason'])) ? urldecode($HTTP_GET_VARS['reason']) : TEXT_LOGIN_ERROR;
    $messageStack->add('login', $fail_reason);
}
if(empty($_SESSION['state']))
{
    $_SESSION['state'] = md5(uniqid(rand(), TRUE));
}
if(isset($HTTP_GET_VARS['action']))
{
    if($HTTP_GET_VARS['action'] == 'remote_login')
    {
        $code = $_REQUEST["code"];

        if($_SESSION['state'] && ($_SESSION['state'] === $_REQUEST['state'])) {
            $token_url = "https://graph.facebook.com/oauth/access_token?"
                . "client_id=" . FB_APP_ID . "&redirect_uri=" . urlencode(tep_href_link(FILENAME_LOGIN, 'action=remote_login', 'SSL'))
                . "&client_secret=" . FB_APP_SECRET . "&code=" . $code;

            $response = file_get_contents($token_url);

            $params = null;
            parse_str($response, $params);
            $graph_url = "https://graph.facebook.com/me?access_token="
                . $params['access_token'];

            $user = json_decode(file_get_contents($graph_url));

            $snUser = tep_db_query("
                SELECT
                    c.*
                FROM `customers_sns` AS sns
                INNER JOIN " . TABLE_CUSTOMERS . " AS c ON (c.customers_id = sns.customers_id)
                WHERE
                    sns.customers_sn_key = '" . $user->id . "' AND
                    sns.customers_sn_type = 'facebook'

            ");
            $snUser = tep_db_fetch_array($snUser);
            if(!$snUser)
            {
                $check_email_query = tep_db_query("
                    SELECT
                        *
                    FROM " . TABLE_CUSTOMERS . "
                    WHERE customers_email_address = '" . tep_db_input($user->email) . "'");

                $customer = tep_db_fetch_array($check_email_query);
                if($customer == false)
                {
                    tep_db_query("
                    INSERT INTO " . TABLE_CUSTOMERS . "
                        (customers_gender, customers_firstname, customers_lastname, customers_email_address, customers_password)
                    VALUES ('" . substr($user->gender, 0, 1) . "', '" . $user->first_name . "', '" . $user->last_name . "', '" . $user->email . "', '" . tep_encrypt_password($user->email) . "')
                    ");
                    $check_email_query = tep_db_query("
                        SELECT
                            *
                        FROM " . TABLE_CUSTOMERS . "
                        WHERE customers_email_address = '" . tep_db_input($user->email) . "'
                    ");
                    $customer = tep_db_fetch_array($check_email_query);
                }
                tep_db_query("
                    INSERT INTO `customers_sns`
                        (customers_id, customers_sn_key, customers_sn_type)
                    VALUES ('" . $customer['customers_id'] . "', '" . $user->id . "', 'facebook');
                ");

                $HTTP_POST_VARS['email_address'] = $user->email;
                $HTTP_POST_VARS['password'] = $customer['customers_password'];
                $passwordEncrypted = true;
                $HTTP_GET_VARS['action'] = 'process';
            }
            else
            {
                $HTTP_POST_VARS['email_address'] = $user->email;
                $HTTP_POST_VARS['password'] = $snUser['customers_password'];
                $passwordEncrypted = true;
                $HTTP_GET_VARS['action'] = 'process';
            }
        }
        else {
            $error = ("The state does not match. You may be a victim of CSRF.");
        }
    }

    if($HTTP_GET_VARS['action'] == 'process')
    {
        $email_address = tep_db_prepare_input($HTTP_POST_VARS['email_address']);
        $password      = tep_db_prepare_input($HTTP_POST_VARS['password']);
        // Check if email exists
        $check_customer_query = tep_db_query("select customers_id, customers_firstname, customers_password, customers_email_address, customers_default_address_id from " . TABLE_CUSTOMERS . " where customers_email_address = '" . tep_db_input($email_address) . "'");
        if(!tep_db_num_rows($check_customer_query))
        {
            $error = true;
        }
        else
        {
            $check_customer = tep_db_fetch_array($check_customer_query);
            // Check that password is good
            if(empty($passwordEncrypted) && !tep_validate_password($password, $check_customer['customers_password']))
            {
                $error = true;
            }
            else
            {
                if(SESSION_RECREATE == 'True')
                {
                    tep_session_recreate();
                }
                $check_country_query         = tep_db_query("select entry_country_id, entry_zone_id from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$check_customer['customers_id'] . "' and address_book_id = '" . (int)$check_customer['customers_default_address_id'] . "'");
                $check_country               = tep_db_fetch_array($check_country_query);
                $customer_id                 = $check_customer['customers_id'];
                $customer_default_address_id = $check_customer['customers_default_address_id'];
                $customer_first_name         = $check_customer['customers_firstname'];
                $customer_country_id         = $check_country['entry_country_id'];
                $customer_zone_id            = $check_country['entry_zone_id'];
                tep_session_register('customer_id');
                tep_session_register('customer_default_address_id');
                tep_session_register('customer_first_name');
                tep_session_register('customer_country_id');
                tep_session_register('customer_zone_id');
                tep_db_query("update " . TABLE_CUSTOMERS_INFO . " set customers_info_date_of_last_logon = now(), customers_info_number_of_logons = customers_info_number_of_logons+1 where customers_info_id = '" . (int)$customer_id . "'");
                // restore cart contents
                $cart->restore_contents();
                refresh_user_info();
                if(sizeof($navigation->snapshot) > 0)
                {
                    $origin_href = tep_href_link($navigation->snapshot['page'], tep_array_to_string($navigation->snapshot['get'], array(tep_session_name())), $navigation->snapshot['mode']);
                    $navigation->clear_snapshot();
                    tep_redirect($origin_href);
                }
                else
                {
                    tep_redirect(tep_href_link(FILENAME_DEFAULT));
                }
            }
        }
    }

}
if ($error == true)
{
    $messageStack->add('login', TEXT_LOGIN_ERROR);
}
$breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_LOGIN, '', 'SSL'));
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
    <meta name="robots" content="noindex, nofollow">
    <title><?php echo TITLE; ?></title>
    <base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
    <link rel="stylesheet" type="text/css" href="stylesheet.css">
    <script language="javascript"><!--
        function session_win() {
        window.open("<?php echo tep_href_link(FILENAME_INFO_SHOPPING_CART); ?>","info_shopping_cart","height=460,width=430,toolbar=no,statusbar=no,scrollbars=yes").focus();
        }
        //-->
    </script>
    <style>
        a.fb-logn {
            background: url("/face_bool_login_button.jpg") repeat scroll 0 0 transparent;
            color: transparent;
            display: block;
            height: 18px;
            margin: 0 0 0 30px;
            width: 134px;
        }
    </style>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
    <div id="fb-root"></div>
    <script>(function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/ru_RU/all.js#xfbml=1&appId=<?php echo FB_APP_ID?>";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
    </script>
    <?php require(DIR_WS_INCLUDES . 'header.php'); ?>
    <div class="container">
        <div class="row">
            <div class="span12">
                <a href="https://www.facebook.com/dialog/oauth?state=<?php echo $_SESSION['state']?>&client_id=<?php echo FB_APP_ID?>&redirect_uri=<?php echo urlencode(tep_href_link(FILENAME_LOGIN, 'action=remote_login', 'SSL'))?>&response_type=code&scope=email,publish_stream,publish_actions" class="fb-logn" accesskey="">&nbsp;&nbsp;&nbsp;&nbsp;</a>
                <TABLE BORDER="0" WIDTH="100%" CELLSPACING="0" CELLPADDING="0">
                    <TR>
                        <td width="100%" valign="top">
                            <?php echo tep_draw_form('login', tep_href_link(FILENAME_LOGIN, 'action=process', 'SSL')); ?>
                            <table border="0" width="100%" cellspacing="0" cellpadding="12">
                                <tr>
                                    <td>
                                        <table border="0" width="100%" cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <?php if ($messageStack->size('login') > 0):?>
                                <tr>
                                    <td><?php echo $messageStack->output('login'); ?></td>
                                </tr>
                                <tr>
                                </tr>
                                <?php endif;?>
                                <?php
                                    if ($cart->count_contents() > 0) {}
                                ?>
                                <tr>
                                    <td>
                                        <table border="0" width="100%" cellspacing="0" cellpadding="2">
                                            <?php
                                            if (PWA_ON == 'false')
                                            {
                                                require(DIR_WS_INCLUDES . FILENAME_PWA_ACC_LOGIN);
                                            }
                                            else
                                            {
                                                require(DIR_WS_INCLUDES . FILENAME_PWA_PWA_LOGIN);
                                            }
                                            ?>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </form>
                    </td>
                </TR>
            </TABLE>
        </div>
    </div>
</div>
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>