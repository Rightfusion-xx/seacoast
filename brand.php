<?php
/*
  $Id: index.php,v 1.1 2003/06/11 17:37:59 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

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
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>




  <title>Top Selling Vitamin Brands & Manufacturers</title>

<meta name="keywords" content="vitamin manufacturers"/>
<meta name="description" content="Best brands and manufacturers of nutritional supplements."/>

<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
    <!-- header //-->
    <?php
        require(DIR_WS_INCLUDES . 'header.php');
        $_REQUEST['page_caption'] = 'Best Selling Nutritional Supplement Brands & Manufacturers';
    ?>
    <!-- header_eof //-->
    <div id="content">
        <p>
            Below are the best selling brands of supplements from manufacturers around the world. These manufacturers abide by
            good manufacturing principles and source their raw material ethically. Products and goods are required to meet
            nutritional standards and are subject to FDA regulations on nutritional supplements.
        </p>
        <?php $query=tep_db_query('SELECT manufacturers_name, manufacturers_id FROM manufacturers m order by manufacturers_name asc');?>
        <ul>
            <?php while($manufacturer_info=tep_db_fetch_array($query)):?>
                <li style="float:left;width:200px;margin-left:50px;"><a href="/index.php?manufacturers_id=<?php echo $manufacturer_info['manufacturers_id'];?>"><?php echo $manufacturer_info['manufacturers_name'];?></a></li>
            <?php endwhile?>
        </ul>
    </div>
    <br style="clear:both;"/>
    <?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
    <!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>