<?php
/*
  $Id: shipping.php,v 1.22 2003/06/05 23:26:23 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_SHIPPING);

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_SHIPPING));
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
    <TD WIDTH="<?php echo BOX_WIDTH; ?>" VALIGN="top" BGCOLOR="#336699">
	  <TABLE BORDER="0" WIDTH="<?php echo BOX_WIDTH; ?>" CELLSPACING="2" CELLPADDING="0">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
      </TABLE></TD>
<!-- body_text //-->
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
          <td>
            <table width="680" border="0" height="48" align="center">
              <tr> 
                <td width="328" height="2"><font face="Arial, Helvetica, sans-serif" size="3"><a href="http://usps.com"><img src="http://www.seacoastvitamins.com/img_index/usps.jpg" alt="http://www.usps.com" border="0"></a></font></td>
                <td width="342" height="2"><font face="Arial, Helvetica, sans-serif" size="3"><a href="http://ups.com"><img src="http://www.seacoastvitamins.com/img_index/ups.JPG" alt="http://www.ups.com" border="0"></a></font></td>
              </tr>
              <tr> 
                <td width="328" height="60"> 
                  <ul>
                    <li><font face="Arial, Helvetica, sans-serif" size="3"> USPS 
                      Flat Rate Shipping</font></li>
                    <li><font face="Arial, Helvetica, sans-serif" size="3">Same 
                      Day Shipping </font></li>
                    <li><font face="Arial, Helvetica, sans-serif" size="3">If 
                      your order is placed before 2:00pm;EST</font></li>
                    <li><font face="Arial, Helvetica, sans-serif" size="3">No 
                      Padded Shipping Cost</font></li>
                    <li><font face="Arial, Helvetica, sans-serif" size="3">All 
                      Shipping At Cost</font></li>
                    <li><a href="http://www.stamps.com/shipstatus/"><font face="Arial, Helvetica, sans-serif" size="3">USPS 
                      - Tracking</font></a><font face="Arial, Helvetica, sans-serif" size="3"><br>
                      <br>
                      </font></li>
                  </ul>
                </td>
                <td width="342" height="60"> 
                  <ul>
                    <li><font face="Arial, Helvetica, sans-serif" size="3">UPS</font></li>
                    <li><font face="Arial, Helvetica, sans-serif" size="3">Same 
                      Day Shipping</font></li>
                    <li><font face="Arial, Helvetica, sans-serif" size="3"> If 
                      your order is placed before 5:00pm; EST</font></li>
                    <li><font face="Arial, Helvetica, sans-serif" size="3">No 
                      Padded Shipping Cost</font></li>
                    <li><font face="Arial, Helvetica, sans-serif" size="3">All 
                      Shipping At Cost</font></li>
                    <li><font face="Arial, Helvetica, sans-serif" size="3">Commercial 
                      Shipping Available</font></li>
                    <li><font face="Arial, Helvetica, sans-serif" size="3"><a href="http://www.ups.com/WebTracking/track?loc=en_US">UPS 
                      - Tracking</a></font></li>
                  </ul>
                </td>
              </tr>
              <tr> 
                <td colspan="2" height="2"> 
                  <ul>

                  </ul>
                </td>
              </tr>
            </table>
            <div align="center"><br>
            </div>
            <table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main">
			<table border="0" width="100%" cellspacing="0" cellpadding="7">
              <tr>
                  <td width="50%" valign="top"> <strong>Domestic Shipping</strong><BR>
				        <UL>
                          <LI>We offer either UPS or USPS Priority Mail shipping.
                          <LI>Your rate will be automatically calculated when 
                            you go through the checkout procedure. We locate 
                            the lowest shipping charges available.
                          <LI>The most notable difference between the two carriers 
                            is that orders shipped via UPS will be trackable throughout 
                            their journey, while orders shipped via Priority Mail 
                            receive delivery confirmation only.
                          <LI>Insurance only by request <font size="2">-</font><b><font size="1">extra 
                            charges may apply</font></b>
                        </UL>
				</td>
				  <td width="50%"> <strong>International Shipping</strong><BR>
				        <UL>
                          <LI>All international orders will be shipped via USPS 
                            Global Express mail.
                          <LI>Due to the complications involved in shipping nutritional 
                            supplements to foreign countries, it is up to the 
                            customer to know the laws and restrictions of their 
                            country regarding the importation of any products 
                            purchased from us.
                          <LI>We will not refund or replace any international 
                            orders until either the product is returned to us, 
                            or we are reimbursed for our losses through the postal 
                            service.
                          <LI>Shipping costs are non-refundable.
                        </UL>
                        <p><font size="1">International Shipping<br>
                          Seacoast Vitamins ships nutritional supplements to countries 
                          around the world. Please note: It is important for you 
                          to become familiar with the Customs policies for your 
                          specific country before ordering. Customs regulations 
                          vary greatly by country and lack of knowledge regarding 
                          the customs rules governing the country your importing 
                          to can result in your order incurring high import duties, 
                          delivery being delayed, or returned to us. It is your 
                          responsibility to check with your Customs office to 
                          see if your country permits the shipment of our products.</font></p>
                        <p><font size="1">International Shipping Terms &amp; Conditions:<br>
                          All prices listed are in U.S. dollars. When you pay 
                          by credit card, the credit card company will calculate 
                          the exchange rate and include it in your monthly statement. 
                          </font></p>
                        <p><font size="1">All International shipments will be 
                          charged postage costs calculated by weight and destination. 
                          Seacoast Vitamins&#146;s prices do not include customs 
                          fees, taxes and tariffs. Customers outside of the United 
                          States may be subject to customs fees and/or import 
                          duties and taxes, which are levied once a shipment reaches 
                          your country. You the customer are solely responsible 
                          for any additional customs clearance fees; we have no 
                          control over these charges and cannot predict what they 
                          may be. It is your responsibility to make sure that 
                          you are allowed to import nutritional supplements from 
                          the US</font></p>
                        <p><font size="1">If the package(s) is returned to us 
                          because of an address error made by the customer or 
                          the items ordered are not allowed into the country by 
                          customs, the customer will be responsible for the shipping 
                          cost billed to Seacoast Vitamins for the return of the 
                          package. If the package is stopped by customs because 
                          items ordered are not allowed in the country and the 
                          package is abandoned or customs destroys the package, 
                          there will be no credit to the customer for the order. 
                          <br>
                          Customs policies vary widely from country to country; 
                          you should contact your local customs office or tax 
                          authority for information specific to your situation. 
                          Additionally, when ordering from Seacoastvitamins.com, 
                          you are considered the importer of record and must comply 
                          with all laws and regulations of the country in which 
                          you are receiving the goods. Any fees charged to Seacoastvitamins.com 
                          due to non compliance with your countries laws and regulations 
                          will be charged to you, the importer. If an item or 
                          items are returned to us due to non compliance issues 
                          with your country&#146;s&#146; laws and regulations, 
                          a refund will be issued for the merchandise only there 
                          will be a 15% restocking fee.<br>
                          </font><font size="2"> </font> </p>
                      </td>
              </tr>
			  <tr>
                  <td width="50%" valign="top"> <strong>Order Tracking</strong><BR>
				<UL><LI>Tracking is available on all domestic orders shipped via UPS.<LI>International orders are trackable up to the time they leave the US. After that, trackability varies depending on destination country.</UL>
				</td>
				  <td width="50%" valign="top"> <strong>Tax Policy</strong><BR>
				Michigan residents will be charge a 6% sales tax on non-food items.
				</td>
              </tr>
			  <tr>
                  <td width="50%" valign="top"> <strong>Back Orders</strong><BR>
				We make every effort to maintain our full line of products in stock, but due to the unpredictability of the internet, we will occasionally be out of stock on an item.  We will contact you in the event of any backorders, and you will be given the following options:<UL><LI>Hold the entire order until the backordered product arrives in stock.<LI>Process the order with those products that are in stock and reorder the backordered products at another time.</UL> 
				</td>
				  <td width="50%" valign="top"> <strong>Return Policy</strong><BR>
				 <UL><LI>Any unopened items may be returned within 30 days of purchase.<LI>You will be charged a 15% restocking fee on all returned items.<LI>Shipping and handling fees are non-refundable.<LI>Items returned more than 30 days after purchase will not be accepted.</UL>
				</td>
              </tr>
            </table>
			</td>
          </tr>
        </table></td>
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
   <TD WIDTH="<?php echo BOX_WIDTH; ?>" VALIGN="top" >
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
