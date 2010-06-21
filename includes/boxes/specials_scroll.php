<?php
/*
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  $rp_query = tep_db_query("select p.products_id, pd.products_name, p.products_price, p.products_tax_class_id, p.products_image, s.specials_new_products_price from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_SPECIALS . " s where p.products_status = '1' and p.products_id = s.products_id and pd.products_id = s.products_id and pd.language_id = '" . (int)$languages_id . "' and s.status = '1' order by s.specials_date_added desc");
  if (tep_db_num_rows($rp_query)) {
    $info_box_contents = array();
    $info_box_contents[] = array('text' => BOX_HEADING_SPECIALS);
?>
<!-- specials //-->
<div id="nav_manufacturers" class="nav_box">

  <div class="nav_header"><a href="/index.php?cPath=314">Discount Supplements</a></div>
  Buy cheap, wholesale herbal &amp; natural food supplements for diet &amp; nutrition. 
  <?php
/*
    while ($random_product = tep_db_fetch_array($rp_query)) {
	$rp .= '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $random_product["products_id"]) . '">' . '</a><br><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $random_product['products_id']) . '">' . $random_product['products_name'] . '</a><br><s>' . $currencies->display_price($random_product['products_price'], tep_get_tax_rate($random_product['products_tax_class_id'])) . '</s><br><span class="productSpecialPrice">' . $currencies->display_price($random_product['specials_new_products_price'], tep_get_tax_rate($random_product['products_tax_class_id']));
	$rp .= "</SPAN><BR />\n---------\n";
    }

    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'center',
                                 'text' => '<MARQUEE behavior= "scroll" align= "center" direction= "up" height="160" scrollamount= "2" scrolldelay= "20" onmouseover=\'this.stop()\' onmouseout=\'this.start()\'>'.$rp.'</span></MARQUEE>');

    new infoBox($info_box_contents);*/
?>
</div>
<!-- specials_eof //-->
<?php
  }
?>
