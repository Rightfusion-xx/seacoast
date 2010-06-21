<?php
/*
  $Id: gifts.php,v 1.5 2005/04/25 
  Created by Jack York @ www.oscommerce-solution.com
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
 
// retreive the gifts  
  $gift_list_query = tep_db_query("SELECT p.products_id, p.products_status, p.products_carrot, pd.products_id, pd.products_name FROM products p, products_description pd WHERE pd.language_id = '".$languages_id."'
			AND p.products_id = pd.products_id AND p.products_status = '1' AND p.products_carrot = '1' ORDER BY pd.products_name ASC");
    
    if (tep_db_num_rows($gift_list_query)) {
?>
<!-- gifts //-->
          <tr>
            <td>
<?php
      
	  require(DIR_WS_INCLUDES . '/boxes/boxTop.php');
	  $info_box_contents = array();
      $info_box_contents[] = array('text' => BOX_HEADING_GIFTS);
      
      new infoBoxHeading($info_box_contents, false, false);    
      require(DIR_WS_INCLUDES . '/boxes/boxMiddle.php');
      $gifts_string = '<table border="0" width="100%" cellspacing="0" cellpadding="1">';
      while ($gift_list = tep_db_fetch_array($gift_list_query)) { 
        $gifts_string .= '  <tr>' .
                         '    <td class="infoBoxContents"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $gift_list['products_id']) . '">' . $gift_list['products_name'] . '</a></td>' .
                         '    <td class="infoBoxContents" align="right" valign="top"><a href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=cust_order&pid=' . $gift_list['products_id']) . '">'  . '</a></td>' .
                         '  </tr><tr><td>' . tep_draw_separator('pixel_trans.gif', '100%', '10') .'</td></tr>';
      }
      $gifts_string .= '</table>';

      $info_box_contents = array();
      $info_box_contents[] = array('text' => $gifts_string);

      new infoBox($info_box_contents);
	  require(DIR_WS_INCLUDES . '/boxes/boxBottom.php');
?>
            </td>
          </tr>
<!-- gifts_eof //-->
<?php
    }   
?>
