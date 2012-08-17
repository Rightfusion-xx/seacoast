<?php
/*
  $Id: orders_tracking.php, v2.8b September 08, 2005 

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 - 2004 osCommerce

  Orders Tracking originally developed by Kieth Waldorf
  v2.8b updates by Steel Shadow - fixed date error causing "Yesterday" info not to work and
  changed default profit rate from 30% to 60%
  v2.8 updates by David Radford with suggestions from forum
  v2.1, v2.3, v2.4, v2.5, 2.6 updates by Jared Call with suggestions from the forums
  v2.2 updates by Robert Hellemans
  Localization work for English and Brazilian Portugese added by alan
  Released under the GNU General Public License
*/

  require('includes/application_top.php');

// get main currency symbol for this store
  $currency_query = tep_db_query("select symbol_left from " . TABLE_CURRENCIES . " where  code = '".DEFAULT_CURRENCY."' ");
  $currency_symbol_results = tep_db_fetch_array($currency_query);
  $store_currency_symbol = tep_db_output($currency_symbol_results['symbol_left']);
  
setlocale(LC_MONETARY, 'en_US');

function get_month($mo, $yr) {
    $query = "SELECT * FROM " . TABLE_ORDERS. " WHERE date_purchased LIKE \"$yr-$mo%\"";
    $result = mysql_query($query) or die("Query failed : " . mysql_error());
    $month=0;
    while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
                $month++;
    }
    mysql_free_result($result);
    return $month;
}

function get_order_total($mo, $yr) {
    $query = "SELECT orders_id FROM " . TABLE_ORDERS . " WHERE date_purchased LIKE \"$yr-$mo%\"  ORDER by orders_id";
    $result = mysql_query($query) or die("Query failed : " . mysql_error());
    $i=0;
    while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
        foreach ($line as $col_value) {
           if ( $i == 0 ) {
                $first=$col_value;
                $last=$col_value;
                $i++;
           } else {
                $last=$col_value;
           }
        }
    }
    mysql_free_result($result);

    $query = "SELECT sum(value) FROM " . TABLE_ORDERS_TOTAL . " WHERE orders_id >= \"$first\" and  orders_id <= \"$last\" and class = \"ot_total\"";
    $result = mysql_query($query) or die("Query failed : " . mysql_error());
    while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
        foreach ($line as $col_value) {
                $total=$col_value;
        }
    }
    mysql_free_result($result);
    return $total;
}

function get_status($type) {
    $query = "SELECT orders_status FROM " . TABLE_ORDERS . " WHERE orders_status = \"$type\"";
    $result = mysql_query($query) or die("Query failed : " . mysql_error());
    $orders_this_status=0;
    while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
        foreach ($line as $col_value) {
           $orders_this_status++;
  	}
    }
    mysql_free_result($result);
    return $orders_this_status;
}


# Get total dollars in orders

    $query = "SELECT sum(value) FROM " . TABLE_ORDERS_TOTAL . " WHERE class = \"ot_total\"";
    $result = mysql_query($query) or die("Query failed : " . mysql_error());
    while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
        foreach ($line as $col_value) {
	    $grand_total=$col_value;
        }
    }
    mysql_free_result($result);

    
# Get total shipping charges

    $query = "SELECT sum(value) FROM " . TABLE_ORDERS_TOTAL . " WHERE class like \"ot_shipping\"";
    $result = mysql_query($query) or die("Query failed : " . mysql_error());
    while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
        foreach ($line as $col_value) {
	    $shipping=$col_value;
        }
    }
    mysql_free_result($result);


# Get total number of customers
    $query = "SELECT * FROM " . TABLE_CUSTOMERS . "";
    $result = mysql_query($query) or die("Query failed : " . mysql_error());
    $customer_count=0;
    while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$customer_count++;
    }
    mysql_free_result($result);

    
