<?php
include_once './includes/actionAbstract.php';
ob_start();
require(DIR_WS_INCLUDES . 'column_left.php');

global $nav;
$nav = ob_get_clean();

class actionClass extends ActionAbstract
{
    protected $_pageTitle = 'My Store';
    //protected $_hideAddButton = true;
    protected $_table = TABLE_CONFIGURATION;
    protected $_keyField = 'configuration_id';
    protected $_scriptUrl = '/configuration.php';
    protected $_gridFields = array(
        'configuration_title' => 'Title',
        'configuration_key'   => 'Key',
        'configuration_value' => 'Value',
        'lastSet'             => 'Modified'
    );

    public function __construct()
    {
        parent::__construct();
        if(array_key_exists('gID', $_GET))//!empty($_GET['gID']))
        {
            $this->_scriptUrl .= '?gID='.$_GET['gID'];
            $group = tep_db_fetch_array(tep_db_query('SELECT * FROM  `configuration_group` WHERE `configuration_group_id` = '.intval($_GET['gID'])));
            if(!empty($group))
            {
                $this->_pageTitle = $group['configuration_group_title'];
            }
        }
    }

    protected function _getGridReq()
    {
        $q = '
            SELECT
                t.*,
                IFNULL(t.last_modified, t.date_added) AS lastSet
            FROM `' . $this->_table . '` AS t
        ';
        // 	configuration_group_id
        if(array_key_exists('gID', $_GET))//!empty($_GET['gID']))
        {
            $q .= '
                WHERE `configuration_group_id` = \'' . $_GET['gID'] . '\'
            ';
        }
        return $q;
    }
    public function renderListRow($row)
    {
        $this->_rowsCount++;
        $row['lastSet'] = '<nobr>' . date("F j, Y, g:i a", strtotime($row['lastSet'])) . '</nobr>';
        $val = preg_split("/[\s]+/",$row['configuration_value']);
        if(count($val) > 4)
        {
            $val = array_splice($val, 0, 4);
            $row['configuration_value'] = implode(' ', $val) . ' ...';
        }
        else
        {
            $val = preg_split("/[;]+/",$row['configuration_value']);
            if(count($val) > 4)
            {
                $val = array_splice($val, 0, 4);
                $row['configuration_value'] = implode(';', $val) . ' ...';
            }
        }
        echo '<tr class="dataTableRow' . ((($this->_rowsCount%2) == 0)? ' white-row': '') . '">';
        foreach($this->_gridFields as $field => $title)
        {
            echo '<td class="dataTableContent">' . $row[$field] . '&nbsp;</td>';
        }
        echo '<td class="dataTableContent" style="width:75px;">
                    <nobr>
                        ' . $this->getListLineActions($row) . '
                    </nobr>
                </td>';
        echo '</tr>';
    }

