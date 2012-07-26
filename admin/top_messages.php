<?php
require('includes/application_top.php');
if(!empty($_REQUEST['remove_id']))
{
    tep_db_query('
        DELETE FROM `top_messages` WHERE message_id=\'' . $_REQUEST['remove_id'] . '\'
    ');
    tep_redirect('top_messages.php');
    exit();
}
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
    <!-- WOL 1.6 - Cleaned up refresh -->
    <?php if( $HTTP_SERVER_VARS["QUERY_STRING"] > 0 ){  ?>
    <meta http-equiv="refresh" content="<?php echo $HTTP_SERVER_VARS["QUERY_STRING"];?>;URL=top_messages.php?<?php echo $HTTP_SERVER_VARS["QUERY_STRING"];?>">
    <?php } ?>
    <!-- WOL 1.6 EOF -->
    <title><?php echo TITLE; ?></title>
    <link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
    <link rel="stylesheet" type="text/css" href="includes/css/ui-lightness/jquery-ui-1.8.22.custom.css">
    <script language="javascript" src="includes/js/jquery-1.7.2.min.js"></script>
    <script language="javascript" src="includes/js/jquery-ui-1.8.22.custom.min.js"></script>
    <script language="javascript" src="includes/general.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $('.ae-action').click(function(){
                var container = $(document.body).find('.update-container').get(0);
                if(container == undefined)
                {
                    var container = document.createElement('div');
                    $(container)
                        .attr('title', 'Add/Edit Text')
                        .appendTo(document.body)
                        .dialog({
                            autoOpen: false,
                            modal : true,
                            width: 600,
                            buttons: {
                                Save: function(){
                                    $(container).find('form').submit();
                                }
                            }
                        });
                }

                $.get($(this).attr('href'), function(data){
                    $(container).html(data);
                    $(container).dialog('open');
                })
                return false;
            })
        });
    </script>
</head>
    <body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
        <!-- header //-->
        <?php require(DIR_WS_INCLUDES . 'header.php'); ?>
        <table border="0" width="100%" cellspacing="2" cellpadding="2">
            <tr>
                <td width="<?php echo BOX_WIDTH; ?>" valign="top">
                    <table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
                        <!-- left_navigation //-->
                        <?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
                        <!-- left_navigation_eof //-->
                    </table>
                </td>
                <td width="100%" valign="top">
                    <table border="0" width="100%" cellspacing="0" cellpadding="2">
                        <tr>
                            <td>
                                <table border="0" width="100%" cellspacing="0" cellpadding="2">
                                    <tr>

                                        <td class="pageHeading" style="padding:10px;">Top Messages</td>
                                        <td class="pageHeading" style="padding:10px;text-align: right">
                                            <a href="top_messages_update.php" class="ae-action">Add</a>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <table border="0" width="100%" cellspacing="0" cellpadding="2">
                                    <tr class="dataTableHeadingRow">
                                        <td class="dataTableHeadingContent" align="center" width="50px">Id</td>
                                        <td class="dataTableHeadingContent">Keyword</td>
                                        <td class="dataTableHeadingContent">Text</td>
                                        <td class="dataTableHeadingContent" nowrap width="50px">Actions</td>
                                    </tr>
                                    <?php
                                    $query = tep_db_query('
                                        SELECT * FROM `top_messages`
                                    ');
                                    ?>
                                    <?php if(tep_db_num_rows($query) == 0):?>
                                        <tr class="dataTableRow">
                                            <td class="dataTableContent" colspan="4" style="text-align: center; padding: 10px;"> No data to show </td>
                                        </tr>
                                    <?php else:?>
                                        <?php while($row = tep_db_fetch_array($query)):?>
                                            <tr class="dataTableRow">
                                                <td class="dataTableContent" align="center" width="50px"><?php echo $row['message_id']?></td>
                                                <td class="dataTableContent"><?php echo $row['message_keyword']?></td>
                                                <td class="dataTableContent">
                                                    <?php $text = strip_tags($row['message_text']);
                                                        $text = explode(' ', $text);
                                                        if(count($text) > 50)
                                                        {
                                                            $text = array_slice($text, 0, 50);
                                                        }
                                                        echo implode(' ',$text);
                                                    ?>
                                                </td>
                                                <td class="dataTableContent" nowrap style="white-space: nowrap;" width="50px">
                                                    <a class="ae-action" href="top_messages_update.php?text_id=<?php echo $row['message_id']?>" >Edit</a> |
                                                    <a href="top_messages.php?remove_id=<?php echo $row['message_id']?>" onclick="return confirm('Delete this text?')">Remove</a>
                                                </td>
                                            </tr>
                                        <?php endwhile;?>
                                    <?php endif;?>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            <tr>
        </table>
    </body>
</html>