# Get total number new customers

    $like = date('Y-m-d');
    $query = "SELECT customers_info_date_account_created FROM " . TABLE_CUSTOMERS_INFO . " WHERE customers_info_date_account_created like \"$like%\"";
    $result = mysql_query($query) or die("Query failed : " . mysql_error());
    $newcust=0;
    while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$newcust++;
    }
    mysql_free_result($result);

    
# Whos online

    $query = "SELECT * FROM " . TABLE_WHOS_ONLINE . "";
    $result = mysql_query($query) or die("Query failed : " . mysql_error());
    $whos_online=0;
    while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$whos_online++;
    }
    mysql_free_result($result);

    
# Whos online again

    $query = "SELECT * FROM " . TABLE_WHOS_ONLINE . " WHERE customer_id != \"0\"";
    $result = mysql_query($query) or die("Query failed : " . mysql_error());
    $who_again=0;
    while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$who_again++;
    }
    mysql_free_result($result);

    
# How many orders today total

    $date = date('Y-m-d'); #2003-09-07%
    $query = "SELECT * FROM " . TABLE_ORDERS . " WHERE date_purchased LIKE \"$date%\"";
    $result = mysql_query($query) or die("Query failed : " . mysql_error());
    $today_order_count=0;
    while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$today_order_count++;
    }
    mysql_free_result($result);

    
# How many orders yesterday

    $mo = date('m');
    $today = date('d');
    $year = date('Y');
    
    $last_month = $mo-1;
    if ( $last_month == 0) $last_month = 12; //if jan, then last month is dec (12th mo, not 0th month)
    $yesterday = date('d') - 1;
    if ($yesterday == "0") //today is the first day of the month, now "Thirty days hath November . . ." for the prev month
     { $first_day_of_month=1;
       if ( ($last_month == 1) OR ($last_month == 3) OR ($last_month == 5) OR ($last_month == 7) OR ($last_month == 8) OR ($last_month == 10) OR ($last_month == 12) )
          $yesterday = "31";
        elseif  ( ($last_month == 4) OR ($last_month == 6) OR ($last_month == 9) OR ($last_month == 11) )
          $yesterday = "30";
//calculate Feb end day, including leap year calculation from http://www.mitre.org/tech/cots/LEAPCALC.html
        else {
              if ( ($year % 4) != 0) $yesterday = "28";
               elseif ( ($year % 400) == 0) $yesterday = "29";
               elseif ( ($year % 100) == 0) $yesterday = "28";
               else $yesterday = "29";
              }
     }

// set $yesterday_month so that we can properly run stats for yesterday, not the first day of last month
    if ($first_day_of_month == 1) 
       $yesterday_month = $last_month; 
    else $yesterday_month = $mo;

// set $yesterday_year so that we can properly run stats for yesterday, not the first day of last year or this month last year        
    if ( ($yesterday_month == 12) && ($first_day_of_month == 1) )
      $yesterday_year = $year - 1;
    else
      $yesterday_year = $year;

// next line to normalize $yesterday format to 2 digits
    if ($yesterday <10) {$yesterday = "0$yesterday";}
//    if ($yesterday_month <10) {$yesterday_month = "0$yesterday_month";}
//    if ($first_day_of_month == 1)  // if today is the first day of the month, then run yesterday stats for last_month,day instead of this_month,day
    $query = "SELECT * FROM " . TABLE_ORDERS . " WHERE date_purchased LIKE \"$yesterday_year-$yesterday_month-$yesterday%\"";
    $result = mysql_query($query) or die("Query failed : " . mysql_error());
    $yesterday_order_count=0;
    while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
    $yesterday_order_count++;
    }
    mysql_free_result($result);

# Get the last order_id

    $query = "SELECT orders_id FROM " . TABLE_ORDERS_TOTAL . " WHERE class = \"ot_total\" ORDER BY orders_id ASC";
    $result = mysql_query($query) or die("Query failed : " . mysql_error());
    while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
        foreach ($line as $col_value) {
		$latest_order_id=$col_value;
	}
    }
    mysql_free_result($result);

    
