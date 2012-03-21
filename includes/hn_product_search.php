<?php


  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ADVANCED_SEARCH);
  
  
// create column list
  $define_list = array('PRODUCT_LIST_MODEL' => PRODUCT_LIST_MODEL,
                       'PRODUCT_LIST_NAME' => PRODUCT_LIST_NAME,
                       'PRODUCT_DESCRIPTION' => PRODUCT_DESCRIPTION,
                       'PRODUCT_LIST_PRICE' => PRODUCT_LIST_PRICE,
                       'PRODUCT_LIST_QUANTITY' => PRODUCT_LIST_QUANTITY,
                       'PRODUCT_LIST_WEIGHT' => PRODUCT_LIST_WEIGHT,
                       'PRODUCT_LIST_IMAGE' => PRODUCT_LIST_IMAGE,
                       'PRODUCT_LIST_BUY_NOW' => PRODUCT_LIST_BUY_NOW);

  asort($define_list);

  $column_list = array();
  reset($define_list);
  while (list($key, $value) = each($define_list)) {
    if ($value > 0) $column_list[] = $key;
  }

    if (strlen($metaKeywords)>0) {
      $keywords = $metaKeywords;
      $search_keywords=preg_split("/,/",$keywords);

  $select_str = "select distinct p.products_model, concat(LEFT(pd.products_head_desc_tag,100),'...') as product_desc, m.manufacturers_id, p.products_id, pd.products_name, p.products_price, p.products_tax_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, p.products_price) as final_price ";
  $from_str = "from " . TABLE_PRODUCTS . " p left join " . TABLE_MANUFACTURERS . " m using(manufacturers_id), " . TABLE_PRODUCTS_DESCRIPTION . " pd left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id, " . TABLE_CATEGORIES . " c, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c";
  $where_str = " where p.products_status = '1' and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and p.products_id = p2c.products_id and p2c.categories_id = c.categories_id and (";

    for ($i=0, $n=sizeof($search_keywords); $i<$n; $i++ ) {
          $keyword = tep_db_prepare_input($search_keywords[$i]);
    if($i>0)$where_str .= " or ";
          $where_str .= "(pd.products_name like '%" . tep_db_input($keyword) . "%'";
          $where_str .= " or pd.products_description like '% " . tep_db_input($keyword) . " %'";
          $where_str .= ') ';
      }
        $order_str .= ")";

  $listing_sql = $select_str . $from_str . $where_str . $order_str;
  
  require(DIR_WS_MODULES . FILENAME_PRODUCT_LISTING);
  
  }


  
?>

