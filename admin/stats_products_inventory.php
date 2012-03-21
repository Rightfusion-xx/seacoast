
<?php
    set_time_limit  ( 1000  );
  require('includes/application_top.php');
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/general.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><h1>Product Inventory</h1></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo 'Product'; ?></td>
                <td class="dataTableHeadingContent"><?php echo 'Manufacturer'; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo 'SKU'; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo 'UPC'; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo 'QTY'; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo 'NEAR EXPIRY'; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo 'MSRP'; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo 'Price'; ?></td>
              </tr>
<?php
  if (isset($HTTP_GET_VARS['page']) && ($HTTP_GET_VARS['page'] > 1)) $rows = $HTTP_GET_VARS['page'] * MAX_DISPLAY_SEARCH_RESULTS - MAX_DISPLAY_SEARCH_RESULTS;
  $rows = 0;
  $products_query_raw = "select p.products_nearest_expiration_date, p.products_id, pd.products_name, p.products_sku, m.manufacturers_name, p.products_upc, p.products_msrp, p.products_price, products_available from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, manufacturers m where m.manufacturers_id=p.manufacturers_id and p.products_id = pd.products_id order by manufacturers_name asc, products_name asc ";
  $products_split = new splitPageResults($HTTP_GET_VARS['page'], 50000, $products_query_raw, $products_query_numrows);
  $products_query = tep_db_query($products_query_raw);
  while ($products = tep_db_fetch_array($products_query)) {
    $rows++;

    if (strlen($rows) < 2) {
      $rows = '0' . $rows;
    }
?>
              <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" >
                <td class="dataTableContent"><?php echo   $products['products_name'] . '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'action=new_product&pID=' . $products['products_id'] . '&origin=' . 'stats_products_inventory.php' . '?page=' . $HTTP_GET_VARS['page'], 'NONSSL') . '">[#]</a>'; ?></td>
                <td class="dataTableContent" align="left"><?php echo $products['manufacturers_name']; ?></td>
                <td class="dataTableContent" align="left"><?php echo $products['products_sku']; ?></td>
                <td class="dataTableContent" align="left"><?php echo $products['products_upc']; ?></td>
                <td class="dataTableContent" align="left"><?php echo $products['products_available']; ?></td>
                <td class="dataTableContent" align="left"><?php echo $products['products_nearest_expiration_date']; ?></td>
                <td class="dataTableContent" align="left"><?php echo $products['products_msrp']; ?></td>
                <td class="dataTableContent" align="left"><?php echo $products['products_price']; ?></td>
              </tr>
<?php
  }
?>
            </table></td>
          </tr>
          <!--<tr>
            <td colspan="3"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="smallText" valign="top"><?php echo $products_split->display_count($products_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_PRODUCTS); ?></td>
                <td class="smallText" align="right"><?php echo $products_split->display_links($products_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page']); ?></td>
              </tr>
            </table></td>
          </tr>  -->
          <tr>
            <td>
                <b><?php echo $rows?> rows.</b>
            </td>
          </tr>
        </table></td>
      </tr>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
