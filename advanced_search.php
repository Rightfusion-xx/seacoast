<?php
/*
  $Id: advanced_search.php,v 1.50 2003/06/05 23:25:46 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ADVANCED_SEARCH);

  $breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_ADVANCED_SEARCH));
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<meta name="robots" content="noindex, nofollow">
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<title><?php echo TITLE; ?></title>
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
        <td valign="top"><TABLE WIDTH="100%" BORDER="0" CELLPADDING="1" CELLSPACING="0" BGCOLOR="#336699"><TR><TD>
<TABLE WIDTH="100%" BORDER="0" CELLPADDING="7" CELLSPACING="0" BGCOLOR="#FFFFFF"><TR><TD><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE_1; ?></td>
            <td class="pageHeading" align="right"><?php //echo tep_image(DIR_WS_IMAGES . 'table_background_browse.gif', HEADING_TITLE_1, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
  if ($messageStack->size('search') > 0) {
?>
      <tr>
        <td><?php echo $messageStack->output('search'); ?></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
  }
?>
      <tr>
        <td valign="top"><p>Search now for your favorite products on SeacoastVitamins.com.</p>
     
        
  <!-- Google CSE code begins -->
<form id="searchbox_000660513327178783876:xhlq1omeol8" onsubmit="return false;" >
  <input type="text" name="q" size="40"/>
  <input type="submit" value="Search"/>
</form>
<script src="http://www.google.com/coop/cse/brand?form=searchbox_000660513327178783876:xhlq1omeol8"></script>

<div id="results_000660513327178783876:xhlq1omeol8" style="display:none">
  <div class="cse-closeResults"> 
    <a>&times; close</a>
  </div>
  <div class="cse-resultsContainer"></div>
</div>

<style type="text/css">
@import url(http://www.google.com/cse/api/overlay.css);
</style>

<script src="http://www.google.com/uds/api?file=uds.js&v=1.0&key=ABQIAAAAglTwHuKa4X6ginkI8s9FMRQsrXfHugipe53ZNGeWAJ68l02CwxTIZvxnFVdos6rh6UZls78iBqeL9Q" type="text/javascript"></script>
<script src="http://www.google.com/cse/api/overlay.js"></script>
<script type="text/javascript">
function OnLoad() {
  new CSEOverlay("000660513327178783876:xhlq1omeol8",
                 document.getElementById("searchbox_000660513327178783876:xhlq1omeol8"),
                 document.getElementById("results_000660513327178783876:xhlq1omeol8"));
}
GSearch.setOnLoadCallback(OnLoad);
</script>
<!-- Google CSE Code ends -->




</td></tr></table></td></table>
		
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
