<?php
include_once './includes/actionAbstract.php';
ob_start();?>
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<?php
global $nav;
$nav = ob_get_clean();
class emailTemplates extends ActionAbstract
{
    protected $_table = 'newsletter_emails';
    protected $_gridFields = array(
        'email' => 'Email',
        'FirstName' => 'First Name',
        'LastName'  => 'Last Name',
        'timecreated' => 'Date'
    );
    protected $_pageTitle = 'Newsletter Subscribers';
    protected $_keyField = 'email';
    protected $_scriptUrl = '/newsletter_subscribers.php';

    public function importAction()
    {
        if(!empty($_POST))
        {
            if(!empty($_FILES['csv']['tmp_name']))
            {
                $cursor = fopen($_FILES['csv']['tmp_name'], 'r');
                while($dataRow = fgetcsv($cursor, null, ';'))
                {
                    $dbRow = tep_db_query('SELECT * FROM `' . $this->_table . '` WHERE `' . $this->_keyField . '` = \'' . $dataRow[0] . '\'');
                    if(tep_db_num_rows($dbRow) == 0)
                    {
                        tep_db_query('INSERT INTO `' . $this->_table . '` (`email`, `FirstName`, `LastName`) VALUES (\'' . $dataRow[0] . '\', \'' . $dataRow[1] . '\', \'' . $dataRow[2] . '\')');
                    }
                    else
                    {
                        tep_db_query('UPDATE `' . $this->_table . '` SET `FirstName` = \'' . $dataRow[1] . '\', `LastName` = \'' . $dataRow[2] . '\' WHERE `' . $this->_keyField . '` = \'' . $dataRow[0] . '\'');
                    }
                }
                fclose($cursor);
                unlink($_FILES['csv']['tmp_name']);
            }
            tep_redirect($this->_scriptUrl);
            exit();
        }

        if(empty($_GET['ajax']))
        {
            $this->headOut();
        }

        echo '<form id="main_form" enctype="multipart/form-data" method="post" action="' . $_SERVER['REQUEST_URI'] . '">
            <input type="hidden" name="psted" value="yes" />
            <table width="100%">
                <tr>
                    <td>Select a file:</td>
                    <td><input type="file" name="csv"></td>
                </tr>
            </table>
        </form>';

        if(empty($_GET['ajax']))
        {
            $this->footOut();
        }
    }

    public function outTitleRow()
    {?>
    <table border="0" width="100%" cellspacing="0" cellpadding="2">
        <tr>
            <td class="pageHeading" style="padding:10px;"><?php echo $this->_pageTitle?></td>
            <td class="pageHeading" style="padding:10px;text-align: right">
                <a href="<?php echo $this->_scriptUrl.'?action=add'?>" class="ae-action">Add</a>
                <a href="<?php echo $this->_scriptUrl.'?action=import'?>" class="ae-action">Import CSV</a>
            </td>
        </tr>
    </table>
    <?php
    }

    public function getForm($data = array())
    {
        return '
            <table width="100%">
                <tr>
                    <td>Email:</td>
                    <td><input type="text" name="email" style="width:100%" value="' . htmlentities($data['email']) . '" /></td>
                </tr>
                <tr>
                    <td>First Name:</td>
                    <td><input type="text" name="FirstName" style="width:100%" value="' . htmlentities($data['FirstName']) . '" /></td>
                </tr>
                <tr>
                    <td>Last Name:</td>
                    <td><input type="text" name="LastName" style="width:100%" value="' . htmlentities($data['LastName']) . '" /></td>
                </tr>
            </table>
            <script type="text/javascript">
                $(document).ready(function()
                {
                    $("#main_form").submit(function(){
                        if(this.email.value == \'\')
                        {
                            alert("Enter Subscriber Email");
                            return false;
                        }
                        else if(this.FirstName.value == \'\')
                        {
                            alert("Enter Subscriber First name");
                            return false;
                        }
                        else if(this.LastName.value == \'\')
                        {
                            alert("Enter Subscriber Last name");
                            return false;
                        }
                    });
                });
            </script>
        ';
    }

}

$emailTemplates = new emailTemplates();


$emailTemplates->run();
?>