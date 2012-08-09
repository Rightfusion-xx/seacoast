<?php
/*
$Id: product_info.php,v 1.97 2003/07/01 14:34:54 hpdl Exp $
osCommerce, Open Source E-Commerce Solutions
http://www.oscommerce.com
Copyright (c) 2003 osCommerce
Released under the GNU General Public License
*/
require('includes/application_top.php');
require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_PRODUCT_INFO);
$product_check_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'");
$product_check = tep_db_fetch_array($product_check_query);
?>
<!doctype html>
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<?php
// BOF: WebMakers.com Changed: Header Tag Controller v1.0
// Replaced by header_tags.php
if ( file_exists(DIR_WS_INCLUDES . 'header_tags.php') ) {
require(DIR_WS_INCLUDES . 'header_tags.php');
} else {
?>
<title><?php echo TITLE ?></title>
<?php
}
// EOF: WebMakers.com Changed: Header Tag Controller v1.0
?>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
<script language="javascript"><!--
function popupWindow(url) {
window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=no,width=100,height=100,screenX=150,screenY=150,top=150,left=150')
}
//--></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<a name="<?php echo $tags_array['title']; ?>"></a>
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<TABLE BORDER="0" WIDTH="100%" CELLSPACING="0" CELLPADDING="0">
<TR>
<TD WIDTH="<?php echo BOX_WIDTH; ?>" VALIGN="top" rowspan="2">
<TABLE BORDER="0" WIDTH="<?php echo BOX_WIDTH; ?>" CELLSPACING="2" CELLPADDING="0">
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
</TABLE></TD><td valign="top" colspan="2" valign="top"><?php require(DIR_WS_INCLUDES . 'titlebar.php'); ?></td></tr><tr>
<td width="100%" valign="top">
<TABLE WIDTH="100%" BORDER="0" CELLPADDING="5" CELLSPACING="0" BGCOLOR="#FFFFFF">
<TR><TD>

                  
                        <?php echo tep_draw_form('cart_quantity', tep_href_link(FILENAME_PRODUCT_INFO, tep_get_all_get_params(array('action')) . 'action=add_product')); ?>
                        <table border="0" width="100%" cellspacing="0" cellpadding="0">
                          <?php
