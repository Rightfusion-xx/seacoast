<?php
include_once './includes/actionAbstract.php';
ob_start();?>
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<?php
global $nav;
$nav = ob_get_clean();
/*
class actionClass extends ActionAbstract
{
    protected $_table = 'newsletter_subscribers';

    protected $_keyField = 'customers_id';
    protected $_pageTitle = 'Newsletter Subscribers';
    protected $_scriptUrl = '/newsletter_subscribers.php';

    protected $_gridFields = array(
        'customers_id'                     => 'Customer id',
        'customer_name_concated'           => 'Customer Name',//customers_firstname, customers_lastname
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

class actionClass extends ActionAbstract
{
    protected $_table = 'newsletter_contects_queries';

    protected $_keyField = 'query_id';
    protected $_pageTitle = 'Contects Queries';
    protected $_scriptUrl = '/newsletter_subscribers.php';

    protected $_gridFields = array(
        'query_id'      => 'List id',
        'query_title'   => 'List Title',
        //'query_query'   => 'List Query',
        'query_created' => 'List Created'
    );

    public function subscribersListAction()
    {
        $customerFields = array(
            'customers_id'                     => 'Customer id',
            'customers_firstname'              => 'Customer Name', //customers_firstname, customers_lastname
            'customers_lastname'               => 'Customer Last Name',
            'customers_email_address'          => 'Customer e-mail'
        );
        $this->_pageTitle = '<a href="'.$this->_scriptUrl.'">' . $this->_pageTitle . '</a> &gt; Customers';

        $this->_hideAddButton = true;

        $row = tep_db_fetch_array(tep_db_query('
            SELECT
                *
            FROM `' . $this->_table . '`
            WHERE `' . $this->_keyField . '` = \'' . $_GET['id'] . '\'
        '));
        $query = $row['query_query'];
        $this->headOut();
        ?>
        <table border="0" width="100%" cellspacing="0" cellpadding="2">
                            <tr class="dataTableHeadingRow">
                                <?php foreach($customerFields as $field => $title):?>
                                <td class="dataTableHeadingContent"><?php echo $title?></td>
                                <?php endforeach;?>
                            </tr>
        <?php
        $req = tep_db_query($query);

        if(tep_db_num_rows($req) > 0)
        {
            while($row = tep_db_fetch_array($req))
            {
                $this->renderSubscriberListRow($row);
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

    public function renderSubscriberListRow($row)
    {

        $customerFields = array(
            'customers_id'                     => 'Customer id',
            'customers_firstname'              => 'Customer Name', //customers_firstname, customers_lastname
            'customers_lastname'               => 'Customer Last Name',
            'customers_email_address'          => 'Customer e-mail'
        );
        $this->_rowsCount++;
        echo '<tr class="dataTableRow' . ((($this->_rowsCount%2) == 0)? ' white-row': '') . '">';
        foreach($customerFields as $field => $title)
        {
            echo '<td class="dataTableContent">' . $row[$field] . '</td>';
        }
        echo '</tr>';
    }

    public function getListLineActions($row)
    {
        return '
        <a class="" href="' . $this->_scriptUrl . (strpos($this->_scriptUrl, '?')? '&': '?') . 'action=subscribersList&id=' . $row[$this->_keyField] . '">View Users</a> |
        <a class="ae-action" href="' . $this->_scriptUrl . (strpos($this->_scriptUrl, '?')? '&': '?') . 'action=edit&id=' . $row[$this->_keyField] . '">Edit</a> |
        <a onclick="return confirm(\'Are you sure you want to remove this record?\')" href="' . $this->_scriptUrl . (strpos($this->_scriptUrl, '?')? '&': '?') . 'action=remove&id=' . $row[$this->_keyField] . '">Remove</a>';
    }

    public function getForm($data)
    {
        $defaultQuery = 'SELECT
    `c`.*
FROM `customers` AS `c`
INNER JOIN `newsletter_subscribers` AS s ON (s.customer_id = c.customers_id)
INNER JOIN `newsletter_categories` AS nc ON (s.newsletter_id = nc.category_id)';
        return '
            <table width="100%">
                <tr>
                    <td>Title:</td>
                    <td><input style="width:100%" type="text" name="query_title" value="' . $data['query_title'] .'" /></td>
                </tr>
                <tr>
                    <td>Query:</td>
                    <td><textarea  wrap="off" name="query_query" style="width:100%;height:300px;">' . (!empty($data['query_query'])? $data['query_query']: $defaultQuery) .'</textarea></td>
                </tr>
                <input type="hidden" value="' . (empty($data['query_created']) ? date('Y-m-d H:i:s') : $data['query_created']) . '" name="query_created" />
            </table>
        ';
    }
}
$obj = new actionClass();
$obj->run();
?>