# Calculate the order_id number less the number of orders today

    $yesterday_last_order_id = $latest_order_id - $today_order_count;
    $twodaysago_last_order_id = $yesterday_last_order_id - $yesterday_order_count;

    
# Grab the sum of all orders greater than $yesterday_last_order_id
# In other words, how much have we done so far in sales today?

    $query = "SELECT sum(value) FROM " . TABLE_ORDERS_TOTAL . " WHERE orders_id > \"$yesterday_last_order_id\" and class = \"ot_total\"";
    $result = mysql_query($query) or die("Query failed : " . mysql_error());
    while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
        foreach ($line as $col_value) {
	    $orders_today=$col_value;
        }
    }
    mysql_free_result($result);

    
# Grab the sum of all orders greater than $twodaysago_last_order_id and less than yesterday_last_order_id
# In other words, how much did we do in sales yesterday?

    $query = "SELECT sum(value) FROM " . TABLE_ORDERS_TOTAL . " WHERE orders_id > \"$twodaysago_last_order_id\" and orders_id <= \"$yesterday_last_order_id\" and class = \"ot_total\"";    
    $result = mysql_query($query) or die("Query failed : " . mysql_error());
    while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
        foreach ($line as $col_value) {
	    $orders_yesterday=$col_value;
        }
    }
    mysql_free_result($result);

# How many repeat orders today total

    $date = date('Y-m-d');
    $query = "SELECT * FROM " . TABLE_ORDERS. " WHERE date_purchased LIKE \"$date%\" AND customers_id < \"$yesterday_last_order_id\"";
    $result = mysql_query($query) or die("Query failed : " . mysql_error());
    $repeat_orders=0;
    while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$repeat_orders++;
    }
    mysql_free_result($result);

# Find all repeat orders

    $date = date('Y-m-d');
    $query = "SELECT customers_id FROM " . TABLE_ORDERS . " ORDER by customers_id";
    $result = mysql_query($query) or die("Query failed : " . mysql_error());
    $repeats=0;
    while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
        foreach ($line as $col_value) {
	    $cust_id=$col_value;
		if ( $cust_id == $cust_id_last ) {
		$repeats++;
		}
	    $cust_id_last=$cust_id;
    	}
    }
    mysql_free_result($result);

// if a profit rate has been entered as part of the URL, use that profit rate, else 60%
    if (isset($HTTP_GET_VARS['profit_rate'])) {
	    $year=$HTTP_GET_VARS['profit_rate'];
    }
    else {
	    $profit_rate="60";
    }
    if ($profit_rate=="") {
	    $profit_rate="60";
    }

    $profit_rate_display=$profit_rate;
    
// divide profit_rate by 100 to get correct multiplier value
    $profit_rate = $profit_rate / 100;
    
    
# How many per month

// if a year has been entered as part of the URL, use that year instead
//  commented out and replaced by following line as per forum suggestion  
//  if (isset($HTTP_GET_VARS['year'])) $year=$HTTP_GET_VARS['year'];

    if (isset($HTTP_GET_VARS['year']) && $HTTP_GET_VARS['year'] != '') $year=$HTTP_GET_VARS['year'];
      else $year = date('Y'); #current year

    
    $month = date('M'); #current month

    $dec = get_month("12", $year);
    $nov = get_month("11", $year);
    $oct = get_month("10", $year);
    $sep = get_month("09", $year);
    $aug = get_month("08", $year);
    $jul = get_month("07", $year);
    $jun = get_month("06", $year);
    $may = get_month("05", $year);
    $apr = get_month("04", $year);
    $mar = get_month("03", $year);
    $feb = get_month("02", $year);
    $jan = get_month("01", $year);
    $current_month = get_month($mo, $year);


# Only Process Month Info if Month has info to process
# Always tally totals, even if zero

