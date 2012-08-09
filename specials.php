<?php
/*
  $Id: specials.php,v 1.49 2003/06/09 22:35:33 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_SPECIALS);

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_SPECIALS));
?>
<!doctype html>
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<?php

if ( file_exists(DIR_WS_INCLUDES . 'header_tags.php') ) {

  require(DIR_WS_INCLUDES . 'header_tags.php');

} else {

?> 

  <title><?php echo TITLE ?></title>

<?php

}

?>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">

 

<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<TABLE BORDER="0" WIDTH="100%" CELLSPACING="0" CELLPADDING="0">
  <TR>
    <TD WIDTH="<?php echo BOX_WIDTH; ?>" VALIGN="top" rowspan="2">
	  <TABLE BORDER="0" WIDTH="<?php echo BOX_WIDTH; ?>" CELLSPACING="2" CELLPADDING="0">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
      </TABLE></TD>
<td valign="top" colspan="2" valign="top"><?php require(DIR_WS_INCLUDES . 'titlebar.php'); ?></td></tr><tr><!-- body_text //-->
    <td width="100%" valign="top">
		
		<table border="0" width="100%" cellspacing="0" cellpadding="12">
      <tr>
          <td> 
            <table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td><h1>Extra Special Savings | Introductory and One-Time-Only Offers</h1>
            <?php if(!isset($_REQUEST['page'])) {?>
            <p>
                The friendliest natural health store in the world is pleased to offer the following premier items at one-time-only, hot off the press, going fast prices. Seacoast selects only
                premier natural products for your naturally healthy lifestyle.
            </p><?php } ?></td>
            <td class="pageHeading" align="right"><div align="center">
              <?php //echo tep_image(DIR_WS_IMAGES . 'table_background_specials.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?>
            </div></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
  $specials_query_raw = "select p.products_id, pd.products_name, p.products_price, p.products_tax_class_id, p.products_image, s.specials_new_products_price from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_SPECIALS . " s where p.products_status = '1' and s.products_id = p.products_id and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and s.status = '1' order by s.specials_date_added DESC";
  $specials_split = new splitPageResults($specials_query_raw, MAX_DISPLAY_SPECIAL_PRODUCTS);

  if (($specials_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '1') || (PREV_NEXT_BAR_LOCATION == '3'))) {
?>
      <tr>
        <td>
		<table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr CLASS="infoBoxContents">
            <td class="smallText"><?php echo $specials_split->display_count(TEXT_DISPLAY_NUMBER_OF_SPECIALS); ?></td>
            <td align="right" class="smallText"><?php echo TEXT_RESULT_PAGE . ' ' . str_replace('?page=1','',$specials_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y')))); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr CLASS="infoBoxContents">
        <td COLSPAN="3"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
  }
?>
      <tr>
        <td>
		<table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr CLASS="infoBoxContents">
<?php
    $row = 0;
    $specials_query = tep_db_query($specials_split->sql_query);
    while ($specials = tep_db_fetch_array($specials_query)) {
      $row++;

      echo '            <td align="center" width="33%" class="smallText" VALIGN="TOP"><TABLE BORDER="0" WIDTH="100%" CELLPADDING="1" CELLSPACING="0" CLASS="infoBox"><TR><TD><TABLE WIDTH="100%" BORDER="0" CELLPADDING="2" CELLSPACING="0"><TR CLASS="infoBoxContents"><TD ALIGN="CENTER"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $specials['products_id']) . '">' . $specials['products_name'] . '</a><br><s>' . $currencies->display_price($specials['products_price'], tep_get_tax_rate($specials['products_tax_class_id'])) . '</s><br><span class="productSpecialPrice">' . $currencies->display_price($specials['specials_new_products_price'], tep_get_tax_rate($specials['products_tax_class_id'])) . '</span></td></TR></TABLE></TD></TR></TABLE></TD>' . "\n";

      if ((($row / 3) == floor($row / 3))) {
?>
          </tr>
          <tr CLASS="infoBoxContents">
            <td COLSPAN="3"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
          </tr>
          <tr>
<?php
      }
    }
?>
          </tr>
        </table></td>
      </tr>
<?php
  if (($specials_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '2') || (PREV_NEXT_BAR_LOCATION == '3'))) {
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2"><TR><TD>
		<table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr CLASS="infoBoxContents">
            <td class="smallText"><?php echo $specials_split->display_count(TEXT_DISPLAY_NUMBER_OF_SPECIALS); ?></td>
            <td align="right" class="smallText"><?php echo TEXT_RESULT_PAGE . ' ' . str_replace('?page=1','',$specials_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y')))); ?></td>
          </tr>
        </table></td>
      </tr>
<?php
  }
?>
    </table></TD></TR></TABLE></td>
		
<!-- body_text_eof //-->
   <TD WIDTH="<?php echo BOX_WIDTH; ?>" VALIGN="top" rowspan="2">
     <TABLE BORDER="0" WIDTH="<?php echo BOX_WIDTH; ?>" CELLSPACING="2" CELLPADDING="0">
<!-- right_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_right.php'); ?>
<!-- right_navigation_eof //-->
     </TABLE></TD></TR></TABLE>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
