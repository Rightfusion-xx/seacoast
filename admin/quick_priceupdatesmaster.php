<?php
/*
  $Id: server_info.php,v 1.3 2002/03/16 01:36:56 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License

  Original Author: Mattice <mattice@xs4all.nl>
  Adaptations By:  Mike Lessar <mlessar@bluetruck.net>
  			 Dustin Chambers <info@dllmodz.com>
*/

  require('includes/application_top.php');

/// optional parameter to set max products per row:
$max_cols = 3; //default 3

/// optional parameter to set width of product & info display
$width = 200; //default 200

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
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading">Quick Price Update</td>

          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td>

<table style="border:none" border="0" width="90%" align="center" class="none"><tr><td>
<?php
// we've done nothing cool yet... 
$msg_stack = 'Queried products from category';

// Product Price (NET) BOC
 if ($HTTP_POST_VARS['price_update']) { 

   //set counter
     $stock = 0;
     $status_a = 0;
     $status_d = 0;


  while (list($key, $value) = each($price_update)) {

  // update the quatity in stock
   $update = tep_db_query("UPDATE products SET products_price = $value WHERE products_id = $key");
   $stock_i++;
  }     
 $msg_stack = '<br>Updated <br>"Products Price (Net)"</b>';
 }
// Product Price (NET) EOC

// Stock Update) BOC
    if ($HTTP_POST_VARS['stock_update']) { 

   //set counter
     $stock_i = 0;
     $status_a = 0;
     $status_d = 0;


  while (list($key, $value) = each($stock_update)) {

  // update the quatity in stock
   $update = tep_db_query("UPDATE products SET products_quantity = $value WHERE products_id = $key");
   $stock_i++;

 // we're de-re-activating the selected products
   if ($HTTP_POST_VARS['update_status']) {
     if ($value >= 1 ) { 
                       $dereac = tep_db_query("UPDATE products SET products_status = 1 WHERE products_id = $key");
     $status_a++;
     }else{
                       $dereac = tep_db_query("UPDATE products SET products_status = 0 WHERE products_id = $key");
     $status_d++;
    }
   }
  }     
 $msg_stack = '<br>Total Products: ' . $stock_i . '<br># Active: ' . $status_a . '<br># Not Active: ' .  $status_d;
 }
// Stock Update EOC

?>
<br><form method="post" action="quick_priceupdatesmaster.php">
<?php

   // first select all categories that have 0 as parent:
      $sql = tep_db_query("SELECT c.categories_id, cd.categories_name from categories c, categories_description cd WHERE c.parent_id = 0 AND c.categories_id = cd.categories_id AND cd.language_id = 1");
       echo '<table border="0" align="center"><tr>';
        while ($parents = tep_db_fetch_array($sql)) {
           // check if the parent has products
           $check = tep_db_query("SELECT products_id FROM products_to_categories WHERE categories_id = '" . $parents['categories_id'] . "'");
	   if (tep_db_num_rows($check) > 0) {
          
              $tree = tep_get_category_tree(); 
              $dropdown= tep_draw_pull_down_menu('cat_id', $tree, '', 'onChange="this.form.submit();"'); //single
              $all_list = '<form method="post" action="quick_priceupdates.php"><th class="smallText" align="left" valign="top">All categories:<br>' . $dropdown . '</form></th>';
              
           } else {
    
           // get the tree for that parent
              $tree = tep_get_category_tree($parents['categories_id']);
             // draw a dropdown with it:
                $dropdown = tep_draw_pull_down_menu('cat_id', $tree, '', 'onChange="this.form.submit();"');
                $list .= '<form method="post" action="quick_priceupdates.php"><th class="smallText" align="left" valign="top">' . $parents['categories_name'] . '<br>' . $dropdown . '</form></th>';
        }
       }
       echo /*$list .*/ $all_list . '</form></tr></table><p>';

   // see if there is a category ID:

  if ($HTTP_POST_VARS['cat_id']) {
 
      // start the table
      echo '<form method="post" action="quick_priceupdatesmaster.php"><table border="0" width="100%"><tr>';
       $i = 0;

      // get all active prods in that specific category

       $sql2 = tep_db_query("SELECT p.products_id, 
	   p.products_quantity, 
	   p.products_status, 
	   p.products_image,
	   p.products_model, 
	   p.products_status,
	   p.products_price,
	   p.products_msrp,  
	   pd.products_name from products p, products_to_categories ptc, products_description pd where p.products_id = ptc.products_id and p.products_id = pd.products_id and language_id = $languages_id and ptc.categories_id = '" . $HTTP_POST_VARS['cat_id'] . "'");

     while ($results = tep_db_fetch_array($sql2)) {
           $i++;

			if ($results['products_master_status'] == "1") { 
			  $products_name = '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $HTTP_POST_VARS['cat_id'] . '&pID=' . $results['products_id'] . '&action=new_product') . '"><span style="color: #800080;"><b>(Master) ' . $results['products_name'] . ' </span></b></a>';
			} elseif ($results['products_master'] != 0) {
			  $products_name = '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $HTTP_POST_VARS['cat_id'] . '&pID=' . $results['products_id'] . '&action=new_product') . '"><span style="color: #0080C0">(Slave) ' . $results['products_name'] . ' </a></span>';	
			  } else {
			  $products_name = '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $HTTP_POST_VARS['cat_id'] . '&pID=' . $results['products_id'] . '&action=new_product') . '">' . $results['products_name'] . '</a>';
			  } 
							
             echo '<td width="'. $width . '" align="left" style="padding-top: 20px;">' . tep_image(DIR_WS_CATALOG . DIR_WS_IMAGES . $results['products_image'], $results['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'hspace="5" vspace="5"') . '<br>';
             echo '<span class="smallText">' . $products_name . '</span><br>';
			 echo '<table>
			 			<tr>
			 				<td><span class="smallText">Products Model#: </span></td>
							<td><span class="smallText">' . $results['products_model'] . '</span></td>
						</tr>
			 			<tr>
							<td><span class="smallText">Products SRP: </span></td>
							<td>$<input type="text" size="7" name="msrp_update[' . $results['products_id'] . ']" value="' . $results['products_mspr'] . '"></td>
						</tr>
						<tr>
							<td><span class="smallText">Stock:</span></td>
							<td><input type="text" size="7" name="stock_update[' . $results['products_id'] . ']" value="' . $results['products_quantity'] . '"></td>
						</tr>
						<tr>
							<td><span class="smallText">Status:</span></td>
							<td><span class="smallText">' . (($results['products_status'] == 0) ? '<font color="ff0000"><b>Not Active</b></font>' : '<font color="009933"><b>Active</b></font>') . '</span></td>
						</tr>
						<tr>
							<td></td>
							<td></td>
						</tr>
				    </table>
					<i></td>';
		 	   echo '</i></td>';
          if ($i == $max_cols) {
               echo '</tr><tr>';
               $i =0;
         }
    }
  echo '<input type="hidden" name="cat_id" value="' . $HTTP_POST_VARS['cat_id'] . '">';
  echo '</tr><td align="center" colspan="10">';
  echo '<input type="checkbox" checked="true" name="update_status"><span class="smallText">Confirm Status Change (active/not active)</span><p>';
  echo '<input type="submit" value="Update"></td></tr><td colspan="30" align="left"><font color="333333"><br><b>Last performed action:</b><br>' . $msg_stack . '</b></font></td></tr></form>';
  } //if
?>
    </tr></table>


            </td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
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