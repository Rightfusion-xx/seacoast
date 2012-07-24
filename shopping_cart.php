<?php
$hide_cart = true;
require ("includes/application_top.php");


require(DIR_WS_CLASSES . 'http_client.php');
require(DIR_WS_CLASSES . 'geo_locator.php');
require(DIR_WS_CLASSES . 'shipping.php');
require(DIR_WS_CLASSES . 'order_total.php');
require(DIR_WS_CLASSES . 'order.php');
require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_SHOPPING_CART);

$breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_SHOPPING_CART));
$psavings = $cart -> show_total() > 0 ? number_format($cart -> show_potential_savings() / $cart -> show_total() * 100, 0) : 0;

// if no shipping destination address was selected, use the customers own address as default
if (!tep_session_is_registered('sendto'))
{
    tep_session_register('sendto');
    $sendto = $_SESSION['customer_default_address_id'];
}
else
{
    // verify the selected shipping address
    $check_address_query = tep_db_query("select count(*) as total from " . TABLE_ADDRESS_BOOK . " where customers_id = '" .
                                    (int)$customer_id . "' and address_book_id = '" . (int)$sendto . "'");
    $check_address = tep_db_fetch_array($check_address_query);

    if ($check_address['total'] != '1')
    {
        $sendto = $_SESSION['customer_default_address_id'];
        if (tep_session_is_registered('shipping')) tep_session_unregister('shipping');
    }
}
tep_session_unregister('payment');
$order = new order;

global $total_weight;
$locator = new geo_locator();

function populateDeliveryFields($cCode)
{
    global $order,$locator;

    $country_info = $locator->getCountryInfoFromDb($cCode);

    $order->delivery['country']['iso_code_2'] = $cCode;
    $order->delivery['country']['id'] = $country_info['countries_id'];
    $order->delivery['country_id'] = $country_info['countries_id'];
    $order->delivery['country']['title'] = $country_info['countries_name'];
}

if (!tep_session_is_registered('customer_id')) // NOT logged in
{
    // USA   Sample Ip: 65.65.219.98
    // Spain Sample Ip: 79.159.137.155
    $requestIp = $_SERVER [ 'REMOTE_ADDR' ];
    $cCode = $locator->locate($requestIp);

    if ($locator->isValid($cCode))
    {
        populateDeliveryFields($cCode);
    }
}

if (isset($_SESSION['country']))
{
    populateDeliveryFields($_SESSION['country']);

    if (($_SESSION[country] == 'US') && isset($_SESSION[postcode]))
    {
        $order->delivery['postcode'] = $_SESSION[postcode];
    }
}
else
{ // IF LOGGED IN, DELIVERY ADDRESS WILL BE POPULATED AUTOMATICALLY THROUGH ORDER CONSTRUCTOR
}

$total_weight = $cart->show_weight();

$shipping_module = new shipping();
$cheapestShippingRate = $shipping_module->getCheapestRate();

?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS;?>>
	<head>
            <link rel="icon" type="image/png" href="/favicon.ico">
            <meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET;?>">
            <meta name="robots" content="index, follow">
            <title><?php echo TITLE;?></title>
            <base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
            <link rel="stylesheet" type="text/css" href="stylesheet.css">
            <?php require('includes/form_check.js.php'); ?>
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


			<?php

			if (isset($_GET['products_id']) && !$cart -> in_cart($_GET['products_id'])) {
                $cart->add_cart($_GET['products_id']);
			}


                        ?>