# while ($i < 13)
# (
#   $month_avg = $month_total / $current_month;
#   $current_month_total = get_order_total($i, $year);
#   $order = $order + $current_month_total;
#   )
#   $i++;

$jan_total = get_order_total("01", $year);
if ($jan != 0)   $jan_avg = $jan_total / $jan;
$order = $order + $jan_total;

$feb_total = get_order_total("02", $year);
if ($feb != 0)  $feb_avg = $feb_total / $feb;
$order = $order + $feb_total;

$mar_total = get_order_total("03", $year);
if ($mar != 0)   $mar_avg = $mar_total / $mar;
$order = $order + $mar_total;

$apr_total = get_order_total("04", $year);
if ($apr != 0)   $apr_avg = $apr_total / $apr;
$order = $order + $apr_total;

$may_total = get_order_total("05", $year);
if ($may != 0)   $may_avg = $may_total / $may;
$order = $order + $may_total;

$jun_total = get_order_total("06", $year);
if ($jun != 0)   $jun_avg = $jun_total / $jun;
$order = $order + $jun_total;

$jul_total = get_order_total("07", $year);
if ($jul != 0)   $jul_avg = $jul_total / $jul;
$order = $order + $jul_total;

$aug_total = get_order_total("08", $year);
if ($aug != 0)   $aug_avg = $aug_total / $aug;
$order = $order + $aug_total;

$sep_total = get_order_total("09", $year);
if ($sep != 0)   $sep_avg = $sep_total / $sep;
$order = $order + $sep_total;

$oct_total = get_order_total("10", $year);
if ($oct != 0)   $oct_avg = $oct_total / $oct;
$order = $order + $oct_total;

$nov_total = get_order_total("11", $year);
if ($nov != 0)   $nov_avg = $nov_total / $nov;
$order = $order + $nov_total;

$dec_total = get_order_total("12", $year);
if ($dec != 0)   $dec_avg = $dec_total / $dec;
$order = $order + $dec_total;

$current_month_total = get_order_total($mo, $year);
if ($current_month != 0)   $current_month_avg = $current_month_total / $current_month;


# Daily Averages
if ($today_order_count !=0 ) 	$today_avg = $orders_today / $today_order_count;
  else $today_avg = 0;
if ($yesterday_order_count != 0) $yesterday_avg = $orders_yesterday / $yesterday_order_count;
  else ($yesterday_avg = 0);

$daily = $current_month / $today;
$daily_total = $current_month_total / $today;

if ($daily) $daily_avg = $daily_total / $daily;
  else ($daily_avg = 0);

  
# Calculate days in this month for accurate sales projection

if ( ($mo == 1) OR ($mo == 3) OR ($mo == 5) OR ($mo == 7) OR ($mo == 8) OR ($mo == 10) OR ($mo == 12) )
      $days_this_month = "31";
  elseif ( ($mo == 4) OR ($mo == 6) OR ($mo == 9) OR ($mo == 11) )
           $days_this_month = "30";
           
//calculate Feb end day, including leap year calculation from http://www.mitre.org/tech/cots/LEAPCALC.html
    else {
          if ( ($year % 4) != 0) $days_this_month = "28";
          elseif ( ($year % 400) == 0) $days_this_month = "29";
          elseif ( ($year % 100) == 0) $days_this_month = "28";
              else $days_this_month = "29";
         }

         
# Projected Profits this month
$projected = $daily * $days_this_month;
$projected_total = $daily_total * $days_this_month;

$gross_profit = $grand_total * $profit_rate;

$year_profit = $order * $profit_rate;

If ($newcust != 0) $close_ratio = $today_order_count / $newcust;
  else $close_ratio = 0;

  
