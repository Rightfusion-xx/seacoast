<?php
/*
 $Id$

 osCommerce, Open Source E-Commerce Solutions
 http://www.oscommerce.com

 Copyright (c) 2003 osCommerce

 Released under the GNU General Public License

 Modified by JM Ivler
 Oct 8th, 2003

 Modifed by Aalst (stats_unsold_carts.php,v 1.4)
 aalst@aalst.com
 Nov 7th 2003

 Modifed by Aalst (stats_unsold_carts.php,v 1.4.1)
 aalst@aalst.com
 Nov 9th 2003

 Modifed by Aalst (stats_unsold_carts.php,v 1.4.2)
 aalst@aalst.com
 Nov 10th 2003

 Modifed by Aalst (stats_unsold_carts.php,v 1.6)
 aalst@aalst.com
 Nov 12th 2003

 Modifed by Aalst (stats_unsold_carts.php,v 1.7)
 aalst@aalst.com
 Nov 13th 2003
 
 Modified by Raimund Berg (stats_unsold_carts.php, v1.8)
 rb@malermeister-berg.de
 Original Idea: Roman Gruhn
 Mar 30th, 2004
*/

require('includes/application_top.php');

require(DIR_WS_CLASSES . 'currencies.php');
$currencies = new currencies();

 
// Delete Entry Begin : Roman Gruhn
if ($HTTP_GET_VARS['action']=='delete') { 
   $reset_query_raw = "delete from " . TABLE_CUSTOMERS_BASKET . " where customers_id=$HTTP_GET_VARS[customer_id]"; 
   tep_db_query($reset_query_raw); 
   $reset_query_raw2 = "delete from " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " where customers_id=$HTTP_GET_VARS[customer_id]"; 
   tep_db_query($reset_query_raw2); 
   tep_redirect(tep_href_link(FILENAME_STATS_UNSOLD_CARTS, 'delete=1&customer_id='. $HTTP_GET_VARS['customer_id'] . '&tdate=' . $HTTP_GET_VARS['tdate'])); 
} 
if ($HTTP_GET_VARS['delete']) { 
   $messageStack->add(MESSAGE_STACK_CUSTOMER_ID . $HTTP_GET_VARS['customer_id'] . MESSAGE_STACK_DELETE_SUCCESS, 'success'); 
} 
// Delete Entry End
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">

<html <?php echo HTML_PARAMS; ?>>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET;?>">
  <title><?php echo TITLE; ?></title>
  <link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
</head>

<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">

<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->

<?
function seadate($day)  {
  $rawtime = strtotime("-".$day." days");
  $ndate = date("Ymd", $rawtime);
  return $ndate;
}

function cart_date_short($raw_date) {
  if ( ($raw_date == '00000000') || ($raw_date == '') ) return false;

  $year = substr($raw_date, 0, 4);
  $month = (int)substr($raw_date, 4, 2);
  $day = (int)substr($raw_date, 6, 2);

  if (@date('Y', mktime(0, 0, 0, $month, $day, $year)) == $year) {
    return date(DATE_FORMAT, mktime(0, 0, 0, $month, $day, $year));
  } else {
    return ereg_replace('2037' . '$', $year, date(DATE_FORMAT, mktime(0, 0, 0, $month, $day, 2037)));
  }
}
?>

<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top">
      <table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
      </table>
    </td>
<!-- body_text //-->
    <td width="100%" valign="top">
    <table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td align="left" colspan="2">
        <!-- REPORT TABLE BEGIN //-->
          <table border="0" width="100%" cellspacing="0" cellpadding="2">
            <tr>
              <td class="pageHeading" align="left"  colspan="2"><?php echo HEADING_TITLE; ?></td>
              <td class="pageHeading" align="right"  colspan="4">
                <?php $tdate = $_POST['tdate'];
                if ($_POST['tdate'] == '') $tdate = '30';?>
                <form method=post action=<?php echo $PHP_SELF;?> >
                  <table align="right" width="100%">
                    <tr class="dataTableContent" align="right">
                      <td><?php echo DAYS_FIELD_PREFIX; ?><input type=text size=4 width=4 value=<?php echo $tdate; ?> name=tdate><?php echo DAYS_FIELD_POSTFIX; ?><input type=submit value="<?php echo DAYS_FIELD_BUTTON; ?>"></td>
                    </tr>
                  </table>
                </form>
              </td>
            </tr>
            <tr class="dataTableHeadingRow">
              <td class="dataTableHeadingContent" align="left" colspan="1" width="15%"><?php echo TABLE_HEADING_DATE; ?></td>
              <td class="dataTableHeadingContent" align="left" colspan="1" width="35%"><?php echo TABLE_HEADING_CUSTOMER; ?></td>
              <td class="dataTableHeadingContent" align="left" colspan="1" width="35%"><?php echo TABLE_HEADING_EMAIL; ?></td>
              <td class="dataTableHeadingContent" align="left" colspan="3" width="15%"><?php echo TABLE_HEADING_PHONE; ?></td>
            </tr>
            <tr class="dataTableHeadingRow">
              <td class="dataTableHeadingContent" align="left"   colspan="1" width="20%"><?php echo TABLE_HEADING_MODEL; ?></td>
              <td class="dataTableHeadingContent" align="left"   colspan="2" width="40%"><?php echo TABLE_HEADING_DESCRIPTION; ?></td>
              <td class="dataTableHeadingContent" align="center" colspan="1" width="10%"><?php echo TABLE_HEADING_QUANTY; ?></td>
              <td class="dataTableHeadingContent" align="right"  colspan="1" width="15%"><?php echo TABLE_HEADING_PRICE; ?></td>
              <td class="dataTableHeadingContent" align="right"  colspan="1" width="15%"><?php echo TABLE_HEADING_TOTAL; ?></td>
            </tr>

