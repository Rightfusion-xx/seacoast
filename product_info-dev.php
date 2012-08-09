<?php



require('includes/application_top.php');
require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_PRODUCT_INFO);

$product_info_query = tep_db_query("select pd.products_head_title_tag, pd.products_type, psu.product_sku,pd.products_departments,pd.products_ailments,pd.products_uses, p.products_weight, p.products_ordered, pd.products_head_keywords_tag, pd.products_viewed, date_format(p.products_date_added,'%m/%d/%Y') as products_date_added, p.products_last_modified, psu.product_upc, p.products_id, pd.products_name, pd.products_description, p.products_model, p.products_quantity, p.products_image, pd.products_url, p.products_msrp, p.products_price, p.products_tax_class_id, p.products_date_available, p.manufacturers_id, m.manufacturers_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd , ". TABLE_MANUFACTURERS ." m left outer JOIN products_sku_upc psu ON psu.product_ids = p.products_id where p.products_status = '1' and p.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . 
	"' and pd.products_id = p.products_id and m.manufacturers_id = p.manufacturers_id and pd.language_id =' " . (int)$languages_id . "'");


if(!($product_info = tep_db_fetch_array($product_info_query))){
    //No product found, redirect.
    redir301(HTTP_SERVER);
}
else
{
    tep_db_query("update " . TABLE_PRODUCTS_DESCRIPTION . " set products_viewed = products_viewed+1 where products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and language_id = '" . (int)$languages_id . "'");

    //Get image location
    if (file_exists (DIR_WS_IMAGES.'products/'.$product_info['products_id'].'.gif')){
        $product_image_path = DIR_WS_IMAGES.'products/'.$product_info['products_id'].'.gif';}
    elseif (file_exists (DIR_WS_IMAGES.'products/'.$product_info['products_id'].'.jpg')){
        $product_image_path = DIR_WS_IMAGES.'products/'.$product_info['products_id'].'.jpg';}
    elseif (file_exists (DIR_WS_IMAGES.'products/'.$product_info['products_id'].'.bmp')){
        $product_image_path = DIR_WS_IMAGES.'products/'.$product_info['products_id'].'.bmp';}
    elseif (file_exists (DIR_WS_IMAGES.'products/'.$product_info['products_id'].'.png')){
        $product_image_path = DIR_WS_IMAGES.'products/'.$product_info['products_id'].'.png';}
    elseif  (tep_not_null($product_info['products_image'])){
        $product_image_path = DIR_WS_IMAGES.$product_info['products_image']; 
        }
        
    //check for product specials
    $new_price = tep_get_products_special_price($product_info['products_id']);

    //Get product name
    $products_name = $product_info['products_name'];

}


?>
<!doctype html>
<html <?php echo HTML_PARAMS; ?>>
<head>
    <link rel="stylesheet" href="/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/bootstrap/css/bootstrap-responsive.min.css">
    <link href="/css/main.css" rel="stylesheet">
    <link href="/font/fonts.css" rel="stylesheet">
    <!--[if lt IE 9]>
    <script type="text/javascript" src="/jquery/respond.src.js"></script>
    <![endif]-->
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo $product_info['products_head_title_tag'] ?></title>

<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
<script language="javascript"><!--
function popupWindow(url) {
window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=no,width=100,height=100,screenX=150,screenY=150,top=150,left=150')
}
//--></script>


</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">

<?php require(DIR_WS_INCLUDES . 'header.php'); ?>

<div id="content">

                  
<?php echo tep_draw_form('cart_quantity', tep_href_link(FILENAME_PRODUCT_INFO, tep_get_all_get_params(array('action')) . 'action=add_product')); ?>
                       
                       




<h1 class="h1_prod_head">
    <?php echo $products_name; ?>
</h1>
<span style="font-size:10pt;">
    <?php echo $product_info['manufacturers_name'];  ?>