# format test into current
        $total_orders = $jan + $feb + $mar + $apr + $may + $jun + $jul + $aug + $sep + $oct + $nov + $dec;
	if ($total_orders != 0)   $total = $order / $total_orders;
	$total = number_format($total,2,'.',',');

	$order = number_format($order,2,'.',',');
	$grand_total = number_format($grand_total,2,'.',',');

    	$gross_profit = number_format($gross_profit,2,'.',',');
    	$year_profit = number_format($year_profit,2,'.',',');

    	$projected = number_format($projected,0,'.',',');
    	$projected_total = number_format($projected_total,2,'.',',');

    	$close_ratio = number_format($close_ratio,2,'.',',');

       	$yesterday_avg = number_format($yesterday_avg,2,'.',',');

	$dec_total = number_format($dec_total,2,'.',',');
	$nov_total = number_format($nov_total,2,'.',',');
	$oct_total = number_format($oct_total,2,'.',',');
	$sep_total = number_format($sep_total,2,'.',',');
	$aug_total = number_format($aug_total,2,'.',',');
	$jul_total = number_format($jul_total,2,'.',',');
	$jun_total = number_format($jun_total,2,'.',',');
	$may_total = number_format($may_total,2,'.',',');
	$apr_total = number_format($apr_total,2,'.',',');
	$mar_total = number_format($mar_total,2,'.',',');
	$feb_total = number_format($feb_total,2,'.',',');
	$jan_total = number_format($jan_total,2,'.',',');


       	$orders_today = number_format($orders_today,2,'.',',');
       	$orders_yesterday = number_format($orders_yesterday,2,'.',',');

       	$dec_avg = number_format($dec_avg,2,'.',',');
       	$nov_avg = number_format($nov_avg,2,'.',',');
       	$oct_avg = number_format($oct_avg,2,'.',',');
       	$sep_avg = number_format($sep_avg,2,'.',',');
       	$aug_avg = number_format($aug_avg,2,'.',',');
       	$jul_avg = number_format($jul_avg,2,'.',',');
       	$jun_avg = number_format($jun_avg,2,'.',',');
       	$may_avg = number_format($may_avg,2,'.',',');
       	$apr_avg = number_format($apr_avg,2,'.',',');
       	$mar_avg = number_format($mar_avg,2,'.',',');
       	$feb_avg = number_format($feb_avg,2,'.',',');
       	$jan_avg = number_format($jan_avg,2,'.',',');

       	$today_avg = number_format($today_avg,2,'.',',');

if ($total_orders !=0) $shipping_avg = $shipping / $total_orders;
  else $shipping_avg = 0;

       	$shipping_avg = number_format($shipping_avg,2,'.',',');
       	$shipping = number_format($shipping,2,'.',',');

    	$daily = number_format($daily,2,'.',',');
    	$daily_total = number_format($daily_total,2,'.',',');
    	$daily_avg = number_format($daily_avg,2,'.',',');
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
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr> 
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="dataTableContent" valign="center"><br><?php
                 echo tep_draw_form('search', FILENAME_STATS_ORDERS_TRACKING, '', 'get');
                 echo HEADING_SELECT_YEAR . ' ' . tep_draw_input_field('year', '', 'size="4"');
                 echo '&nbsp;&nbsp;&nbsp;';
                 echo HEADING_SELECT_PROFIT_RATE . ' ' . tep_draw_input_field('profit_rate', '', 'size="2"');
                 echo '&#37;&nbsp;&nbsp;&nbsp;<input type="submit" value="'. HEADING_TITLE_RECALCULATE .'">';
                 echo '</form></td>';
                ?>
            </td>

            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">

<TABLE BORDER=1 CELLPADDING=5 CELLSPACING=1>
<TR class="dataTableHeadingRow" bgcolor=silver><th class="dataTableHeadingContent"><?php echo HEADING_TITLE_DESCRIPTION; ?><th class="dataTableHeadingContent"><?php echo HEADING_TITLE_ORDER_COUNT; ?><th class="dataTableHeadingContent"><?php echo HEADING_TITLE_VALOR; ?><th class="dataTableHeadingContent"><?php echo HEADING_TITLE_AVERAGE; ?></TR>

