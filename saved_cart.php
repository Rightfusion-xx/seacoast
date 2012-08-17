<?php
$hide_cart = true;
require ("includes/application_top.php");
require(DIR_WS_CLASSES . 'http_client.php');
require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_SHOPPING_CART);

$owner = $_REQUEST['cart'];

$owner = tep_db_fetch_array(tep_db_query('
    SELECT
        *
    FROM `' . TABLE_CUSTOMERS . '`
    WHERE `customers_id` = ' . $owner . '
'));

if(!$owner)
{
    header('Location: 404.php');
    exit();
}
$products = $cart->get_customer_cart($owner['customers_id']);
$history_query_raw = "
    SELECT DISTINCT
        tp.*,
        tpd.products_name
    FROM `" . TABLE_ORDERS_PRODUCTS . "` AS `top`
    INNER JOIN `" . TABLE_ORDERS . "`  AS `to` ON (`to`.`orders_id` = top.orders_id)
    INNER JOIN `" . TABLE_PRODUCTS . "` AS `tp` ON (tp.products_id = top.products_id)
    INNER JOIN `" . TABLE_PRODUCTS_DESCRIPTION . "` AS `tpd` ON (tp.products_id = tpd.products_id AND tpd.language_id = '1')
    WHERE to.customers_id = '" . $owner['customers_id'] . "'
    LIMIT 0, 20
";

$other_products = tep_db_query($history_query_raw);

$breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_SHOPPING_CART));
$psavings = $cart -> show_total() > 0 ? number_format($cart -> show_potential_savings() / $cart -> show_total() * 100, 0) : 0;
?>
<!doctype html>
<html <?php echo HTML_PARAMS;?>>
<head>
    <link rel="stylesheet" href="/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/bootstrap/css/bootstrap-responsive.min.css">
    <link href="/css/main.css" rel="stylesheet">
    <link href="/font/fonts.css" rel="stylesheet">
    <!--[if lt IE 9]>
    <script type="text/javascript" src="/jquery/respond.src.js"></script>
    <![endif]-->
    <link rel="icon" type="image/png" href="/favicon.ico">
    <meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET;?>">
    <meta name="robots" content="index, follow">
    <title><?php echo TITLE;?></title>
    <base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
    <link rel="stylesheet" type="text/css" href="stylesheet.css">
    <?php require('includes/form_check.js.php'); ?>
    <script type="text/javascript" src="/jquery/js/jquery-1.3.2.min.js"></script>
    <script src="//connect.facebook.net/en_US/all.js"></script>
    <script type="text/javascript">(function(d, s, id){
        var js, fjs = d.getElementsByTagName(s)[0];
        if(d.getElementById(id)) return;
        js = d.createElement(s);
        js.id = id;
        js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=<?php echo FB_APP_ID?>";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
    FB.init({appId: "<?php echo FB_APP_ID?>", status: true, cookie: true});
    </script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
    <!-- ClickTale Top part -->
    <script type="text/javascript">
        var WRInitTime = (new Date()).getTime();
    </script>
    <!-- ClickTale end of Top part -->
    <!-- header //-->
    <?php
    require (DIR_WS_INCLUDES . 'header.php');
    ?>
    <!-- header_eof //-->
    <?php echo tep_draw_form('cart_quantity', tep_href_link(FILENAME_SHOPPING_CART, '', 'SSL'), 'post'); ?>
    <div class="container">
        <div class="row">
            <div class="span12">
                <h1>
                    <?php echo $owner['customers_firstname']?>'s Shopping Cart
                </h1>
                    <?php
                    for ($i = 0, $n = sizeof($products); $i < $n; $i++) {
                        $info_box_contents[] = array();
                        $cur_row = sizeof($info_box_contents) - 1;
                        $products_name = '' . '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products[$i]['id']) . '"><b>' . $products[$i]['name'] . '</b></a>';
                        if (isset($products[$i]['attributes']) && is_array($products[$i]['attributes']))
                        {
                            reset($products[$i]['attributes']);
                            while (list($option, $value) = each($products[$i]['attributes']))
                            {
                                $products_name .= '<br><small><i> - ' . $products[$i][$option]['products_options_name'] . ' ' . $products[$i][$option]['products_options_values_name'] . '</i></small>';
                            }
                        }
                        $products_name .= '';
                        $info_box_contents[$cur_row][] = array('params' => 'class="productListing-data"', 'text' => $products_name);
                        $info_box_contents[$cur_row][] = array(
                            'align' => 'right',
                            'params' => 'class="productListing-data" valign="top"',
                            'text' => $products[$i]['id'] == CM_FTPID ? '&nbsp;' : '<b><s>' . $currencies
                                ->display_price(
                                ($products[$i]['final_price'] > $products[$i]['msrp'])?$products[$i]['final_price']:$products[$i]['msrp'],
                                tep_get_tax_rate($products[$i]['tax_class_id']),
                                $products[$i]['quantity']
                            ) . '</s></b>'
                        );
                        $info_box_contents[$cur_row][] = array(
                            'align' => 'right',
                            'params' => 'class="productListing-data" valign="top"',
                            'text' => $products[$i]['id'] == CM_FTPID ? '&nbsp;' : '<b>' . $currencies->display_price($products[$i]['final_price'], tep_get_tax_rate($products[$i]['tax_class_id']), $products[$i]['quantity']) . '</b>'
                        );
                        $info_box_contents[$cur_row][] = array(
                            'align' => 'right',
                            'params' => 'class="productListing-data" valign="top"',
                            'text' => $products[$i]['id'] == CM_FTPID ? '&nbsp;' : '<b>' . $currencies->display_price(
                            //$products[$i]['savings'],
                                (($products[$i]['final_price'] > $products[$i]['msrp']) ? $products[$i]['final_price'] : $products[$i]['msrp']) - $products[$i]['final_price'],
                                tep_get_tax_rate($products[$i]['tax_class_id']),
                                $products[$i]['quantity']
                            ) . '</b>'
                        );
                    }

                    // shopping cart table starts here (AH 07 Feb 2012)

                    if(count($products) > 0)
                    {
                        $pl =  '<table style="border-top:1px solid lightgray" class="table table-striped">';
                        $pl .= '<thead>';
                        $pl .= '<th>Product Name</th>';
                        $pl .= '<th style="width:75px;">MSRP</th>';
                        $pl .= '<th style="width:75px;">Your Price</th>';
                        $pl .= '<th style="width:75px;">Savings</th>';
                        $pl .= '</thead>';
                        $pl .= '<tbody>';
                        for ($i=1; $i<count($info_box_contents); $i++)
                        {
                            $pl .= '<tr>';

                            for ($j=0; $j<count($info_box_contents[$i]); $j++)
                            {
                                //if (($i % 2) == 1) { $pl .= '<td style="background-color:#F9F9F9">'. $info_box_contents[$i][$j][text]. '</td>'; }
                                //else {
                                $pl .= '<td>'. $info_box_contents[$i][$j][text]. '</td>';
                                //}
                            }
                            $pl .= '</tr>';
                        }
                        $pl .= '</tbody>';
                        $pl .= '</table>';
                    }
                    else
                    {
                        $pl = '
                            <table style="border-top:1px solid lightgray" class="table table-striped">
                                <tr>
                                    <td style="text-align: center;padding:10px;"><h3>Shopping Cart is empty</h3></td>
                                </tr>
                            </table>
                        ';
                    }

                    echo $pl;?>
                <?php if(tep_db_num_rows($other_products)): ?>
                <div>
                    <h1>
                        <?php echo $owner['customers_firstname']?>'s Vitamin Cabinet
                    </h1>
                    <table style="border-top:1px solid lightgray" class="table table-striped">
                        <thead>
                            <th>Product Name</th>
                            <th>MSRP</th>
                            <th>Your Price</th>
                            <th>Savings</th>
                        </thead>
                        <tbody>
                            <?php while($row = tep_db_fetch_array($other_products)):?>
                            <?php if($row['products_id'] == CM_FTPID) continue;?>
                            <tr>
                                <td>
                                    <a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $row['products_id'])?>">
                                        <b>
                                            <?php echo $row['products_name'] ?>
                                        </b>
                                    </a>
                                </td>
                                <td  style="width:75px;">
                                    <s><?php
                                    echo ($row['products_id'] == CM_FTPID ? '&nbsp;' : '<b>' .
                                        $currencies->display_price(($row['products_price'] > $row['products_msrp'])?$row['products_price']:$row['products_msrp'], tep_get_tax_rate($row['products_tax_class_id']), 1) . '</b>')
                                    ?></s>
                                </td>
                                <td  style="width:75px;">
                                    <?php
                                    echo ($row['products_id'] == CM_FTPID ? '&nbsp;' : '<b>' .
                                        $currencies->display_price($row['products_price'], tep_get_tax_rate($row['products_tax_class_id']), 1) . '</b>')
                                    ?>
                                </td>
                                <td  style="width:75px;">
                                    <?php
                                    echo ($row['products_id'] == CM_FTPID ? '&nbsp;' : '<b>' .
                                        $currencies->display_price(
                                            (($row['products_price'] > $row['products_msrp']) ? $row['products_price'] : $row['products_msrp']) - $row['products_price'],
                                            tep_get_tax_rate($row['products_tax_class_id']),
                                            1
                                        ) . '</b>')
                                    ?>
                                </td>
                            </tr>
                            <?php endwhile?>
                        </tbody>
                    </table>
                </div>
                <?php endif;?>
            </div>
        </div>
        <div id="facebook-comments" class="fb-comments" data-href="http://<?php echo $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']?>" data-num-posts="4" ></div>
        <script type="text/javascript">
            (function(){
                function initCommentSize()
                {
                    var iframe = $("#facebook-comments iframe").get(0);
                    if(typeof(iframe) == "undefined")
                    {
                        setTimeout(function(){initCommentSize()}, 100);
                        return false;
                    }
                    $(iframe).css('width', '100%');
                    $(iframe.parentNode).css('width', '100%');
                    $(iframe.parentNode.parentNode).css('width', '100%');
                }

                initCommentSize();
            })()
        </script>
    </div>
<?php
require (DIR_WS_INCLUDES . 'footer.php');
?>
    <br>
    <script>
        if( typeof (urchinTracker) != 'function')
            document.write('<sc' + 'ript src="' + 'http' + (document.location.protocol == 'https:' ? 's://ssl' : '://www') + '.google-analytics.com/urchin.js' + '"></sc' + 'ript>')

    </script>
    <script>
        _uacct = 'UA-889784-4';
        urchinTracker("/3296973491/goal");

    </script>
    <!-- Google Code for Add to Shopping Cart Conversion Page -->
    <script language="JavaScript" type="text/javascript">
        <!--
        var google_conversion_id = 1064963330;
        var google_conversion_language = "en_US";
        var google_conversion_format = "1";
        var google_conversion_color = "ffffff";
        if(0.0) {
            var google_conversion_value = 0.0;
        }
        var google_conversion_label = "AC7MCM6IYRCCmuj7Aw";
        //-->
    </script>
    <script language="JavaScript" src="https://www.googleadservices.com/pagead/conversion.js"></script>
<noscript>
    <img height="1" width="1" border="0" src="https://www.googleadservices.com/pagead/conversion/1064963330/?value=0.0&amp;label=AC7MCM6IYRCCmuj7Aw&amp;script=0"/>
</noscript>
<script>
    if( typeof (urchinTracker) != 'function')
        document.write('<sc' + 'ript src="' + 'http' + (document.location.protocol == 'https:' ? 's://ssl' : '://www') + '.google-analytics.com/urchin.js' + '"></sc' + 'ript>')

</script>
<script>
    _uacct = 'UA-889784-4';
    urchinTracker("/2072501237/test");

</script>
<!-- Google Website Optimizer Conversion Script -->
<script type="text/javascript">
    if( typeof (_gat) != 'object')
        document.write('<sc' + 'ript src="http' + (document.location.protocol == 'https:' ? 's://ssl' : '://www') + '.google-analytics.com/ga.js"></sc' + 'ript>')

</script>
<script type="text/javascript">
    try {
        var gwoTracker = _gat._getTracker("UA-207538-3");
        gwoTracker._trackPageview("/0386199624/goal");
    } catch(err) {
    }
</script>
<!-- End of Google Website Optimizer Conversion Script -->

</body>
</html>
<?php
require (DIR_WS_INCLUDES . 'application_bottom.php');
?>
