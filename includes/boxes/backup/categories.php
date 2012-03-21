<?php
/*
  $Id: categories.php,v 1.23 2002/11/12 14:09:30 dgw_ Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  function tep_show_category($counter) {
    global $foo, $categories_string, $id, $aa;

    for ($a=0; $a<$foo[$counter]['level']; $a++) {
      if ($a == $foo[$counter]['level']-1)
	  	{
		$categories_string .= "<font color='#ff0000'>&nbsp;&nbsp;&nbsp;</font>";
     	} else
	  		{
	  		$categories_string .= "<font color='#ff0000'>&nbsp;&nbsp;&nbsp;&nbsp;</font>";
     		}

		}
    if ($foo[$counter]['level'] == 0)
	{
		if ($aa == 1)
		{
		$categories_string .= "<hr width=\"100%\" size=\"1\">";
	    }
		else
		{$aa=1;}

	}



    $categories_string .= '<a href="';

    if ($foo[$counter]['parent'] == 0) {
      $cPath_new = 'cPath=' . $counter;
    } else {
      $cPath_new = 'cPath=' . $foo[$counter]['path'];
    }

if ($foo[$counter]['parent'] == 0) {
   $categories_string .= tep_href_link(FILENAME_DEFAULT, $cPath_new);
   $categories_string .= '">' . tep_image(DIR_WS_IMAGES . 'checkout_bullet.gif', '') . '&nbsp;';
} else {
$categories_string .= tep_href_link(FILENAME_DEFAULT, $cPath_new);
   $categories_string .= '">' . tep_image(DIR_WS_IMAGES . 'arrow_green.gif', '') . '&nbsp;';
}

    if ( ($id) && (in_array($counter, $id)) ) {
      $categories_string .= "<b><font color='#ff0000'>";
    }

// display category name
    $categories_string .= $foo[$counter]['name'];

    if ( ($id) && (in_array($counter, $id)) ) {
      $categories_string .= '</font></b>';
    }

   // if (tep_has_category_subcategories($counter)) {
   //   $categories_string .= '-&gt;';
   // }

    $categories_string .= '</a>';

   // if (SHOW_COUNTS == 'true') {
   //   $products_in_category = tep_count_products_in_category($counter);
   //   if ($products_in_category > 0) {
   //     $categories_string .= '&nbsp;(' . $products_in_category . ')';
   //   }    }

    $categories_string .= '<br>';

    if ($foo[$counter]['next_id']) {
      tep_show_category($foo[$counter]['next_id']);
    }
  }
?>
<!-- categories //-->
          <tr>
            <td>
<?php
  $aa = 0;
require(DIR_WS_INCLUDES . '/boxes/boxTop.php');
echo BOX_HEADING_CATEGORIES;
require(DIR_WS_INCLUDES . '/boxes/boxMiddle.php');
	   
	     $categories_string = '';

// add links to products with no category
  $product_query = tep_db_query("select p.products_id, pd.products_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = p2c.products_id and p2c.categories_id = 0 and p.products_id = pd.products_id and pd.language_id ='"  . (int)$languages_id . "' order by pd.products_name " );
  while ($no_category = tep_db_fetch_array($product_query))  {
        $no_cat_product_id = $no_category['products_id'];
        $no_cat_products_name = $no_category['products_name'];
$myref = "<a href=" . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $no_cat_product_id) . '>' . $no_cat_products_name . '</a><br><br>';
$categories_string .= $myref;
}
  // end links to products with no category[/CODE]

  $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '0' and c.categories_id = cd.categories_id and cd.language_id='" . $languages_id ."' order by sort_order, cd.categories_name");
  while ($categories = tep_db_fetch_array($categories_query))  {
    $foo[$categories['categories_id']] = array(
                                        'name' => $categories['categories_name'],
                                        'parent' => $categories['parent_id'],
                                        'level' => 0,
                                        'path' => $categories['categories_id'],
                                        'next_id' => false
                                       );

    if (isset($prev_id)) {
      $foo[$prev_id]['next_id'] = $categories['categories_id'];
    }

    $prev_id = $categories['categories_id'];

    if (!isset($first_element)) {
      $first_element = $categories['categories_id'];
    }
  }

  //------------------------
  if ($cPath) {
    $new_path = '';
    $id = split('_', $cPath);
    reset($id);
    while (list($key, $value) = each($id)) {
      unset($prev_id);
      unset($first_id);
      $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . $value . "' and c.categories_id = cd.categories_id and cd.language_id='" . $languages_id ."' order by sort_order, cd.categories_name");
      $category_check = tep_db_num_rows($categories_query);
      if ($category_check > 0) {
        $new_path .= $value;
        while ($row = tep_db_fetch_array($categories_query)) {
          $foo[$row['categories_id']] = array(
                                              'name' => $row['categories_name'],
                                              'parent' => $row['parent_id'],
                                              'level' => $key+1,
                                              'path' => $new_path . '_' . $row['categories_id'],
                                              'next_id' => false
                                             );

          if (isset($prev_id)) {
            $foo[$prev_id]['next_id'] = $row['categories_id'];
          }

          $prev_id = $row['categories_id'];

          if (!isset($first_id)) {
            $first_id = $row['categories_id'];
          }

          $last_id = $row['categories_id'];
        }
        $foo[$last_id]['next_id'] = $foo[$value]['next_id'];
        $foo[$value]['next_id'] = $first_id;
        $new_path .= '_';
      } else {
        break;
      }
    }
  }
  tep_show_category($first_element);

 echo $categories_string;
require(DIR_WS_INCLUDES . '/boxes/boxBottom.php');

?>
</td>
</tr>
<!-- categories_eof //-->