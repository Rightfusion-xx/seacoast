<?php
  /*
  $Id: cvv2.php, v2.0 2003/05/03 14:14:14 waza04_ Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce / Warren Ashcroft

  Support:
  oscdev@ukcomputersystems.com
  waza04@hotmail.com (MSN Messenger)

  Paypal Donations:
  paypal@ukcomputersystems.com

  Web:
  http://www.ukcomputersystems.com/

  Released under the GNU General Public License
*/
  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_POPUP_CVV);

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_POPUP_CVV, '', 'NONSSL'));

?>
<!doctype html>
<html <?php echo HTML_PARAMS; ?>>
<head>
    <link rel="stylesheet" href="/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/bootstrap/css/bootstrap-responsive.min.css">
    <link href="/css/main.css" rel="stylesheet">
    <link href="/font/fonts.css" rel="stylesheet">
    <!--[if lt IE 9]>
    <script type="text/javascript" src="/jquery/respond.src.js"></script>
    <![endif]-->
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>   
<base href="<?php echo ($_SERVER['HTTPS'] == 'on' ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="<? echo THEMA_STYLE;?>">
<?php require('includes/flash_check.js.php'); ?>
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
    </table></td>
<td valign="top" colspan="2" valign="top"><?php require(DIR_WS_INCLUDES . 'titlebar.php'); ?></td></tr><tr><!-- body_text //-->
<?php
//if ($HTTP_GET_VARS['cctypes']) {
    switch ($HTTP_GET_VARS['cctypes']) {
      case 'uk':
?>
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_image(DIR_WS_IMAGES . 'cvvsmall.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><?php echo TEXT_INFORMATION_VISA; ?></td>
            </tr>
            <tr>
            <td class="main" align="center">
          <SCRIPT LANGUAGE="JavaScript" type="text/javascript">
<!--
// In this section we set up the content to be placed dynamically on the page.
// Customize movie tags and alternate html content below.

if (!useRedirect) {    // if dynamic embedding is turned on
  if(hasRightVersion) {  // if we've detected an acceptable version
    var oeTags = '<OBJECT CLASSID="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"'
    + 'WIDTH="325" HEIGHT="325"'
    + 'CODEBASE="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab">'
    + '<PARAM NAME="MOVIE" VALUE="images/cvv2.swf">'
    + '<PARAM NAME="PLAY" VALUE="true">'
    + '<PARAM NAME="LOOP" VALUE="false">'
    + '<PARAM NAME="QUALITY" VALUE="high">'
    + '<PARAM NAME="MENU" VALUE="false">'
    + '<EMBED SRC="movie.swf"'
    + 'WIDTH="325" HEIGHT="325"'
    + 'PLAY="true"'
    + 'LOOP="false"'
    + 'QUALITY="high"'
    + 'MENU="false"'
    + 'TYPE="application/x-shockwave-flash"'
    + 'PLUGINSPAGE="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash">'
    + '</EMBED>'
    + '</OBJECT>';

    document.write(oeTags);   // embed the flash movie
  } else {  // flash is too old or we can't detect the plugin
    // NOTE: height, width are required!
    var alternateContent =
        '<tr>'
      + '<td class="main" align="center"><?php echo VISA_SET_UK . '<br>'; ?><IMG SRC="images/cvv2visa.gif">'
      + '</td>'
      + '</tr>';

    document.write(alternateContent);  // insert non-flash content
  }
}

// -->
</SCRIPT>
<NOSCRIPT>
          <tr>
            <td class="main" align="center"><?php echo VISA_SET_US . '<br>'; ?><IMG SRC="images/cvv2visa.gif">
            </td>
          </tr>
</NOSCRIPT></td>
            </tr>
        </table></td>
      </tr>
      <tr>
        <td align="right" class="main"><br><?php echo '<a href="' . tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL') . '">' . tep_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE) . '</a>'; ?></td>
      </tr>
    </table></td>
    
<?php
break;
case 'us':
?>
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_image(DIR_WS_IMAGES . 'cvvsmall.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><?php echo TEXT_INFORMATION_AMEX; ?></td>
          </tr>
          <tr>
            <td class="main" align="center"><?php echo VISA_SET_US . '<br>'; ?><IMG SRC="images/cvv2visa.gif">
            </td>
          </tr>
          <tr>
            <td class="main" align="center"><?php echo '<br>' . AMEX_SET_US . '<br>'; ?><IMG SRC="images/cvv2amex.gif">
             </td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td align="right" class="main"><br><?php echo '<a href="' . tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL') . '">' . tep_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE) . '</a>'; ?></td>
      </tr>
    </table></td>
<?php
break;
default:
?>
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_image(DIR_WS_IMAGES . 'cvvsmall.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><?php echo TEXT_INFORMATION_UKUS; ?></td>
          </tr>
          <tr>
            <td class="main" align="center"><?php echo VISA_SET_UK . '<br>'; ?><IMG SRC="images/cvv2visa.gif">
            </td>
          </tr>
          <tr>
            <td class="main" align="center"><?php echo '<br>' . AMEX_SET_US . '<br>'; ?><IMG SRC="images/cvv2amex.gif">
             </td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td align="right" class="main"><br><?php echo '<a href="' . tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL') . '">' . tep_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE) . '</a>'; ?></td>
      </tr>
    </table></td>
<?php
break;
}
//}
?>
<!-- body_text_eof //-->
   <TD WIDTH="<?php echo BOX_WIDTH; ?>" VALIGN="top" rowspan="2">
     <TABLE BORDER="0" WIDTH="<?php echo BOX_WIDTH; ?>" CELLSPACING="2" CELLPADDING="0">
<!-- right_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_right.php'); ?>
<!-- right_navigation_eof //-->
    </table></td>
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
