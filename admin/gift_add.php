<?php
/*
  $Id: gift_add.php,v 1.9 2005/04/25 
  edited by: Jack York @ www.oscommerce-solution.com
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
 
  require('includes/application_top.php');
  
  $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');
  
  if (tep_not_null($action)) {  
    switch ($action) {
      case 'addGift':
       $id = ''; 
       $action_gift = (isset($_GET['free_gifts']) ? $_GET['free_gifts'] : '');
       if (tep_not_null($action_gift))	{
	      $product_query = tep_db_query("SELECT products_id, language_id, products_name FROM products_description WHERE language_id = '".$languages_id."' AND products_name = '" . $action_gift ."'");
        $productID = tep_db_fetch_array($product_query);
        $id = $productID['products_id'];
       }
	  	 $threshold = $HTTP_GET_VARS['threshold'];
		
		if ($id && $threshold) {
	  		$SQL = tep_db_query("INSERT INTO free_gifts (threshold, products_id) VALUES ('".$threshold."','".$id."')");
		} else {
			$message = '<font color="red">FORM ERROR</font>';
		}
		
		tep_redirect('gift_add.php');
		break;
	  case 'delete' :
	  	$id = $HTTP_GET_VARS['ID'];
		if ($id) {
		    tep_db_query("DELETE FROM free_gifts WHERE products_id = '".$id."'");
		}
		tep_redirect('gift_add.php');
        break;
    }
  }
 
  $freeGifts = array();
  $gift_list_query = tep_db_query("SELECT p.products_id, p.products_carrot, pd.products_id, pd.products_name FROM products p, products_description pd WHERE pd.language_id = '".$languages_id."'
			AND p.products_id = pd.products_id AND p.products_carrot = '1' ORDER BY pd.products_name ASC");
  while ($gift_list = tep_db_fetch_array($gift_list_query)) 
   $freeGifts[] = array('id' => $gift_list['products_name'], 'text' => $gift_list['products_name']);
 
    
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
     <tr>
     
  	  <tr> 
       <td class="pageHeading">Free gifts</td>
      </tr>
       
      <tr>
       <td><table border="0">
        <?php   
			$gift_query = tep_db_query("SELECT fg.*, p.products_id, pd.products_name FROM free_gifts fg, products p
			LEFT JOIN products_description pd ON (pd.products_id=fg.products_id)
			WHERE pd.language_id = '".$languages_id."'
			AND p.products_id = fg.products_id
			ORDER BY fg.threshold ASC");
			
			while ($gift = tep_db_fetch_array($gift_query)) {
             
				echo '<form action="gift_add.php" method="GET"><tr>
				<td class="smallText">$<input type="text" size="4" name="threshold" value="'.$gift['threshold'].'"></td>
				<td class="smallText">'.$gift['products_id'].'</td>
				<td class="smallText">'.$gift['products_name'].'</td>
			<td><a href="gift_add.php?ID=' . $gift['products_id'] . '&action=delete">' . tep_image_button('button_delete.gif', 'Delete') . '</a>&nbsp;<a href="categories.php?pID=' . $gift['products_id'] . '&action=new_product">' . tep_image_button('button_edit.gif', 'Edit') . '</a></td>
				</tr></form>';
			}	
		?>
     </table></td>
     </tr>
	  <tr><td height="30"></td></tr>		    
    <tr>
     <td><table border="0">     
  	  <tr><td colspan="4" class="pageHeading"><p>Add New Gift<br><?php echo $message; ?></td></tr>
	  		
	    <!-- next provide for additional free gifts to be added -->
      <form name="newGift" method="GET">
	    <tr>
	     <td class="smallText">$ <input type="text" name="threshold" size="4">Threshold</td>
       <td witdh="40">&nbsp;</td>
	     <td align="left"><?php echo tep_draw_pull_down_menu('free_gifts', $freeGifts, '', '', false);?></td>
	     <td class="smallText"><input type="hidden" name="action" value="addGift"></td>
	     <td class="smallText"><input type="submit" name="Submit" value=" add "></td>
	    </tr>
	    <tr>
       <td colspan="4"></td>
      </tr>
	    </form>      
     </table></td>
    </tr> 
		
	 </table></td>   
	</tr>
 </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>