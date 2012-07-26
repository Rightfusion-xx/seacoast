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
<title>FAQ and Shipping</title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">



<!-- header //-->
<?php
    require(DIR_WS_INCLUDES . 'header.php');
    $_REQUEST['page_caption'] = 'FAQ & Shipping';
?>
<!-- header_eof //-->

<!-- body //-->
<div class="container">
<div class="row">
<div class="span12">

<div class="alert alert-error">
                              <span style="font-size:18pt;">We <i class="icon-heart">&nbsp;</i> Customers   </span>
</div>

		<p>
        We ship primarily from our warehouse in Las Vegas, Nevada, which is located next to a United States Postal Service logistics hub. Most regular USPS deliveries to locations in the US arrive in about 2 days (sometimes less!).
         </p>
         <p>
                <img src="http://www.seacoastvitamins.com/img_index/usps.jpg" border="0">
                <img src="http://www.seacoastvitamins.com/img_index/ups.JPG" border="0" style="float:left;">
                <br style="clear:both" />
                </p>

              <p>
              <ul>
                <li>
                    We pass the discounts right on to you, and charge for shipping at-cost. There's never hidden service charges, or handling fees.
                </li>
                <li>
                    Orders made before 6:00pm Eastern Standard Time ship the same day.
                </li>
                <li>
                    Includes tracking on most packages, and insurance in some cases.
                </li>
              </ul>
              </p>

			<table border="0" width="100%" cellspacing="0" cellpadding="7">
              <tr>
                  <td width="50%" valign="top"> <strong>Domestic Shipping</strong><BR>
				        <UL>
                          <LI>We offer both UPS and USPS Priority Mail shipping.
                          <LI>The shipping rate is automatically calculated when
                            you go through the checkout procedure. This is determined by weight, size, and destination. We locate
                            the lowest shipping charges available.
                          <LI>Both USPS and UPS will be trackable throughout
                            their journey.
                            <li>A delivery signature is required for orders over $200.</li>
                          <LI>Add insurance by request <b>extra
                            charges apply (at-cost)</b>
                        </UL>
				</td>
				  <td width="50%" rowspan="5" valign="top"> <strong>International Shipping</strong><BR>
				        <UL>
                          <LI>For international shipping, we prefer USPS Global Express Mail.
                          <LI>Sorry. Shipping to international destination is sometimes difficult. It is up to you
                            to know the laws and restrictions of your
                            country regarding the importation of any products
                            shipped to you.
                          <LI>Sorry.  We cannot refund or replace any international
                            orders unless the product is returned to us,
                            or we are reimbursed for our losses through the postal
                            service.
                        <li>We will not lower the value of the order under any circumstance.    </li>

                        </UL>

                        <p>
                          Seacoast Vitamins ships nutritional supplements to countries
                          around the world. It is important for you
                          to become familiar with the Customs policies for your
                          specific country before ordering. Customs regulations
                          vary greatly by country and lack of knowledge regarding
                          the customs rules governing the country your importing
                          to can result in your order incurring high import duties,
                          delivery being delayed, or returned to us. It is your
                          responsibility to check with your Customs office to
                          see if your country permits the shipment of our products.</p>
                        <p>International Shipping Terms &amp; Conditions:<br>
                          All prices listed are in U.S. dollars. When you pay
                          by credit card, the credit card company will calculate
                          the exchange rate and include it in your monthly statement.
                          </p>
                        <p>All International shipments are
                          charged postage costs calculated by weight and destination.
                          Seacoast Vitamins prices do not include customs
                          fees, taxes and tariffs. Customers outside of the United
                          States may be subject to customs fees and/or import
                          duties and taxes, which are levied once a shipment reaches
                          your country.
                        <p>If your order is returned to us, we will process a refund on the products, but sadly, the shipping costs will still be charged to you.

                          <p>
                          Customs policies vary widely from country to country;
                          you should contact your local customs office or tax
                          authority for information specific to your situation.
                          Additionally,
                          you are considered the importer of record and must comply
                          with all laws and regulations of the country in which
                          you are receiving the goods. </p>

                      </td>
              </tr>
			  <tr>
                  <td width="50%" valign="top"> <strong>Order Tracking</strong><BR>
				<UL><LI>Tracking is available on all domestic orders shipped via UPS.<LI>International orders are trackable up to the time they leave the US. After that, trackability varies depending on destination country.</UL>
				</td>
              </tr>
              <tr>
				  <td width="50%" valign="top"> <strong>Tax Policy</strong><BR>
				Michigan residents will be charge a 6% sales tax on non-food items. Nevada residents will be charge 8.1% for all purchases
				</td>
              </tr>
			  <tr>
                  <td width="50%" valign="top"> <strong>Back Orders</strong><BR>
				We try really hard to keep all of our 10,000 products in stock. Occasionally, we will be out of stock on an item.  If an item is out of stock, we will ship the rest of the order to you right away.
                When the backorder item comes in, it will automatically be shipped. We will let you know the status of your order in any event!
				</td>
                </tr>
                <tr>
				  <td width="50%" valign="top"> <strong>Return Policy</strong><BR>
				 <p>Any unopened items may be returned within 30 days of purchase. There is no re-stocking fee! Woohoo! :) Vitamins-Direct members may return un-opened product past 30 days, and may also return opened products within 30 days
                 for a full refund. Just return the product to our warehouse with your order number, and the adjustments will be made. You will be notified by email with a confirmation of the refund.</p>
                 <p>Seacoast.com<br/>
                 7310 Smoke Ranch Rd, Ste K</br>
                 Las Vegas, NV 89128</p>
				</td>
              </tr>
            </table>



</div></div></div>

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
