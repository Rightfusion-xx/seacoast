<?php
/*
  $Id: admin_notes.php,v 1.0 2004/11/10 12:30:35 popthetop Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

_________________________________________________________________
Admin Notes MODULE for osC Admin Side
By PopTheTop of www.popthetop.com
Original Code By: Robert Hellemans of www.RuddlesMills.com 
These are LIVE SHOPS - So please, no TEST accounts etc...
We will report you to your ISP if you abuse our websites!

*/

  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ADMIN_NOTES);
  
  function tep_set_admin_query_status($contr_id, $status) {
    if ($status == '0') {
      return tep_db_query("update " . TABLE_ADMIN_NOTES . " set status = '0', date_status_change = NULL where contr_id = '" . $contr_id . "'");
    } elseif ($status == '1') {
      return tep_db_query("update " . TABLE_ADMIN_NOTES . " set status = '1', date_status_change = now() where contr_id = '" . $contr_id . "'");
    } elseif ($status == '2') {
      return tep_db_query("update " . TABLE_ADMIN_NOTES . " set status = '2', date_status_change = now() where contr_id = '" . $contr_id . "'");
    } elseif ($status == '3') {
      return tep_db_query("update " . TABLE_ADMIN_NOTES . " set status = '3', date_status_change = now() where contr_id = '" . $contr_id . "'");
    } else {
      return -1;
    }
  }
  switch ($HTTP_GET_VARS['action']) {
    case 'setflag':
      tep_set_admin_query_status($HTTP_GET_VARS['id'], $HTTP_GET_VARS['flag']);
      break;
    case 'insert':
      if ($HTTP_POST_VARS['category_new'] != '') { tep_db_query("insert into " . TABLE_ADMIN_NOTES_TYPE . " (type_id, type_name, status) values ('1', '" . $HTTP_POST_VARS['category_new'] . "','1')"); }
      tep_db_query("insert into " . TABLE_ADMIN_NOTES . " (contr_id, category, admin_note, config_comments, note_created, status, last_update) values ('','" . $HTTP_POST_VARS['file_type_id'] . "','" . $HTTP_POST_VARS['admin_note_new'] . "','" . $HTTP_POST_VARS['config_comments'] . "', now(), '2', '" . $HTTP_POST_VARS['last_update'] . "' )");
      tep_redirect(tep_href_link(FILENAME_ADMIN_NOTES, '&sID=' . $contr_id));
      break;

    case 'copy_to':
      $product_query = tep_db_query("select p.contr_id, p.category, p.admin_note, p.config_comments, p.status, p.last_update from " . TABLE_ADMIN_NOTES . " p where p.contr_id = '" . $HTTP_GET_VARS['sID'] . "'");
      $product = tep_db_fetch_array($product_query);
      $corrected_admin_note = ereg_replace("[']", "\'", $product['admin_note']);
      $corrected_config_comments = ereg_replace("[']", "\'", $product['config_comments']);
      tep_db_query("insert into " . TABLE_ADMIN_NOTES . " (contr_id, category, admin_note, config_comments, note_created, status, last_update) values ('','" . $product['category'] . "','" . $corrected_admin_note . " (copy)','" . $corrected_config_comments . "', now(), '3', '" . $product['last_update'] . "')");
      tep_redirect(tep_href_link(FILENAME_ADMIN_NOTES, 'page=' . $HTTP_GET_VARS['page'] . '&sort=' . $HTTP_GET_VARS['sort'] . '&sID=' . $HTTP_GET_VARS['sID']));
      break;

    case 'update':
      tep_db_query("update " . TABLE_ADMIN_NOTES . " set contr_last_modified = now(), status =  '" . $status . "', admin_note =  '" . $admin_note_new . "', category =  '" . $file_type_id . "', config_comments = '" . $config_comments . "', last_update = '" . $last_update . "' where contr_id = '" . $HTTP_POST_VARS['contr_id'] . "'");
      tep_redirect(tep_href_link(FILENAME_ADMIN_NOTES, 'page=' . $HTTP_GET_VARS['page'] . '&sID=' . $contr_id . '&sort=' . $HTTP_GET_VARS['sort']));
      break;
    case 'deleteconfirm':
      $contr_id = tep_db_prepare_input($HTTP_GET_VARS['sID']);
      tep_db_query("delete from " . TABLE_ADMIN_NOTES . " where contr_id = '" . tep_db_input($contr_id) . "'");
      tep_redirect(tep_href_link(FILENAME_ADMIN_NOTES, 'page=' . $HTTP_GET_VARS['page'] . '&sort=' . $HTTP_GET_VARS['sort']));
      break;
  }
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<SCRIPT LANGUAGE='JAVASCRIPT' TYPE='TEXT/JAVASCRIPT'>
var win=null;
function NewWindow(mypage,myname,w,h,pos,infocus){
if(pos=="random"){myleft=(screen.width)?Math.floor(Math.random()*(screen.width-w)):100;mytop=(screen.height)?Math.floor(Math.random()*((screen.height-h)-75)):100;}
if(pos=="center"){myleft=(screen.width)?(screen.width-w)/2:100;mytop=(screen.height)?(screen.height-h)/2:100;}
else if((pos!='center' && pos!="random") || pos==null){myleft=0;mytop=20}
settings="width=" + w + ",height=" + h + ",top=" + mytop + ",left=" + myleft + ",scrollbars=yes,location=no,directories=no,status=no,menubar=no,toolbar=no,resizable=yes";win=window.open(mypage,myname,settings);
win.focus();}
</script>
<script language="javascript" src="includes/general.js"></script>
<?php
// set sub title
      if ( $HTTP_GET_VARS['action'] == 'readonly') $HEADING_SUB_TITLE = HEADING_SUB_TITLE_READONLY;
      if ( $HTTP_GET_VARS['action'] == 'delete') $HEADING_SUB_TITLE = HEADING_SUB_TITLE_DELETE;
      if ( $HTTP_GET_VARS['action'] == 'update') $HEADING_SUB_TITLE = HEADING_SUB_TITLE_EDIT;
      if ( $HTTP_GET_VARS['action'] == 'edit') $HEADING_SUB_TITLE = HEADING_SUB_TITLE_EDIT;
      if ( $HTTP_GET_VARS['action'] == 'copy_to') $HEADING_SUB_TITLE = HEADING_SUB_TITLE_COPYTO;
      if ( $HTTP_GET_VARS['action'] == 'new') $HEADING_SUB_TITLE = HEADING_SUB_TITLE_INSERT;
      if ( $HTTP_GET_VARS['action'] == 'setflag') $HEADING_SUB_TITLE = HEADING_SUB_TITLE_SETFLAG;
      if ( $HTTP_GET_VARS['action'] == '') $HEADING_SUB_TITLE = HEADING_SUB_TITLE;
      
  if ( ($HTTP_GET_VARS['action'] == 'new') || ($HTTP_GET_VARS['action'] == 'edit') ) {
?>
<link rel="stylesheet" type="text/css">
<?php
  }
