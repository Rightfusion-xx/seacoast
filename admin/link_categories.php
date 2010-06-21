<?php
/*
  $Id: link_categories.php,v 1.00 2003/10/02 Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

// define our link functions
  require(DIR_WS_FUNCTIONS . 'links.php');

  $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');

  $error = false;
  $processed = false;

  if (tep_not_null($action)) {
    switch ($action) {
      case 'setflag':
        $status = tep_db_prepare_input($HTTP_GET_VARS['flag']);

        if ($status == '1') {
          tep_db_query("update " . TABLE_LINK_CATEGORIES . " set link_categories_status = '1' where link_categories_id = '" . (int)$HTTP_GET_VARS['cID'] . "'");
        } elseif ($status == '0') {
          tep_db_query("update " . TABLE_LINK_CATEGORIES . " set link_categories_status = '0' where link_categories_id = '" . (int)$HTTP_GET_VARS['cID'] . "'");
        } 

        tep_redirect(tep_href_link(FILENAME_LINK_CATEGORIES, '&cID=' . $HTTP_GET_VARS['cID']));
        break;
      case 'insert':
      case 'update':
        if (isset($HTTP_POST_VARS['link_categories_id'])) $link_categories_id = tep_db_prepare_input($HTTP_POST_VARS['link_categories_id']);
        $link_categories_sort_order = tep_db_prepare_input($HTTP_POST_VARS['link_categories_sort_order']);
        $link_categories_status = ((tep_db_prepare_input($HTTP_POST_VARS['link_categories_status']) == 'on') ? '1' : '0');

        $sql_data_array = array('link_categories_sort_order' => $link_categories_sort_order, 
                                'link_categories_status' => $link_categories_status);

        if ($action == 'insert') {
          $insert_sql_data = array('link_categories_date_added' => 'now()');

          $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

          tep_db_perform(TABLE_LINK_CATEGORIES, $sql_data_array);

          $link_categories_id = tep_db_insert_id();
        } elseif ($action == 'update') {
          $update_sql_data = array('link_categories_last_modified' => 'now()');

          $sql_data_array = array_merge($sql_data_array, $update_sql_data);

          tep_db_perform(TABLE_LINK_CATEGORIES, $sql_data_array, 'update', "link_categories_id = '" . (int)$link_categories_id . "'");
        }

        $languages = tep_get_languages();
        for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
          $link_categories_name_array = $HTTP_POST_VARS['link_categories_name'];
          $link_categories_description_array = $HTTP_POST_VARS['link_categories_description'];

          $language_id = $languages[$i]['id'];

          $sql_data_array = array('link_categories_name' => tep_db_prepare_input($link_categories_name_array[$language_id]), 
                                  'link_categories_description' => tep_db_prepare_input($link_categories_description_array[$language_id]));

          if ($action == 'insert') {
            $insert_sql_data = array('link_categories_id' => $link_categories_id,
                                     'language_id' => $languages[$i]['id']);

            $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

            tep_db_perform(TABLE_LINK_CATEGORIES_DESCRIPTION, $sql_data_array);
          } elseif ($action == 'update') {
            tep_db_perform(TABLE_LINK_CATEGORIES_DESCRIPTION, $sql_data_array, 'update', "link_categories_id = '" . (int)$link_categories_id . "' and language_id = '" . (int)$languages[$i]['id'] . "'");
          }
        }

        if ($link_categories_image = new upload('link_categories_image', DIR_FS_CATALOG_IMAGES)) {
          tep_db_query("update " . TABLE_LINK_CATEGORIES . " set link_categories_image = '" . tep_db_input($link_categories_image->filename) . "' where link_categories_id = '" . (int)$link_categories_id . "'");
        }

        tep_redirect(tep_href_link(FILENAME_LINK_CATEGORIES, '&cID=' . $link_categories_id));
        break;
      case 'delete_confirm':
        if (isset($HTTP_POST_VARS['link_categories_id'])) {
          $link_categories_id = tep_db_prepare_input($HTTP_POST_VARS['link_categories_id']);

          $link_ids_query = tep_db_query("select links_id from " . TABLE_LINKS_TO_LINK_CATEGORIES . " where link_categories_id = '" . (int)$link_categories_id . "'");

          while ($link_ids = tep_db_fetch_array($link_ids_query)) {
            tep_remove_link($link_ids['links_id']);
          }

          tep_remove_link_category($link_categories_id);
        }

        tep_redirect(tep_href_link(FILENAME_LINK_CATEGORIES));
        break;
      default:
        $link_categories_query = tep_db_query("select lc.link_categories_id, lc.link_categories_image, lc.link_categories_status, lc.link_categories_sort_order, lc.link_categories_date_added, lc.link_categories_last_modified, lcd.link_categories_name, lcd.link_categories_description from " . TABLE_LINK_CATEGORIES . " lc left join " . TABLE_LINK_CATEGORIES_DESCRIPTION . " lcd on lc.link_categories_id = lcd.link_categories_id where lcd.link_categories_id = lc.link_categories_id and lc.link_categories_id = '" . (int)$HTTP_GET_VARS['cID'] . "' and lcd.language_id = '" . (int)$languages_id . "'");
        $link_categories = tep_db_fetch_array($link_categories_query);

        $links_count_query = tep_db_query("select count(*) as link_categories_count from " . TABLE_LINKS_TO_LINK_CATEGORIES . " where link_categories_id = '" . (int)$HTTP_GET_VARS['cID'] . "'");
        $links_count = tep_db_fetch_array($links_count_query);

        $cInfo_array = array_merge($link_categories, $links_count);
        $cInfo = new objectInfo($cInfo_array);
    }
  }
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
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
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr><?php echo tep_draw_form('search', FILENAME_LINK_CATEGORIES, '', 'get'); ?>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', 1, HEADING_IMAGE_HEIGHT); ?></td>
            <td class="smallText" align="right"><?php echo HEADING_TITLE_SEARCH . ' ' . tep_draw_input_field('search'); ?></td>
          </form></tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_NAME; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_STATUS; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
    $search = '';
    if (isset($HTTP_GET_VARS['search']) && tep_not_null($HTTP_GET_VARS['search'])) {
      $keywords = tep_db_input(tep_db_prepare_input($HTTP_GET_VARS['search']));
      $search = " and lcd.link_categories_name like '%" . $keywords . "%'";

      $link_categories_query_raw = "select lc.link_categories_id, lc.link_categories_image, lc.link_categories_status, lc.link_categories_sort_order, lc.link_categories_date_added, lc.link_categories_last_modified, lcd.link_categories_name, lcd.link_categories_description from " . TABLE_LINK_CATEGORIES . " lc left join " . TABLE_LINK_CATEGORIES_DESCRIPTION . " lcd on lc.link_categories_id = lcd.link_categories_id where lcd.language_id = '" . (int)$languages_id . "'" . $search . " order by lc.link_categories_sort_order, lcd.link_categories_name";
    } else {
      $link_categories_query_raw = "select lc.link_categories_id, lc.link_categories_image, lc.link_categories_status, lc.link_categories_sort_order, lc.link_categories_date_added, lc.link_categories_last_modified, lcd.link_categories_name, lcd.link_categories_description from " . TABLE_LINK_CATEGORIES . " lc left join " . TABLE_LINK_CATEGORIES_DESCRIPTION . " lcd on lc.link_categories_id = lcd.link_categories_id  where lcd.language_id = '" . (int)$languages_id . "' order by lc.link_categories_sort_order, lcd.link_categories_name";
    }

    $link_categories_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $link_categories_query_raw, $link_categories_query_numrows);
    $link_categories_query = tep_db_query($link_categories_query_raw);
    while ($link_categories = tep_db_fetch_array($link_categories_query)) {
      if ((!isset($HTTP_GET_VARS['cID']) || (isset($HTTP_GET_VARS['cID']) && ($HTTP_GET_VARS['cID'] == $link_categories['link_categories_id']))) && !isset($cInfo)) { 
        $links_count_query = tep_db_query("select count(*) as link_categories_count from " . TABLE_LINKS_TO_LINK_CATEGORIES . " where link_categories_id = '" . (int)$link_categories['link_categories_id'] . "'");
        $links_count = tep_db_fetch_array($links_count_query);

        $cInfo_array = array_merge($link_categories, $links_count);
        $cInfo = new objectInfo($cInfo_array);
      }

      if (isset($cInfo) && is_object($cInfo) && ($link_categories['link_categories_id'] == $cInfo->link_categories_id)) {
        echo '          <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_LINK_CATEGORIES, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->link_categories_id . '&action=edit') . '\'">' . "\n";
      } else {
        echo '          <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_LINK_CATEGORIES, tep_get_all_get_params(array('cID')) . 'cID=' . $link_categories['link_categories_id']) . '\'">' . "\n";
      }
?>
                <td class="dataTableContent"><?php echo $link_categories['link_categories_name']; ?></td>
                <td  class="dataTableContent" align="right">
<?php
      if ($link_categories['link_categories_status'] == '1') {
        echo tep_image(DIR_WS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_GREEN, 10, 10) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_LINK_CATEGORIES, 'action=setflag&flag=0&cID=' . $link_categories['link_categories_id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) . '</a>';
      } else {
        echo '<a href="' . tep_href_link(FILENAME_LINK_CATEGORIES, 'action=setflag&flag=1&cID=' . $link_categories['link_categories_id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10) . '</a>&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_RED, 10, 10);
      }
?></td>
                <td class="dataTableContent" align="right"><?php if (isset($cInfo) && is_object($cInfo) && ($link_categories['link_categories_id'] == $cInfo->link_categories_id)) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_LINK_CATEGORIES, tep_get_all_get_params(array('cID')) . 'cID=' . $link_categories['link_categories_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
    }
?>
              <tr>
                <td colspan="4"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $link_categories_split->display_count($link_categories_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_LINK_CATEGORIES); ?></td>
                    <td class="smallText" align="right"><?php echo $link_categories_split->display_links($link_categories_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page'], tep_get_all_get_params(array('page', 'info', 'x', 'y', 'cID'))); ?></td>
                  </tr>
                  <tr>
<?php
    if (isset($HTTP_GET_VARS['search']) && tep_not_null($HTTP_GET_VARS['search'])) {
?>
                    <td align="right"><?php echo '<a href="' . tep_href_link(FILENAME_LINK_CATEGORIES) . '">' . tep_image_button('button_reset.gif', IMAGE_RESET) . '</a>'; ?></td>
                    <td align="right"><?php echo '<a href="' . tep_href_link(FILENAME_LINK_CATEGORIES, 'page=' . $HTTP_GET_VARS['page'] . '&action=new') . '">' . tep_image_button('button_new_category.gif', IMAGE_NEW_CATEGORY) . '</a>'; ?></td>
<?php
    } else {
?>
                    <td align="right" colspan="2"><?php echo '<a href="' . tep_href_link(FILENAME_LINK_CATEGORIES, 'page=' . $HTTP_GET_VARS['page'] . '&action=new') . '">' . tep_image_button('button_new_category.gif', IMAGE_NEW_CATEGORY) . '</a>'; ?></td>
<?php
    }
?>
                  </tr>
                </table></td>
              </tr>
            </table></td>
<?php
  $heading = array();
  $contents = array();

  switch ($action) {
    case 'new':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_NEW_LINK_CATEGORY . '</b>');

      $contents = array('form' => tep_draw_form('new_link_categories', FILENAME_LINK_CATEGORIES, 'action=insert', 'post', 'enctype="multipart/form-data"'));
      $contents[] = array('text' => TEXT_NEW_LINK_CATEGORIES_INTRO);

      $link_category_inputs_string = '';
      $languages = tep_get_languages();
      for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
        $link_category_inputs_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('link_categories_name[' . $languages[$i]['id'] . ']');
      }

      $link_category_description_inputs_string = '';
      for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
        $link_category_description_inputs_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;<br>' . tep_draw_textarea_field('link_categories_description[' . $languages[$i]['id'] . ']', 'soft', '40', '5');
      }

      $contents[] = array('text' => '<br>' . TEXT_LINK_CATEGORIES_NAME . $link_category_inputs_string);
      $contents[] = array('text' => '<br>' . TEXT_LINK_CATEGORIES_DESCRIPTION . $link_category_description_inputs_string);
      $contents[] = array('text' => '<br>' . TEXT_LINK_CATEGORIES_IMAGE . '<br>' . tep_draw_file_field('link_categories_image'));
      $contents[] = array('text' => '<br>' . TEXT_LINK_CATEGORIES_SORT_ORDER . '<br>' . tep_draw_input_field('link_categories_sort_order', '', 'size="2"'));
      $contents[] = array('text' => '<br>' . TEXT_LINK_CATEGORIES_STATUS . '&nbsp;&nbsp;' . tep_draw_radio_field('link_categories_status', 'on', true) . ' ' . TEXT_LINK_CATEGORIES_STATUS_ENABLE . '&nbsp;&nbsp;' . tep_draw_radio_field('link_categories_status', 'off') . ' ' . TEXT_LINK_CATEGORIES_STATUS_DISABLE);
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_save.gif', IMAGE_SAVE) . ' <a href="' . tep_href_link(FILENAME_LINK_CATEGORIES) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    case 'edit':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_EDIT_LINK_CATEGORY . '</b>');

      $contents = array('form' => tep_draw_form('edit_link_categories', FILENAME_LINK_CATEGORIES, 'action=update', 'post', 'enctype="multipart/form-data"') . tep_draw_hidden_field('link_categories_id', $cInfo->link_categories_id));
      $contents[] = array('text' => TEXT_EDIT_LINK_CATEGORIES_INTRO);

      $link_category_inputs_string = '';
      $languages = tep_get_languages();
      for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
        $link_category_inputs_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('link_categories_name[' . $languages[$i]['id'] . ']', tep_get_link_category_name($cInfo->link_categories_id, $languages[$i]['id']));
      }

      $link_category_description_inputs_string = '';
      for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
        $link_category_description_inputs_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;<br>' . tep_draw_textarea_field('link_categories_description[' . $languages[$i]['id'] . ']', 'soft', '40', '5', tep_get_link_category_description($cInfo->link_categories_id, $languages[$i]['id']));
      }

      $contents[] = array('text' => '<br>' . TEXT_LINK_CATEGORIES_NAME . $link_category_inputs_string);
      $contents[] = array('text' => '<br>' . TEXT_LINK_CATEGORIES_DESCRIPTION . $link_category_description_inputs_string);
      $contents[] = array('text' => '<br>' . tep_info_image($cInfo->link_categories_image, $cInfo->link_categories_name) . '<br>' . $cInfo->link_categories_image);
      $contents[] = array('text' => '<br>' . TEXT_LINK_CATEGORIES_IMAGE . '<br>' . tep_draw_file_field('link_categories_image'));
      $contents[] = array('text' => '<br>' . TEXT_LINK_CATEGORIES_SORT_ORDER . '&nbsp;' . tep_draw_input_field('link_categories_sort_order', $cInfo->link_categories_sort_order, 'size="2"'));
      $contents[] = array('text' => '<br>' . TEXT_LINK_CATEGORIES_STATUS . '&nbsp;&nbsp;' . tep_draw_radio_field('link_categories_status', 'on', ($cInfo->link_categories_status == '1') ? true : false) . ' ' . TEXT_LINK_CATEGORIES_STATUS_ENABLE . '&nbsp;&nbsp;' . tep_draw_radio_field('link_categories_status', 'off', ($cInfo->link_categories_status == '0') ? true : false) . ' ' . TEXT_LINK_CATEGORIES_STATUS_DISABLE);
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_save.gif', IMAGE_SAVE) . ' <a href="' . tep_href_link(FILENAME_LINK_CATEGORIES, 'cID=' . $cInfo->link_categories_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    case 'delete':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_LINK_CATEGORY . '</b>');

      $contents = array('form' => tep_draw_form('delete_link_categories', FILENAME_LINK_CATEGORIES, 'action=delete_confirm') . tep_draw_hidden_field('link_categories_id', $cInfo->link_categories_id));
      $contents[] = array('text' => TEXT_DELETE_LINK_CATEGORIES_INTRO);
      $contents[] = array('text' => '<br><b>' . $cInfo->link_categories_name . '</b>');
      if ($cInfo->link_categories_count > 0) $contents[] = array('text' => '<br>' . sprintf(TEXT_DELETE_WARNING_LINKS, $cInfo->link_categories_count));
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_LINK_CATEGORIES, 'cID=' . $cInfo->link_categories_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    default:
      if (isset($cInfo) && is_object($cInfo)) {
        $heading[] = array('text' => '<b>' . $cInfo->link_categories_name . '</b>');

        $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_LINK_CATEGORIES, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->link_categories_id . '&action=edit') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_LINK_CATEGORIES, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->link_categories_id . '&action=delete') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a>');

        $contents[] = array('text' => '<br>' . tep_info_image($cInfo->link_categories_image, $cInfo->link_categories_name, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT) . '<br>' . $cInfo->link_categories_image);
        $contents[] = array('text' => '<br>' . TEXT_INFO_LINK_CATEGORY_DESCRIPTION . ' ' . $cInfo->link_categories_description);
        $contents[] = array('text' => '<br>' . TEXT_DATE_LINK_CATEGORY_CREATED . ' ' . tep_date_short($cInfo->link_categories_date_added));
        if (tep_not_null($cInfo->link_categories_last_modified)) {
          $contents[] = array('text' => '<br>' . TEXT_DATE_LINK_CATEGORY_LAST_MODIFIED . ' ' . tep_date_short($cInfo->link_categories_last_modified));
        }
        $contents[] = array('text' => '<br>' . TEXT_INFO_LINK_CATEGORY_COUNT . ' '  . $cInfo->link_categories_count);
        $contents[] = array('text' => '<br>' . TEXT_INFO_LINK_CATEGORY_SORT_ORDER . ' '  . $cInfo->link_categories_sort_order);
      }
      break;
  }

  if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
    echo '            <td width="25%" valign="top">' . "\n";

    $box = new box;
    echo $box->infoBox($heading, $contents);

    echo '            </td>' . "\n";
  }
?>
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
