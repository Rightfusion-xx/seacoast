<?php
/*
  $Id: admin_notes_help.php,v 1.2 2003/04/04 10:58:19 roberthellemans Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ADMIN_NOTES_HELP);
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title>Admin Notes Help</title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
</head>
<style type="text/css"><!--
BODY { margin-bottom: 10px; margin-left: 10px; margin-right: 10px; margin-top: 10px; }
//--></style>
<body marginwidth="10" marginheight="10" topmargin="10" bottommargin="10" leftmargin="10" rightmargin="10">
<table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td valign="top">
		<table border="0" width="100%" cellspacing="0" cellpadding="2">
			<tr class="dataTableHeadingRow">
				<td class="dataTableHeadingContent" align="center"><?php echo TEXT_GRAY_TITLE; ?></td>
			</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td width="100%" class="dataTableContent">
		<?php echo TEXT_NOTE; ?>
		<br><br>
		<?php echo ADMIN_NOTES_TITLE . AUTHOR . TEXT_IC_ONE; ?></td>
	</tr>
</table>
<p class="smallText" align="right"><?php echo '<a href="javascript:window.close()">' . TEXT_CLOSE_WINDOW . '</a>'; ?></p>
</body>
</html>
<?php require('includes/application_bottom.php'); ?>