<TR class="dataTableRow">
  <td class="dataTableContent" align="left"><A href="orders.php?selected_box=customers&status=1">
    <?php echo HEADING_TITLE_TODAY; ?> <?php echo "$mo-$today"; ?></a></TD><td class="dataTableContent" align="left"><A href="orders.php?selected_box=customers&status=1"><?php echo "$today_order_count ($repeat_orders)"; ?> *</a></TD>
  <td class="dataTableContent" align=right><?php echo $store_currency_symbol . $orders_today ?></TD>
  <td class="dataTableContent" align=right><?php echo $store_currency_symbol . $today_avg ?></TD>
</TR>
<TR class="dataTableRow">
  <td class="dataTableContent"><?php echo HEADING_TITLE_YESTERDAY; ?> <?php echo "$yesterday_month-$yesterday"; ?></TD>
  <td class="dataTableContent"><?php echo $yesterday_order_count ?></TD>
  <td class="dataTableContent" align=right> <?php echo $store_currency_symbol . $orders_yesterday ?></TD>
  <td class="dataTableContent" align=right> <?php echo $store_currency_symbol . $yesterday_avg ?></TD>
</TR>
<TR class="dataTableRow">
  <td class="dataTableContent"><?php echo HEADING_TITLE_DAILY_AVERAGE; ?> <?php echo $month ?></TD>
  <td class="dataTableContent"><?php echo $daily ?></TD><td class="dataTableContent" align=right><?php echo $store_currency_symbol . $daily_total ?></TD>
  <td class="dataTableContent" align=right><?php echo $store_currency_symbol . $daily_avg ?></TD>
</TR>
<TR class="dataTableRow">
  <td class="dataTableContent"><?php echo HEADING_TITLE_PROJECTION; ?> <?php echo $month ?></TD>
  <td class="dataTableContent"><?php echo $projected ?></TD>
  <td class="dataTableContent" align=right><?php echo $store_currency_symbol . $projected_total ?></TD>
  <td class="dataTableContent" align=right>&nbsp;</TD>
</TR>

<TR class="dataTableHeadingRow" bgcolor=silver><td class="dataTableContent" class="dataTableHeadingContent" COLSPAN=4><b><center><br><?php echo HEADING_TITLE_MONTH_TOTAL; ?></b></center></TD></TR>

<TR>
<TR class="dataTableRow"><td class="dataTableContent" >Jan <?php echo $year ?> </TD><td class="dataTableContent" > <?php echo $jan ?></TD>
<td class="dataTableContent"  align=right><?php echo $store_currency_symbol . $jan_total ?></TD>
<td class="dataTableContent"  align=right><?php echo $store_currency_symbol . $jan_avg ?></TD></TR>

<TR>
<TR class="dataTableRow"><td class="dataTableContent" >Feb <?php echo $year ?> </TD><td class="dataTableContent" > <?php echo $feb ?></TD>
<td class="dataTableContent"  align=right><?php echo $store_currency_symbol . $feb_total ?></TD>
<td class="dataTableContent"  align=right><?php echo $store_currency_symbol . $feb_avg ?></TD></TR>

<TR>
<TR class="dataTableRow"><td class="dataTableContent" >Mar <?php echo $year ?> </TD><td class="dataTableContent" > <?php echo $mar ?></TD>
<td class="dataTableContent"  align=right><?php echo $store_currency_symbol . $mar_total ?></TD>
<td class="dataTableContent"  align=right><?php echo $store_currency_symbol . $mar_avg ?></TD></TR>

<TR>
<TR class="dataTableRow"><td class="dataTableContent" >Apr <?php echo $year ?> </TD><td class="dataTableContent" > <?php echo $apr ?></TD>
<td class="dataTableContent"  align=right><?php echo $store_currency_symbol . $apr_total ?></TD>
<td class="dataTableContent"  align=right><?php echo $store_currency_symbol . $apr_avg ?></TD></TR>

