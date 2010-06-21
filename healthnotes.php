<?php
/*
  $Id: index.php,v 1.1 2003/06/11 17:37:59 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');



 $category_depth = 'top';
  if (isset($cPath) && tep_not_null($cPath)) {
    $categories_products_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where categories_id = '" . (int)$current_category_id . "'");
   $org = 'seacoast'; $contentUrl = 'http://www.healthnotes.info/http/healthnotes.cfm'; 
if (empty($_GET['org'])) {
$url = "{$contentUrl}?org={$org}&{$HTTP_SERVER_VARS['QUERY_STRING']}"; }
else { $url = "{$contentUrl}?{$HTTP_SERVER_VARS['QUERY_STRING']}"; }  	
$categories_products = tep_db_fetch_array($categories_products_query);
    if ($categories_products['total'] > 0) {
         }
  }

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_DEFAULT);
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>"> 
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
      </table>
    </td>
    <td valign="top" colspan="2" valign="top"><?php require(DIR_WS_INCLUDES . 'titlebar.php'); ?></td></tr><tr><!-- body_text //-->
    <?php
  if ($category_depth == 'nested') {
    $category_query = tep_db_query("select cd.categories_name, c.categories_image from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = '" . (int)$current_category_id . "' and cd.categories_id = '" . (int)$current_category_id . "' and cd.language_id = '" . (int)$languages_id . "'");
    $category = tep_db_fetch_array($category_query);  $org = 'seacoast'; $contentUrl = 'http://www.healthnotes.info/http/healthnotes.cfm'; 
if (empty($_GET['org'])) {
$url = "{$contentUrl}?org={$org}&{$HTTP_SERVER_VARS['QUERY_STRING']}"; }
else { $url = "{$contentUrl}?{$HTTP_SERVER_VARS['QUERY_STRING']}"; }
?>
    <td width="100%" valign="top"> 
      <table border="0" width="100%" cellspacing="0" cellpadding="0">
        <tr> 
          <td> 
            <table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr> </tr>
            </table>
          </td>
        </tr>
        <tr> 
          <td> 
            <?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?>
          </td>
        </tr>
        <tr> 
          <td> 
            <table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr> 
                <td> 
                  <table border="0" width="100%" cellspacing="0" cellpadding="2">
                    <tr> </tr>
                  </table>
                </td>
              </tr>
              <tr> 
                <td> 
                  <?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?>
                </td>
              </tr>
              <tr> </tr>
            </table>
          </td>
        </tr>
      </table>
    </td>
    <td width="100%" valign="top"> 
      <table border="0" width="100%" cellspacing="0" cellpadding="0">
        <tr> 
          <td> 
            <table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr> </tr>
            </table>
          </td>
        </tr>
        <tr> 
          <td> 
            <?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?>
          </td>
        </tr>
        <tr> 
          <td> 
            <?php include(DIR_WS_MODULES . FILENAME_PRODUCT_LISTING); ?>
          </td>
        </tr>
      </table>
    </td>
    <?php
  } else { // default page
?>
    <td width="100%" valign="top"> 
      <table border="0" width="100%" cellspacing="0" cellpadding="0">
        <tr> 
          <td> 
            <table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr> 
                <td class="pageHeading"> 
                  <?php echo HEADING_TITLE; ?>
                </td>
                <td class="pageHeading" align="right">&nbsp;</td>
              </tr>
            </table>
          </td>
        </tr>
        <tr> 
          <td> 
            <?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?>
          </td>
        </tr>
        <tr> 
          <td> 
            <table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr> 
                <td> 
                  <?php
$org = 'seacoast';
$contentUrl = 'http://www.healthnotes.info/http/healthnotes.cfm'; 

// Include 'org' parameter unless it is already in the request URL
if (empty($_GET['org']))
{
    $url = "{$contentUrl}?org={$org}&{$HTTP_SERVER_VARS['QUERY_STRING']}";
}
else
{
    $url = "{$contentUrl}?{$HTTP_SERVER_VARS['QUERY_STRING']}";
}

echo '<!-- Begin Healthnotes content -->';
echo file_get_contents($url);  
echo '<!-- End Healthnotes content -->';
?>
                </td>
              </tr>
              <tr> 
                <td> 
                  <?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?>
                </td>
              </tr>
              <tr> 
                <td class="main"> 
                  <?php echo TEXT_MAIN; ?>
                </td>
              </tr>
              <tr> 
                <td> 
                  <?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?>
                </td>
              </tr>
              <tr> 
                <td></td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
    </td>
    <?php
  }
?>
    <!-- body_text_eof //-->
   <TD WIDTH="<?php echo BOX_WIDTH; ?>" VALIGN="top" rowspan="2">
     <TABLE BORDER="0" WIDTH="<?php echo BOX_WIDTH; ?>" CELLSPACING="2" CELLPADDING="0">
        <!-- right_navigation //-->
        <?php require(DIR_WS_INCLUDES . 'column_right.php'); ?>
        <!-- right_navigation_eof //-->
      </table>
    </td>
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
