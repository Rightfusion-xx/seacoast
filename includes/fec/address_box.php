<?php

  if (!tep_session_is_registered('registered_now')) {

?>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td>
			<table border="0" width="50%" cellspacing="0" cellpadding="2">
              <tr>

               <td align="left" valign="top">
			   <table border="0" cellspacing="0" cellpadding="2">
                  <tr>
				  <td class="main"><b><?php echo TABLE_HEADING_SHIPPING_ADDRESS; ?></b></td>
				  </tr>
				  <tr>
                   <td class="main" valign="top"><?php echo tep_address_label($customer_id, $sendto, true, ' ', '<br>'); ?></td>
                   </tr><tr>
				  <td class="main"  valign="top"><?php echo  '<a href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING_ADDRESS, '', 'SSL') . '">' . tep_image_button('button_change_address.gif', IMAGE_BUTTON_CHANGE_ADDRESS) . '</a>'; ?></td>
				  </tr>
                </table>
				</td> </tr>
                </table>
				</td>
                <td>
			    <table border="0" width="50%" cellspacing="0" cellpadding="2">
              <tr>
               <td align="right"  valign="top">
			   <table border="0" cellspacing="0" cellpadding="2">
                  <tr>
				 <td class="main"><b><?php echo TABLE_HEADING_BILLING_ADDRESS; ?></b></td>
				 </tr>
				 <tr>
                  <td class="main" valign="top"><?php echo tep_address_label($customer_id, $billto, true, ' ', '<br>'); ?></td></tr>
					<tr>
				  <td class="main"  valign="top"><?php echo '<a href="' . tep_href_link(FILENAME_CHECKOUT_PAYMENT_ADDRESS, '', 'SSL') . '">' . tep_image_button('button_change_address.gif', IMAGE_BUTTON_CHANGE_ADDRESS) . '</a>'; ?></td>
              </tr>

                </table></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
<?php

 }

?>