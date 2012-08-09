<?php
/*
  $Id: specials.php,v 1.41 2003/06/29 22:50:52 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
@require('includes/application_top.php');

$action = (isset($HTTP_POST_VARS['action']) ? $HTTP_POST_VARS['action'] : '');

if ($HTTP_GET_VARS['action'] == 'setflag') {
  $sql_data_array = array('products_extra_fields_status' => tep_db_prepare_input($HTTP_GET_VARS['flag']));
	tep_db_perform(TABLE_PRODUCTS_EXTRA_FIELDS, $sql_data_array, 'update', 'products_extra_fields_id=' . $HTTP_GET_VARS['id']);
  tep_redirect(tep_href_link(FILENAME_PRODUCTS_EXTRA_FIELDS));
}

if (tep_not_null($action)) {
  switch ($action) {
    case 'add':
      $sql_data_array = array('products_extra_fields_name' => tep_db_prepare_input($HTTP_POST_VARS['field']['name']),
															'products_extra_fields_order' => tep_db_prepare_input($HTTP_POST_VARS['field']['order']));
			tep_db_perform(TABLE_PRODUCTS_EXTRA_FIELDS, $sql_data_array, 'insert');

      tep_redirect(tep_href_link(FILENAME_PRODUCTS_EXTRA_FIELDS));
      break;
    case 'update':
      foreach ($HTTP_POST_VARS['field'] as $key=>$val) {
        $sql_data_array = array('products_extra_fields_name' => tep_db_prepare_input($val['name']),
			   											  'products_extra_fields_order' => tep_db_prepare_input($val['order']));
			  tep_db_perform(TABLE_PRODUCTS_EXTRA_FIELDS, $sql_data_array, 'update', 'products_extra_fields_id=' . $key);
      }
      tep_redirect(tep_href_link(FILENAME_PRODUCTS_EXTRA_FIELDS));

      break;
    case 'remove':
      //print_r($HTTP_POST_VARS['mark']);
      if ($HTTP_POST_VARS['mark']) {
        foreach ($HTTP_POST_VARS['mark'] as $key=>$val) {
          tep_db_query("DELETE FROM " . TABLE_PRODUCTS_EXTRA_FIELDS . " WHERE products_extra_fields_id=" . tep_db_input($key));
          tep_db_query("DELETE FROM " . TABLE_PRODUCTS_TO_PRODUCTS_EXTRA_FIELDS . " WHERE products_extra_fields_id=" . tep_db_input($key));
        }
        tep_redirect(tep_href_link(FILENAME_PRODUCTS_EXTRA_FIELDS));
      }

      break;
  }
}
?>
<!doctype html>
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/general.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onload="SetFocus();">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
 <tr>
  <TD WIDTH="<?php echo BOX_WIDTH; ?>" VALIGN="top" rowspan="2">
   <table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
   </table>
  </td>
<td valign="top" colspan="2" valign="top"><?php require(DIR_WS_INCLUDES . 'titlebar.php'); ?></td></tr><tr><!-- body_text //-->
  <td width="100%" valign="top">
   <table border="0" width="100%" cellspacing="0" cellpadding="2">
    <tr>
     <td width="100%">
      <table border="0" width="100%" cellspacing="0" cellpadding="0">
       <tr>
        <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
       </tr>
      </table>
     </td>
    </tr>

    <tr>
     <td width="100%">
      <!--
      <div style="font-family: verdana; font-weight: bold; font-size: 17px; margin-bottom: 8px; color: #727272;">
       <? echo SUBHEADING_TITLE; ?>
      </div>
      -->
      <br />
      <? echo tep_draw_form("add_field", FILENAME_PRODUCTS_EXTRA_FIELDS); ?>
      <table border="0" width="400" cellspacing="0" cellpadding="2">
       <tr class="dataTableHeadingRow">
        <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_FIELDS; ?></td>
        <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_ORDER; ?></td>
       </tr>

       <tr>
        <td class="dataTableContent">
         <? echo tep_draw_input_field('field[name]', $field['name'], 'size=30', false, 'text', true);?>
        </td>
			  <td class="dataTableContent" align="center">
         <? echo tep_draw_input_field('field[order]', $field['order'], 'size=5', false, 'text', true);?>
        </td>
        <td class="dataTableHeadingContent" align="right">
         <? echo tep_image_submit('button_add_field.gif', IMAGE_ADD_FIELD, 'name=action value=add');  ?>
        </td>
       </tr>
       </form>
      </table>
      <hr />
      <br>
      <?
       echo tep_draw_form('extra_fields', FILENAME_PRODUCTS_EXTRA_FIELDS);
      ?>
      <? echo $action_message; ?>
      <table border="0" width="100%" cellspacing="0" cellpadding="2">
       <tr class="dataTableHeadingRow">
        <td class="dataTableHeadingContent" width="20">&nbsp;</td>
        <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_FIELDS; ?></td>
        <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_ORDER; ?></td>
        <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_STATUS; ?></td>
       </tr>
<?
$products_extra_fields_query = tep_db_query("SELECT * FROM " . TABLE_PRODUCTS_EXTRA_FIELDS . " ORDER BY products_extra_fields_order");
while ($extra_fields = tep_db_fetch_array($products_extra_fields_query)) {
?>
       <tr>
        <td width="20">
         <? echo tep_draw_checkbox_field('mark['.$extra_fields['products_extra_fields_id'].']', 1) ?>
        </td>
        <td class="dataTableContent">
         <? echo tep_draw_input_field('field['.$extra_fields['products_extra_fields_id'].'][name]', $extra_fields['products_extra_fields_name'], 'size=30', false, 'text', true);?>
        </td>
			  <td class="dataTableContent" align="center">
         <? echo tep_draw_input_field('field['.$extra_fields['products_extra_fields_id'].'][order]', $extra_fields['products_extra_fields_order'], 'size=5', false, 'text', true);?>
        </td>
				<td  class="dataTableContent" align="center">
         <?php
          if ($extra_fields['products_extra_fields_status'] == '1') {
            echo tep_image(DIR_WS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_GREEN, 10, 10) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_PRODUCTS_EXTRA_FIELDS, 'action=setflag&flag=0&id=' . $extra_fields['products_extra_fields_id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) . '</a>';
          }
          else {
            echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_EXTRA_FIELDS, 'action=setflag&flag=1&id=' . $extra_fields['products_extra_fields_id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10) . '</a>&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_RED, 10, 10);
          }
         ?>
        </td>
       </tr>
<? } ?>
       <tr>
        <td colspan="4">
         <? echo tep_image_submit('button_update_fields.gif', IMAGE_UPDATE_FIELDS, 'name=action value=update');  ?>
         &nbsp;&nbsp;
         <? echo tep_image_submit('button_remove_fields.gif', IMAGE_REMOVE_FIELDS, 'name=action value=remove');  ?>
        </td>
       </tr>
       </form>
      </table>
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
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
