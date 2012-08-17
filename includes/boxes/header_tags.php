<?php
/*
  $Id: header_tags.php,v 1.6 2007/01/10 by Jack_mcs

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>

<!-- header_tags.php //-->
          <tr>
            <td>
<?php
  $product_info_query = tep_db_query("select pd.products_name, pd.products_description from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '" . (int)$_GET['products_id'] . "' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'");
  $product_info = tep_db_fetch_array($product_info_query);
  
  $info_box_contents = array();
  $info_box_contents[] = array('text' => $tags_array['title']);

  new infoBoxHeading($info_box_contents, false, false);

  $info_box_contents = array();
  $info_box_contents[] = array('text' => substr($product_info['products_description'], 0, 100).'<a style="color: red;" href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . (int)$_GET['products_id']).'"  >  (...see more)</a>');
                  



  new infoBox($info_box_contents);
?>
            </td>
          </tr>
<!-- header_tags.php_eof //-->
