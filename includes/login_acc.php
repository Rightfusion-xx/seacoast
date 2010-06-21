<td>
<?php
$returning_customer_info = "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-collapse: collapse\" bordercolor=\"#111111\" width=\"100%\" id=\"AutoNumber1\">
  <tr>
    <td width=\"100%\" class=\"main\" colspan=\"3\">" . tep_draw_separator('pixel_trans.gif', '100%', '10') . "</td>
  </tr>
  <tr>
    <td class=\"main\">

<table width="100%" border="0">
  <tr>
    <td width="24%">" . ENTRY_EMAIL_ADDRESS . "</td>
    <td width="27%">&quot; . tep_draw_input_field('email_address') . &quot;</td>
    <td width="1%">&nbsp;</td>
    <td width="48%">' . TEXT_NEW_CUSTOMER_INTRODUCTION . "</td>
  </tr>
  <tr>
    <td width="24%">" . ENTRY_PASSWORD . "<br>
      <br>
    </td>
    <td width="27%">" . tep_draw_password_field('password') . "<br>
      <br>
    </td>
    <td width="1%">&nbsp;</td>
    <td width="48%">" . tep_draw_separator('pixel_trans.gif', '100%', '10') . "</td>
  </tr>
  <tr>
    <td width="24%">" . tep_image_submit('button_login.gif', IMAGE_BUTTON_LOGIN) 
      . "<br>
      <br>
      " . '<a href="' . tep_href_link(FILENAME_PASSWORD_FORGOTTEN, '', 'SSL') . '">' 
      . TEXT_PASSWORD_FORGOTTEN . '</a>' . "<br>
      <br>
    </td>
    <td width="27%">&nbsp;</td>
    <td width="1%">&nbsp;</td>
    <td width="48%">" . '<a href="' . tep_href_link(FILENAME_CREATE_ACCOUNT, '', 'SSL') . '">' . tep_image_button('button_create_account.gif', IMAGE_BUTTON_CREATE_ACCOUNT) . '</a>' . "</td>
  </tr>
</table>
";
//===========================================================
?>
<table width="100%" cellpadding="5" cellspacing="0" border="0">
    <tr>
     <td class="main" width=100% valign="top" align="center">
<?php
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                               'text'  => $returning_customer_title );
  new infoBoxHeading($info_box_contents, true, false);

  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                               'text'  => $returning_customer_info);
  new infoBox($info_box_contents);
?>
  </td>
 </tr>
</table>
<?php
//EOF: MaxiDVD Returning Customer Info SECTION
//===========================================================





//MaxiDVD New Account Sign Up SECTION
//===========================================================
$create_account_title = HEADING_NEW_CUSTOMER;
$create_account_info = "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-collapse: collapse\" bordercolor=\"#111111\" width=\"100%\" id=\"AutoNumber1\">
  <tr>
    <td width=\"100%\" class=\"main\" colspan=\"3\">" . TEXT_NEW_CUSTOMER_INTRODUCTION . "</td>
  </tr>
  <tr>
    <td width=\"100%\" class=\"main\" colspan=\"3\">" . tep_draw_separator('pixel_trans.gif', '100%', '10') . "</td>
  </tr>
  <tr>
    <td width=\"33%\" class=\"main\"></td>
    <td width=\"33%\"></td>
    <td width=\"34%\" rowspan=\"3\" align=\"center\">" . '<a href="' . tep_href_link(FILENAME_CREATE_ACCOUNT, '', 'SSL') . '">' . tep_image_button('button_create_account.gif', IMAGE_BUTTON_CREATE_ACCOUNT) . '</a>' . "<br><br></td>
  </tr>
</table>";
//===========================================================
?>
<?php echo tep_draw_separator('pixel_trans.gif', '100%', '15'); ?>
<table width="100%" cellpadding="5" cellspacing="0" border="0">
    <tr>
     <td class="main" width=100% valign="top" align="center">
<?php
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                               'text'  => $create_account_title );
  new infoBoxHeading($info_box_contents, false, false);

  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                               'text'  => $create_account_info);
  new infoBox($info_box_contents);
?>
  </td>
  </tr>
</table>
<?php
//EOF: MaxiDVD New Account Sign Up SECTION
//===========================================================
?>
</td>