?>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onload="SetFocus();">
<div id="spiffycalendar" class="text"></div>
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
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?>&nbsp;<a href="javascript:NewWindow('admin_notes_help.php','ic','600','400','center','front');"><?php echo tep_image(DIR_WS_IMAGES . 'help.png', TABLE_HEADING_HELP); ?></a><br><font class="smallText"><?php echo $HEADING_SUB_TITLE; ?></font></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
<?php 
if ($HTTP_GET_VARS['action'] != 'new' || $HTTP_GET_VARS['action'] != 'edit') {
    $file_type_array = array(array('id' => '', 'text' => '- ' . HEADING_TITLE_SELECT_CATEGORY . ' -'));
    $file_type_query = tep_db_query("select type_name from " . TABLE_ADMIN_NOTES_TYPE . " order by type_name");
    while ($file_type_search = tep_db_fetch_array($file_type_query)) {
      $file_type_array[] = array('id' => $file_type_search['type_name'],
                                     'text' => $file_type_search['type_name']);
    }
if (htmlspecialchars(StripSlashes(@$HTTP_GET_VARS["search"])) == '')
        { $searchquery = 'enter search query'; } else { $searchquery = htmlspecialchars(StripSlashes(@$HTTP_GET_VARS["search"])) ;}
?>
            <td align="right"><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr><?php echo tep_draw_form('search', FILENAME_ADMIN_NOTES, '', 'get'); ?>
                <td class="smallText" align="right"><?php echo HEADING_TITLE_SEARCH . ' ' . tep_draw_input_field('search', $searchquery, "onFocus=\"if (this.value == 'enter search query') { this.value='' }\""); ?></td>
              </form></tr>
              <tr><?php echo tep_draw_form('search_type', FILENAME_ADMIN_NOTES, '', 'get'); ?>
                <td class="smallText" align="right"><?php echo TEXT_SELECT_CATEGORY . ' ' . tep_draw_pull_down_menu('file_type_id', $file_type_array, $file_type_search['category'], 'onChange="this.form.submit();"'); ?></td>
              </form></tr>
            </table></td>
<?php 
}
?>            
          </tr>
        </table></td>
      </tr>