<?php
	// the second condition "$cart->in_cart" is to cover a situation where user might enter
	// a random number for products_id query string. (AH 30 January 2012)
	if (isset($_GET['products_id']) && $cart->in_cart($_GET['products_id']) && strlen($_POST['qty'])<1)
	{

                $product_info_query = tep_db_query("select pd.products_target_keyword, p.products_keywords, p.products_die, p.products_sku, p.products_upc,
                p.products_dieqty, pd.products_head_title_tag, pd.products_head_keywords_tag,
                pd.products_head_desc_tag, pd.products_type,
                pd.products_departments,pd.products_ailments,pd.products_uses,
                p.products_weight, p.products_ordered, pd.products_head_keywords_tag,
                pd.products_viewed, date_format(p.products_date_added,'%m/%d/%Y') as
                products_date_added, p.products_last_modified,
                p.products_id, pd.products_name, pd.products_description, p.products_model,
                p.products_quantity, p.products_image, pd.products_url, p.products_msrp,
                p.products_price, p.products_tax_class_id, p.products_date_available,
                p.manufacturers_id, m.manufacturers_name, pd.products_takeaway
                from " . TABLE_PRODUCTS . " p join  " . TABLE_PRODUCTS_DESCRIPTION . " pd on
                p.products_id=pd.products_id join ". TABLE_MANUFACTURERS ." m on m.manufacturers_id=p.manufacturers_id
                where p.products_status = '1' and p.products_id = '" . (int)$_REQUEST['products_id'] .
            "' and pd.language_id =' " . (int)$languages_id . "'");


            if(!($product_info = tep_db_fetch_array($product_info_query))){
            //No product found, redirect.
            redir301(HTTP_SERVER);
        }

         $is_cm_eligible=strpos($product_info['products_name'],'*') ? 0 : 1;

    //Get price
    //check for product specials
    $new_price = tep_get_products_special_price($product_info['products_id']);

    //Get product name
    $products_name = $product_info['products_name'];

    //Get price
    if ($new_price != '')
        { $price=($new_price);}
          else
          { $price=$product_info['products_price'];}

    //Calculate membership discounts
    if($product_info['manufacturers_id']==69)
    {
        $cm_price=$price*.75; //25% Off
    }
    elseif(!strpos($product_info['products_name'],'*'))
    {
        $cm_price=$price*.85; //15% Off
    }
    else {
        $cm_price=$price;
    }




		?>

                                <div class="container">
        <div class="row">
        <div class="span12">
            <table border="0" cellspacing="0" cellpadding="12" width="100%">
                <tr>
                    <td valign="top" width="700">
        <a href="/product_info.php?products_id=<?php echo $product_info['products_id'];?>"><?php echo '<h1>'.$product_info['products_name'] .'</h1>'; ?>   </a>

		<table border="0" width="100%" cellspacing="1" cellpadding="2">
			<tr>
				<td style="padding-bottom:5px;">
				 <?php
				$productDetailHtml = '<div style="padding:5px 0 5px 0">';
				if (strlen($product_info['products_image']) > 0) {
					$productDetailHtml .= '<img title="' . $product_info['products_name'] . '"';
					$productDetailHtml .= 'src="' . DIR_WS_IMAGES . $product_info['products_image'] . '" style="margin:5px; "';
					$productDetailHtml .= 'ALIGN="left" />';
				}
				$productDetailHtml .= '</div>';
				echo $productDetailHtml;
				?>
				</td>
                <td>
                <div id="item_details" style="text-align:left;margin:1em;">




                <?php if($product_info['products_die'] && $product_info['products_dieqty']<1){?>
                    <p><?php echo $product_info['products_name'];?> is not available from Seacoast Vitamins at this time. We recommend the following alternatives, below.</p>
                <?php }else{?> <span style="margin-left:1em";>
                    <form method="post" action="/shopping_cart.php" >
                    <input type="hidden" name="action" value="add_product">
                    <input type="hidden" name="products_id" value="<?php echo $_REQUEST['products_id']; ?>">
                    <b>Quantity:</b>
                    <select name="qty">
                    <?php for($index=1;$index<=30;$index++){?>
                        <option value="<?php echo $index; ?>"><?php echo $index; ?></option>
                    <?php }?>
                    </select>
                    <br/>
                    <?php if(!$product_info['products_die'] && $new_price){?>
                        <span style="font-size:8pt;color:#FF0000;font-weight:bold;">Hurry! On sale while supplies last!</span>
                    <?php }?>
                    <?php if($product_info['products_die']){?>
                        <span style="font-size:8pt;color:#FF0000;font-weight:bold;">Hurry! Only <?php echo $product_info['products_dieqty']?> left at this price.</span>
                    <?php } ?>
                    <input type="hidden" name="products_id" value="<?php echo $product_info['products_id'];?>">
                    <br/>
                    <div style="margin-left:1em;float:left;border:dashed 1px #dddddd;padding-left:1em;padding-right:1em;">
                        <div><b>Choose Price</b><br/>
                            <input type="submit" class="btn btn-primary" id="button_price" value="<?php echo $currencies->display_price($cm_price, tep_get_tax_rate($product_info['products_tax_class_id'])); ?>*" style="width:200px;height:30px;color:#fff;font-weight:bold;font-size:18pt;">
                        </div>
                        <?php if($is_cm_eligible){ ?>
                        <?php if(!$_SESSION['cm_is_member']){ ?>
                            <script type="text/javascript">
                            function toggle_price(show_discount){
                                if(show_discount){
                                    document.getElementById('button_price').value='<?php echo $currencies->display_price($cm_price, tep_get_tax_rate($product_info['products_tax_class_id'])); ?>*';
                                    document.getElementById('button_price').style.color='#fff';
                                    document.getElementById('button_price').style.fontWeight='bold';
                                    document.getElementById('cm_price_disclaimer').style.display='block';
                                    document.getElementById('extra_savings').style.display='none';
                                }else{
                                    document.getElementById('button_price').value='<?php echo $currencies->display_price($price, tep_get_tax_rate($product_info['products_tax_class_id'])); ?>';
                                    document.getElementById('button_price').style.color='#fff';
                                    document.getElementById('button_price').style.fontWeight='normal';
                                    document.getElementById('cm_price_disclaimer').style.display='none';
                                    document.getElementById('extra_savings').style.display='inline';
                                }
                            }
                            </script>
                            <input type="checkbox" name="cm_freetrial" value="true" checked onclick="toggle_price(this.checked);"/> Yes! I want Direct-to-Member prices.<br/>My membership is FREE for 14-days.
                            <span id="extra_savings" style="display:none;"><br/><span style="color:#ff0000;font-weight:bold;">Save an extra <?php echo number_format(($price-$cm_price)/$price*100,0) ?>% plus
                            </span></span>
                            <br/>I'll get:
                            <ul style="font-weight:bold;margin-left:3em;">
                                <li>Member prices, cheap shipping</li>
                                <li>Side Effects Protection; <br/><i style="font-weight:normal;">Return opened product</i></li>
                            </ul>
                        <?php } ?>

                        <div id="cm_price_disclaimer"><i>* Seacoast Vitamins-Direct price shown.</i><br/>
                        <?php if(!$_SESSION['cm_is_member']){ ?><a href="/community/" target="_blank" rel="nofollow">Learn more.</a><?php } ?>
                        </div>
                        <?php } ?>
                    </div>
                 <?php } ?>  <br style="clear:both"/></span></form>

             </div>



                </td>
                <td>
                <div style="border:1px solid #665;padding:1em;margin:0px;text-align:center;">
                        <p>
                            <b style="color:#665">Risk Free</b>
                            <br/>
                            <div style="white-space:nowrap;"><img src="/images/quality.jpg" style="width:75px;"/><b style="color:#666666">
                                <br/>
                                120% Guarantee</b>  <br/><br/>
                            </div>
                        </p><img src="http://www.shopping.com/merchant_logo?ID=406477" width="130" border="0"alt="Great Store!" />
                        <br/>
                        <!-- BEGIN: BizRate Medal (125x73 pixels) -->
                        <img  style="margin-top:3em;" src="http://medals.bizrate.com/medals/dynamic/162489_medal.gif" alt="BizRate Customer Certified (GOLD) Site" width="125" height="73" align="top" border="0" >
                        <!-- END: BizRate Medal (125x73 pixels) -->
                    </div>
                </td>
			</tr>

		</table> </td></tr></table>
        </div> </div></div>






            <?php }else{

            echo tep_draw_form('cart_quantity', tep_href_link(FILENAME_SHOPPING_CART, '', 'SSL'), 'post');
            ?>
<div class="container">
<div class="row">
<div class="span12">
<h1>Seacoast Vitamins-Direct Savings</h1>


        <table style="margin-top: 30px;" border="0" width="100%" cellspacing="0" cellpadding="2">

        <tr>
            <td colspan="5">
            <?php
            $info_box_contents = array();
            $info_box_contents[0][] = array('align' => 'center', 'params' => 'style="font-weight:bold;"', 'text' => TABLE_HEADING_REMOVE);
            $info_box_contents[0][] = array('params' => 'style="font-weight:bold;"', 'text' => TABLE_HEADING_PRODUCTS);
            $info_box_contents[0][] = array('align' => 'center', 'params' => 'style="font-weight:bold;"', 'text' => TABLE_HEADING_QUANTITY);
            $info_box_contents[0][] = array('align' => 'right', 'params' => 'style="font-weight:bold;"', 'text' => TABLE_HEADING_TOTAL);
            $info_box_contents[0][] = array('align' => 'right', 'params' => 'style="font-weight:bold;"', 'text' => 'Savings');
            $any_out_of_stock = 0;
            $products = $cart -> get_products();
            for ($i = 0, $n = sizeof($products); $i < $n; $i++) {

            // Push all attributes information in an array
            if (isset($products[$i]['attributes']) && is_array($products[$i]['attributes'])) {
                    while (list($option, $value) = each($products[$i]['attributes'])) {
                            echo tep_draw_hidden_field('id[' . $products[$i]['id'] . '][' . $option . ']', $value);
        $attributes = tep_db_query("select popt.products_options_name, poval.products_options_values_name, pa.options_values_price, pa.price_prefix
        from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa
        where pa.products_id = '" . $products[$i]['id'] . "'
        and pa.options_id = '" . $option . "'
        and pa.options_id = popt.products_options_id
        and pa.options_values_id = '" . $value . "'
        and pa.options_values_id = poval.products_options_values_id
        and popt.language_id = '" . $languages_id . "'
        and poval.language_id = '" . $languages_id . "'");
                        $attributes_values = tep_db_fetch_array($attributes);

                        $products[$i][$option]['products_options_name'] = $attributes_values['products_options_name'];
                        $products[$i][$option]['options_values_id'] = $value;
                        $products[$i][$option]['products_options_values_name'] = $attributes_values['products_options_values_name'];
                        $products[$i][$option]['options_values_price'] = $attributes_values['options_values_price'];
                        $products[$i][$option]['price_prefix'] = $attributes_values['price_prefix'];
                }
            }
        }

        for ($i = 0, $n = sizeof($products); $i < $n; $i++) {

                $info_box_contents[] = array();

                $cur_row = sizeof($info_box_contents) - 1;
                $info_box_contents[$cur_row][] = array('align' => 'center', 'params' => 'class="productListing-data" valign="top"',
                                        'text' => tep_draw_checkbox_field('cart_delete[]', $products[$i]['id']));
                $products_name = '' . '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products[$i]['id']) . '"><b>' .
                                    $products[$i]['name'] . '</b></a>';
                if (STOCK_CHECK == 'true') {
                        $stock_check = tep_check_stock($products[$i]['id'], $products[$i]['quantity']);
                        if (tep_not_null($stock_check)) {
                                $any_out_of_stock = 1;
                                $products_name .= $stock_check;
                        }
                }

                if (isset($products[$i]['attributes']) && is_array($products[$i]['attributes'])) {
                        reset($products[$i]['attributes']);
                        while (list($option, $value) = each($products[$i]['attributes'])) {
                                $products_name .= '<br><small><i> - ' . $products[$i][$option]['products_options_name'] . ' ' . $products[$i][$option]['products_options_values_name'] . '</i></small>';
                        }
                }
                $products_name .= '';

                $info_box_contents[$cur_row][] = array('params' => 'class="productListing-data"', 'text' => $products_name);
                $info_box_contents[$cur_row][] = array('align' => 'center', 'params' => 'class="productListing-data" valign="top"', 'text' => $products[$i]['id'] == CM_FTPID ? tep_draw_hidden_field('products_id[]', $products[$i]['id']) . '&nbsp;' : tep_draw_input_field('cart_quantity[]', $products[$i]['quantity'], 'size="4"') .
                        tep_draw_hidden_field('products_id[]', $products[$i]['id']));

                $info_box_contents[$cur_row][] = array('align' => 'right', 'params' => 'class="productListing-data" valign="top"', 'text' => $products[$i]['id'] == CM_FTPID ? '&nbsp;' : '<b>' . $currencies -> display_price($products[$i]['final_price'], tep_get_tax_rate($products[$i]['tax_class_id']), $products[$i]['quantity']) . '</b>');
                $info_box_contents[$cur_row][] = array('align' => 'right', 'params' => 'class="productListing-data" valign="top"', 'text' => $products[$i]['id'] == CM_FTPID ? '&nbsp;' : '<b>' . $currencies -> display_price($products[$i]['savings'], tep_get_tax_rate($products[$i]['tax_class_id']), $products[$i]['quantity']) . '</b>');
        }

        // shopping cart table starts here (AH 07 Feb 2012)
        $pl =  '<table style="border-top:1px solid lightgray" class="table table-striped">';
        $pl .= '<thead>';
        $pl .= '<th>Remove</th>';
        $pl .= '<th>Product Name</th>';
        $pl .= '<th>Quantity</th>';
        $pl .= '<th>Total</th>';
        $pl .= '<th>Savings</th>';
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
        $pl.='<tr><td colspan="2">
                <input type="hidden" name="action" value="update_product" />
                <input type="submit" value="Remove Selected">
                </td><td><input type="submit" value="Update Quantity" /></td><td></td><td></td></tr>';
        $pl .= '</tbody>';
        $pl .= '</table>';

        echo $pl;

        // shopping cart table ends

                                ?>
            </td>
                                </tr>
                                </table>
                                </div>
                                </div>

<div class="row">
    <div class="span12">
     <?php if($cart->in_cart(CM_FTPID) || (!$_SESSION['cm_is_member']) && $psavings>0){
                                        ?>

                                        <div style="padding:1em 0 1em 0;">
                                                <b class="spiffy"> <b class="spiffy1"><b></b></b> <b class="spiffy2"><b></b></b> <b class="spiffy3"></b> <b class="spiffy4"></b> <b class="spiffy5"></b></b>
                                                <div class="spiffyfg" style="color:#ff0000;padding:20px;text-align: left;">
                                                        <?php if($cart->in_cart(CM_FTPID)){
                                                        ?>
                                                        <b>Direct-to-member prices:</b> You are now using a FREE 14-Day Seacoast Vitamins-Direct trial membership. <a href="/" style="color:#ff0000;"><b>Shop Now</b></a> to
                                                        receive member only prices on your entire order. <a href="/community/" style="color:#ff0000">Learn more...</a>
                                                        <?php }elseif ($psavings>0){?>
                                                        <b>Save <?php echo $psavings;?>% Now</b>: Join Seacoast Vitamins-Direct FREE for 14-Days. <a style="color:#ff0000;font-weight:bold;" href="/shopping_cart.php?action=buy_now&products_id=<?php echo CM_FTPID ?>">Start Now</a> or <a href="/community/" style="color:#ff0000">Learn more...</a>

<?php }?>
                                                </div>

                                        </div><?php }?>


    </div>
</div>

<div class="row" style="padding-top:20px;padding-bottom:40px;">
    <div class="span6">
        <?php echo '<a style="float:left;" class="btn btn-primary" title="Checkout Now" href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL') . '"><i class="icon-play icon-white">&nbsp;</i>&nbsp;Checkout Now</a>';?>
        <a style="margin-left:10px;" class="btn btn-primary" target="_blank" href="/publish_cart.php">Publish to facebook</a>
    </div>
    <div class="span6">
    <?php if($cart->show_savings()>0) {
                                        ?><b>Member Only Savings: <span style="color:#ff0000;background:yellow;">$<?php echo number_format($cart -> show_savings(), 2);?></span></b>
                                        <br/>
                                        <?php }?>
                                        <?php echo SUB_TITLE_SUB_TOTAL;?>
                                        <?php echo $currencies -> format($cart -> show_total());?>

    </div>
</div>







                                <div class="row ">

                                <div class="span12">
                                <div class="alert alert-info">
                                        <?php
                                                if (!tep_session_is_registered('customer_id'))
                                                {
                                                echo '<b>Shipping Estimator</b> - Select country to calculate shipping:<br />';
                                                echo tep_get_country_list_with_iso_code("country", $order->delivery[country][iso_code_2],
                                                                "onChange='return submitForm(this.form);'", !tep_session_is_registered('customer_id'));
                                                }
                                                else {
                                                    echo 'Your Location: <br /><b>'.$order->delivery[city].', '.$order->delivery[country][title].'</b>';
                                                }
                                                ?>
                                                <script type="text/javascript">

                                                    function submitForm(f,actionText) {
                                                        //var f = list.form;
                                                        if (actionText) {
                                                            f.action = actionText;
                                                        }
                                                        f.submit();
                                                        return true;
                                                    }

                                                </script>


                                                <?php

                                                if (!tep_session_is_registered('customer_id'))
                                                {
                                                    if ((isset($_SESSION['country']) && ($_SESSION['country'] == "US")) ||
                                                            ($order->delivery['country']['iso_code_2'] == "US"))
                                                    {
                                                            echo 'Enter zip code: <br />';
                                                            echo tep_draw_input_field('postcode', $order->delivery['postcode'],'style="width:80px;"');
                                                            echo tep_draw_input_field('postcode_btn','Calculate Shipping',
                                                                    "style='margin-left:10px;font-weight:bold;' onClick='return submitForm(this.form);'",'submit');
                                                    }
                                                }
                                                ?>


                                        <?php
                                            function getLowestShippingCost()
                                            {
                                                global $cheapestShippingRate;
                                                return number_format($cheapestShippingRate, 2);
                                            }

                                            if($cart->show_total()>=25 &&
                                                defined('MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING') &&
                                                (MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING == 'true'))
                                            {
                                                echo 'Ships FREE<br/>(US Lower 48)';
                                            }
                                            elseif (MODULE_SHIPPING_FLAT_STATUS=='True')
                                            {
                                                echo 'Shipping: $4.95 flat rate<br/>(US Lower 48)';
                                            }
                                            else
                                            {
                                                if ($cart->count_contents() > 0 && getLowestShippingCost() > 0
                                                   )
                                                {
                                                    $costStr =  '<p>Shipping: <b style="background:yellow;">';
                                                    $costStr .= '$ ' . getLowestShippingCost();
                                                    $costStr .= '</b></p>';
                                                    echo $costStr;
                                                }
                                            }
                                         ?>
                                         <?php echo '<br/><a class="btn btn-primary" title="Checkout Now" href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL') . '"><i class="icon-play icon-white">&nbsp;</i>&nbsp;Checkout Now</a><BR>';?>


    <p><b>Need international or expedited shipping? Continue to checkout for real-time rates. </b></p></div>
    </div>
    </div>


