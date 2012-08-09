<?php
/*
  $Id: orders_tracking_postcodes.php,v 2.4 August 20, 2004 01:30:00 

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Orders Tracking originally developed by Kieth Waldorf
  Other Orders Tracking functions added to package by Jared Call  
  Released under the GNU General Public License
*/

  require('includes/application_top.php');
?>

<!doctype html>
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
                 echo tep_draw_form('search', FILENAME_STATS_ORDERS_TRACKING_POSTCODES, '', 'get');
                 echo HEADING_SELECT_YEAR . ' ' . tep_draw_input_field('year', '', 'size="4"');
                 echo '  ' . HEADING_SELECT_MONTH . ' ' . tep_draw_input_field('month', '', 'size="4"');
                 echo '<p>' . HEADING_SELECT_POSTPREFIX . ' ' . tep_draw_input_field('postcode_prefix', '', 'size="3"');
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
<TR class="dataTableHeadingRow" bgcolor=silver><th class="dataTableHeadingContent"><?php echo HEADING_ZONE ?><th class="dataTableHeadingContent"><?php echo HEADING_ORDER_COUNT ?></TR>

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

  if ( (isset($HTTP_GET_VARS['postcode_prefix'])) && ($HTTP_GET_VARS['postcode_prefix'] != '') ) {
	  $prefix = '"' . $HTTP_GET_VARS['postcode_prefix'] . '"';
	  $postcode_query = tep_db_query("SELECT count(*) AS count, customers_postcode FROM " . TABLE_ORDERS . " WHERE substring(customers_postcode,1,3) = $prefix and customers_postcode IS NOT NULL and date_purchased >= '$year-$startmonth-01 00:00:00' and date_purchased <= '$year-$endmonth-31 11:59:59' GROUP BY customers_postcode ORDER BY customers_postcode DESC LIMIT 50");
	  
	  while ($customers_location = tep_db_fetch_array($postcode_query)) {
	        $location_contents .= '<tr class="dataTableRow"><td class="dataTableContent">' . $customers_location['customers_postcode'] . '</font>';
	        if ( is_numeric($customers_location['customers_postcode']) ) {
		        $location_contents .= '&nbsp;&nbsp;<a href="' . ZIP_URL . $customers_location['customers_postcode'] . '" target="_blank">' . POST_CODE_LOOKUP . '</a>';
	        }
	        $location_contents .= '</td><td class="dataTableContent">' . $customers_location['count'] . '</td></tr>';
        }

  } else {
  
  $location_query = tep_db_query("SELECT count(*) as count, substring(customers_postcode,1,3) as customers_postcode from " . TABLE_ORDERS . " WHERE customers_postcode IS NOT NULL and date_purchased >= '$year-$startmonth-01 00:00:00' and date_purchased <= '$year-$endmonth-31 11:59:59' group by customers_postcode ORDER BY customers_postcode ASC");    

//  $location_query = tep_db_query("SELECT sum(ot.value) as total, count(*) as count, substring(o.customers_postcode,1,3) as customers_postcode from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_TOTAL . " ot WHERE o.customers_postcode IS NOT NULL and o.date_purchased >= '$year-$startmonth-01 00:00:00' and o.date_purchased <= '$year-$endmonth-31 11:59:59' group by customers_postcode ORDER BY customers_postcode ASC");    
  
 
  while ($customers_location = tep_db_fetch_array($location_query)) {
	        $location_contents .= '<tr class="dataTableRow"><td class="dataTableContent"><a href="' . FILENAME_STATS_ORDERS_TRACKING_POSTCODES . '?postcode_prefix=' . $customers_location['customers_postcode'] . '&year=' . $year . '&month='. $month . '">' . $customers_location['customers_postcode'] . 'xx</a></font></td><td class="dataTableContent">' . $customers_location['count'] . '</td></tr>';	        
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
