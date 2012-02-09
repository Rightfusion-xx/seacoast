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

if (isset($HTTP_POST_VARS['country']))
{
    populateDeliveryFields($HTTP_POST_VARS['country']);
            
    if (($HTTP_POST_VARS[country] == 'US') && isset($HTTP_POST_VARS[postcode]))
    {
        $order->delivery['postcode'] = $HTTP_POST_VARS[postcode];
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
            <meta name="robots" content="noindex, nofollow">
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
		<div id="content">
			<?php
			
			if (isset($_GET['products_id']) && !$cart -> in_cart($_GET['products_id'])) {
				$cart -> add_cart($_GET['products_id']);
			}
			
			//echo tep_draw_form('cart_quantity', tep_href_link(FILENAME_SHOPPING_CART, 'action=update_product', 'SSL'), 'post');
                        echo tep_draw_form('cart_quantity', tep_href_link(FILENAME_SHOPPING_CART, '', 'SSL'), 'post'); 
                        ?>
			<table border="0" cellspacing="0" cellpadding="12" width="100%">
				<tr>
					<td valign="top" width="700"><?php
	// the second condition "$cart->in_cart" is to cover a situation where user might enter
	// a random number for products_id query string. (AH 30 January 2012)
	if (isset($_GET['products_id']) && $cart->in_cart($_GET['products_id']))
	{
		?>
                                            
		<table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
			<tr>
				<td style="padding-bottom:5px;"><?php

				$query = "select p.products_image,pd.products_name from products p, products_description pd ";
				$query .= "where p.products_id=pd.products_id and p.products_id='" . $_GET['products_id'] . "'";
				$product_info = tep_db_fetch_array(tep_db_query($query));
				
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
			</tr>
			<tr><td style="padding-bottom:30px;">
				<?php echo '<h1>'.$product_info['products_name'] .'</h1>'; ?>
			</td></tr>
		</table>
                                            
            <?php } ?>

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
        $pl .= '</tbody>';
        $pl .= '</table>';
        
        echo $pl;
        
        // shopping cart table ends

                                ?>
            </td>
                                </tr>

                                <tr>
                                        <td colspan="5" class="main" align="right" valign="top"><?php if($cart->in_cart(CM_FTPID) || (!$_SESSION['cm_is_member']) && $psavings>0){
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
                                                <b class="spiffy"> <b class="spiffy5"></b> <b class="spiffy4"></b> <b class="spiffy3"></b> <b class="spiffy2"><b></b></b> <b class="spiffy1"><b></b></b></b>
                                        </div><?php }?>
                                        <?php if($cart->show_savings()>0) {
                                        ?><b>Member Only Savings: <span style="color:#ff0000;background:yellow;">$<?php echo number_format($cart -> show_savings(), 2);?></span></b>
                                        <br/>
                                        <?php }?>
                                        <?php echo SUB_TITLE_SUB_TOTAL;?>
                                        <?php echo $currencies -> format($cart -> show_total());?></td>
                                </tr>
        <tr>
        <td width="220">
            
                <?php
                if (!tep_session_is_registered('customer_id'))
                {
                echo 'Select country to calculate shipping:<br />';
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
<td style="padding-left:20px; width: auto;">
    <?php
    
    if (!tep_session_is_registered('customer_id'))
    {
        if ((isset($HTTP_POST_VARS[country]) && ($HTTP_POST_VARS[country] == "US")) || 
                ($order->delivery['country']['iso_code_2'] == "US"))
        {
                echo 'Please enter ZIP code: <br />';
                echo tep_draw_input_field('postcode', $order->delivery['postcode']);
                echo tep_draw_input_field('postcode_btn','Calculate Shipping',
                        "style='margin-left:10px;font-weight:bold;' onClick='return submitForm(this.form);'",'submit');
        }
    }
    ?>
</td>
            <td style="text-align: right" colspan="3" align="right" class="main" > 
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
                        $costStr =  'Shipping: <b style="background:yellow;">';
                        $costStr .= '$ ' . getLowestShippingCost();
                        $costStr .= '</b>';
                        echo $costStr;
                    }
                }
             ?>
            </td>
        </tr>
        <tr><td style="padding:0 0 30px 0;" colspan="4"> 
                <br /> 
                <b>Need international or expedited shipping? Continue to checkout for real-time rates. </b>
        </td></tr>
        <tr><td colspan="5">
            <div id="ship_policy" style="display:block;border:solid 1px #ff0000;padding:5px;">
                    Seacoast Vitamins finds you the lowest rates available.
            </div> </noscript>    
        </td></tr>
                
        
        
        </tr>
						<tr>
							<td width="10">&nbsp;</td>
							<td class="main">&nbsp;</td>
							<td class="main">
                                                            <?php echo tep_image_submit('button_update_cart.gif', IMAGE_BUTTON_UPDATE_CART,
                                                                        'onclick=updateProd("cart_quantity","update_product")');
                                                            ?></td>
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
							<td align="right" class="main" colspan="2"><b> <?php echo '<a title="Checkout Now" href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL') . '">Checkout Now<br/>' . tep_image_button('button_checkout.gif', IMAGE_BUTTON_CHECKOUT) . '</a><BR>';?>

                <tr>
                        <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1');?></td>
                        <td class="main">&nbsp;</td>
                        <td class="main">Removes or Updates Quantity</td>
                        <td align="right" class="main">&nbsp; </td>
                        <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1');?></td>
                </tr>
                <tr>
                        <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1');?></td>
                        <td class="main">&nbsp;</td>
                        <?php

$back = sizeof($navigation->path)-2;
if (isset($navigation->path[$back])) {
                        ?>
                        <td class="main" valign="top">&nbsp;</td>
                        <?php
                        }
                        ?>
                        <td align="right" class="main" valign="top"></td>
                        <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1');?></td>

</td>
				</tr>
			</table>
			<hr class="sectiondivider"/>
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
					<td rowspan="4" valign="top" align="center" width="150">
					<div style="border:1px solid #666666;padding:1em;margin:0px;">
						<p>
							<b style="color:#666666">Risk Free</b>
							<br/>
							<div style="white-space:nowrap;"><img src="/images/quality.jpg" style="width:75px;"/><b style="color:#666666">
								<br/>
								120% Guarantee</b>
							</div>
						</p><img src="http://www.shopping.com/merchant_logo?ID=406477" width="130" border="0"alt="Great Store!" />
						<br/>
						<!-- BEGIN: BizRate Medal (125x73 pixels) -->
						<img  style="margin-top:3em;" src="http://medals.bizrate.com/medals/dynamic/162489_medal.gif" alt="BizRate Customer Certified (GOLD) Site" width="125" height="73" align="top" border="0" >
						<!-- END: BizRate Medal (125x73 pixels) -->
					</div>
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
					<tr>
						<td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10');?></td>
					</tr>
					<?php
					}
					?>
					</table></form>
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
		<!-- ClickTale Bottom part -->
		<div id="ClickTaleDiv" style="display: none;"></div>
		<script type='text/javascript'>
			document.write(unescape("%3Cscript%20src='" + (document.location.protocol == 'https:' ? 'https://clicktale.pantherssl.com/' : 'http://s.clicktale.net/') + "WRc3.js'%20type='text/javascript'%3E%3C/script%3E"));

		</script>
		<script type="text/javascript">
			var ClickTaleSSL = 1;
			if( typeof ClickTale == 'function')
				ClickTale(1368, 1, "www07");

		</script>
		<!-- ClickTale end of Bottom part -->
	</body>
</html>
<?php
	require (DIR_WS_INCLUDES . 'application_bottom.php');
?>