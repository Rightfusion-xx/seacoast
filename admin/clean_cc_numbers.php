<?php
/*
  $Id: clean_cc_numbers.php,v 1.1 2006/08/09 18:00:00 Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  $defaultdays = 7; // DEFAULT VALUE: ANY RECORD older than this date will be X'ed out unless otherwise specified.

  $today = time();
?>
<!doctype html>
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
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
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
		  <tr>
		  	<td class="smallText">
			<?php 
			  $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');
				$thedelay = ($HTTP_POST_VARS['delay_input']) * 24 * 3600;
				$cutoffdate = date('Y-m-d', $today - $thedelay);
				if ($action == 'clean') {
					$orders_query = tep_db_query("select orders_id,cc_number,date_purchased,orders_status from " . TABLE_ORDERS . " where orders_status = " . $HTTP_POST_VARS['status_input'] . " AND cc_number > '1' AND date_purchased < \"".$cutoffdate."\";");
					if (tep_db_num_rows($orders_query)) {
						echo '<a href="' . tep_href_link(FILENAME_CLEAN_CC) . '">Return to prebivious page.</a><br><br>';
						echo "Orders shipped up to " . $cutoffdate . ".<br />";
						echo tep_db_num_rows($orders_query)." items updating ...<br />";
						while ($orders = tep_db_fetch_array($orders_query)) {
						  	$newNum = 'xxxxxxxxxxxx';
							$newNum .= substr($orders['cc_number'], strlen($orders['cc_number'])-4); 
							//echo $orders['orders_id'] . "\t" . $orders['date_purchased'] . "<br />"; // Debug: Use this to check order dates
							echo $orders['orders_id']."\t".$newNum."\t".$orders['orders_status']."\n<br />";
							//tep_db_query("update " . TABLE_ORDERS . " set cc_number='".$newNum."' where orders_id=".$orders['orders_id']); // This will ACTIVATE the script
						}
						echo "END<br /><br />";
						echo '<a href="' . tep_href_link(FILENAME_CLEAN_CC) . '">Return to prebivious page.</a><br><br>';
						
					} else {
						echo ERROR_EMPTY_LOG;
					}
				} else {
			?>
				<p><?php echo DESCRIPTION_TEXT_INIT; ?></p>
				<p><?php echo DESCRIPTION_TEXT_SEC; ?></p>
				<p><?php echo DESCRIPTION_TEXT_THRD; ?></p>
				<div>
				<form name="clean_cc" method="post" <?php echo 'action="'. tep_href_link(FILENAME_CLEAN_CC, 'action=clean') . '" onClick="return confirm('.CONFIRM_SCRIPT.')"'?> >
				  <?php echo BODY_TEXT_STATUSIS ?>
				  <select name="status_input">
				  <?php 
				    $status_query = tep_db_query("select orders_status_id,language_id,orders_status_name from ". TABLE_ORDERS_STATUS." where language_id = '" . $languages_id . "';");
					if (tep_db_num_rows($status_query)){
					  while($status = tep_db_fetch_array($status_query)){
					    if ($status[orders_status_id] == '3'){
					      echo "<option value=".$status[orders_status_id]." selected>".$status[orders_status_name]."</option>\n";
					    }else{
					      echo "<option value=".$status[orders_status_id].">".$status[orders_status_name]."</option>\n";
					    }
					  }
					}else{
					  echo "ERROR";
					}
				  ?>
				  </select> 
				  <?php echo BODY_TEXT_MORETHAN.'<input type="text" name="delay_input" value="'. $defaultdays .'" size="7" maxlength="5" ></input>'.BODY_TEXT_DAYSOLD ?>
				<!-- <input type="submit" value="OK"> // Debug: Use this if image button does not work -->
				</div>
				<div align="center">
				<?php 
				echo '<br />';
				echo '<button type="submit" name="submit" value="submit" onClick="return confirm(\'' . CONFIRM_SCRIPT .'\')">' . tep_image_button('button_clean_cc.gif', IMAGE_CLEAN) . '</button>'; 
				?>
				</form>
				</div>
			<?php
			}
			?>
		  	</td>
			<td></td>
		  </tr>
        </table></td>
      </tr>
      <tr>
        <td>
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
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
