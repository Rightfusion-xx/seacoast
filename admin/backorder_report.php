
      <?php
  require('includes/application_top.php');
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title>Backorder Report</title>
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
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo 'Backorder Report'; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo 'Order #'; ?></td>
                <td class="dataTableHeadingContent"><?php echo 'Date'; ?>&nbsp;</td>
                <td class="dataTableHeadingContent" style="overflow:hidden;"><?php echo 'Customer Name'; ?></td>
                <td class="dataTableHeadingContent" align="center" style="overflow:hidden;"><?php echo 'Manufacturer'; ?>&nbsp;</td>
                <td class="dataTableHeadingContent" align="center" style="overflow:hidden;"><?php echo 'Product Name'; ?>&nbsp;</td>
                <td class="dataTableHeadingContent" align="center" style="overflow:hidden;"><?php echo 'Mfg#'; ?>&nbsp;</td>
                <td class="dataTableHeadingContent" align="center" style="overflow:hidden;"><?php echo 'Can Fill?'; ?>&nbsp;</td>
                
              </tr>
<?php
  if (isset($HTTP_GET_VARS['page']) && ($HTTP_GET_VARS['page'] > 1)) $rows = $HTTP_GET_VARS['page'] * 1000 - 1000;
  $products_query_raw = 'select o.orders_id, date_purchased, customers_name, manufacturers_name, pd.products_name, products_sku,
CASE WHEN products_available>=0 THEN "Y" ELSE "N" END AS canfill  FROM orders o JOIN orders_products op ON op.orders_id=o.orders_id
JOIN products p ON p.products_id=op.products_id
JOIN products_description pd ON pd.products_id=p.products_id
JOIN manufacturers m ON m.manufacturers_id=p.manufacturers_id
WHERE orders_status=4 ORDER BY o.orders_id desc';

  $rows = 0;
  $products_query = tep_db_query($products_query_raw);
  $rowcolor='';
  $lastid='';
  while ($products = tep_db_fetch_array($products_query)) {
    $rows++;

    if (strlen($rows) < 2) {
      $rows = '0' . $rows;
    }
    

    if($lastid!=$products['orders_id'])
    {
     $rowcolor=($rowcolor=='#FFFFFF') ? '#CCCCFF' : '#FFFFFF';
    }

?>
              <tr class="dataTableRow" style="background-color:<?php echo $rowcolor;?>;" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)">
                <td class="dataTableContent"><?php echo '<a href="' . tep_href_link('orders.php', 'selected_box=customers&action=edit&oID=' . $products['orders_id'] . '&origin=' . 'backorder_report.php', 'NONSSL') . '">' . $products['orders_id'] . '</a>'; ?></td>
                <td class="dataTableContent"><?php echo date('m/d/y H:i', strtotime($products['date_purchased'])); ?></td>
                <td class="dataTableContent"><?php echo $products['customers_name']; ?></td>
                <td class="dataTableContent"><?php echo $products['manufacturers_name']; ?></td>
                <td class="dataTableContent"><?php echo $products['products_name']; ?></td>
                <td class="dataTableContent"><?php echo $products['products_sku']; ?></td>
                <td class="dataTableContent"><?php echo $products['canfill']; ?></td>
                <td class="dataTableContent" align="center"><?php echo $products['products_ordered']; ?>&nbsp;</td>
              </tr>
<?php

    $lastid= $products['orders_id'];

  }
?>
            </table></td>
          </tr>
          <tr>
            <td colspan="3"><table border="0" width="100%" cellspacing="0" cellpadding="2">

            </table></td>
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
