<?php
require('includes/application_top.php');

$text_id = !empty($_REQUEST['text_id']) ? $_REQUEST['text_id'] : null;

if(!empty($_POST))
{
    if($text_id == null)
    {
        $query = '
          INSERT INTO `top_messages`
            (`message_keyword`, `message_text`)
          VALUES
            (\'' . tep_db_input($_POST['keyword']) . '\', \'' . str_replace("'", "\'", $_POST['html']) . '\')
        ';
        tep_db_query($query);
    }
    else
    {
        tep_db_query('
            UPDATE
                `top_messages`
            SET `message_keyword` = \'' . tep_db_input($_POST['keyword']) . '\', `message_text` = \'' . str_replace("'", "\'", $_POST['html']) . '\'
            WHERE `message_id` = \''.$text_id.'\'
        ');
    }

    tep_redirect('top_messages.php');
    exit();
}
$row = array(
    'message_keyword' => '',
    'message_text'    => ''
);
if(!empty($text_id))
{
    $row = tep_db_fetch_array(tep_db_query('
        SELECT * FROM `top_messages` WHERE message_id = \'' . $text_id . '\'
    '));
}
?>
<form method="POST" action="top_messages_update.php?text_id=<?php echo $text_id?>" onsubmit="if(this.keyword.value == ''){alert('Enter keyword please');return false;}">
    <table>
        <tr>
            <td>Keyword:</td>
            <td><input style="min-width: 500px;" type="text" name="keyword" value="<?php echo addslashes($row['message_keyword'])?>" /></td>
        </tr>
        <tr>
            <td>Html:</td>
            <td><textarea name="html" style="min-width: 500px;min-height: 300px;"><?php echo $row['message_text']?></textarea></td>
        </tr>
    </table>
</form>