<?php
include_once './includes/actionAbstract.php';
error_reporting(E_ALL);
ini_set('display_errors', 'Off');

ob_start();?>
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<?php
global $nav;
$nav = ob_get_clean();
class newsletters extends ActionAbstract
{
    protected $_table = 'newsletter_categories';
    protected $_gridFields = array(
        'category_id' => 'Id',
        'category_title' => 'Title',
    );
    protected $_pageTitle = 'Newsletters';
    protected $_keyField = 'category_id';
    protected $_scriptUrl = '/newsletters.php';


    public function removeAction()
    {
        tep_db_query('
            DELETE FROM `' . $this->_table . '`
            WHERE `' . $this->_keyField . '` = \'' . $_GET['id'] . '\'
        ');
        tep_db_query('
            DELETE FROM `newsletter_subscribers`
            WHERE `category_id` = \'' . $_GET['id'] . '\'
        ');
        tep_redirect($this->_scriptUrl);
        exit();
    }

    public function outTitleRow()
    {?>
    <table border="0" width="100%" cellspacing="0" cellpadding="2">
        <tr>
            <td class="pageHeading" style="padding:10px;"><?php echo $this->_pageTitle?></td>
            <td class="pageHeading" style="padding:10px;text-align: right">
                <a href="<?php echo $this->_scriptUrl.'?action=add'?>" class="ae-action">Add</a>
            </td>
        </tr>
    </table>
    <?php
    }

    public function getForm($data = array())
    {
        $sign = time();
        return '
            <table width="100%">
                <tr>
                    <td>Title:</td>
                    <td>
                        <input type="text" name="category_title" style="width:100%" value="' . htmlentities($data['category_title']) . '" />
                    </td>
                </tr>
                <tr>
                    <td>Description:</td>
                    <td>
                        <textarea id="category_description' . $sign . '" name="category_description" style="width:100%">' . htmlentities($data['category_description']) . '</textarea>
                    </td>
                </tr>
                <tr>
                    <td>Is Enabled:</td>
                    <td>
                        <input type="hidden" name="is_enabled" value="no" />
                        <input type="checkbox" name="is_enabled" value="yes"' . ((!empty($data['is_enabled']) && $data['is_enabled'] == 'yes')?' checked="checked"':'') .' />
                    </td>
                </tr>
            </table>
            <script type="text/javascript">
                $(document).ready(function()
                {
                    $("#main_form").submit(function(){
                        if(this.category_title.value == \'\')
                        {
                            alert("Enter Newsletter Title");
                            return false;
                        }

                    });
                    tinyMCE.init({
                        mode : "exact",
                        elements : "category_description' . $sign . '",
                        theme : "simple"
                    });
                });
            </script>
        ';
    }

}

$emailTemplates = new newsletters();


$emailTemplates->run();