</span><br>
<span style="font-size:10pt;">
<?php
    if (strlen($product_info['product_sku'])>0){
    echo 'SKU: '.$product_info['product_sku']; }
?>
</span><span style="font-size:10pt;"><br>
    <?php
    if (strlen($product_info['product_upc'])>0 ){
    echo 'UPC: '.$product_info['product_upc']; }
    ?>
</span> 




<?php if(time()<strtotime('2007/10/15')){ ?> <div onmouseover="javascript:document.getElementById('divpop').style.display='block';document.getElementById('divbg').style.display='block';" onmouseout="javascript:document.getElementById('divpop').style.display='none';document.getElementById('divbg').style.display='none';" style="margin-top:10px;margin-bottom:10px;background-color:#ffffff;border:solid 5px red;padding:10px; width:250px;">
            <img src="/images/onsale.gif" width="150" height="67" border="0"><br/>
            <h3 style="text-decoration:none;display:inline;">25% Off Orders of $75 or More</h3><br/>
                        <b>Through Monday, October 29th</b><br/>
            <span style="text-decoration:underline;">view details</span>
        </div><?php } ?> 
                            
                            

                                                                         

<?php



if(isset ($product_image_path)) {?>
<td valing="top" nowrap>
<script language="javascript"><!--
document.write('<?php echo '<a href="javascript:popupWindow(\\\'' . tep_href_link(FILENAME_POPUP_IMAGE, 'pID=' . $product_info['products_id']) . '\\\')">' . tep_image($product_image_path, addslashes($product_info['products_name']), SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'hspace="5" vspace="5"') . '<br><span style="font-size:8pt;">' . TEXT_CLICK_TO_ENLARGE . '</span></a>'; ?>');
//--></script>
<noscript> 
<?php echo '<a href="' . tep_href_link($product_image_path) . '" target="_blank">' . tep_image($product_image_path, $product_info['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'hspace="5" vspace="5"') . '<br>' . TEXT_CLICK_TO_ENLARGE . '</a>'; ?>
</noscript></td>
<?php
}
?>

   <div style="white-space:nowrap;">
        <div style="border:2px solid red;white-space:nowrap;float:left;">
        <?php 
        if ($product_info['products_msrp'] > $product_info['products_price'])
        echo 'MSRP: <span class="oldPrice" align=right>' . $currencies->display_price($product_info['products_msrp'], tep_get_tax_rate($product_info['products_tax_class_id']) ) . '</span>';
        echo '<br/>Our Price: ';
        if ($new_price != '')
        {echo '<span class="oldPrice">';}
        else
        {echo '<span>';}
        echo  $currencies->display_price($product_info['products_price'], tep_get_tax_rate($product_info['products_tax_class_id']));
        if ($new_price != '')
        {echo '<span class="productSpecialPrice">' . TEXT_PRODUCTS_SALE . '</span>' . $currencies->display_price($new_price, tep_get_tax_rate($product_info['products_tax_class_id'])) ;}
        if ($product_info['products_msrp'] > $product_info['products_price'])
        {if ($new_price != '')
        {echo '<br/>You Save: ' . $currencies->display_price(($product_info['products_msrp'] - $new_price), tep_get_tax_rate($product_info['products_tax_class_id'])) . '';}
        else
        {echo '<br/>You Save: ' . $currencies->display_price(($product_info['products_msrp'] - $product_info['products_price']), tep_get_tax_rate($product_info['products_tax_class_id'])) . '';}}
        else
        {if ($new_price != '')
        {echo '<br/>You Save: ' . $currencies->display_price(($product_info['products_price'] - $new_price), tep_get_tax_rate($product_info['products_tax_class_id'])) . '';}}
        ?>
<br/>Quantity: <input type="text" value="1" name="qty" size="4"><br/><input type="hidden" name="products_id" value="<?php echo $product_info['products_id'];?>"><input type="image" src="/includes/languages/english/images/buttons/button_in_cart.gif" border="0" alt="Add to Cart" title=" Add to Cart "><br/>
        </div>
<div style="white-space:nowrap;float:left;">
    <span style="font-size:10pt;font-weight:bold;color:#CC6600;">Online or Toll Free<br/>1-800-555-6792                              
</span> </div> </div></form>   
<br style="clear:both;"/>
<?php if(time()<strtotime('2008/1/1') && !strpos($product_info['products_name'],'*')){ ?><script>utmx_section("50free")</script><span style="font-size:10pt;color:red;font-weight:bold;">This earns $50 free. <br/><a href="/january_rewards.php" style="color:red;">See Details...</a></span></noscript><?php } ?>


 </p>

<hr class="sectiondivider"/>

                     <?php if(strlen($product_info['products_uses'])>0){?>
                     <div style="margin-right:20px;float:left;">
               <h2>Uses & Indications</h2>
            <p>
                <ul>
                    <?php 
                        $uses=split(',',str_replace(', ',',',str_replace('  ',' ',$product_info['products_uses'])));
                        $benefit_links='';
                        foreach($uses as $usename)
                        { ?>
                            <li><?php echo ucwords($usename)?></li>
                          <?php
                          
                          $benefits='<a href="/natural_uses.php?use='.urlencode(strtolower($usename)).'">'.ucwords($usename).' '.$product_info['products_type'].'</a> &nbsp;'.$benefits;
                                                    
                        }
                    ?>
                </ul>
            </p></div><?php } ?>
            
            
                     <?php if(strlen($product_info['products_uses'])>0){
                     
                     ?>
               <div style="margin-right:20px;float:left;">
               <h2>Ailments & Concerns</h2>
            <p>
                <ul>
                    <?php 
                        $uses=split(',',str_replace(', ',',',str_replace('  ',' ',$product_info['products_ailments'])));
                        $benefit_links='';
                        foreach($uses as $usename)
                        { ?>
                            <li><?php echo ucwords($usename)?></li>
                          <?php
                          
                          $ailments='<a href="/ailments.php?remedy='.urlencode(strtolower($usename)).'">'.ucwords($usename).' '.$product_info['products_type'].'</a> &nbsp;'.$ailments;
                                                    
                        }
                    ?>
                </ul>
            </p></div> <hr class="sectiondivider"/><?php } ?>

 <h2><?php echo $product_info['products_head_title_tag'];?></h2>
            
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
?>

                          <tr> 
                            <td> 

                                    <table border="0" cellspacing="0" cellpadding="2" width="100%">
                                      <tr> 
                                        <td width="10"> 
                                          <?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?>
                                        </td>
                                        <td class="main"> 
                                          <?php echo '<a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS, tep_get_all_get_params()) . '"><b>Read Customer Reviews</b></a>'; ?>
                                          <br/><?php echo '<a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS_WRITE, tep_get_all_get_params()) . '"><b>Write a Review</b></a>'; ?>
                                        </td>
                                       <td class="main" align="right">

                          
<b>Price:</b> <span style="color:#669933;font-weight:bold;"><?php echo $currencies->display_price($product_info['products_price'],0); ?></span>
                                             <?php echo tep_draw_form('buy_now', tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=buy_now&amp;buyqty=1&amp;products_id='. $product_info['products_id']), 'POST') . ''  . tep_image_submit('button_in_cart.gif', IMAGE_BUTTON_IN_CART, 'align=absmiddle') . '</form>';?>

                                        </td>
                                        <td width="10"> 
                                          <?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?>

                                  </td>
                                </tr>
                              </table>
                            </td>
                          </tr>
                          <?php if(strtotime($product_info['products_last_modified'])<strtotime('2007-03-01'))
                          { ?>
                          <tr>
                            <td>
                                <?php
                                
                                ?><br/><br/>
                                <p>
                                You have reached <?php echo $product_info['products_name']?> on Seacoast Vitamins.com from the manufacturer <?php echo $product_info['manufacturers_name']?>. We're proud to have 
                                served <?php echo $product_info['products_viewed']?> customers since <?php echo $product_info['products_date_added']?> who were also interested in purchasing <?php echo $product_info['products_name']?>.
                                It currently ranks as our <?php echo $product_info['products_ordered']?> most popular natural health product.
                                </p>
                                <p><b>Technical <?php echo $product_info['products_name']?> Details:</b> Locate this product using sku number <?php echo $product_info['product_sku']?> or ISBN <?php echo $product_info['product_upc']?>. For shipping, the weight is
                                equal to <?php echo $product_info['products_weight']?> pounds and is available for shipment immediately. Typical inquiries include
                                <?php
                                $keywords=split(',',$product_info['products_head_keywords_tag']);
                                $keywords=array_reverse($keywords);
                                foreach($keywords as $keyword)
                                {
                                    echo $keyword.', ';
                                }
                                    
                             ?> and natural health. Seacoast Vitamins offers this product at a $<?php echo $product_info['products_msrp']-$product_info['products_price']?> discount
                             off of the suggested retail price $<?php echo $product_info['products_msrp']?>. Our price is $<?php echo $product_info['products_price']?>.</p>
                            </td>
                          </tr>
                          <?php } ?>
                          <tr> 
                            <td> 
                              <?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?>
                            </td>
                          </tr>

                           

                      		        <tr><td>
                    <div style="border:1px solid #000000;background-color:#eeeeee" name="AddlInfo" id="AddlInfo">
                     <h2>Explore <?php echo $product_info['products_name'];?></h2>
                     <p>
                        <?php echo $breadcrumb->trail(' &raquo; '); ?>
                     </p>
                       <p>
                    <br/>
                    <b>Buy <?php echo $product_info['products_name'];?> from Seacoast Vitamins.</b> We provide the natural health community with the best natural food supplements &amp; herbal remedies from <?php echo $product_info['manufacturers_name'];?> for fitness, vitality, &amp; weight loss. Add nutrition to your diet
                    with access to our cheap, discount, wholesale <?php echo $product_info['products_name'];?>.

                  </p>
                     <h2>Related Health Guides</h2><?php include(DIR_WS_MODULES . 'products_categories.php');?><?php related_product_categories($product_info['products_id']);?>
                     <h2>More From <?php echo $product_info['manufacturers_name'];?></h2>
                     <p>
                        <a href="index.php?manufacturers_id=<?php echo $product_info['manufacturers_id']?>">
                                    <?php echo $product_info['manufacturers_name'];  ?>
                                    </a>
                     </p>
                     
                     <?php if(strlen($product_info['products_uses'])>0){?>
               <h2>Related Uses</h2>
            <p>
                    <?php 
                        
                            echo $benefits;
                    ?>
            </p><?php } ?>
                     <?php if(strlen($product_info['products_ailments'])>0){?>
               <h2>Guides to Relevent Concerns & Ailments</h2>
            <p>
     
                    <?php 
                        
                            echo $ailments;
                    ?>
            
            </p><?php } ?>

                     <?php if(strlen($product_info['products_departments'])>0){?>
               <h2>Related Departments</h2>
            <p>
             
                    <?php 
                    $uses=split(',',str_replace(', ',',',str_replace('  ',' ',$product_info['products_departments'])));
                        $benefit_links='';
                        foreach($uses as $usename)
                        {
                            $departments='<a href="/departments.php?benefits='.urlencode(strtolower($usename)).'">'.ucwords($usename).'</a> &nbsp;'.$departments;
                        }
                            echo $departments;
                    ?>
              
            </p>          
            
                              <?php }

?>
                           <?php include(DIR_WS_MODULES . 'product_healthnotes.php');?></div>

          
</div>
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<br>


</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>

