<?php

require('application_top.php');

class ActionAbstract
{
    protected $_table = null;
    protected $_gridFields = array();
    protected $_pageTitle = null;
    protected $_keyField = null;
    protected $_scriptUrl = null;
    protected $_hideAddButton = false;
    protected $_rowsCount = 0;

    public function __construct()
    {
        $this->_init();
    }

    protected function _init()
    {

    }

    public function listAction()
    {
        $this->headOut();
        ?>
        <table border="0" width="100%" cellspacing="0" cellpadding="2">
                            <tr class="dataTableHeadingRow">
                                <?php foreach($this->_gridFields as $field => $title):?>
                                <td class="dataTableHeadingContent"><?php echo $title?></td>
                                <?php endforeach;?>
                                <td class="dataTableHeadingContent" nowrap width="75px">Actions</td>
                            </tr>
        <?php
        $req = tep_db_query($this->_getGridReq());

        if(tep_db_num_rows($req) > 0)
        {
            while($row = tep_db_fetch_array($req))
            {
                $this->renderListRow($row);
            }
        }
        else
        {
            ?>
            <tr class="dataTableRow">
                <td class="dataTableContent" colspan="<?php echo (count($this->_gridFields)+1);?>" style="text-align: center; padding: 10px;"> No data to show </td>
            </tr>
            <?
        }
        echo "</table>";
        $this->footOut();
    }

    public function renderListRow($row)
    {
        $this->_rowsCount++;
        echo '<tr class="dataTableRow' . ((($this->_rowsCount%2) == 0)? ' white-row': '') . '">';
        foreach($this->_gridFields as $field => $title)
        {
            echo '<td class="dataTableContent">' . $row[$field] . '</td>';
        }
        echo '<td class="dataTableContent" style="width:75px;">
                    <nobr>
                        ' . $this->getListLineActions($row) . '
                    </nobr>
                </td>';
        echo '</tr>';
    }
    public function getListLineActions($row)
    {
        return '<a class="ae-action" href="' . $this->_scriptUrl . (strpos($this->_scriptUrl, '?')? '&': '?') . 'action=edit&id=' . $row[$this->_keyField] . '">Edit</a> |
        <a onclick="return confirm(\'Are you sure you want to remove this record?\')" href="' . $this->_scriptUrl . (strpos($this->_scriptUrl, '?')? '&': '?') . 'action=remove&id=' . $row[$this->_keyField] . '">Remove</a>';
    }

    protected function _getGridReq()
    {
        return '
            SELECT
                *
            FROM `' . $this->_table . '`
        ';
    }

