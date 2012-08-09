<?php
/*
  $Id: index.php,v 1.1 2003/06/11 17:37:59 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');



  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_DEFAULT);
?>
<!doctype html>
<html <?php echo HTML_PARAMS; ?>>
<head>
  <title>Join Seacoast Vitamins FREE!</title>


<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<meta name="description" content="Join for free to Seacoast Vitamins for special discounts, instantly."/>
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


    <table border="0" width="100%" cellspacing="0" cellpadding="7">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td><h1>Join Seacoast Vitamins FREE!</h1></td>    
          </tr>
        </table></td>
      </tr>
      
      <tr>
        <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
		  <tr>
            <td class="main" align="left" valign="top"><?php require(DIR_WS_INCLUDES . 'newslettersub.php'); ?></td>
           </tr>
		  <TR>
                <TD ALIGN="CENTER">&nbsp;</TD>
              </TR>
          <tr>
                <td class="main" align="right">
   </td>
          </tr>
		  <TR>
                
              </TR>
         
          <tr>
            <td></td>
          </tr>
          <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
          </tr>
          
	</td>
	</tr></table></td>

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
