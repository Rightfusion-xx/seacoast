<?php

define('TITLE_PRICE', 'Price list');

define('SHOW_QUANTITY',false); // true - show, false - hide quantity
define('SHOW_MARKED_OUT_STOCK',false); // show marked out of stock (true - show, false - hide)
define('TAX_INCREASE', 0); // 0 - No increase, 1 - Add 1%, 5 - Add 5%, Any number - add number%
define('SHOW_MODEL',true); // true - show model, false - hide model

	require('includes/application_top.php');
	// the following cPath references come from application_top.php
	$category_depth = 'top';
	if (isset($cPath) && tep_not_null($cPath)) {
		$categories_products_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where categories_id = '" . (int)$current_category_id . "'");
		$cateqories_products = tep_db_fetch_array($categories_products_query);
		if ($cateqories_products['total'] > 0) {
			$category_depth = 'products'; // display products
		} else {
			$category_parent_query = tep_db_query("select count(*) as total from " . TABLE_CATEGORIES . " where parent_id = '" . (int)$current_category_id . "'");
			$category_parent = tep_db_fetch_array($category_parent_query);
			if ($category_parent['total'] > 0) {
				$category_depth = 'nested'; // navigate through the categories
			} else {
				$category_depth = 'products'; // category has no products, but display the 'no products' message
			}
		}
	}
	require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_DEFAULT);
	$breadcrumb->add(TITLE_PRICE, tep_href_link("price_list.php", '', 'SSL')); 
?>

<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>"> 
<title><?php echo TITLE; ?> - Price List <?php echo date("Y"); ?></title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>

<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
	<!-- header //-->
		<?php require(DIR_WS_INCLUDES . 'header.php');?>
	<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="3" cellpadding="3">
	<tr>
<?php  // print function and edit remove by willross
if ($print=="yes"){?>
<!-- column_left disabled for print //-->
<?php } else {?>
		<TD WIDTH="<?php echo BOX_WIDTH; ?>" VALIGN="top" rowspan="2">
			<table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
				<!-- left_navigation //-->
					<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
				<!-- left_navigation_eof //-->
			</table>
		</td>
<?php }?>
		<td valign="top" colspan="2" valign="top"><?php require(DIR_WS_INCLUDES . 'titlebar.php'); ?></td></tr><tr><!-- body_text //-->
		<td valign="top">
			<table border="0" cellspacing="0" cellpadding="2">
				 <tr>
    				<td class="pageHeading"><? echo TITLE_PRICE ?>&nbsp;<?php echo date("Y"); ?><sup>
<?php  // print function and edit remove by willross
if ($print=="yes"){?>
<font style="font-size:10px"><a href="/price_list.php">[full view]</a></font>
<?php } else {?>
<font style="font-size:10px"><a href="/price_list.php?print=yes">[printable version]</a></font>
<?php }?></sup>
                   </td>
  				</tr>
  				<tr>
    				<td>
<?
// group have products?
function check_products($id_group){
	$products_price_query = tep_db_query("select products_to_categories.products_id FROM products_to_categories where products_to_categories.categories_id = ".$id_group." LIMIT 0,1");
	if($products_price = tep_db_fetch_array($products_price_query)){
		return true;	
	}
	return false;
}

// list products determined group
function get_products($id_group){
	global $currencies;
	$query = "";
	if(!SHOW_MARKED_OUT_STOCK){
		$query = " and products.products_status = 1";
	}
	$products_price_query = tep_db_query("select products_description.products_name, products.products_quantity, products.products_price, products.products_model, products_to_categories.products_id, products_to_categories.categories_id FROM products, products_description, products_to_categories where products.products_id = products_description.products_id and products.products_quantity <> 0".$query." and products.products_id = products_to_categories.products_id and products_to_categories.categories_id = ".$id_group);
	$x=0;
	while ($products_price = tep_db_fetch_array($products_price_query)){
		$cell = tep_get_products_special_price($products_price['products_id']);
		if($cell == 0)
			$cell = $products_price['products_price'];
		if($x==1) {
			$col = "#F8F8F9";
			$x = 0;	
		}else{
			$col = "#FFFFFF";
			$x++;
		}
		$quantity = "";
		$model = "";
		if(SHOW_QUANTITY)
			$quantity = "<td width=\"100\" align=\"right\" class=\"productListing-data\">(".$products_price['products_quantity'].")</td>";
		if(SHOW_MODEL)
			$model = "<td width=\"100\" align=\"right\" class=\"productListing-data\">[".$products_price['products_model']."]</td>";
		print "<tr bgcolor=\"".$col."\">".$model."<td width=\"1000\" class=\"productListing-data\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"" . tep_href_link(FILENAME_PRODUCT_INFO, "products_id=" . $products_price['products_id']) . "\">".$products_price['products_name']."</a></td><td width=\"150\" align=\"right\" class=\"productListing-data\">".$currencies->display_price($cell,TAX_INCREASE)."</td></tr>";
	}
}

// get all groups
function get_group($id_parent,$position){
	$groups_price_query = tep_db_query("select categories.categories_id, categories_description.categories_name from categories, categories_description where categories.categories_id = categories_description.categories_id and categories.parent_id = ".$id_parent." order by categories.sort_order");
	while ($groups_price = tep_db_fetch_array($groups_price_query)){
		$str = "";
		for($i = 0; $i < $position; $i++){
			$str = $str . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		}
		$class = "productListing-heading";
		if($position == 0) {
			$class = "headerNavigation";
			print "<tr><td colspan=\"4\" width=\"1000\" class=\"productListing-data\">&nbsp;</td></tr>";
		}
		if(check_products($groups_price['categories_id']) || $position == 0){
			print "<tr><td colspan=\"4\" width=\"1000\" class=\"".$class."\"><strong><font color=\"#FFFFFF\">".$str.$groups_price['categories_name']."</font></strong></td></tr>";
			get_products($groups_price['categories_id']);
		}
		get_group($groups_price['categories_id'],$position+1);
	}
}
?>
<table width="100%" border="0" cellspacing="1" cellpadding="0">
<?

  get_group(0,0);
?>

</table>
    				</td>

  				</tr>
			</table>
		</td>
		<!-- body_text_eof //-->
<?php  // print function and edit remove by willross
if ($print=="yes"){?>
<!-- column_right disabled for print //-->
<?php } else {?>
		<TD WIDTH="<?php echo BOX_WIDTH; ?>" VALIGN="top" rowspan="2">
			<table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
			<!-- right_navigation //-->
				<?php require(DIR_WS_INCLUDES . 'column_right.php'); ?>
			<!-- right_navigation_eof //-->
			</table>
		</td>
<?php }?>
	</tr>

</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
