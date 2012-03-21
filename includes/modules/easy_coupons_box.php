<?php /*  Released under the GNU General Public License */ ?>
      <tr>
       <td width="100%" align="center">
        <?php echo tep_draw_form('coupon', tep_href_link(basename($PHP_SELF), '', $request_type)); ?>
        <table class="bordergray" width="100%">
         <tr>
          <td valign="middle" align="right" width="50%">
           <?php echo EC_COUPONCODE; ?>
<script>
document.write(' <a href="javascript:session_win2();">');
</script>
<noscript>
<?php echo ' <a href="'.FILENAME_INFO_COUPON.'">';?>
</noscript>
<?php echo tep_image_button('button_about.gif','?',' style="vertical-align: middle" ') . '</a>&nbsp;'; ?>

          </td>
          <td align="center" valign="middle" nowrap>
          <?php if (EASY_COUPONS) {
                  echo tep_draw_password_field('coupon_code1', '', ' size="2"  onKeyup="autotab(this, document.coupon.coupon_code2)" maxlength=4 class="inputbox" ') . ' - ' . tep_draw_password_field('coupon_code2', '', ' size="2" onKeyup="autotab(this, document.coupon.coupon_code3)" maxlength=4 class="inputbox" ') . ' - ' . tep_draw_password_field('coupon_code3', '', ' size="2" onKeyup="autotab(this, document.coupon.coupon_code4)" maxlength=4 class="inputbox" ') . ' - ' . tep_draw_password_field('coupon_code4', '', ' size="2" maxlength=4 class="inputbox" ');
                } else {
                  echo tep_draw_password_field('coupon_code1', '', ' size="2"  maxlength=4 disabled class="inputbox" ') . ' - ' . tep_draw_password_field('coupon_code2', '', ' size="2" maxlength=4 disabled class="inputbox" ') . ' - ' . tep_draw_password_field('coupon_code3', '', ' size="2" maxlength=4 disabled class="inputbox" ') . ' - ' . tep_draw_password_field('coupon_code4', '', ' size="2" maxlength=4 disabled class="inputbox" ');
                }
          ?>
          </td>
          <td align="left" width="50%">
          <?php
                if (EASY_COUPONS) {
                 echo tep_hide_session_id() . tep_image_submit('button_cash_in.gif', 'Cash in Coupon');
                } else {
                 echo '<img src="includes/languages/'.$language.'/images/buttons/button_cash_in_gray.gif" style="vertical-align:bottom;" alt="Unavailable" title="Unavailable">';
                }
          ?>
          </td>
         </tr>
        </table>
       </form>
       </td>
      </tr>
