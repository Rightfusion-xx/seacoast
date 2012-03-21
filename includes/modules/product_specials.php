<?php
/*
  $Id: product_specials.php,v 1.0 2005/11/11 00:00:00 holforty Exp $

  Designed for: osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2005 Todd Holforty - mtholforty@surfalot.com

  Released under the GNU General Public License
*/
?>
<!-- product_specials //-->
<?php

// are we viewing a specific product? ($product_info['products_id'] is set if we are)
  if (tep_not_null($product_info['products_id'])) {
    $the_products_catagory_query = tep_db_query("select products_id, categories_id from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . $product_info['products_id'] . "'" . " order by products_id,categories_id");
    $the_products_catagory = tep_db_fetch_array($the_products_catagory_query);

    $product_category_id = $the_products_catagory['categories_id'];
  }
  
  if ( (LIMIT_PRODUCT_SPECIALS_SCOPE=='true') && !empty($new_products_category_id) ) { /// We are in category depth 
    $product_specials_query = tep_db_query("select distinct p.products_id, pd.products_name, p.products_price, p.products_tax_class_id, s.specials_new_products_price from products p, products_description pd, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_CATEGORIES . " c, specials s where p.products_status='1' and p.products_id=s.products_id and p.products_id=pd.products_id and pd.language_id='".(int)$languages_id."' and p.products_id = p2c.products_id and p2c.categories_id = c.categories_id and s.status='1' and c.parent_id = '" . (int)$new_products_category_id . "' order by rand() limit ".MAX_DISPLAY_PRODUCT_SPECIALS); 
  } else if ( (LIMIT_PRODUCT_SPECIALS_SCOPE=='true') && !empty($product_category_id) ) { // products info page
    $product_specials_query = tep_db_query("select distinct p.products_id, pd.products_name, p.products_price, p.products_tax_class_id, s.specials_new_products_price from products p, products_description pd, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_CATEGORIES . " c, specials s where p.products_status='1' and p.products_id=s.products_id and p.products_id=pd.products_id and pd.language_id='".(int)$languages_id."' and p.products_id = p2c.products_id and p2c.categories_id = c.categories_id and s.status='1' and c.categories_id = '" . (int)$product_category_id . "' order by rand() limit ".MAX_DISPLAY_PRODUCT_SPECIALS); 
  } else { // default
    $product_specials_query = tep_db_query("select p.products_id, pd.products_name, p.products_price, p.products_tax_class_id, s.specials_new_products_price from products p, products_description pd, specials s where p.products_status='1' and p.products_id=s.products_id and p.products_id=pd.products_id and pd.language_id='".(int)$languages_id."' and s.status='1' order by rand() limit ".MAX_DISPLAY_PRODUCT_SPECIALS); 
  }

  if (tep_db_num_rows($product_specials_query)>0) {

    $info_box_contents = array();
    $info_box_contents[] = array('text' => '<a href="'.tep_href_link(FILENAME_SPECIALS).'">'.TABLE_HEADING_PRODUCT_SPECIALS.'</a>' );

    new infoBoxHeading($info_box_contents,false,false, tep_href_link(FILENAME_SPECIALS));

    $row = 0;
    $col = 0;
    $info_box_contents = array();
    while ($product_specials = tep_db_fetch_array($product_specials_query)) {
      $products_price = (tep_not_null($product_specials['specials_new_products_price'])?'<s>'.$currencies->display_price($product_specials['products_price'], tep_get_tax_rate($product_specials['products_tax_class_id'])).'</s>&nbsp;&nbsp;':'').'<span class="productSpecialPrice">' . $currencies->display_price($product_specials['specials_new_products_price'], tep_get_tax_rate($product_specials['products_tax_class_id'])).'</span>'; 
      $info_box_contents[$row][$col] = array('align' => 'center',
                                             'params' => 'class="smallText" width="33%" valign="top"',
                                             'text' => '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $product_specials['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $product_specials['products_image'], $product_specials['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a><br><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $product_specials['products_id']) . '">' . $product_specials['products_name'] . '</a><br>' . $products_price );

      $col ++;
      if ($col > PRODUCT_SPECIALS_DISPLAY_COLUMNS-1) {
        $col = 0;
        $row ++;
      }
    }

    new contentBox($info_box_contents);
  
  }
?>
<!-- product_specials_eof //-->