    public function editAction()
    {
        if(!empty($_POST))
        {
            $parts = array();
            foreach($_POST as $k=>$v)
            {
                $parts[] = '`' . $k . '` = \'' . str_replace("'", "\'", $v) . '\'';
            }
            $q = '
                UPDATE
                    `' . $this->_table . '`
                SET
                    ' . implode(', ', $parts) . '
                WHERE `' . $this->_keyField . '` = \'' . $_GET['id'] . '\'
            ';
            tep_db_query($q);
            tep_redirect($this->_scriptUrl);
            exit();
        }
        if(empty($_GET['ajax']))
        {
            $this->headOut();
        }

        $row = tep_db_fetch_array(tep_db_query('
            SELECT
                *
            FROM `' . $this->_table . '`
            WHERE `' . $this->_keyField . '` = \'' . $_GET['id'] . '\'
        '));
        echo '<form id="main_form" method="post" action="' . $_SERVER['REQUEST_URI'] . '">' . $this->getForm($row) . '</form>';

        if(empty($_GET['ajax']))
        {
            $this->footOut();
        }
    }

    public function addAction()
    {
        if(!empty($_POST))
        {
            foreach($_POST as $k=>$v)
            {
                $_POST[$k] = str_replace("'", "\'", $v);
            }
            $q = '
                INSERT INTO `' . $this->_table . '`
                    (`' . implode('`, `', array_keys($_POST)) . '`)
                VALUES
                    (\'' .  implode('\', \'', array_values($_POST)). '\')
            ';
            tep_db_query($q);
            tep_redirect($this->_scriptUrl);
            exit();
        }

        if(empty($_GET['ajax']))
        {
            $this->headOut();
        }

        echo '<form id="main_form" method="post" action="' . $_SERVER['REQUEST_URI'] . '">' . $this->getForm() . '</form>';

        if(empty($_GET['ajax']))
        {
            $this->footOut();
        }
    }

    public function getForm()
    {

    }

    public function removeAction()
    {
        tep_db_query('
            DELETE FROM `' . $this->_table . '`
            WHERE `' . $this->_keyField . '` = \'' . $_GET['id'] . '\'
        ');
        tep_redirect($this->_scriptUrl);
        exit();
    }

    public function headOut()
    {
        global $nav;
        ?>
    <!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
    <!-- WOL 1.6 - Cleaned up refresh -->
    <?php if(!empty($HTTP_SERVER_VARS["QUERY_STRING"]) && $HTTP_SERVER_VARS["QUERY_STRING"] > 0 ){  ?>
    <meta http-equiv="refresh" content="<?php echo $HTTP_SERVER_VARS["QUERY_STRING"];?>;URL=top_messages.php?<?php echo $HTTP_SERVER_VARS["QUERY_STRING"];?>">
    <?php } ?>
    <!-- WOL 1.6 EOF -->
    <title><?php echo TITLE; ?></title>
    <link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
    <link rel="stylesheet" type="text/css" href="includes/css/ui-lightness/jquery-ui-1.8.22.custom.css">
    <script language="javascript" src="includes/js/jquery-1.7.2.min.js"></script>
    <script language="javascript" src="includes/js/jquery-ui-1.8.22.custom.min.js"></script>
    <script language="javascript" src="includes/general.js"></script>
    <script language="javascript" src="tiny_mce/tiny_mce.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $('.ae-action').click(function(){
                var container = $(document.body).find('.update-container').get(0);
                if(container == undefined)
                {
                    var container = document.createElement('div');
                    $(container)
                        .attr('title', 'Add/Edit')
                        .appendTo(document.body)
                        .dialog({
                            autoOpen: false,
                            modal : true,
                            width: 700,
                            buttons: {
                                Save: function(){
                                    $(container).find('form').submit();
                                }
                            }
                        });
                }

                var url = $(this).attr('href');

                if(url.indexOf('?') == -1)
                {
                    url = url +'?ajax=1';
                }
                else
                {
                    url = url +'&ajax=1';
                }

                $.get(url, function(data){
                    $(container).html(data);
                    $(container).dialog('open');
                })
                return false;
            })
        });
    </script>
    <Style>
        .action-abstract .dataTableHeadingContent{
            padding-left: 5px;
            padding-right: 5px;
            padding-top: 3px;
            padding-bottom: 3px;
            line-height: 25px;
            font-size: 14px;
            background-color: #777;
        }
        .action-abstract .dataTableContent{
            padding-left: 5px;
            padding-right: 5px;
            padding-top: 3px;
            padding-bottom: 3px;
            line-height: 25px;
            font-size: 13px;

        }
        .action-abstract .dataTableRow{
            background-color: #F0F1F1;
        }
        .action-abstract .dataTableRow .dataTableContent{
            border-bottom: 1px dotted #333;
        }
        .action-abstract .dataTableRow:hover{
            background-color: #ccc;
        }
        .white-row{
            background-color: #fff !important;
        }
    </Style>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" class="action-abstract">
<!-- header //-->
    <?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<table border="0" width="100%" cellspacing="2" cellpadding="2">
    <tr>
        <td width="<?php echo BOX_WIDTH; ?>" valign="top">
            <table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
                <!-- left_navigation //-->
                <?php echo $nav ?>
                <!-- left_navigation_eof //-->
            </table>
        </td>
        <td width="100%" valign="top">
            <table border="0" width="100%" cellspacing="0" cellpadding="2">
                <tr>
                    <td>
                        <?php echo $this->outTitleRow();?>

                    </td>
                </tr>
                <tr>
                    <td>

    <?php
    }

    public function footOut()
    {
        ?>

                            </td>
                        </tr>
                    </table>
                </td>
            <tr>
        </table>
    </body>
</html>
    <?php
    }

    public function outTitleRow()
    {?>
            <table border="0" width="100%" cellspacing="0" cellpadding="2">
                <tr>
                    <td class="pageHeading" style="padding:10px;"><?php echo $this->_pageTitle?></td>
                    <td class="pageHeading" style="padding:10px;text-align: right">
                        <?php if(!$this->_hideAddButton):?>
                            <a href="<?php echo $this->_scriptUrl . (strpos($this->_scriptUrl, '?')? '&': '?') . 'action=add'?>" class="ae-action">Add</a>
                        <?php endif;?>
                    </td>
                </tr>
            </table>
        <?php
    }

    public function run()
    {
        $action = !empty($_REQUEST['action'])? $_REQUEST['action'] : 'list';
        if(!method_exists($this, $action.'Action'))
        {
            throw new Exception('Undefined action "' . $action . '"');
        }

        $this->{$action.'Action'}();
    }
}