<?php
/*
  $Id: best_sellers.php,v 1.21 2003/06/09 22:07:52 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
  
  if (!isset($current_category_id) || ($current_category_id == '0')) {
    $best_sellers_query = tep_db_query("select distinct p.products_id, pd.products_name from " . TABLE_PRODUCTS . " p JOIN " . TABLE_PRODUCTS_DESCRIPTION . " pd on pd.products_id=p.products_id where p.products_status = '1' order by p.products_date_added desc limit ".MAX_DISPLAY_NEW_PRODUCTS);
  } else {
    $best_sellers_query = tep_db_query("select distinct p.products_id, pd.products_name from " . TABLE_PRODUCTS . " p join " . TABLE_PRODUCTS_DESCRIPTION . " pd on pd.products_id=p.products_id join " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c  on p2c.products_id=p.products_id JOIN " . TABLE_CATEGORIES . " c on c.categories_id=p2c.categories_id where (p2c.categories_id = '" . (int)$current_category_id . "' OR c.parent_id = '" . (int)$current_category_id . "') and p.products_status = '1' order by p.products_date_added desc limit " . MAX_DISPLAY_NEW_PRODUCTS);
   
  }
  
  if (tep_db_num_rows($best_sellers_query) >= MIN_DISPLAY_BESTSELLERS || 1==1) {
?>
<div id="nav_manufacturers" class="nav_box">

  <div class="nav_header">New Arrivals</div>
  <ol style="text-indent:0px;">
  <?php

    while ($best_sellers = tep_db_fetch_array($best_sellers_query)) {
      $bestsellers_list .= '<li>' . '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $best_sellers['products_id']) . '">' . $best_sellers['products_name'] . '</a>';
    }

    $info_box_contents = array();
    $info_box_contents[] = array('text' => $bestsellers_list);

    echo $bestsellers_list;
?>
</ol></div>
<!-- best_sellers_eof //-->
<?php
  }
?>