<TR>
<TR class="dataTableRow"><td class="dataTableContent" >May <?php echo $year ?> </TD><td class="dataTableContent" > <?php echo $may ?></TD>
<td class="dataTableContent"  align=right><?php echo $store_currency_symbol . $may_total ?></TD>
<td class="dataTableContent"  align=right><?php echo $store_currency_symbol . $may_avg ?></TD></TR>

<TR>
<TR class="dataTableRow"><td class="dataTableContent" >Jun <?php echo $year ?> </TD><td class="dataTableContent" > <?php echo $jun ?></TD>
<td class="dataTableContent"  align=right><?php echo $store_currency_symbol . $jun_total ?></TD>
<td class="dataTableContent"  align=right><?php echo $store_currency_symbol . $jun_avg ?></TD></TR>

<TR>
<TR class="dataTableRow"><td class="dataTableContent" >Jul <?php echo $year ?> </TD><td class="dataTableContent" > <?php echo $jul ?></TD>
<td class="dataTableContent"  align=right><?php echo $store_currency_symbol . $jul_total ?></TD>
<td class="dataTableContent"  align=right><?php echo $store_currency_symbol . $jul_avg ?></TD></TR>

<TR>
<TR class="dataTableRow"><td class="dataTableContent" >Aug <?php echo $year ?> </TD><td class="dataTableContent" > <?php echo $aug ?></TD>
<td class="dataTableContent"  align=right><?php echo $store_currency_symbol . $aug_total ?></TD>
<td class="dataTableContent"  align=right><?php echo $store_currency_symbol . $aug_avg ?></TD></TR>

<TR>
<TR class="dataTableRow"><td class="dataTableContent" >Sep <?php echo $year ?> </TD><td class="dataTableContent" > <?php echo $sep ?></TD>
<td class="dataTableContent"  align=right><?php echo $store_currency_symbol . $sep_total ?></TD>
<td class="dataTableContent"  align=right><?php echo $store_currency_symbol . $sep_avg ?></TD></TR>

<TR>
<TR class="dataTableRow"><td class="dataTableContent" >Oct <?php echo $year ?> </TD><td class="dataTableContent" > <?php echo $oct ?></TD>
<td class="dataTableContent"  align=right><?php echo $store_currency_symbol . $oct_total ?></TD>
<td class="dataTableContent"  align=right><?php echo $store_currency_symbol . $oct_avg ?></TD></TR>

<TR>
<TR class="dataTableRow"><td class="dataTableContent" >Nov <?php echo $year ?> </TD><td class="dataTableContent" > <?php echo $nov ?></TD>
<td class="dataTableContent"  align=right><?php echo $store_currency_symbol . $nov_total ?></TD>
<td class="dataTableContent"  align=right><?php echo $store_currency_symbol . $nov_avg ?></TD></TR>

<TR>
<TR class="dataTableRow"><td class="dataTableContent" >Dec <?php echo $year ?> </TD><td class="dataTableContent" > <?php echo $dec ?></TD>
<td class="dataTableContent"  align=right><?php echo $store_currency_symbol . $dec_total ?></TD>
<td class="dataTableContent"  align=right><?php echo $store_currency_symbol . $dec_avg ?></TD></TR>

<TR class="dataTableRow" >
<td class="dataTableContent" NOWRAP><B>Total <?php echo $year ?> </TD><td class="dataTableContent"><B><?php echo "$total_orders / $repeats"; ?> *</TD><td class="dataTableContent" align=right><B><?php echo $store_currency_symbol . $order ?></TD><td class="dataTableContent" align=right><B><?php echo $store_currency_symbol . $total ?></TD></TR>
<TR class="dataTableRow" ><td class="dataTableContent"><B><?php echo $year ?> Profit @ <?php echo $profit_rate_display ?>%</TD><td class="dataTableContent" colspan=2 align=right><B><?php echo $store_currency_symbol . $year_profit ?></TD><td class="dataTableContent" align=right>&nbsp;</TD></TR>

