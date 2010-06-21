<?php
if($fromlogin!=1){
?>
      <tr>
      <td height="18" class="headerNavigation"><?php echo LOGINBOX_EXSISTING_CUSTOMER ?></td>
      </tr>
      <tr>
        <td><?php

$loginboxcontent = tep_draw_form('login', tep_href_link(FILENAME_LOGIN, 'action=process', 'SSL'))
 								 . '<table width="310" border="0" cellspacing="0" cellpadding="0"><tr><td colspan="3" class="smallText">'
								 .'<tr><td class="smallText">'
								 . tep_draw_separator('pixel_trans.gif', '2', '1')
								 . LOGINBOX_EMAIL
								 . '</td><td  class="smallText" colspan="3">'
						 		 . tep_draw_input_field('email_address', '', 'size="10" maxlength="100" style="width: ' . (BOX_WIDTH-30) . 'px"')
								 .'</td></tr><tr><td class="smallText">'
								 . tep_draw_separator('pixel_trans.gif', '2', '1')
								 . LOGINBOX_PASSWORD
								 . '</td><td class="smallText">'
								 . tep_draw_password_field('password', '', 'size="10" maxlength="40" style="width: ' . (BOX_WIDTH-30) . 'px"')
								 . '</td><td class="smallText">'


								 . '</a></td></tr><tr><td class="smallText" colspan="3">'
								 . tep_draw_separator('pixel_trans.gif', '5', '25')
								 . tep_image_submit('button_login.gif', IMAGE_BUTTON_LOGIN)
						  		 . '</form>'
								 . tep_draw_separator('pixel_trans.gif', '5', '1')
								 .  LOGINBOX_TEXT_PASSWORD
								 . '<a href="'
								 . tep_href_link(FILENAME_PASSWORD_FORGOTTEN, '', 'SSL')
								 . '">'
								 . LOGINBOX_FORGOT_PASSWORD
								 . '</a></td></tr></table><tr>'
					             ;

 $info_box_contents = array();
    $info_box_contents[] = array('align' => '',
                                 'text'  => $loginboxcontent);
    new infoBox($info_box_contents);
?></td>
      </tr>
<?php
}
?>