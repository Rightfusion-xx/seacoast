<?php
/*
  $Id: popup_cvv.php,v 1.3 2003/02/19 23:10:56 jgb Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  $navigation->remove_current_page();

  require(DIR_WS_LANGUAGES . $language . '/' . modules . '/' . payment . '/' . 'cc.php');
?>
<!doctype html>
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>
<style type="text/css"><!--
BODY { margin-bottom: 10px; margin-left: 10px; margin-right: 10px; margin-top: 10px; }
//--></style>
<body marginwidth="10" marginheight="10" topmargin="10" bottommargin="10" leftmargin="10" rightmargin="10">
<TABLE WIDTH="100%" BORDER="1" CELLPADDING="3" CELLSPACING="0" BGCOLOR="#FFFFCC">
<TR>
<TD ALIGN="CENTER">
Here is the CVV2 on VISA, Master Card, and Discover.<BR>
<img src="images/cv_card.gif">
<P>
Here is the CVV2 on American Express.
<img src="images/cv_amex_card.gif">

<p class="smallText" align="right"><?php echo '<a href="javascript:window.close()">' . TEXT_CLOSE_WINDOW . '</a>'; ?></p>

</body>
</html>
<?php require('includes/application_bottom.php'); ?>
