<?php
$code = time().'_'.rand();
require ("includes/application_top.php");

$server_url=((substr($_SERVER['SERVER_PROTOCOL'],0,4)=="HTTP")?"http://":"https://").$_SERVER['HTTP_HOST'];

if(!empty($_GET['returnto']))
{
    tep_session_register('returnto');
    $_SESSION['returnto'] = $returnto = $_GET['returnto'];
}

if (isset($_GET['products_id']) && !$cart -> in_cart($_GET['products_id'])) {
    $cart->add_cart($_GET['products_id']);
    if(!empty($_GET['products_id']))
    {
        $_SESSION['temp_products_id'] = $_GET['products_id'];
    }
}

if(empty($_SESSION['customer_id']))
{
    header('Location: https://www.facebook.com/dialog/oauth?state=' .
        FB_APP_ID . '&client_id=' . FB_APP_ID . '&redirect_uri=' .
        urlencode(tep_href_link(FILENAME_LOGIN, 'action=remote_login&back=publish_cart.php', 'SSL')) . '&response_type=code&scope=' . FB_APP_SCOPE);
    exit();
}

if(!empty($_REQUEST['action']) && $_REQUEST['action'] == 'callback' && !empty($_REQUEST['scode']))
{
    tep_db_query('
        UPDATE
            `' . TABLE_CUSTOMERS . '`
        SET
            `customers_basket_published` = \'yes\'
        WHERE
            `customers_id` = \'' . $_SESSION['customer_id'] . '\'
    ');

    $messageStack->add_session('top_messages', SHOPPINGCART_PUBLISHED_MESSAGE, 'regular');

    if(empty($_SESSION['returnto']))
    {
        header('Location: /shopping_cart.php'.(!empty($_SESSION['temp_products_id'])?'?products_id='.$_SESSION['temp_products_id'] : ''));
    }
    else
    {
        header('Location: ' . $_SESSION['returnto']);
        unset($_SESSION['returnto']);
        tep_session_unregister($_SESSION['returnto']);
    }

    if(!empty($_SESSION['temp_products_id']))
    {
        unset($_SESSION['temp_products_id']);
    }
    exit();
}
else
{
    //$code = $cart->save_contents();
    $saved = $cart->get_products();
    $contents = '';
    foreach($saved as $product)
    {
        $contents .= $product['name'] . ' x ' . $product['quantity']."<center> </center>";
    }

    $url = 'http://www.facebook.com/dialog/feed?'.
        'app_id=' . FB_APP_ID . '&' .
        'link=' . urlencode($server_url . '/cart/' . $_SESSION['customer_id']) . '&' .
        'picture=' . urlencode($server_url . '/favicon.ico') .'&' .
        'name=' . urlencode(SHOPPINGCART_PUBLISH_MESSAGE) . '&' .
        'caption=' . urlencode(' ') . '&' .
        'description=' . urlencode($contents) . '&' .
        'redirect_uri=' . urlencode($server_url.'/publish_cart.php?action=callback&scode=' . $code);
    //var_dump($server_url.'/publish_cart.php?action=callback&scode=' . $code);exit();
    header('Location: ' . addslashes($url));
    exit();
}