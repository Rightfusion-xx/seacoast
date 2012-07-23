<?php
require ("includes/application_top.php");
$server_url=((substr($_SERVER['SERVER_PROTOCOL'],0,4)=="HTTP")?"http://":"https://").$_SERVER['HTTP_HOST'];


if(empty($_SESSION['customer_id']))
{
    header('Location: https://www.facebook.com/dialog/oauth?state=' .
        FB_APP_ID . '&client_id=' . FB_APP_ID . '&redirect_uri=' .
        urlencode(tep_href_link(FILENAME_LOGIN, 'action=remote_login&back=publish_cart.php', 'SSL')) . '&response_type=code&scope=' . FB_APP_SCOPE);
    exit();
}

if(!empty($_REQUEST['action']) && $_REQUEST['action'] == 'callback' && !empty($_REQUEST['scode']))
{
    $code = $_REQUEST['scode'];
    $saved = $cart->get_saved_contents($code);
    if(!empty($saved))
    {
        tep_db_query('
            UPDATE
                `' . TABLE_CUSTOMERS . '`
            SET
                `customers_basket_published` = \'yes\'
            WHERE
                `customers_id` = \'' . $_SESSION['customer_id'] . '\'
        ');
    }
    echo '<script type="text/javascript">window.opener.location.reload();window.close();</script>';
    exit();

}
else
{
    $code = $cart->save_contents();
    $saved = $cart->get_saved_contents($code);
    $contents = '';
    foreach($saved['products'] as $product)
    {
        $contents .= $product['name'] . ' x ' . $product['quantity']."<center> </center>";
    }

    $url = 'http://www.facebook.com/dialog/feed?'.
        'app_id=' . FB_APP_ID . '&' .
        'link=' . urlencode($server_url . '/cart/' . $code) . '&' .
        'picture=' . urlencode($server_url . '/favicon.ico') .'&' .
        'name=' . urlencode('I just got free shipping on my Seacoast Vitamins, and you can, too! Here\'s my shopping cart!') . '&' .
        'caption=' . urlencode(' ') . '&' .
        'description=' . urlencode($contents) . '&' .
        'redirect_uri=' . urlencode($server_url.'/publish_cart.php?action=callback&scode=' . $code);
    header('Location: ' . addslashes($url));
    exit();
}