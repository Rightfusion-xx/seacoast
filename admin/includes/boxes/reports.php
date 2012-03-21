<?php

/*

  $Id: reports.php,v 1.5 2003/07/09 01:18:53 hpdl Exp $



  osCommerce, Open Source E-Commerce Solutions

  http://www.oscommerce.com



  Copyright (c) 2002 osCommerce



  Released under the GNU General Public License

*/

?>

<!-- reports //-->

          <tr>

            <td>

<?php

  $heading = array();

  $contents = array();



  $heading[] = array('text'  => BOX_HEADING_REPORTS,

                     'link'  => tep_href_link(FILENAME_STATS_PRODUCTS_VIEWED, 'selected_box=reports'));

  

  if ($selected_box == 'reports') {

    $contents[] = array('text'  =>'<a href="' . tep_href_link('backorder_report.php', '', 'NONSSL') . '" class="menuBoxContentLink">' . 'Backorder Report' . '</a><br>' .
                                  '<a href="' . tep_href_link(FILENAME_STATS_PRODUCTS_VIEWED, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_REPORTS_PRODUCTS_VIEWED . '</a><br>' .
                                    '<a href="' . tep_href_link('stats_products_3060.php', '', 'NONSSL') . '" class="menuBoxContentLink">' . '30/60 Inventory Needs' . '</a><br>' .
                                        '<a href="' . tep_href_link('stats_products_inventory.php', '', 'NONSSL') . '" class="menuBoxContentLink">' . 'Product Inventory' . '</a><br>' .
                                    '<a href="' . tep_href_link('stats_subscription_sales.php', '', 'NONSSL') . '" class="menuBoxContentLink">' . 'Subscription Sales' . '</a><br>' .                                    '<a href="' . tep_href_link(FILENAME_STATS_PRODUCTS_PURCHASED, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_REPORTS_PRODUCTS_PURCHASED . '</a><br>' .
				'<a href="' . tep_href_link(FILENAME_STATS_CUSTOMERS, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_REPORTS_ORDERS_TOTAL . '</a><br>' .
                                    '<a href="' . tep_href_link(FILENAME_STATS_MANUFACTURERS, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_REPORTS_SALES_MANUFACTURERS . '</a><br>');   


								   

  }

  

  $box = new box;

  echo $box->menuBox($heading, $contents);

?>

            </td>

          </tr>

<!-- reports_eof //-->

