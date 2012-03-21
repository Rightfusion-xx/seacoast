<?php
/*
  $Id: best_sellers.php,v 1.21 2003/06/09 22:07:52 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  if (isset($current_category_id) && ($current_category_id > 0)) {
    $best_sellers_query = tep_db_query("select distinct p.products_id, pd.products_name from " . TABLE_PRODUCTS . " p join " . TABLE_PRODUCTS_DESCRIPTION . " pd on p.products_id=pd.products_id join " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c on p2c.products_id=p.products_id join " . TABLE_CATEGORIES . " c on c.categories_id=p2c.categories_id where p.products_status = '1' and p.products_ordered > 0 and  pd.language_id = '" . (int)$languages_id . "' and  '" . (int)$current_category_id . "' in (c.categories_id, c.parent_id) order by p.products_ordered desc, pd.products_name limit " . MAX_DISPLAY_BESTSELLERS);
  } else {
    $best_sellers_query = tep_db_query("select distinct p.products_id, pd.products_name from " . TABLE_PRODUCTS . " p join " . TABLE_PRODUCTS_DESCRIPTION . " pd on p.products_id=pd.products_id where p.products_status = '1' and p.products_ordered > 0 and pd.language_id = '" . (int)$languages_id . "' order by p.products_ordered desc, pd.products_name limit " . MAX_DISPLAY_BESTSELLERS);
  }

  if (tep_db_num_rows($best_sellers_query) >= MIN_DISPLAY_BESTSELLERS) {
?>
<!-- best_sellers //-->
          <tr>
            <td>
<?php
    require(DIR_WS_INCLUDES . '/boxes/boxTop.php');
	echo BOX_HEADING_BESTSELLERS;

    require(DIR_WS_INCLUDES . '/boxes/boxMiddle.php');

    $rows = 0;
    $bestsellers_list = '<table border="0" width="100%" cellspacing="0" cellpadding="1" id="scv_bestsell_box">';
    while ($best_sellers = tep_db_fetch_array($best_sellers_query)) {
      $rows++;
      $bestsellers_list .= '<tr><td class="infoBoxContents" valign="top">' . tep_row_number_format($rows) . '.</td><td class="infoBoxContents"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $best_sellers['products_id']) . '">' . $best_sellers['products_name'] . '</a></td></tr>';
    }
    $bestsellers_list .= '</table>';

    $info_box_contents = array();
    $info_box_contents[] = array('text' => $bestsellers_list);

    echo $bestsellers_list;
	require(DIR_WS_INCLUDES . '/boxes/boxBottom.php');
?>
            </td>
          </tr>
<!-- best_sellers_eof //-->
<?php
  }
?>