<?php
  if ( ($HTTP_GET_VARS['action'] == 'new') || ($HTTP_GET_VARS['action'] == 'edit') ) {

    $file_type_array = array(array('id' => '', 'text' => TEXT_SELECT_CATEGORY));
    $file_type_query = tep_db_query("select type_name from " . TABLE_ADMIN_NOTES_TYPE . " order by type_name");
    while ($file_type = tep_db_fetch_array($file_type_query)) {
      $file_type_array[] = array('id' => $file_type['type_name'],
                                     'text' => $file_type['type_name']);
    }

    $form_action = 'insert';
                
    if ( ($HTTP_GET_VARS['action'] == 'edit') && ($HTTP_GET_VARS['sID']) ) {
          $form_action = 'update';
                
      $product_query = tep_db_query("select p.contr_id, p.category, p.admin_note, p.status, p.config_comments, p.last_update from " . TABLE_ADMIN_NOTES . " p where p.contr_id = '" . $HTTP_GET_VARS['sID'] . "'");
      $product = tep_db_fetch_array($product_query);
      $sInfo = new objectInfo($product);
    } else {
      $sInfo = new objectInfo(array());
      $contr_array = array();
    }
                        
?>
<link rel="stylesheet" type="text/css" href="includes/javascript/spiffyCal/spiffyCal_v2_1.css">
<script language="JavaScript" src="includes/javascript/spiffyCal/spiffyCal_v2_1.js"></script>
<script language="javascript">
  var LastUpdate = new ctlSpiffyCalendarBox("LastUpdate", "new_contr", "last_update","btnDate1","<?php echo $sInfo->last_update; ?>",scBTNMODE_CUSTOMBLUE);
</script>

      <tr><form name="new_contr" <?php echo 'action="' . tep_href_link(FILENAME_ADMIN_NOTES, tep_get_all_get_params(array('action', 'info', 'sID')) . 'action=' . $form_action, 'NONSSL') . '"'; ?> method="post"><?php if ($form_action == 'update') echo tep_draw_hidden_field('contr_id', $HTTP_GET_VARS['sID']) ; ?>
        <td><br><table border="0" cellspacing="0" cellpadding="2">
         <tr class="dataTableRow">
            <td class="dataTableContent"><?php echo TEXT_CATEGORY; ?>&nbsp;</td>
            <td class="dataTableContent"><?php echo tep_draw_pull_down_menu('file_type_id', $file_type_array, $sInfo->category); ?>&nbsp;<?php echo tep_draw_input_field('category_new', '', 'size=30 maxlength=40'); ?></td>
          </tr>
          <tr class="dataTableRow">
            <td class="dataTableContent"><?php echo TEXT_NAME_VERSION; ?>&nbsp;</td>
            <td class="dataTableContent"><?php echo tep_draw_input_field('admin_note_new', $sInfo->admin_note, 'size=50 maxlength=255'); ?></td>
          </tr>
          <tr class="dataTableRow">
            <td class="dataTableContent"><?php echo TEXT_INFO_TO_REMEMBER; ?>&nbsp;</td>
            <td class="dataTableContent"><script language="javascript">LastUpdate.writeControl(); LastUpdate.dateFormat="yyyy-MM-dd";</script>(<?php echo TEXT_INFO_TO_REMEMBER_HELP; ?>)</td>
          </tr>
          <tr class="dataTableRow">
            <TD VALIGN="top" CLASS="dataTableContent"><?php echo TEXT_CONFIG_COMMENTS; ?>&nbsp;</TD>
            <td class="dataTableContent"><?php echo tep_draw_textarea_field('config_comments', 'soft', '70', '15', ($sInfo->config_comments)) ; ?></td>
          </tr>
<?php
if ( $HTTP_GET_VARS['action'] == 'edit') {
?>                                        
          <tr class="dataTableRow">
            <td class="dataTableContent"><?php echo TEXT_CONFIG_STATUS; ?>&nbsp;</td>
            <td class="dataTableContent"><?php echo tep_draw_input_field('status', $sInfo->status, 'size=2 maxlength=1'); ?>&nbsp;&nbsp;&nbsp;0 = <?php echo tep_image(DIR_WS_IMAGES . 'icon_status_red.gif', 'Important', 10, 10); ?> Important&nbsp;&nbsp;&nbsp;1 = <?php echo tep_image(DIR_WS_IMAGES . 'icon_status_green.gif', 'Not so important', 10, 10); ?> Not so important&nbsp;&nbsp;&nbsp;2 = <?php echo tep_image(DIR_WS_IMAGES . 'icon_status_yellow.gif', 'Caution', 10, 10); ?> Caution</td>
          </tr>
<?php
}
?>          
       </table>
                 
                         </td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main" align="right" valign="top"><br><?php echo (($form_action == 'insert') ? tep_image_submit('button_insert.gif', IMAGE_INSERT) : tep_image_submit('button_update.gif', IMAGE_UPDATE)). '&nbsp;&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_ADMIN_NOTES, 'page=' . $HTTP_GET_VARS['page'] . '&sID=' . $HTTP_GET_VARS['sID']) . '&sort=' . $HTTP_GET_VARS['sort'] . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>&nbsp;&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_ADMIN_NOTES, 'sID=' . $HTTP_GET_VARS['sID']) . '&action=' . $HTTP_GET_VARS['action'] . '" onclick="reset();">' . tep_image_button('button_reset.gif', IMAGE_RESET) . '</a>'; ?></td>
          </tr>
        </table></td>
      </form></tr>
<?php
  } elseif ($HTTP_GET_VARS['action'] != 'readonly') {
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo "$PHP_SELF?sort=2a" . "&page=" . $HTTP_GET_VARS['page'] . "&sID=" . $HTTP_GET_VARS['sID']; ?>"><?php echo tep_image(DIR_WS_IMAGES . 'icon_up.gif', IMAGE_BUTTON_UPSORT); ?></a><a href="<?php echo "$PHP_SELF?sort=2d" . "&page=" . $HTTP_GET_VARS['page'] . "&sID=" . $HTTP_GET_VARS['sID']; ?>"><?php echo tep_image(DIR_WS_IMAGES . 'icon_down.gif', IMAGE_BUTTON_DOWNSORT); ?></a><br><?php echo TABLE_HEADING_NAME; ?></td>
                <td class="dataTableHeadingContent" align="center"><a href="<?php echo "$PHP_SELF?sort=4a" . "&page=" . $HTTP_GET_VARS['page'] . "&sID=" . $HTTP_GET_VARS['sID']; ?>"><?php echo tep_image(DIR_WS_IMAGES . 'icon_up.gif', IMAGE_BUTTON_UPSORT); ?></a><a href="<?php echo "$PHP_SELF?sort=4d" . "&page=" . $HTTP_GET_VARS['page'] . "&sID=" . $HTTP_GET_VARS['sID']; ?>"><?php echo tep_image(DIR_WS_IMAGES . 'icon_down.gif', IMAGE_BUTTON_DOWNSORT); ?></a>&nbsp;&nbsp;<br><?php echo TABLE_HEADING_STATUS; ?></td>
                <td class="dataTableHeadingContent" align="right"><a href="<?php echo "$PHP_SELF?sort=5a" . "&page=" . $HTTP_GET_VARS['page'] . "&sID=" . $HTTP_GET_VARS['sID']; ?>"><?php echo tep_image(DIR_WS_IMAGES . 'icon_up.gif', IMAGE_BUTTON_UPSORT); ?></a><a href="<?php echo "$PHP_SELF?sort=5d" . "&page=" . $HTTP_GET_VARS['page'] . "&sID=" . $HTTP_GET_VARS['sID']; ?>"><?php echo tep_image(DIR_WS_IMAGES . 'icon_down.gif', IMAGE_BUTTON_DOWNSORT); ?></a>&nbsp;&nbsp;<br><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
              
<?php
          switch ($sort) {
              case "2a":
               $order_it_by = "admin_note, status ";
               break;
              case "2d":
               $order_it_by = "admin_note DESC, status ";
               break;
              case "4a":
               $order_it_by = "status , admin_note";
               break;
              case "4d":
               $order_it_by = "status DESC, admin_note";
               break;
              case "5a":
               $order_it_by = "last_update , admin_note";
               break;
              case "5d":
               $order_it_by = "last_update DESC, admin_note";
               break;
              default:
               $order_it_by = "status , admin_note";
                  }
    if ($HTTP_GET_VARS['search']) {
    $admin_query_query_raw = "select contr_id, admin_note, category, status, note_created , contr_last_modified , date_status_change , last_update from " . TABLE_ADMIN_NOTES . " where admin_note like '%" . $HTTP_GET_VARS['search'] . "%' or category like '%" . $HTTP_GET_VARS['search'] . "%' order by " . $order_it_by . " ";
    $admin_query_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $admin_query_query_raw, $admin_query_query_numrows);
    $admin_query_query = tep_db_query($admin_query_query_raw);            
    if (tep_db_num_rows($admin_query_query) == 0) echo '<td  class="dataTableContent" align="right">' . TEXT_NO_DATA . '</td>';
    } elseif ($HTTP_GET_VARS['file_type_id']) {
    $admin_query_query_raw = "select contr_id, admin_note, category, status, note_created , contr_last_modified , date_status_change , last_update from " . TABLE_ADMIN_NOTES . " where category = '" . $HTTP_GET_VARS['file_type_id'] . "' order by " . $order_it_by . " ";
    $admin_query_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $admin_query_query_raw, $admin_query_query_numrows);
    $admin_query_query = tep_db_query($admin_query_query_raw);
    if (tep_db_num_rows($admin_query_query) == 0) echo '<td  class="dataTableContent" align="right">' . TEXT_EMPTY_CATEGORY . '</td>';
    } else {            
    $admin_query_query_raw = "select contr_id, admin_note, category, status, note_created , contr_last_modified , date_status_change , last_update from " . TABLE_ADMIN_NOTES . " order by " . $order_it_by . " ";
    $admin_query_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $admin_query_query_raw, $admin_query_query_numrows);
    $admin_query_query = tep_db_query($admin_query_query_raw);
    if (tep_db_num_rows($admin_query_query) == 0) echo '<td  class="dataTableContent" align="right">' . TEXT_EMPTY_DATABASE . '</td>';
    }
    while ($admin_quer = tep_db_fetch_array($admin_query_query)) {
      if ( ((!$HTTP_GET_VARS['sID']) || ($HTTP_GET_VARS['sID'] == $admin_quer['contr_id'])) && (!$sInfo) ) {
        $sInfo_array = array_merge($admin_quer);
        $sInfo = new objectInfo($sInfo_array);
      }
if ($HTTP_GET_VARS['search'] == '') {
      if ( (is_object($sInfo)) && ($admin_quer['contr_id'] == $sInfo->contr_id)) {
        echo '                  <tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_ADMIN_NOTES, 'page=' . $HTTP_GET_VARS['page'] . '&sID=' . $sInfo->contr_id . '&sort=' . $HTTP_GET_VARS['sort'] . '&action=readonly') . '\'">' . "\n";
      } else {
        echo '                  <tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_ADMIN_NOTES, 'page=' . $HTTP_GET_VARS['page'] . '&sID=' . $admin_quer['contr_id']) . '&sort=' . $HTTP_GET_VARS['sort'] . '\'">' . "\n"; }
} else {
    $searchresult_query_query_raw = "select contr_id from " . TABLE_ADMIN_NOTES . " order by " . $order_it_by . " ";
    $searchresult_query_query = tep_db_query($searchresult_query_query_raw);
    $searchresult_page= round($searchresult_query_query_numrows / MAX_DISPLAY_SEARCH_RESULTS)-1;

      if ( (is_object($sInfo)) && ($admin_quer['contr_id'] == $sInfo->contr_id)) {
        echo '                  <tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_ADMIN_NOTES, 'page=' . $HTTP_GET_VARS['page'] . '&sID=' . $sInfo->contr_id . '&sort=' . $HTTP_GET_VARS['sort'] . '&action=readonly') . '\'">' . "\n";
      } else {
        echo '                  <tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_ADMIN_NOTES, 'page=' . $searchresult_page . '&sID=' . $admin_quer['contr_id']) . '&sort=' . $HTTP_GET_VARS['sort'] . '\'">' . "\n"; }
}
?>

                <td  class="dataTableContent"><?php echo '<a name="' . $admin_quer['admin_note'] . '" title="' . $admin_quer['category'] . '">' . $admin_quer['admin_note']; ?>&nbsp;</a></td>                                                                
                <td  class="dataTableContent" align="center">
<?php
      if ($admin_quer['status'] == '0') {
                echo tep_image(DIR_WS_IMAGES . 'icon_status_red.gif', 'Important', 10, 10) . '&nbsp;<a href="' . tep_href_link(FILENAME_ADMIN_NOTES, 'action=setflag&flag=1&id=' . $admin_quer['contr_id'] . '&page=' . $HTTP_GET_VARS['page'] . '&sID=' . $admin_quer['contr_id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', 'Set: Not so Important Marker', 10, 10) . '</a>&nbsp;<a href="' . tep_href_link(FILENAME_ADMIN_NOTES, 'action=setflag&flag=2&id=' . $admin_quer['contr_id'] . '&page=' . $HTTP_GET_VARS['page'] . '&sID=' . $admin_quer['contr_id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_yellow_light.gif', IMAGE_ICON_STATUS_YELLOW_LIGHT, 10, 10) . '</a>';
      } elseif ($admin_quer['status'] == '1') {
                       echo '<a href="' . tep_href_link(FILENAME_ADMIN_NOTES, 'action=setflag&flag=0&id=' . $admin_quer['contr_id'] . '&page=' . $HTTP_GET_VARS['page'] . '&sID=' . $admin_quer['contr_id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', 'Set: Important Marker', 10, 10) . '</a>&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_status_green.gif', 'Not So Important', 10, 10) . '</a>&nbsp;<a href="' . tep_href_link(FILENAME_ADMIN_NOTES, 'action=setflag&flag=2&id=' . $admin_quer['contr_id'] . '&page=' . $HTTP_GET_VARS['page'] . '&sID=' . $admin_quer['contr_id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_yellow_light.gif', IMAGE_ICON_STATUS_YELLOW_LIGHT, 10, 10) . '</a>';
      } elseif ($admin_quer['status'] == '2') {
                       echo '<a href="' . tep_href_link(FILENAME_ADMIN_NOTES, 'action=setflag&flag=0&id=' . $admin_quer['contr_id'] . '&page=' . $HTTP_GET_VARS['page'] . '&sID=' . $admin_quer['contr_id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', 'Set: Important Marker', 10, 10) . '</a>&nbsp;<a href="' . tep_href_link(FILENAME_ADMIN_NOTES, 'action=setflag&flag=1&id=' . $admin_quer['contr_id'] . '&page=' . $HTTP_GET_VARS['page'] . '&sID=' . $admin_quer['contr_id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', 'Set: Not So Important', 10, 10) . '</a>&nbsp;<a href="' . tep_href_link(FILENAME_ADMIN_NOTES, 'action=setflag&flag=3&id=' . $admin_quer['contr_id'] . '&page=' . $HTTP_GET_VARS['page'] . '&sID=' . $admin_quer['contr_id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_yellow.gif', IMAGE_ICON_STATUS_YELLOW, 10, 10) . '</a>';
      } else {
                if ($admin_quer['status'] == '3')  {
                        echo '<a href="' . tep_href_link(FILENAME_ADMIN_NOTES, 'action=setflag&flag=0&id=' . $admin_quer['contr_id'] . '&page=' . $HTTP_GET_VARS['page'] . '&sID=' . $admin_quer['contr_id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', 'Set: Important Marker', 10, 10) . '</a>&nbsp;<a href="' . tep_href_link(FILENAME_ADMIN_NOTES, 'action=setflag&flag=1&id=' . $admin_quer['contr_id'] . '&page=' . $HTTP_GET_VARS['page'] . '&sID=' . $admin_quer['contr_id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', 'Set: Not So Important', 10, 10) . '</a>&nbsp;<a href="' . tep_href_link(FILENAME_ADMIN_NOTES, 'action=setflag&flag=2&id=' . $admin_quer['contr_id'] . '&page=' . $HTTP_GET_VARS['page'] . '&sID=' . $admin_quer['contr_id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_yellow_light.gif', IMAGE_ICON_STATUS_YELLOW_LIGHT, 10, 10) . '</a>';
                }
      }
?></td>
                <td class="dataTableContent" align="right"><?php if ( (is_object($sInfo)) && ($admin_quer['contr_id'] == $sInfo->contr_id) ) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_ADMIN_NOTES, 'page=' . $HTTP_GET_VARS['page'] . '&sID=' . $admin_quer['contr_id']) . '&sort=' . $HTTP_GET_VARS['sort'] . '&action=readonly">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', 'Preview') . '</a>'; } ?>&nbsp;</td>
      </tr>
<?php
    }
?>
             <tr>
                <td colspan="5"><table border="0" width="100%" cellpadding="0"cellspacing="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $admin_query_split->display_count($admin_query_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_RECORDS); ?></td>
                    <td class="smallText" align="right"><?php echo $admin_query_split->display_links($admin_query_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page'], 'sID=' . $sInfo->contr_id . '&sort=' . $HTTP_GET_VARS['sort']); ?></td>
                  </tr>
<?php
  if (!$HTTP_GET_VARS['action']) {
?>
                  <tr> 
                    <td colspan="2" align="right"><?php echo '<a href="' . tep_href_link(FILENAME_ADMIN_NOTES, 'action=new') . '">' . tep_image_button('button_insert.gif', IMAGE_INSERT) . '</a>'; ?></td>
                  </tr>
<?php
  } 
?>  
                </table></td>
              </tr>
            </table></td>
<?php

  $heading = array();

  $contents = array();

  switch ($HTTP_GET_VARS['action']) {

    case 'delete':
      $heading[] = array('text' => '<center><b>' . TEXT_INFO_HEADING_DELETE . '</b></center>');
      $contents = array('form' => tep_draw_form('install_contr_del', FILENAME_ADMIN_NOTES, 'page=' . $HTTP_GET_VARS['page'] . '&sID=' . $sInfo->contr_id . '&sort=' . $HTTP_GET_VARS['sort'] . '&action=deleteconfirm'));
      $contents[] = array('text' => TEXT_INFO_DELETE_INTRO);
      $contents[] = array('text' => '<br><b>' . $sInfo->admin_note . '</b>');
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . '&nbsp;<a href="' . tep_href_link(FILENAME_ADMIN_NOTES, 'page=' . $HTTP_GET_VARS['page'] . '&sID=' . $sInfo->contr_id) . '&sort=' . $HTTP_GET_VARS['sort'] . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    default:
      if (is_object($sInfo)) {
        $heading[] = array('text' => '<center><b>' . $sInfo->admin_note . '</b></center>');
        $contents[] = array('text' => '' . TEXT_INFO_TO_REMEMBER . ' ' . tep_date_short($sInfo->last_update));
        $contents[] = array('text' => '<br>' . TEXT_INFO_DATE_ADDED . ' ' . tep_date_short($sInfo->note_created));
		  $contents[] = array('text' => '' . TEXT_INFO_LAST_MODIFIED . ' ' . tep_date_short($sInfo->contr_last_modified) . '<BR><BR>');
        $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_ADMIN_NOTES, 'page=' . $HTTP_GET_VARS['page'] . '&sID=' . $sInfo->contr_id . '&sort=' . $HTTP_GET_VARS['sort'] . '&action=edit') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_ADMIN_NOTES, 'page=' . $HTTP_GET_VARS['page'] . '&sID=' . $sInfo->contr_id . '&sort=' . $HTTP_GET_VARS['sort'] . '&action=delete') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a><br><br> <a href="' . tep_href_link(FILENAME_ADMIN_NOTES, 'page=' . $HTTP_GET_VARS['page'] . '&sID=' . $sInfo->contr_id . '&sort=' . $HTTP_GET_VARS['sort'] . '&action=readonly') . '">' . tep_image_button('button_preview.gif', IMAGE_PREVIEW) . '</a> <a href="' . tep_href_link(FILENAME_ADMIN_NOTES, 'page=' . $HTTP_GET_VARS['page'] . '&sID=' . $sInfo->contr_id . '&sort=' . $HTTP_GET_VARS['sort'] . '&action=copy_to') . '">' . tep_image_button('button_copy.gif', IMAGE_COPY) . '</a>' );
      }
      break;
  }
  if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
    echo '            <td width="25%" valign="top">' . "\n";
    $box = new box;
    echo $box->infoBox($heading, $contents);
    echo '            </td>' . "\n";
  }
} elseif ($HTTP_GET_VARS['action'] == 'readonly') {
// BOF readonly
      $product_query = tep_db_query("select contr_id, category, admin_note, status, config_comments, date_status_change, note_created, contr_last_modified, last_update from " . TABLE_ADMIN_NOTES . " where contr_id = '" . $HTTP_GET_VARS['sID'] . "'");
      $product = tep_db_fetch_array($product_query);
      $sInfo = new objectInfo($product);
      if ($sInfo->status == '0') $status_desc = IMAGE_ICON_STATUS_GREEN;
      if ($sInfo->status == '1') $status_desc = IMAGE_ICON_STATUS_RED;      
      if ($sInfo->status == '2') $status_desc = IMAGE_ICON_STATUS_YELLOW;
      if ($sInfo->status == '3') $status_desc = IMAGE_ICON_STATUS_YELLOW_LIGHT;
?>
      <tr>
        <td><br><table border="0" cellspacing="0" cellpadding="2" class="formArea" align="center">
          <tr class="dataTableRow">
            <td class="dataTableContent" NOWRAP><b><?php echo TEXT_NAME_VERSION; ?>&nbsp;</b></td>
            <td class="formAreaTitle"><b><font color=blue><?php echo $sInfo->admin_note; ?></font></b></td>
          </tr>
          <tr class="dataTableRowOver">
            <td class="dataTableContent" NOWRAP><b><?php echo TEXT_CATEGORY; ?>&nbsp;</b></td>
            <td class="dataTableContent"><?php echo $sInfo->category; ?></td>
          </tr>
          <tr class="dataTableRowOver">
            <td class="dataTableContent" NOWRAP><b><?php echo TEXT_CONFIG_COMMENTS; ?>&nbsp;</b></td>
            <td class="dataTableContent"><?php echo nl2br($sInfo->config_comments); ?></td>
          </tr>
          <tr class="dataTableRowOver">
            <td class="dataTableContent" NOWRAP><b><?php echo TEXT_INFO_TO_REMEMBER; ?>&nbsp;</b></td>
            <td class="dataTableContent"><?php echo tep_date_short($sInfo->last_update); ?></td>
          </tr>
          <tr class="dataTableRow">
            <td class="dataTableContent" NOWRAP><b><?php echo TEXT_INFO_DATE_ADDED; ?>&nbsp;</b></td>
            <td class="dataTableContent"><?php echo tep_date_short($sInfo->note_created); ?></td>
          </tr>                    
          <tr class="dataTableRowOver">
            <td class="dataTableContent" NOWRAP><b><?php echo TEXT_INFO_LAST_MODIFIED; ?>&nbsp;</b></td>
            <td class="dataTableContent"><?php if ($sInfo->contr_last_modified != '0000-00-00 00:00:00') echo tep_date_short($sInfo->contr_last_modified); ?></td>
          </tr>                  
          <tr class="dataTableRow">
            <td class="dataTableContent" NOWRAP><b><?php echo TEXT_INFO_STATUS; ?>&nbsp;</b></td>
            <td class="dataTableContent"><?php echo $sInfo->status . ':&nbsp' . $status_desc; ?></td>
          </tr>          
          <tr class="dataTableRowOver">
            <td class="dataTableContent" NOWRAP><b><?php echo TEXT_INFO_STATUS_CHANGE; ?>&nbsp;</b></td>
            <td class="dataTableContent"><?php if ($sInfo->date_status_change != '0000-00-00 00:00:00') echo tep_date_short($sInfo->date_status_change); ?></td>
          </tr>
       </table>
                 
         </td>
      </tr>
      <tr>
            <td align="center" class="main"><br><?php echo '<a href="' . tep_href_link(FILENAME_ADMIN_NOTES, 'page=' . $HTTP_GET_VARS['page'] . '&sID=' . $sInfo->contr_id . '&sort=' . $HTTP_GET_VARS['sort'], 'NONSSL') . '">' . tep_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; ?>
        </td>
      </tr>      
<?php          
} // EOF readonly         
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
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
