<?php
include_once './includes/actionAbstract.php';
ob_start();?>
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<?php
global $nav;
$nav = ob_get_clean();
class emailTemplates extends ActionAbstract
{
    protected $_table = 'system_email_templates';
    protected $_gridFields = array(
        'template_code' => 'Code',
        'template_from' => 'From',
        'template_subject' => 'Subject'
    );
    protected $_pageTitle = 'System Email Templates';
    protected $_keyField = 'template_id';
    protected $_scriptUrl = '/email_templates.php';

    public function getForm($data = array())
    {
        return '
            <table>
                <tr>
                    <td>Code:</td>
                    <td><input type="text" name="template_code" style="width:100%" value="' . htmlentities($data['template_code']) . '" /></td>
                </tr>
                <tr>
                    <td>From e-mail:</td>
                    <td><input type="text" name="template_from" style="width:100%" value="' . htmlentities($data['template_from']) . '" /></td>
                </tr>
                <tr>
                    <td>From name:</td>
                    <td><input type="text" name="template_from_name" style="width:100%" value="' . htmlentities($data['template_from_name']) . '" /></td>
                </tr>
                <tr>
                    <td>Reply to:</td>
                    <td><input type="text" name="template_replay_to" style="width:100%" value="' . htmlentities($data['template_replay_to']) . '" /></td>
                </tr>
                <tr>
                    <td>Copy to:</td>
                    <td><input type="text" name="template_copy_to" style="width:100%" value="' . htmlentities($data['template_copy_to']) . '" /></td>
                </tr>
                <tr>
                    <td>Subject:</td>
                    <td><input type="text" name="template_subject" style="width:100%" value="' . htmlentities($data['template_subject']) . '" /></td>
                </tr>
                <tr>
                    <td>
                        Allowed variables:
                    </td>
                    <td>
                        ' . $data['template_available_vars'] . '
                    </td>
                </tr>
                <tr>
                    <td>Text body:</td>
                    <td><textarea name="template_text_body" style="width:500px;height:250px;">' . $data['template_text_body'] . '</textarea></td>
                </tr>
                <tr>
                    <td>Html body:</td>
                    <td><textarea id="template_html_body" name="template_html_body" style="width:500px;height:250px;">' . htmlentities($data['template_html_body']) . '</textarea></td>
                </tr>
            </table>
            <script type="text/javascript">
                $(document).ready(function()
                {
                    $("#main_form").submit(function(){
                        if(this.template_code.value == \'\')
                        {
                            alert("Enter Template Code");
                            return false;
                        }
                    });
                    // Notice: The simple theme does not use all options some of them are limited to the advanced theme
                    tinyMCE.init({
                        mode : "exact",
                        elements : "template_html_body",
                        theme : "simple"
                    });
                });

            </script>
        ';
    }

}

$emailTemplates = new emailTemplates();


$emailTemplates->run();
?>