<TR valign="bottom"><TD bgcolor=silver colspan=4></TD></TR>
<TR class="dataTableRow" ><td class="dataTableContent"><a href="/admin/customers.php"><?php echo HEADING_TITLE_TOTAL_CUSTOMERS; ?></a></TD><td class="dataTableContent"><?php echo $customer_count ?></TD><td class="dataTableContent"><A href="whos_online.php"><?php echo HEADING_TITLE_TOTAL_CUSTOMERS_ONLINE; ?></a></TD><td class="dataTableContent"><?php echo "$whos_online / $who_again"; ?> *</TD></TR>
<TR class="dataTableRow" ><td class="dataTableContent"><?php echo HEADING_TITLE_NEW_CUSTOMERS_TODAY; ?></TD><td class="dataTableContent"><?php echo $newcust ?></TD><td class="dataTableContent"><?php echo HEADING_TITLE_CLOSE_RATIO; ?></TD><td class="dataTableContent"><?php echo $close_ratio ?>%</TR>

<TR class="dataTableHeadingRow" bgcolor=silver><td class="dataTableContent" class="dataTableHeadingContent" COLSPAN=4><b><center><br><?php echo HEADING_TITLE_ORDER_STATUS; ?></b></center></TD></TR>

<?php
  $orders_status_total = 0;
  $orders_status_query = tep_db_query("select orders_status_name, orders_status_id from " . TABLE_ORDERS_STATUS . " where language_id = '" . $languages_id . "'");
  while ($orders_status = tep_db_fetch_array($orders_status_query)) {
    $orders_pending_query = tep_db_query("select count(*) as count from " . TABLE_ORDERS . " where orders_status = '" . $orders_status['orders_status_id'] . "'");
    $orders_pending = tep_db_fetch_array($orders_pending_query);
  
    $orders_contents .= '<tr class="dataTableRow"><td class="dataTableContent"><a href="' . tep_href_link(FILENAME_ORDERS, 'selected_box=orders&status=' . $orders_status['orders_status_id']) . '">' . $orders_status['orders_status_name'] . '</font></a></td><td class="dataTableContent">' . $orders_pending['count'] . '</td><td class="dataTableContent" colspan="2" align="right"> &nbsp;</td></tr>';
    
  }
//  $orders_contents = substr($orders_contents, 0, -5);
echo $orders_contents;
?>

<TR class="dataTableHeadingRow" bgcolor=silver><td class="dataTableContent" class="dataTableHeadingContent" COLSPAN=4><b><center><br><?php echo HEADING_TITLE_GRAND_TOTAL; ?></b></center></TD></TR>
<TR class="dataTableRow" ><td class="dataTableContent"><B><?php echo HEADING_TITLE_GRAND_TOTAL2; ?></TD><td class="dataTableContent" colspan=2 align=right><B><?php echo $store_currency_symbol . $grand_total ?></TD><td class="dataTableContent" align=right>&nbsp;</TD></TR>
<TR class="dataTableRow" ><td class="dataTableContent"><B><?php echo HEADING_TITLE_GROSS_PROFIT; ?></TD><td class="dataTableContent" colspan=2 align=right><B><?php echo $store_currency_symbol . $gross_profit ?></TD><td class="dataTableContent" align=right>&nbsp;</TD></TR>
<TR class="dataTableRow" ><td class="dataTableContent"><B><?php echo HEADING_TITLE_SHIPPING_CHARGES; ?></TD><td class="dataTableContent" colspan=2 align=right><B><?php echo $store_currency_symbol . $shipping ?></TD><td class="dataTableContent" align=right><B><?php echo $store_currency_symbol . $shipping_avg ?></TD></TR>

</TABLE>

<FONT SIZE=-1>
<br>
<?php echo HEADING_TITLE_TEXT_1; ?>
<BR>
<?php echo HEADING_TITLE_TEXT_2; ?></FONT>


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