<?php
/*
  $Id: orders_tracking_zones.php,v 2.4 August 20, 2004 01:30:00 

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Orders Tracking originally developed by Kieth Waldorf
  Orders Tracking Zones added to package by Jared Call
  Released under the GNU General Public License
*/

  require('includes/application_top.php');
?>

<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/general.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top">
     <table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td>
         <table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr> 
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="dataTableContent"><br><br><?php
                 echo tep_draw_form('search', FILENAME_STATS_ORDERS_TRACKING_ZONES, '', 'get');
                 echo HEADING_SELECT_YEAR . ' ' . tep_draw_input_field('year', '', 'size="4"');
                 echo '  ' . HEADING_SELECT_MONTH . ' ' . tep_draw_input_field('month', '', 'size="4"');                 
                 echo '&nbsp;&nbsp;&nbsp;<input type="submit" value="'. HEADING_TITLE_RECALCULATE .'">';
                 echo '</form>';
                ?><br><br>
            </td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
       </tr>
       <tr>
        <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">


<CENTER>
<TABLE BORDER=1 CELLPADDING=5 CELLSPACING=1>
<TR class="dataTableHeadingRow" bgcolor=silver><th class="dataTableHeadingContent">Description<th class="dataTableHeadingContent">Order Count</TR>

<?php

// if no year has been selected, use current year
  if (isset($HTTP_GET_VARS['year'])) {
	  $year=$HTTP_GET_VARS['year'];
  }
  else {
	  $year = date('Y'); #current year
  }

// if selected a month but not year, assume current year    
if ($HTTP_GET_VARS['year'] == '') {
	  $year = date('Y'); #current year
  }      

// if a month has been selected, set the date range for just that month
  if (isset($HTTP_GET_VARS['month'])) {
	  $startmonth=$HTTP_GET_VARS['month'];
	  $endmonth=$startmonth;
  }

// if no month has been selected, we want entire year of data
  if ($HTTP_GET_VARS['month'] == '') {
	  $startmonth=01;
	  $endmonth=12;
  } 
  
    
  $location_query = tep_db_query("select zone_name, zone_country_id from " . TABLE_ZONES . " order by zone_country_id DESC");    
  while ($customers_location = tep_db_fetch_array($location_query)) {
    $location_pending_query = tep_db_query("select count(*) as count from " . TABLE_ORDERS . " where customers_state = '". $customers_location['zone_name'] ." ' and date_purchased >= '$year-$startmonth-01 00:00:00' and date_purchased <= '$year-$endmonth-31 11:59:59' ");
    $location_pending = tep_db_fetch_array($location_pending_query);
    if ( $location_pending['count'] != 0 ) //if there are orders in this zone, print the zone and the count 
    {
	        $location_contents .= '<tr class="dataTableRow"><td class="dataTableContent">' . $customers_location['zone_name'] . '</font></td><td class="dataTableContent">' . $location_pending['count'] . '</td></tr>';
	}
  }
echo $location_contents;

?>

</TABLE>

            </td>
          </tr>
        </table></td>
      </tr>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>

<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
</body>
</html>
