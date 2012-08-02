<?php
include_once './includes/actionAbstract.php';
ob_start();?>
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<?php
global $nav;
$nav = ob_get_clean();

class actionClass extends ActionAbstract
{
    protected $_table = 'newsletter_subscribers';

    protected $_keyField = 'customers_id';
    protected $_pageTitle = 'Newsletter Subscribers';
    protected $_scriptUrl = '/newsletter_subscribers.php';

    protected $_gridFields = array(
        'customers_id'                     => 'Customer id',
        'customer_name_concated'           => 'Customer Name',
        'customers_email_address'          => 'Customer e-mail',
        'newsletter_categories_subscribed' => 'Subscribed to'
    );

    protected function _getGridReq()
    {
        return '
            SELECT
                CONCAT (c.customers_firstname, \' \', c.customers_lastname) AS customer_name_concated,
                concat(\'"\', GROUP_CONCAT(nc.category_title SEPARATOR \'"; "\'), \'"\') AS newsletter_categories_subscribed,
                c.*
            FROM `customers` AS c
            INNER JOIN `' . $this->_table . '` AS s ON (s.customer_id = c.customers_id)
            INNER JOIN `newsletter_categories` AS nc ON (s.newsletter_id = nc.category_id)
            GROUP BY c.customers_id
        ';
    }

    public function getListLineActions($row)
    {
        return '<a class="ae-action" href="' . $this->_scriptUrl . '?action=edit&id=' . $row[$this->_keyField] . '">Edit</a> |
        <a onclick="return confirm(\'Are you sure you want to unsubscribe?\')" href="' . $this->_scriptUrl . '?action=remove&id=' . $row[$this->_keyField] . '">Unsubscribe all</a>';
    }

    public function removeAction()
    {
        tep_db_query('
            DELETE FROM `newsletter_subscribers`
            WHERE `customer_id` = \'' . $_GET['id'] . '\'
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
            </td>
        </tr>
    </table>
    <?php
    }

    public function editAction()
    {
        if(!empty($_POST))
        {
            tep_db_query('DELETE FROM newsletter_subscribers WHERE `customer_id` = \'' . (int)$_GET['id'] . '\'');

            if(!empty($_POST['subscrube_to']))
            {
                $insValues = array();
                foreach($_POST['subscrube_to'] as $nId)
                {
                    $insValues[] = '(\'' . (int)$_GET['id'] . '\', \'' . (int)$nId . '\')';
                }
                tep_db_query('
                    INSERT INTO `newsletter_subscribers` (`customer_id`, `newsletter_id`) VALUES ' . implode(', ', $insValues) . '
                ');
            }
            tep_redirect($this->_scriptUrl);
            exit();
        }

        if(empty($_GET['ajax']))
        {
            $this->headOut();
        }
        echo '<form id="main_form" method="post" action="' . $_SERVER['REQUEST_URI'] . '">' . $this->getForm(array()) . '</form>';

        if(empty($_GET['ajax']))
        {
            $this->footOut();
        }
    }

    public function getForm($data = array())
    {
        $body = '';
        $availableNewsletters = tep_db_query('
            SELECT * FROM `newsletter_categories` WHERE `is_enabled` = \'yes\'
        ');

        $subscribed = array();
        $subscribedQ = tep_db_query('SELECT `newsletter_id` FROM `newsletter_subscribers` WHERE `customer_id` = \'' . (int)$_GET['id'] . '\'');
        while($row = tep_db_fetch_array($subscribedQ))
        {
            $subscribed[] = $row['newsletter_id'];
        }

        while($row = tep_db_fetch_array($availableNewsletters))
        {
            $body .= '
                <tr>
                    <td valign="top">
                        <input type="checkbox" name="subscrube_to[]" value="' . $row['category_id'] . '"' . (in_array($row['category_id'], $subscribed)?' checked="checked"':'') . ' />
                    </td>
                    <td>
                        ' . $row['category_title'] . '
                        <div style="margin:0px;padding:0px;margin-left:20px;margin-bottom:5px;">
                            ' . str_replace(array('<p>','</p>'), array('',''), $row['category_description']) . '
                        </div>
                    </td>
                </tr>
            ';
        }
        return '
            <table>
                    ' . $body . '
            </table>
        ';
    }
}

/*
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

*/
$obj = new actionClass();
$obj->run();
?>