if ($product_check['total'] < 1) {
?>
                          <tr> 
                            <td> 
                              <?php new infoBox(array(array('text' => TEXT_PRODUCT_NOT_FOUND))); ?>
                            </td>
                          </tr>
                          <tr> 
                            <td> 
                              <?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?>
                            </td>
                          </tr>
                          <tr> 
                            <td> 
                              <table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
                                <tr class="infoBoxContents"> 
                                  <td> 
                                    <table border="0" width="100%" cellspacing="0" cellpadding="2">
                                      <tr> 
                                        <td width="10"> 
                                          <?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?>
                                        </td>
                                        <td align="right"> 
                                          <?php echo '<a href="/">' . tep_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE) . '</a>'; ?>
                                        </td>
                                        <td width="10"> 
                                          <?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?>
                                        </td>
                                      </tr>
                                    </table>
                                  </td>
                                </tr>
                              </table>
                            </td>
                          </tr>
                          <?php
} else {
$product_info_query = tep_db_query("select psu.product_sku, psu.product_upc, p.products_id, pd.products_name, pd.products_description, p.products_model, p.products_quantity, p.products_image, pd.products_url, p.products_msrp, p.products_price, p.products_tax_class_id, p.products_date_added, p.products_date_available, p.manufacturers_id, m.manufacturers_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd , ". TABLE_MANUFACTURERS ." m left outer JOIN products_sku_upc psu ON psu.product_ids = p.products_id where p.products_status = '1' and p.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . 
	"' and pd.products_id = p.products_id and m.manufacturers_id = p.manufacturers_id and pd.language_id =' " . (int)$languages_id . "'");
$product_info = tep_db_fetch_array($product_info_query);
tep_db_query("update " . TABLE_PRODUCTS_DESCRIPTION . " set products_viewed = products_viewed+1 where products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and language_id = '" . (int)$languages_id . "'");
$products_price = '<table class="PriceList" border="0" width="100%" cellspacing="0" cellpadding="0">';
$new_price = tep_get_products_special_price($product_info['products_id']);
if ($product_info['products_msrp'] > $product_info['products_price'])
$products_price .= '<tr><td>' . TEXT_PRODUCTS_MSRP . '</td><td class="oldPrice" align=right>' . $currencies->display_price($product_info['products_msrp'], tep_get_tax_rate($product_info['products_tax_class_id'])) . '</td></tr>';
$products_price .= '<tr><td>' . TEXT_PRODUCTS_OUR_PRICE . '</td>';
if ($new_price != '')
{$products_price .= '<td class="oldPrice"';}
else
{$products_price .= '<td';}
$products_price .= ' align=right>' . $currencies->display_price($product_info['products_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) . '</td></tr>';
if ($new_price != '')
{$products_price .= '<tr class="productSpecialPrice"><td>' . TEXT_PRODUCTS_SALE . '</td><td align=right>' . $currencies->display_price($new_price, tep_get_tax_rate($product_info['products_tax_class_id'])) . '</td></tr>';}
if ($product_info['products_msrp'] > $product_info['products_price'])
{if ($new_price != '')
{$products_price .= '<tr><td>' . TEXT_PRODUCTS_SAVINGS . '</td><td align=right>' . $currencies->display_price(($product_info['products_msrp'] - $new_price), tep_get_tax_rate($product_info['products_tax_class_id'])) . '</td></tr>';}
else
{$products_price .= '<tr><td>' . TEXT_PRODUCTS_SAVINGS . '</td><td align=right>' . $currencies->display_price(($product_info['products_msrp'] - $product_info['products_price']), tep_get_tax_rate($product_info['products_tax_class_id'])) . '</td></tr>';}}
else
{if ($new_price != '')
{$products_price .= '<tr><td>' . TEXT_PRODUCTS_SAVINGS . '</td><td align=right>' . $currencies->display_price(($product_info['products_price'] - $new_price), tep_get_tax_rate($product_info['products_tax_class_id'])) . '</td></tr>';}}
$products_price .= '</table>';
//if (tep_not_null($product_info['products_model'])) {
// $products_name = $product_info['products_name'] . '<br><span class="smallText">[' . $product_info['products_model'] . ']</span>';
//} else {
$products_name = $product_info['products_name'];
//}
?>
                          <tr> 
                            <td> 
                              <table border="0" width="100%" cellspacing="0" cellpadding="7">
                                <tr> 
                                  <td valign="top"> <?php echo $breadcrumb->trail(' &raquo; '); ?>
                                    <h1 class="h1_prod_head">
                                      <?php echo $products_name; ?>
                                    </h1>
                                    <span style="font-family:arial;font-size:10pt;"><a href="index.php?manufacturers_id=<?php echo $product_info['manufacturers_id']?>">
                                    <?php echo $product_info['manufacturers_name'];  ?>
                                    </a></span><br>
                                    <span style="font-size:10pt;">
                                    <?php
			 //$product_info['product_sku']="";
			 if (strlen($product_info['product_sku'])>0){
					echo 'SKU: '.$product_info['product_sku']; }
			?>
                                    </span><span style="font-size:10pt;"><br>
                                    <?php
			 //$product_info['product_upc']="";
			 if (strlen($product_info['product_upc'])>0 ){
					echo 'UPC: '.$product_info['product_upc']; }
			?>
                                    </span> </td>
								  <td align="right" valign="top"> 
                                    <?php echo $products_price; ?><br/><input type="hidden" name="products_id" value="785"><input type="image" src="/includes/languages/english/images/buttons/button_in_cart.gif" border="0" alt="Add to Cart" title=" Add to Cart "><br/><span style="font-size:10pt;font-weight:bold;color:#CC6600;">Online or Toll Free<br/>1-800-555-6792</span>                                    </td>

                                  </td>
                                </tr>
                              </table>
                            </td>
                          </tr>
                          <tr> 
                            <td> 
                                                                          <table border="0" width="100%" cellspacing="1" cellpadding="2" >

                                <tr> 
                                  <td> 
                                    <table border="0" width="100%" cellspacing="0" cellpadding="2">
                                      <tr> 
                                        <td width="10"> 
                                          <img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1">                                        </td>
                                        <td class="main" align="right"> 

                                        <td width="10"> 
                                          <img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1">                                        </td>
                                      </tr>
                                    </table>
                                  </td>
                                </tr>
                              </table>

                            </td>
                          </tr>
                          <tr> 
                            <td class="main"> 
                              <?php
if (tep_not_null($product_info['products_image'])) {
?>
                              <table border="0" cellspacing="0" cellpadding="2" align="right">
                                <tr> 
                                  <td align="center" class="smallText"> 
                                    <script language="javascript"><!--
document.write('<?php echo '<a href="javascript:popupWindow(\\\'' . tep_href_link(FILENAME_POPUP_IMAGE, 'pID=' . $product_info['products_id']) . '\\\')">' . tep_image(DIR_WS_IMAGES . $product_info['products_image'], addslashes($product_info['products_name']), SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'hspace="5" vspace="5"') . '<br>' . TEXT_CLICK_TO_ENLARGE . '</a>'; ?>');
//--></script>
                                    <noscript> 
                                    <?php echo '<a href="' . tep_href_link(DIR_WS_IMAGES . $product_info['products_image']) . '" target="_blank">' . tep_image(DIR_WS_IMAGES . $product_info['products_image'], $product_info['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'hspace="5" vspace="5"') . '<br>' . TEXT_CLICK_TO_ENLARGE . '</a>'; ?>
                                    </noscript> </td>
                                </tr>
                              </table>
                              <?php
}
?>
                              <p> 
                                <?php echo stripslashes($product_info['products_description']); ?>
                              </p>
                              <?php
$products_attributes_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . (int)$HTTP_GET_VARS['products_id'] . "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . (int)$languages_id . "'");
$products_attributes = tep_db_fetch_array($products_attributes_query);
if ($products_attributes['total'] > 0) {
?>
                              <table border="0" cellspacing="0" cellpadding="2">
                                <tr> 
                                  <td class="main" colspan="2"> 
                                    <?php echo TEXT_PRODUCT_OPTIONS; ?>
                                  </td>
                                </tr>
                                <?php
$products_options_name_query = tep_db_query("select distinct popt.products_options_id, popt.products_options_name from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . (int)$HTTP_GET_VARS['products_id'] . "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . (int)$languages_id . "' order by popt.products_options_name");
while ($products_options_name = tep_db_fetch_array($products_options_name_query)) {
$products_options_array = array();
$products_options_query = tep_db_query("select pov.products_options_values_id, pov.products_options_values_name, pa.options_values_price, pa.price_prefix from " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov where pa.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and pa.options_id = '" . (int)$products_options_name['products_options_id'] . "' and pa.options_values_id = pov.products_options_values_id and pov.language_id = '" . (int)$languages_id . "'");
while ($products_options = tep_db_fetch_array($products_options_query)) {
$products_options_array[] = array('id' => $products_options['products_options_values_id'], 'text' => $products_options['products_options_values_name']);
if ($products_options['options_values_price'] != '0') {
$products_options_array[sizeof($products_options_array)-1]['text'] .= ' (' . $products_options['price_prefix'] . $currencies->display_price($products_options['options_values_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) .') ';
}
}
if (isset($cart->contents[$HTTP_GET_VARS['products_id']]['attributes'][$products_options_name['products_options_id']])) {
$selected_attribute = $cart->contents[$HTTP_GET_VARS['products_id']]['attributes'][$products_options_name['products_options_id']];
} else {
$selected_attribute = false;
}
?>
                                <tr> 
                                  <td class="main"> 
                                    <?php echo $products_options_name['products_options_name'] . ':'; ?>
                                  </td>
                                  <td class="main"> 
                                    <?php echo tep_draw_pull_down_menu('id[' . $products_options_name['products_options_id'] . ']', $products_options_array, $selected_attribute); ?>
                                  </td>
                                </tr>
                                <?php
}
?>
                              </table>
                              <?php
}
?>
                            </td>
                          </tr>
                          <tr> 
                            <td> 
                              <?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?>
                            </td>
                          </tr>
                          <?php
$reviews_query = tep_db_query("select count(*) as count from " . TABLE_REVIEWS . " where products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "'");
$reviews = tep_db_fetch_array($reviews_query);
if ($reviews['count'] > 0) {
?>
                          <tr> 
                            <td class="main"> 
                              <?php echo TEXT_CURRENT_REVIEWS . ' ' . $reviews['count']; ?>
                            </td>
                          </tr>
                          <tr> 
                            <td> 
                              <?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?>
                            </td>
                          </tr>
                          <?php
}
if (tep_not_null($product_info['products_url'])) {
?>
                          <tr> 
                            <td class="main"> 
                              <?php echo sprintf(TEXT_MORE_INFORMATION, tep_href_link($product_info['products_url'], 'NONSSL', true, false)); ?>
                            </td>
                          </tr>
                          <tr> 
                            <td> 
                              <?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?>
                            </td>
                          </tr>
                          <?php
}
if (tep_not_null($product_info['products_url'])) {
?>
                          <tr> 
                            <td class="main"> 
                              <?php echo sprintf(TEXT_MORE_INFORMATION, tep_href_link(FILENAME_REDIRECT, 'action=url&goto=' . urlencode($product_info['products_url']), 'NONSSL', true, false)); ?>
                            </td>
                          </tr>
                          <tr> 
                            <td> 
                              <?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?>
                            </td>
                          </tr>
                          <?php
}
if ($product_info['products_date_available'] > date('Y-m-d H:i:s')) {
?>
                          <tr> 
                            <td align="center" class="smallText"> 
                              <?php echo sprintf(TEXT_DATE_AVAILABLE, tep_date_long($product_info['products_date_available'])); ?>
                            </td>
                          </tr>
                          <?php
}
?>

                          <tr> 
                            <td> 

                                    <table border="0" width="100%" cellspacing="0" cellpadding="2">
                                      <tr> 
                                        <td width="10"> 
                                          <?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?>
                                        </td>
                                        <td class="main"> 
                                          <?php echo '<a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS, tep_get_all_get_params()) . '">' . tep_image_button('button_reviews.gif', IMAGE_BUTTON_REVIEWS) . '</a>'; ?>
                                        </td>
                                        <td class="main" align="right"> 
                                          <?php echo tep_draw_hidden_field('products_id', $product_info['products_id']) . tep_image_submit('button_in_cart.gif', IMAGE_BUTTON_IN_CART); ?>
                                        </td>
                                        <td width="10"> 
                                          <?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?>

                                  </td>
                                </tr>
                              </table>
                            </td>
                          </tr>
                          <tr> 
                            <td> 
                              <?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?>
                            </td>
                          </tr>
                            <tr>
                            <td><?php include(DIR_WS_MODULES . 'products_categories.php');?></td>
                          </tr>
                          <tr> 
                          <tr> 
                            
                            <td> 
                              <?php
if ((USE_CACHE == 'true') && empty($SID)) {
echo tep_cache_also_purchased(3600);
} else {
include(DIR_WS_MODULES . FILENAME_ALSO_PURCHASED_PRODUCTS);
}
}
?>
                            </td>
                          </tr>
                          <tr>
                            <td><?php include(DIR_WS_MODULES . 'product_healthnotes.php');?></td>
                          </tr>
                          <tr> 
                            <td> 
                              <?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?>
                            </td>
                          </tr>
                          <tr> 
                            <td> 
                              <?php include(DIR_WS_MODULES . FILENAME_PRODUCT_SPECIALS); ?>
                            </td>
                          </tr>
                        </table></form>

               
</TD></TR></TABLE></td>
<TD WIDTH="<?php echo BOX_WIDTH; ?>" VALIGN="top">
<TABLE BORDER="0" WIDTH="<?php echo BOX_WIDTH; ?>" CELLSPACING="2" CELLPADDING="0">
<?php require(DIR_WS_INCLUDES . 'column_right.php'); ?>
</TABLE></TD></TR></TABLE>
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>