<?php
  $tdate = $_POST['tdate'];
  if ($_POST['tdate'] == '') $tdate = '30';
  $ndate = seadate($tdate);
  $query1 = tep_db_query("select cb.customers_id cid,
                                 cb.products_id pid,
                                 cb.customers_basket_quantity qty,
                                 cb.customers_basket_date_added bdate,
                                 cus.customers_firstname fname,
                                 cus.customers_lastname lname,
                                 cus.customers_telephone phone,
                                 cus.customers_email_address email
                          from   " . TABLE_CUSTOMERS_BASKET . " cb,
                                 " . TABLE_CUSTOMERS . " cus
                          where  cb.customers_basket_date_added >= '" . $ndate . "' and
                                 cb.customers_id = cus.customers_id order by cb.customers_basket_date_added desc,
                                 cb.customers_id ");
  $results = 0;
  $curcus = "";
  $tprice = 0;
  $totalAll = 0;
  $knt = mysql_num_rows($query1);
  $first_line = true;
  for ($i = 0; $i <= $knt; $i++) {
    $inrec = tep_db_fetch_array($query1);

    if ($curcus != $inrec['cid']) {
      // output line
      $totalAll += $tprice;
      $tcart_formated = $currencies->format($tprice);
      $cline .= "
            <tr>
              <td class='dataTableContent' align='right' colspan='6'><b>" . TABLE_CART_TOTAL . "</b>" . $tcart_formated . "</td>
            </tr>
            <tr>
              <!-- Delete Button : Roman Gruhn //-->
              <td colspan='6' align='right'><a href=" . tep_href_link(FILENAME_STATS_UNSOLD_CARTS,"action=delete&customer_id=$curcus&tdate=$tdate") . ">" . tep_image_button('button_reset.gif', IMAGE_RESET) . "</a></td>
            </tr>";

      if ($curcus != "") echo $cline;

      // set new cline and curcus
      $curcus = $inrec['cid'];
      $tprice = 0;

      if ($first_line == false) { 
        $cline = "
            <tr>
              <td colspan=6><br></td>
            </tr>";
      } else {
        $cline = "";
        $first_line = false;
      }
      $cline .= "
            <tr>
              <td class='dataTableContent' align='left' width='15%'> " . cart_date_short($inrec['bdate']) . "</td>
              <td class='dataTableContent' align='left' width='35%'><a href='" . tep_href_link(FILENAME_CUSTOMERS, 'search=' . $inrec['lname'], 'NONSSL') . "'>" . $inrec['fname'] . " " . $inrec['lname'] . "</a></td>
              <td class='dataTableContent' align='left' width='35%'><a href='" . tep_href_link('mail.php', 'selected_box=tools&customer=' . $inrec['email']) . "'>" . $inrec['email'] . "</a></td>
              <td class='dataTableContent' align='left' colspan='3' width='15%'>" . $inrec['phone'] . "</td>
            </tr>";
    }

    // empty the shopping cart
    $query2 = tep_db_query("select  p.products_price price,
                                    p.products_model model,
                                    pd.products_name name
                            from    " . TABLE_PRODUCTS . " p,
                                    " . TABLE_PRODUCTS_DESCRIPTION . " pd,
                                    " . TABLE_LANGUAGES . " l
                            where   p.products_id = '" . $inrec['pid'] . "' and
                                    pd.products_id = p.products_id and
                                    l.languages_id = pd.language_id");

    $inrec2 = tep_db_fetch_array($query2);
    $tprice = $tprice + ($inrec['qty'] * $inrec2['price']);

    if ($inrec['qty'] != 0) {
      $pprice_formated  = $currencies->format($inrec2['price']);
      $tpprice_formated = $currencies->format(($inrec['qty'] * $inrec2['price']));
      $cline .= "
            <tr class='dataTableRow'>
              <td class='dataTableContent' align='left'   width='20%'>" . $inrec2['model'] . "</td>
              <td class='dataTableContent' align='left'   colspan='2' width='40%'><a href='" . tep_href_link(FILENAME_CATEGORIES, 'action=new_product_preview&read=only&pID=' . $inrec['pid'] . '&origin=' . FILENAME_STATS_UNSOLD_CARTS . '?page=' . $HTTP_GET_VARS['page'], 'NONSSL') . "'>" . $inrec2['name'] . "</a></td>
              <td class='dataTableContent' align='center' width='10%'>" . $inrec['qty'] . "</td>
              <td class='dataTableContent' align='right'  width='15%'>" . $pprice_formated . "</td>
              <td class='dataTableContent' align='right'  width='15%'>" . $tpprice_formated . "</td>
            </tr>";
    }
  }
  $totalAll_formated = $currencies->format($totalAll);
  
  $cline .= "
            <tr>
              <td class='dataTableContent' align='right' colspan='6'><b>" . TABLE_GRAND_TOTAL . "</b>" . $totalAll_formated . "</td>
            </tr>";

  echo $cline;
?>
        </tr>    
          </table>
        <!-- REPORT TABLE END //-->
        </td>
      </tr>
    </table>
  </td>
<!-- body_text_eof //-->
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