<?php
/*
	Contribution Name: Manufacturer Sales Report
	Contribution Version: 2.0

	Author Name: Robert Heath
	Author E-Mail Address: robert@rhgraphicdesign.com
	Author Website: http://www.rhgraphicdesign.com
	Donations: www.paypal.com
	Donations Email: robert@rhgraphicdesign

	Released under the GNU General Public License
*/

  require('includes/application_top.php');
   
   if (isset($HTTP_GET_VARS['start_date'])) {
    $start_date = $HTTP_GET_VARS['start_date'];
  } else {
    $start_date = date('Y-m-01');
  }

  if (isset($HTTP_GET_VARS['end_date'])) {
    $end_date = $HTTP_GET_VARS['end_date'];
  } else {
    $end_date = date('Y-m-d');
  }
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
<td width="100%" valign="top">
<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
        <tr>
          <td class="pageHeading"><?php echo 'Subscription Sales'; ?></td>
          <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td><table>
        <?php if (empty($HTTP_GET_VARS['mID'])) { ?>
        <tr class="main">
          <td>&nbsp;</td>
          <td><?php
    echo tep_draw_form('date_range','stats_subscription_sales.php' , '', 'get');
    echo 'Start Date ' . tep_draw_input_field('start_date', $start_date);
    echo ' <td> ';
    echo ' End Date ' . tep_draw_input_field('end_date', $end_date). '&nbsp;';
    echo '<input type="submit" value="'. 'Report' .'">';
    echo '</td></form>';
?>
          </td>
        </tr>
        <?php } ?>
      </table></td>
  </tr>
  <tr>
  
  <td>
  
  <table border="0" width="100%" cellspacing="0" cellpadding="0">
    <tr>
    
    <td valign="top">
    
    <?php if (empty($HTTP_GET_VARS['mID'])) { ?>
    <table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr class="dataTableHeadingRow">
        <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_MANUFACTURERS_NAME; ?></td>
        <td class="dataTableHeadingContent" align="right"><?php echo TABLE_TOTAL_PRODUCTS; ?></td>
        <td class="dataTableHeadingContent" align="right"><?php echo TABLE_TOTAL_SALES; ?></td>
      </tr>
      <?php
      set_time_limit(90);
		$manufacturers_query_raw = "select manufacturers_id, manufacturers_name
										FROM " . TABLE_MANUFACTURERS . "
										ORDER BY manufacturers_name";
		$manufacturers_query = tep_db_query($manufacturers_query_raw);
		while ($manufacturers = tep_db_fetch_array($manufacturers_query)) {		
			$products_query_raw = "select op.products_quantity, op.final_price
											FROM " . TABLE_ORDERS_PRODUCTS . " AS op
											LEFT JOIN " . TABLE_PRODUCTS . " AS p ON op.products_id = p.products_id
											LEFT JOIN " . TABLE_MANUFACTURERS . " AS m on p.manufacturers_id = m.manufacturers_id
											LEFT JOIN " . TABLE_ORDERS . " AS o ON op.orders_id = o.orders_id
											WHERE o.date_purchased BETWEEN '" . $start_date . " 00:00:00' AND '" . $end_date . " 23:59:59'
											AND m.manufacturers_name = '" . str_replace("'","\'",$manufacturers['manufacturers_name']) . "'
											ORDER BY m.manufacturers_name";
			$products_query = tep_db_query($products_query_raw);
			while ($products = tep_db_fetch_array($products_query)) {	
				$total_quantity = $total_quantity + $products['products_quantity'];
				$total_sales = $total_sales + ($products['final_price'] * $products['products_quantity']);
			}
			if (($total_sales > 0) || ($quantity > 0)) {
?>
      <tr class="dataTableRow" onMouseOver="rowOverEffect(this)" onMouseOut="rowOutEffect(this)" onClick="document.location.href='<?php echo tep_href_link(FILENAME_STATS_MANUFACTURERS, 'mID=' . $manufacturers['manufacturers_id'] . "&start=$start_date&end=$end_date", 'NONSSL'); ?>'">
        <td class="dataTableContent"><?php echo $manufacturers['manufacturers_name']; ?></td>
        <td class="dataTableContent" align="right"><?php echo $total_quantity; ?></td>
        <td class="dataTableContent" align="right">$<?php echo $total_sales; ?>&nbsp;</td>
      </tr>
      <?php	
			}
			$total_sales = 0;
			$total_quantity = 0;	
	  }
?>
    </table>
    <?php 
	} else {

		$manufacturers_query_raw = "select manufacturers_name FROM " . TABLE_MANUFACTURERS . " WHERE manufacturers_id = " . $HTTP_GET_VARS['mID'];
		$manufacturers_query = tep_db_query($manufacturers_query_raw);
		if ($manufacturers = tep_db_fetch_array($manufacturers_query)) {		
?>
    <table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr class="4">
        <td class="main"><h3><?php echo $manufacturers['manufacturers_name']; ?></h3></td>
      </tr>
      <tr class="dataTableHeadingRow">
        <td class="dataTableHeadingContent"><?php echo TABLE_PRODUCT_MODEL; ?></td>
        <td class="dataTableHeadingContent"><?php echo TABLE_PRODUCT_NAME; ?></td>
        <td class="dataTableHeadingContent" align="right"><?php echo TABLE_PRODUCT_QUANTITY; ?></td>
        <td class="dataTableHeadingContent" align="right"><?php echo TABLE_PRODUCT_REVENUE; ?></td>
      </tr>
      <?php			  
			$man_products_query_raw = "select DISTINCT op.products_id FROM " . TABLE_ORDERS_PRODUCTS . " AS op
										LEFT JOIN " . TABLE_PRODUCTS . " AS p ON op.products_id = p.products_id
										LEFT JOIN " . TABLE_MANUFACTURERS . " AS m on p.manufacturers_id = m.manufacturers_id
										LEFT JOIN " . TABLE_ORDERS . " AS o ON op.orders_id = o.orders_id
										WHERE o.date_purchased BETWEEN '" . $HTTP_GET_VARS['start'] . " 00:00:00' AND '" . $HTTP_GET_VARS['end'] . " 23:59:59'
										AND m.manufacturers_id = " . $HTTP_GET_VARS['mID'] . "
										ORDER BY op.products_model";
			$man_products_query = tep_db_query($man_products_query_raw);
			while ($man_products = tep_db_fetch_array($man_products_query)) {	
		
				$products_query_raw = "select op.products_model, op.products_name, op.products_quantity, op.final_price FROM " . TABLE_ORDERS_PRODUCTS . " AS op
											LEFT JOIN " . TABLE_PRODUCTS . " AS p ON op.products_id = p.products_id
											LEFT JOIN " . TABLE_ORDERS . " AS o ON op.orders_id = o.orders_id
											WHERE o.date_purchased BETWEEN '" . $HTTP_GET_VARS['start'] . " 00:00:00' AND '" . $HTTP_GET_VARS['end'] . " 23:59:59'
											AND op.products_id = " . $man_products['products_id'];
				$products_query = tep_db_query($products_query_raw);
				while ($products = tep_db_fetch_array($products_query)) {	
					$products_quantity = ($products_quantity + $products['products_quantity']);
					$final_price = ($final_price + ($products['final_price'] * $products['products_quantity']));
					$products_model = $products['products_model'];	
					$products_name = $products['products_name'];
				}
?>
      <tr class="dataTableRow" onMouseOver="rowOverEffect(this)" onMouseOut="rowOutEffect(this)" onClick="document.location.href='<?php echo tep_href_link(FILENAME_STATS_MANUFACTURERS, 'mID=' . $manufacturers['manufacturers_id'], 'NONSSL'); ?>'">
        <td class="dataTableContent"><?php echo $products_model; ?></td>
        <td class="dataTableContent"><?php echo $products_name; ?></td>
        <td class="dataTableContent" align="right"><?php echo $products_quantity; ?></td>
        <td class="dataTableContent" align="right">$<?php echo $final_price; ?></td>
      </tr>
      <?php
				$total_quantity = $total_quantity + $products_quantity;
				$total_sales = ($total_sales + $final_price);		
				$products_quantity = 0;
				$final_price = 0;
			}
?>
      <tr>
        <td class="dataTableContent" colspan="2" align="right"><strong>Total</strong></td>
        <td class="dataTableContent" align="right"><strong><?php echo $total_quantity; ?></strong></td>
        <td class="dataTableContent" align="right"><strong>$<?php echo $total_sales; ?></strong></td>
      </tr>
      </td>
      
      </tr>
      
    </table>
    <?php  		
		} else {
			echo "<h3>Manufacturer not found!</h3>";
		}	
	}
?>
    </td>
    
    </tr>
    
  </table>
  </td>
  
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
