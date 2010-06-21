<tr>
        <td class="main"><b><?php  if($fromlogin!=1){echo CATEGORY_CREATE_ACCOUNT;}else{echo CATEGORY_PASSWORD;} ?></b></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" cellspacing="2" cellpadding="2">
            <?php
if($fromlogin!=1){
?>
              <tr>
			   <td class="main"><?php echo ENTRY_CREATEACCOUNT; ?></td>
			   </tr>
			  <tr>
                <td class="main"><?php echo tep_draw_radio_field('createaccount', 'Y',$checked = true) . '&nbsp;&nbsp;' . YES_ACCOUNT . '&nbsp;&nbsp;</td></tr><tr><td class="main">' . tep_draw_radio_field('createaccount', 'N') . '&nbsp;&nbsp;' . NO_ACCOUNT . '&nbsp;' . (tep_not_null(ENTRY_GENDER_TEXT) ? '<span class="inputRequirement">' . ENTRY_GENDER_TEXT . '</span>': ''); ?></td>
             </tr>
			 <tr>
			    <td class="main">Please choose a password if you want to create an account</td>
             </tr></table><table>
<?php
  }
?>
			  <tr>
			    <td class="main"><?php echo ENTRY_PASSWORD; ?></td>
                <td class="main"><?php echo tep_draw_password_field('password') . '&nbsp;' . (tep_not_null(ENTRY_PASSWORD_TEXT) ? '<span class="inputRequirement">' . ENTRY_PASSWORD_TEXT . '</span>': ''); ?></td>
              </tr>
              <tr>
                <td class="main"><?php echo ENTRY_PASSWORD_CONFIRMATION; ?></td>
                <td class="main"><?php echo tep_draw_password_field('confirmation') . '&nbsp;' . (tep_not_null(ENTRY_PASSWORD_CONFIRMATION_TEXT) ? '<span class="inputRequirement">' . ENTRY_PASSWORD_CONFIRMATION_TEXT . '</span>': ''); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>