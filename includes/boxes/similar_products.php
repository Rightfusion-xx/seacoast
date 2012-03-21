<?php
/*
  $Id: similar_products.php,v 1.0 2004/06/06 jck Exp $
    Based on whats_new.php,v 1.31 by hpdl

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License
*/


// Set the sort order for the display
 
$sort_order = 'p.products_ordered desc';
 
	
// Find the id # of the category that the current product is in
    $category_query = tep_db_query("select categories_id 
	                                from " . TABLE_PRODUCTS_TO_CATEGORIES . " 
									where products_id = '" . (int)$_GET['products_id'] . "'"
                                  );
    $category = tep_db_fetch_array($category_query);
    $category_id = $category['categories_id'];

// Select the other products in the same category
    $products_query = tep_db_query("select p.products_id, 
                                           p.products_price,
                                           p.products_model,
                                           pd.products_name
                                    from " . TABLE_PRODUCTS . " p,  
                                         " . TABLE_PRODUCTS_DESCRIPTION . " pd,  
                                         " . TABLE_PRODUCTS_TO_CATEGORIES . " pc  
                                    where p.products_id = pc.products_id
                                      and p.products_id = pd.products_id
                                      and p.products_id != '" . (int)$_GET['products_id'] . "'
                                      and p.products_status = '1'
                                      and pc.categories_id = '" . (int)$category_id . "'
                                      and pd.language_id = '" . (int)$languages_id . "'
                                    order by " . $sort_order . " 
                                    limit " . 20
                                  );
// Write the output containing each of the products
    $products_string = '';
    $count_products = 0;
    while ($products = tep_db_fetch_array($products_query)) {
	  if ($products['products_id'] != $_GET['products_id']) {
        $products_string .= '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products['products_id']) . '">';
      //$products_string .= tep_image(DIR_WS_IMAGES . $products['products_image'], $products['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT);
        $products_string .= '</a><br><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products['products_id']) . '">';
        if (SIMILAR_PRODUCTS_SHOW_MODEL == 'true' && tep_not_null($products['products_model'])) {
          $products_string .= $products['products_model'] . '<br>';
        }
        if ((SIMILAR_PRODUCTS_SHOW_NAME == 'true') && tep_not_null($products['products_name'])) {
          $products_string .= $products['products_name'] . '<br>';
        }
        if (SIMILAR_PRODUCTS_SHOW_PRICE == 'true') {
          $products_string .= $currencies->display_price($products['products_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) . '<br>';
        }
        $products_string .= '</a><br>';
        $count_products++;
	  }
    }

    


?>
<!-- similar_products //-->
<div id="nav_manufacturers" class="nav_box">

  <div class="nav_header">Similar Items</div>
  <?php

    echo $products_string;
	
?>
</div>
<!-- similar_products_eof //-->
