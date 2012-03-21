<?php
if($fromlogin!=1){	
?>
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

               <td class="main"><?php echo tep_draw_checkbox_field('createaccount', 'Y',$checked = false ,'id="toggle"' ) . '&nbsp;&nbsp;' . YES_ACCOUNT ; ?></td>
             </tr>
			 <tr><td>
<p id="lorem"  ><span class="infoBoxContents"><?php echo ENTRY_PASSWORD.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'; ?>
              <?php echo tep_draw_password_field('password') . '&nbsp;' . (tep_not_null(ENTRY_PASSWORD_TEXT) ? '<span class="inputRequirement">' . ENTRY_PASSWORD_TEXT . '</span>': ''); ?><br><span class="infoBoxContents">
<?php echo ENTRY_PASSWORD_CONFIRMATION; ?>
               <?php echo tep_draw_password_field('confirmation') . '&nbsp;' . (tep_not_null(ENTRY_PASSWORD_CONFIRMATION_TEXT) ? '<span class="inputRequirement">' . ENTRY_PASSWORD_CONFIRMATION_TEXT . '': '</span>'); ?>
</p></td></tr>
			            </table></td>
          </tr>
        </table>
<?php
  }
?></td>
      </tr>
<?php
}else{include('includes/fec/account_box.php');
	}
?>