    public function getForm($row)
    {
        $groups = array();
        $groupsQ = tep_db_query('SELECT * FROM  `configuration_group` ORDER BY sort_order');

        if(!empty($row))
        {
            while($gRow = tep_db_fetch_array($groupsQ))
            {
                $groups[$gRow['configuration_group_id']] = $gRow['configuration_group_title'];
            }
            return '
                <table width="100%">
                    <tr>
                        <td>Title: </td>
                        <td>' . $row['configuration_title'] . '</td>
                    </tr>
                    <tr>
                        <td>Key: </td>
                        <td>' . $row['configuration_key'] . '</td>
                    </tr>
                    <tr>
                        <td>Description: </td>
                        <td>' . $row['configuration_description'] . '</td>
                    </tr>
                    <tr>
                        <td>Value: </td>
                        <td><textarea style="width:100%" name="configuration_value">' . htmlentities($row['configuration_value']) . '</textarea></td>
                    </tr>
                    <tr>
                        <td>Use Function: </td>
                        <td><textarea style="width:100%" name="use_function">' . htmlentities($row['use_function']) . '</textarea></td>
                    </tr>
                    <tr>
                        <td>Set Function: </td>
                        <td><textarea style="width:100%" name="set_function">' . htmlentities($row['set_function']) . '</textarea></td>
                    </tr>
                </table>
                <script type="text/javascript">
                    $(".ui-dialog-title").html("Edit \"' . $row['configuration_title'] . '\"");
                </script>
            ';
        }
        else
        {
            while($gRow = tep_db_fetch_array($groupsQ))
            {
                $groups[] = '<option value="' . $gRow['configuration_group_id'] . '">' . $gRow['configuration_group_title'] . '</option>';
            }
            $groups = '<option value="">Select</option>' . implode('', $groups);
            return '
                <table width="100%">
                    <tr>
                        <td>Title: </td>
                        <td><input style="width:100%" name="configuration_title" type="text" value="" /></td>
                    </tr>
                    <tr>
                        <td>Key: </td>
                        <td><input style="width:100%" name="configuration_key" type="text" value="" /></td>
                    </tr>
                    <tr>
                        <td>Group: </td>
                        <td>
                            <select name="configuration_group_id" style="width:100%">' . $groups . '</select>
                        </td>
                    </tr>
                    <tr>
                        <td>Description: </td>
                        <td><textarea style="width:100%" name="configuration_description"></textarea></td>
                    </tr>
                    <tr>
                        <td>Value: </td>
                        <td><textarea style="width:100%" name="configuration_value"></textarea></td>
                    </tr>
                    <tr>
                        <td>Use Function: </td>
                        <td><textarea style="width:100%" name="use_function"></textarea></td>
                    </tr>
                    <tr>
                        <td>Set Function: </td>
                        <td><textarea style="width:100%" name="set_function"></textarea></td>
                    </tr>
                    <input type="hidden" name="date_added" value="' . date('Y-m-d H:i:s') . '">
                </table>
                <script type="text/javascript">
                    $(".ui-dialog-title").html("Add configuration variable");
                    $(document).ready(function()
                    {
                        $("#main_form").submit(function(){
                            if(this.configuration_title.value == \'\')
                            {
                                alert("Enter parameter title");
                                return false;
                            }
                            if(this.configuration_key.value == \'\')
                            {
                                alert("Enter parameter key");
                                return false;
                            }
                            if(this.configuration_group_id.value == \'\')
                            {
                                alert("Choose configuration group");
                                return false;
                            }
                        });
                    });
                </script>
            ';
        }
    }
}

$obj = new actionClass();
$obj->run();


/*
  $Id: configuration.php,v 1.43 2003/06/29 22:50:51 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
/*
  require('includes/application_top.php');

  $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');

  if (tep_not_null($action)) {
    switch ($action) {
      case 'save':
        $configuration_value = tep_db_prepare_input($HTTP_POST_VARS['configuration_value']);
        $cID = tep_db_prepare_input($HTTP_GET_VARS['cID']);

        tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '" . tep_db_input($configuration_value) . "', last_modified = now() where configuration_id = '" . (int)$cID . "'");

        tep_redirect(tep_href_link(FILENAME_CONFIGURATION, 'gID=' . $HTTP_GET_VARS['gID'] . '&cID=' . $cID));
        break;
    }
  }

  $gID = (isset($HTTP_GET_VARS['gID'])) ? $HTTP_GET_VARS['gID'] : 1;

  $cfg_group_query = tep_db_query("select configuration_group_title from " . TABLE_CONFIGURATION_GROUP . " where configuration_group_id = '" . (int)$gID . "'");
  $cfg_group = tep_db_fetch_array($cfg_group_query);
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
          <tr>
            <td class="pageHeading"><?php echo $cfg_group['configuration_group_title']; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_CONFIGURATION_TITLE; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_CONFIGURATION_VALUE; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
  $configuration_query = tep_db_query("select configuration_id, configuration_title, configuration_value, use_function from " . TABLE_CONFIGURATION . " where configuration_group_id = '" . (int)$gID . "' order by sort_order");
  while ($configuration = tep_db_fetch_array($configuration_query)) {
    if (tep_not_null($configuration['use_function'])) {
      $use_function = $configuration['use_function'];
      if (ereg('->', $use_function)) {
        $class_method = explode('->', $use_function);
        if (!is_object(${$class_method[0]})) {
          include(DIR_WS_CLASSES . $class_method[0] . '.php');
          ${$class_method[0]} = new $class_method[0]();
        }
        $cfgValue = tep_call_function($class_method[1], $configuration['configuration_value'], ${$class_method[0]});
      } else {
        $cfgValue = tep_call_function($use_function, $configuration['configuration_value']);
      }
    } else {
      $cfgValue = $configuration['configuration_value'];
    }

    if ((!isset($HTTP_GET_VARS['cID']) || (isset($HTTP_GET_VARS['cID']) && ($HTTP_GET_VARS['cID'] == $configuration['configuration_id']))) && !isset($cInfo) && (substr($action, 0, 3) != 'new')) {
      $cfg_extra_query = tep_db_query("select configuration_key, configuration_description, date_added, last_modified, use_function, set_function from " . TABLE_CONFIGURATION . " where configuration_id = '" . (int)$configuration['configuration_id'] . "'");
      $cfg_extra = tep_db_fetch_array($cfg_extra_query);

      $cInfo_array = array_merge($configuration, $cfg_extra);
      $cInfo = new objectInfo($cInfo_array);
    }

    if ( (isset($cInfo) && is_object($cInfo)) && ($configuration['configuration_id'] == $cInfo->configuration_id) ) {
      echo '                  <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_CONFIGURATION, 'gID=' . $HTTP_GET_VARS['gID'] . '&cID=' . $cInfo->configuration_id . '&action=edit') . '\'">' . "\n";
    } else {
      echo '                  <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_CONFIGURATION, 'gID=' . $HTTP_GET_VARS['gID'] . '&cID=' . $configuration['configuration_id']) . '\'">' . "\n";
    }
?>
                <td class="dataTableContent"><?php echo $configuration['configuration_title']; ?></td>
                <td class="dataTableContent"><?php echo htmlspecialchars($cfgValue); ?></td>
                <td class="dataTableContent" align="right"><?php if ( (isset($cInfo) && is_object($cInfo)) && ($configuration['configuration_id'] == $cInfo->configuration_id) ) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_CONFIGURATION, 'gID=' . $HTTP_GET_VARS['gID'] . '&cID=' . $configuration['configuration_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
  }
?>
            </table></td>
<?php
  $heading = array();
  $contents = array();

  switch ($action) {
    case 'edit':
      $heading[] = array('text' => '<b>' . $cInfo->configuration_title . '</b>');

      if ($cInfo->set_function) {
        eval('$value_field = ' . $cInfo->set_function . '"' . htmlspecialchars($cInfo->configuration_value) . '");');
      } else {
        $value_field = tep_draw_input_field('configuration_value', $cInfo->configuration_value);
      }

      $contents = array('form' => tep_draw_form('configuration', FILENAME_CONFIGURATION, 'gID=' . $HTTP_GET_VARS['gID'] . '&cID=' . $cInfo->configuration_id . '&action=save'));
      $contents[] = array('text' => TEXT_INFO_EDIT_INTRO);
      $contents[] = array('text' => '<br><b>' . $cInfo->configuration_title . '</b><br>' . $cInfo->configuration_description . '<br>' . $value_field);
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_update.gif', IMAGE_UPDATE) . '&nbsp;<a href="' . tep_href_link(FILENAME_CONFIGURATION, 'gID=' . $HTTP_GET_VARS['gID'] . '&cID=' . $cInfo->configuration_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    default:
      if (isset($cInfo) && is_object($cInfo)) {
        $heading[] = array('text' => '<b>' . $cInfo->configuration_title . '</b>');

        $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_CONFIGURATION, 'gID=' . $HTTP_GET_VARS['gID'] . '&cID=' . $cInfo->configuration_id . '&action=edit') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a>');
        $contents[] = array('text' => '<br>' . $cInfo->configuration_description);
        $contents[] = array('text' => '<br>' . TEXT_INFO_DATE_ADDED . ' ' . tep_date_short($cInfo->date_added));
        if (tep_not_null($cInfo->last_modified)) $contents[] = array('text' => TEXT_INFO_LAST_MODIFIED . ' ' . tep_date_short($cInfo->last_modified));
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
*/