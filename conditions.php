<?php
/*
  $Id: conditions.php,v 1.22 2003/06/05 23:26:22 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CONDITIONS);

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_CONDITIONS));
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
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
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php //echo tep_image(DIR_WS_IMAGES . 'table_background_specials.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td class="main">
		<table border="0" width="100%" cellspacing="0" cellpadding="7">
              <tr>
                  <td width="50%" valign="top"> <strong>Search</strong><BR>
				Enter the name of the product you are looking for in our search field and click on the magnifying glass or hit enter. <BR>If you are less sure of the name of the product, or want a more extensive search, click on the advanced search link. <BR>Be sure and check the box marked "search in product descriptions" when using advanced search options.
				</td>
				  <td width="50%"> <strong>Browse</strong><BR>
				If you are looking for products made by a specific manufacturer, use the drop down list on the left.  <BR>From there you will be taken to a list of all the products we carry in that brand. <BR>You can narrow your choices by using the drop down menu at the top of the page, which will allow you to browse the various categories within that particular brand.
				<P>
				Similarly, you can choose a product category from the list on the left, and then browse the various manufacturers within that category using the drop down menu at the top of the page.
				</td>
              </tr>
			  <tr>
                  <td width="50%" valign="top"> <strong>Call Us</strong><BR>
				If you would like to order by phone, we can be reached from 9 am to 6 pm Eastern Time. <UL><LI>US & Canada:<BR>1-800-555-6792. <LI>International:<BR>1-702-508-9054.</UL>
				</td>
				  <td width="50%" valign="top"> <strong>Fax Us</strong><BR>
				You can fax your orders to:<UL><LI>1-702-508-9114</UL>Look for our printable fax order form, coming soon.
				</td>
              </tr>
			  <tr>
                  <td width="50%" valign="top"> <strong>Payment Options</strong><BR>
				We accept all the major credit cards (Visa, Master Card, American Express, Discover, and Diners Club), or you can pay via check or money order.  <UL><LI>Checks or money orders must be drawn on US funds.<LI>When paying by check or money order, please note that we will not process your order until we receive payment.</UL> 
				</td>
				  <td width="50%" valign="top"> <strong>Mailing Address</strong><BR>
				Orders via check or money order should be mailed to:<P>
				Seacoast Vitamins<BR>
				PO Box 57<BR>
				Deerton, MI 49822
				</td>
              </tr>
            </table>
		</td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td align="right"><?php echo '<a href="' . tep_href_link(FILENAME_DEFAULT) . '">' . tep_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE) . '</a>'; ?></td>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
		
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