</div>
</div>
<script type="text/javascript">
function updateProd(obj, actionValue) {
    var f = document.forms(obj);

    //var f = list.form;
    if (actionValue) {
    f.action = '?action=' + actionValue;
    }
    f.submit();
    return true;
}
</script>


			<?php
			if ((USE_CACHE == 'true') && empty($SID)) {
				echo tep_cache_also_purchased(3600);
			} elseif (isset($_REQUEST['products_id'])) {
				$products_query = tep_db_query("select products_name, m.manufacturers_name, m.manufacturers_id, products_head_keywords_tag from " . TABLE_PRODUCTS_DESCRIPTION . " pd JOIN " . TABLE_PRODUCTS . " p ON p.products_id = pd.products_id JOIN " . TABLE_MANUFACTURERS . " m
on m.manufacturers_id = p.manufacturers_id where p.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "'");
				$product_info = tep_db_fetch_array($products_query);
				$tags_array['keywords'] = $product_info['products_head_keywords_tag'];

				include (DIR_WS_MODULES . 'also_purchased_products.php');
			}
			?>
			<?php if(isset($_REQUEST['products_id'])){
			?><h3>Related Categories</h3>
			<?php
				include (DIR_WS_MODULES . 'products_categories.php');
				related_product_categories($_REQUEST['products_id']);
			?>
			<P>
				<h3>Products from the Manufacturer <?php echo $product_info['manufacturers_name'];?></h3><a href="index.php?manfactuers_id=<?php echo $product_info['manufacturers_id'];?>"><?php echo $product_info['manufacturers_name'];?></a>
				<p>
					<?php
					include (DIR_WS_MODULES . 'product_healthnotes.php');
					}
					?>
					</td>

					</tr> <?php

if ($cart->count_contents() > 0) {

					?>
					<?php
if ($any_out_of_stock == 1) {
if (STOCK_ALLOW_CHECKOUT == 'true') {
					?>
					<tr>
						<td class="stockWarning" align="center">
						<br>
						<?php echo OUT_OF_STOCK_CAN_CHECKOUT;?></td>
					</tr>
					<?php
					} else {
					?>
					<tr>
						<td class="stockWarning" align="center">
						<br>
						<?php echo OUT_OF_STOCK_CANT_CHECKOUT;?></td>
					</tr>
					<?php
					}
					}
					?>
					<tr>
						<td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10');?></td>
					</tr>
					<tr>
						<td>&nbsp;</td>
					</tr>
					<?php
					} else {
					?>
					<tr>
						<td align="center" class="main"><?php new infoBox( array( array('text' => TEXT_CART_EMPTY)));?></td>
					</tr>

					<?php
					}
					?> <?php } ?>
					</table></form>

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
