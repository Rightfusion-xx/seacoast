<?php/*  $Id: stats_customers_per_product.php v3.1  osCommerce, Open Source E-Commerce Solutions  http://www.oscommerce.com  Copyright (c) 2002 osCommerce  Released under the GNU General Public License	  Contribution by Radu Manole, radu_manole@hotmail.com*/  require('includes/application_top.php');  $begin_year = (int)($HTTP_POST_VARS['begin_year'] ? $HTTP_POST_VARS['begin_year'] : $HTTP_GET_VARS['begin_year']);  $begin_month = (int)($HTTP_POST_VARS['begin_month'] ? $HTTP_POST_VARS['begin_month'] : $HTTP_GET_VARS['begin_month']);  $begin_day = (int)($HTTP_POST_VARS['begin_day'] ? $HTTP_POST_VARS['begin_day'] : $HTTP_GET_VARS['begin_day']);  $end_year = (int)($HTTP_POST_VARS['end_year'] ? $HTTP_POST_VARS['end_year'] : $HTTP_GET_VARS['end_year']);  $end_month = (int)($HTTP_POST_VARS['end_month'] ? $HTTP_POST_VARS['end_month'] : $HTTP_GET_VARS['end_month']);  $end_day = (int)($HTTP_POST_VARS['end_day'] ? $HTTP_POST_VARS['end_day'] : $HTTP_GET_VARS['end_day']);  $date_query = tep_db_query('select min(date_purchased) as min_order_date from ' . TABLE_ORDERS);  if ($dates = tep_db_fetch_array($date_query)) {    $first_order_year = substr($dates['min_order_date'],0,4);  }  if (empty($first_order_year)) {    $first_order_year = date("Y") - 5;     }    $years_array = array();  $years_array[] = array('id'    => 0,                         'text' => TEXT_SELECT_YEAR);  for ( $i = $first_order_year ; $i <= date("Y") ; $i++ ) {    $years_array[] = array('id'   => $i,                           'text' => $i);    }  $months_array = array();  $months_array[] = array('id'   => 0,                          'text' => TEXT_SELECT_MONTH);  for ( $i = 1 ; $i <= 12 ; $i++ ) {    $months_array[] = array('id'   => $i,                            'text' => $i);    }     $days_array = array();  $days_array[] = array('id'   => 0,                        'text' => TEXT_SELECT_DAY);  for ( $i = 1 ; $i <= 31 ; $i++ ) {    $days_array[] = array('id'   => $i,                          'text' => $i);    }     $products_id = $_REQUEST['products_id'];  $product_ordered_status = (int)($HTTP_POST_VARS['product_ordered_status'] ? $HTTP_POST_VARS['product_ordered_status'] : $HTTP_GET_VARS['product_ordered_status']) ;  switch ($product_ordered_status) {    case '1':       $didnt_buy = true;       $did_buy = false;       $product_ordered_status = 1;      break;    case '2':      $didnt_buy = false;      $did_buy = true;      $product_ordered_status = 2;      break;    default:       $didnt_buy = false;      $did_buy = true;  }  $where_date = "";  if ($begin_year && $begin_month && $begin_day && $end_year && $end_month && $end_day) { // all date fields were selected    $where_date = " and date_purchased >= '" . $begin_year . '/' . $begin_month . '/' . $begin_day . " 00:00:00' and date_purchased <= '" . $end_year . '/' . $end_month . '/' . $end_day . " 23:59:59'";  } elseif ($begin_year || $begin_month || $begin_day || $end_year || $end_month || $end_day) { // some fields were not selected    $date_advice = TEXT_DATE_ADVICE;  }    //products_drop down  $products_query = tep_db_query("select p.products_id, pd.products_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = pd.products_id and pd.language_id = '" . $languages_id . "' order by products_name");  while ($products = tep_db_fetch_array($products_query)) {    $products_array[] = array('id' => $products['products_id'], 'text' => $products['products_name']);    if ($products['products_id'] == $products_id) {      $products_name = $products['products_name'];       }  }    if (!isset($products_name)) {    $products_name = $products_array[0]['text'];    }?><!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN"><html <?php echo HTML_PARAMS; ?>><head><meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>"><title><?php echo TITLE; ?></title><link rel="stylesheet" type="text/css" href="includes/stylesheet.css"></head><body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF"><!-- header //--><?php require(DIR_WS_INCLUDES . 'header.php'); ?><!-- header_eof //--><!-- body //--><table border="0" width="100%" cellspacing="2" cellpadding="2">  <tr>    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft"><!-- left_navigation //--><?php require(DIR_WS_INCLUDES . 'column_left.php'); ?><!-- left_navigation_eof //-->        </table></td><!-- body_text //-->    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">      <tr>        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">          <tr>            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>          </tr>        </table></td>      </tr>      <tr><?php echo tep_draw_form('list_customers', FILENAME_STATS_CUSTOMERS_PER_PRODUCT);?>           <td>            <table border="0" cellspacing="4" cellpadding="0">              <tr>                <td nowrap class="main"><?php echo TEXT_LIST_CUSTOMERS; ?></td>                <td nowrap class="main"><?php echo tep_draw_radio_field('product_ordered_status', '2', $did_buy) . TEXT_BOUGHT; ?>				</td>                <td nowrap class="main"><?php echo tep_draw_radio_field('product_ordered_status', '1', $didnt_buy) . TEXT_DIDNT_BUY; ?>				</td>              </tr>            </table>             <table border="0" cellspacing="4" cellpadding="0">              <tr>                 <td class="main"><?php echo tep_draw_pull_down_menu('products_id', $products_array, $products_id); ?>                 </td>                <td class="main"><?php echo TEXT_BETWEEN_DATES ?></td>              </tr>            </table>						            <table border="0" cellspacing="4" cellpadding="0">              <tr>                 <td class="main"><?php echo tep_draw_pull_down_menu('begin_year', $years_array, $begin_year) .                           tep_draw_pull_down_menu('begin_month', $months_array, $begin_month) .                          tep_draw_pull_down_menu('begin_day', $days_array, $begin_day) .                          ' ' . TEXT_AND . ' ' .                           tep_draw_pull_down_menu('end_year', $years_array, $end_year) .                           tep_draw_pull_down_menu('end_month', $months_array, $end_month) .                          tep_draw_pull_down_menu('end_day', $days_array, $end_day) . $date_advice; ?>                </td>                <td><?php echo tep_image_submit('button_preview.gif', 'view'); ?></td>              </tr>            </table>						          </td></form>      </tr>      <tr>        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">          <tr>            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">              <tr class="dataTableHeadingRow">                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_NUMBER; ?></td>                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_CUSTOMERS; ?></td>                <?php if ($product_ordered_status == 2) { ?>                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_NUMBER_OF_ORDERS; ?></td>								<td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_NUMBER_OF_PRODS_ORDERED; ?></td>                <?php } ?>																              </tr><?php  if ($HTTP_GET_VARS['page'] > 1) $rows = $HTTP_GET_VARS['page'] * MAX_DISPLAY_SEARCH_RESULTS - MAX_DISPLAY_SEARCH_RESULTS;  // customers who did not buy a product  if ($product_ordered_status == 1) {	      $customers_all_row = "select c.customers_id, c.customers_firstname, c.customers_lastname, c.customers_email_address, count(c.customers_id) as number_of_orders, sum(op.products_quantity) as number_of_products_ordered, op.products_id from " . TABLE_CUSTOMERS . " c, " . TABLE_ORDERS_PRODUCTS . " op, " . TABLE_ORDERS . " o where c.customers_id = o.customers_id and o.orders_id = op.orders_id and op.products_id='" . $products_id . "' " . $where_date . " group by c.customers_id order by number_of_orders DESC";    $customers_all = tep_db_query($customers_all_row);		    while ($customers = tep_db_fetch_array($customers_all)) {      $customers_id_did_buy_array[] = $customers['customers_id'];    }    if (tep_not_null($customers_id_did_buy_array)) {      $sql_string = ' not in ('.join(',', $customers_id_did_buy_array).')';    } 				    $customers_query_row = "select customers_id, customers_firstname, customers_lastname, customers_email_address from " . TABLE_CUSTOMERS . " where customers_id ". $sql_string ." ";    $customers_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $customers_query_row, $customers_query_numrows);    $customers_query = tep_db_query($customers_query_row);    while ($customers = tep_db_fetch_array($customers_query)) {      $rows++;      if (strlen($rows) < 2) {        $rows = '0' . $rows;      }?>		              <tr class="dataTableRow" onmouseover="this.className='dataTableRowOver';this.style.cursor='hand'" onmouseout="this.className='dataTableRow'" onclick="document.location.href='<?php echo tep_href_link(FILENAME_CUSTOMERS, 'search=' . $customers['customers_lastname'], 'NONSSL'); ?>'">                <td class="dataTableContent"><?php echo $rows; ?>.</td>                <td class="dataTableContent"><?php echo '<a href="' . tep_href_link(FILENAME_MAIL, 'selected_box=tools&customer=' . $customers['customers_email_address'], 'NONSSL') . '">' . $customers['customers_firstname'] . ' ' . $customers['customers_lastname'] . '</a>'; ?></td>                              </tr><?				}  // customers who did buy a product  } else {    $customers_query_raw = "select c.customers_id, c.customers_firstname, c.customers_lastname, c.customers_email_address, count(c.customers_id) as number_of_orders, sum(op.products_quantity) as number_of_products_ordered, op.products_id from " . TABLE_CUSTOMERS . " c, " . TABLE_ORDERS_PRODUCTS . " op, " . TABLE_ORDERS . " o where c.customers_id = o.customers_id and o.orders_id = op.orders_id and op.products_id='" . $products_id . "' " . $where_date . " group by c.customers_id order by number_of_orders DESC";    $customers_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $customers_query_raw, $customers_query_numrows);    $customers_query = tep_db_query($customers_query_raw);    // fix counted customers    $customers_query_numrows = tep_db_query("select c.customers_id from " . TABLE_CUSTOMERS . " c, " . TABLE_ORDERS_PRODUCTS . " op, " . TABLE_ORDERS . " o where c.customers_id = o.customers_id and o.orders_id = op.orders_id and op.products_id='" . $products_id . "' group by c.customers_id");    $customers_query_numrows = tep_db_num_rows($customers_query_numrows);    while ($customers = tep_db_fetch_array($customers_query)) {      $rows++;      if (strlen($rows) < 2) {        $rows = '0' . $rows;      }?>              <tr class="dataTableRow" onmouseover="this.className='dataTableRowOver';this.style.cursor='hand'" onmouseout="this.className='dataTableRow'" onclick="document.location.href='<?php echo tep_href_link(FILENAME_CUSTOMERS, 'search=' . $customers['customers_lastname'], 'NONSSL'); ?>'">                <td class="dataTableContent"><?php echo $rows; ?>.</td>                <td class="dataTableContent"><?php echo '<a href="' . tep_href_link(FILENAME_MAIL, 'selected_box=tools&customer=' . $customers['customers_email_address'], 'NONSSL') . '">' . $customers['customers_firstname'] . ' ' . $customers['customers_lastname'] . '</a>'; ?></td>                <td class="dataTableContent" align="right"><?php echo $customers['number_of_orders']; ?>&nbsp;</td>								<td class="dataTableContent" align="right"><?php echo $customers['number_of_products_ordered']; ?>&nbsp;</td>              </tr><?php    }  } // end else	?>            </table></td>          </tr>          <tr>            <td colspan="3"><table border="0" width="100%" cellspacing="0" cellpadding="2">              <tr>                <td class="smallText" valign="top"><?php echo $customers_split->display_count($customers_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_CUSTOMERS); ?></td>                <td class="smallText" align="right"><?php echo $customers_split->display_links($customers_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page'],'products_id=' . $products_id . '&product_ordered_status=' . $product_ordered_status); ?>&nbsp;</td>              </tr>            </table></td>          </tr>        </table></td>      </tr>    </table></td><!-- body_text_eof //-->  </tr></table><!-- body_eof //--><!-- footer //--><?php require(DIR_WS_INCLUDES . 'footer.php'); ?><!-- footer_eof //--></body></html><?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>