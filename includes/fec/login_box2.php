  <tr>

      <td height="35" class="infoboxheader" valign="middle"><?php echo LOGINBOX_EXSISTING_CUSTOMER_NOW ?><?php //echo tep_draw_checkbox_field('caccount', 'Y',$checked = false ,'id="togglenow"' ) . '&nbsp;&nbsp;'  ; ?><input type="image" src="images/collapse_tcat.gif" name="row" value="1" onclick="return toggle_collapse('forumbit_2')"></td>
      </tr><TBODY id=collapseobj_forumbit_2>
                <td><p id="lorem2"  >
				<table border="0" width="100%" height="100%" cellspacing="0" cellpadding="2">
                  <tr class="infoBoxContents">
                    <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?><?php echo tep_draw_form('login', tep_href_link(FILENAME_LOGIN, 'action=process', 'SSL')); ?></td>
                  </tr>

                    <td colspan="2"><?php //echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
                  </tr>
                  <tr class="infoBoxContents">
                    <td class="main"><b><?php echo ENTRY_EMAIL_ADDRESS; ?></b></td>
                    <td class="main"><?php echo tep_draw_input_field('email_address'); ?></td>
                  </tr>
                  <tr class="infoBoxContents">
                    <td class="main"><b><?php echo ENTRY_PASSWORD; ?></b></td>
                    <td class="main"><?php echo tep_draw_password_field('password'); ?></td>
                  </tr>
                  <tr>
                    <td colspan="2"><?php //echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
                  </tr>
                  <tr>



                        <td align="right"><?php echo tep_image_submit('button_login.gif', IMAGE_BUTTON_LOGIN); ?></P></td><td class="smallText" colspan="2"><?php echo '<a href="' . tep_href_link(FILENAME_PASSWORD_FORGOTTEN, '', 'SSL') . '">' . TEXT_PASSWORD_FORGOTTEN . '</a>'; ?></td>

                  </table> </form></td><script>toggle_collapse('forumbit_2')</script>
                  </tr>  </TBODY>
