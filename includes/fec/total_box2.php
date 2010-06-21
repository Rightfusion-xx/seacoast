 <tr class="infoBoxContents" >
        <td align-"right><table border="0" width="100%" cellspacing="1" cellpadding="2" align-"center"   class="infoBox"><tr class="infoBoxContents"><td>
<table border="0" width="100%" cellspacing="1" cellpadding="2" align-"center"   class="infoBoxcontent"  >
         
              
<?php
 
  if (MODULE_ORDER_TOTAL_INSTALLED) {
    $order_total_modules->process();
    echo $order_total_modules->output();
  }

  if (is_array($payment_modules->modules)) {
    if ($confirmation = $payment_modules->confirmation()) {

?>
<?php
      for ($i=0, $n=sizeof($confirmation['fields']); $i<$n; $i++) {
?>
              <tr  class="infoBoxContents"  align="right">
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td class="main"><?php echo $confirmation['fields'][$i]['title']; ?></td>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td class="main"><?php echo $confirmation['fields'][$i]['field']; ?></td>
              </tr>
<?php
      } } }

  
$html = '<input type="submit" name="action" value="Add" />';

  
?>

 </table></td></tr>
 </table></td>
      </tr>