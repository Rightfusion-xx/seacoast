<?php
/*
  $Id: categories.php,v 1.25 2003/07/09 01:13:58 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

  changed August 2003, by Nils Petersson
  contribution "Category Box Enhancement", version 1.1

*/

  function tep_show_category($counter) {

// BoF - Contribution Category Box Enhancement 1.1
    global $tree, $categories_string, $cPath_array, $cat_name;

    for ($i=0; $i<$tree[$counter]['level']; $i++) {
      $categories_string .= "&nbsp;&nbsp;";
    }
    $cPath_new = 'cPath=' . $tree[$counter]['path'];
    if (isset($cPath_array) && in_array($counter, $cPath_array) && $cat_name == $tree[$counter]['name']) { //Link nicht anklickbar, wenn angewählt
             $categories_string .= '<a href="';
             $categories_string .= tep_href_link(FILENAME_DEFAULT, $cPath_new) . '">';																	 	 //Link nicht anklickbar, wenn angewählt
    } else {						 																					 //Link nicht anklickbar, wenn angewählt
    $categories_string .= '<a href="';
    $categories_string .= tep_href_link(FILENAME_DEFAULT, $cPath_new) . '">';
    }									 																						 //Link nicht anklickbar, wenn angewählt
    if (tep_has_category_subcategories($counter)) {
      $categories_string .= tep_image(DIR_WS_IMAGES . 'pointer_blue.gif', '');
    }
    else {
      $categories_string .= tep_image(DIR_WS_IMAGES . 'pointer_blue_light.gif', '');
    }

    if (isset($cPath_array) && in_array($counter, $cPath_array)) {
      $categories_string .= '<b>';
    }

    if ($cat_name == $tree[$counter]['name']) {
      $categories_string .= '<span class="errorText">';
    }

// display category name
    $categories_string .= $tree[$counter]['name'];

		if ($cat_name == $tree[$counter]['name']) {
			$categories_string .= '</span>';
    }

    if (isset($cPath_array) && in_array($counter, $cPath_array)) {
      $categories_string .= '</b>';
    }
// 	EoF Category Box Enhancement

    $categories_string .= '</a>';

    if (SHOW_COUNTS == 'true') {
      $products_in_category = tep_count_products_in_category($counter);
      if ($products_in_category > 0) {
        $categories_string .= '&nbsp;(' . $products_in_category . ')';
      }
    }

    $categories_string .= '<br>';

    if ($tree[$counter]['next_id'] != false) {
      tep_show_category($tree[$counter]['next_id']);
    }
  }
?>
<!-- categories //-->
          <tr id="scv_categories">
            <td>
<?php

// BoF - Contribution Category Box Enhancement 1.1
 if (isset($cPath_array)) {
		for ($i=0, $n=sizeof($cPath_array); $i<$n; $i++) {
				$categories_query = tep_db_query("select categories_name from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . (int)$cPath_array[$i] . "' and language_id = '" . (int)$languages_id . "'");
				if (tep_db_num_rows($categories_query) > 0)
				$categories = tep_db_fetch_array($categories_query);
		}
	$cat_name = $categories['categories_name'];
	}
// EoF Category Box Enhancement
// display category name

  $info_box_contents = array();
  $info_box_contents[] = array('text' => BOX_HEADING_CATEGORIES);

  new infoBoxHeading($info_box_contents, true, false);

  $categories_string = '';
  $tree = array();

  $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '0' and c.categories_id = cd.categories_id and cd.language_id='" . (int)$languages_id ."' order by sort_order, cd.categories_name");
  while ($categories = tep_db_fetch_array($categories_query))  {
    $tree[$categories['categories_id']] = array('name' => $categories['categories_name'],
                                                'parent' => $categories['parent_id'],
                                                'level' => 0,
                                                'path' => $categories['categories_id'],
                                                'next_id' => false);

    if (isset($parent_id)) {
      $tree[$parent_id]['next_id'] = $categories['categories_id'];
    }

    $parent_id = $categories['categories_id'];

    if (!isset($first_element)) {
      $first_element = $categories['categories_id'];
    }
  }

  //------------------------
  if (tep_not_null($cPath)) {
    $new_path = '';
    reset($cPath_array);
    while (list($key, $value) = each($cPath_array)) {
      unset($parent_id);
      unset($first_id);
      $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . (int)$value . "' and c.categories_id = cd.categories_id and cd.language_id='" . (int)$languages_id ."' order by sort_order, cd.categories_name");
      if (tep_db_num_rows($categories_query)) {
        $new_path .= $value;
        while ($row = tep_db_fetch_array($categories_query)) {
          $tree[$row['categories_id']] = array('name' => $row['categories_name'],
                                               'parent' => $row['parent_id'],
                                               'level' => $key+1,
                                               'path' => $new_path . '_' . $row['categories_id'],
                                               'next_id' => false);

          if (isset($parent_id)) {
            $tree[$parent_id]['next_id'] = $row['categories_id'];
          }

          $parent_id = $row['categories_id'];

          if (!isset($first_id)) {
            $first_id = $row['categories_id'];
          }

          $last_id = $row['categories_id'];
        }
        $tree[$last_id]['next_id'] = $tree[$value]['next_id'];
        $tree[$value]['next_id'] = $first_id;
        $new_path .= '_';
      } else {
        break;
      }
    }
  }
  tep_show_category($first_element);

  $info_box_contents = array();
  $info_box_contents[] = array('text' => $categories_string);

 if ( (basename($PHP_SELF) != FILENAME_PRODUCTS_NEW)) {
$info_box_contents[] = array('align' => 'left',
                               'text'  => '<font size=-2><b><a href="' . tep_href_link(FILENAME_PRODUCTS_NEW, '', 'NONSSL') . '">' . BOX_INFORMATION_PRODUCTS_NEW . '</a></b></font>');
 }else{
 $info_box_contents[] = array('align' => 'left',
                               'text'  => '<font size=-2><b><a href="' . tep_href_link(FILENAME_PRODUCTS_NEW, '', 'NONSSL') . '"><span class="errorText">' . BOX_INFORMATION_PRODUCTS_NEW . '</a></b></font></span>');
  }
  function tep_get_paths($categories_array = '', $parent_id = '0', $indent = '', $path='') {
  /Start Multiple Cats Query
function tep_get_multiple_paths($multiple_category_ids) {
global $cPath_array;
static $cats = array();
$cp_size = sizeof($cPath_array);
$exploded = explode(',', $multiple_category_ids);
if (!in_array('"' . $cPath_array[($cp_size-1)] . '"', $exploded)) {
   $multiple_category_ids .= "," . $cPath_array[($cp_size-1)];
}
unset($exploded);
//Mod
$categories_parents_result = tep_db_query("SELECT parent_id, categories_id from " . TABLE_CATEGORIES . " where categories_id in (" . $multiple_category_ids . ") ");
while ($categories_parents = tep_db_fetch_array($categories_parents_result)) {
    $category_parent[] = array (
                               'categories_id' => $categories_parents['categories_id'],
                               'parent_id'     => $categories_parents['parent_id']);
    $parent_by_category[$categories_parents['categories_id']] = $categories_parents['parent_id'];
  }
  tep_db_free_result($categories_parents_result); // Housekeeping
   unset($multiple_category_ids); //Housekeeping
   $counted = count($category_parent);
   for($i=0; $i<$counted; $i++) {
$current_category_id = $category_parent[$i]['categories_id'];
//End Mod
if (tep_not_null($current_category_id)) {
$cp_size = sizeof($cPath_array);
if ($cp_size == 0) {
$cPath_new = $current_category_id;
} else {
$cPath_new = '';
if ( !isset($cats[($cp_size-1)]) ){
$last_category['parent_id'] = $parent_by_category[$cPath_array[($cp_size-1)]];
$cats[($cp_size-1)] = $last_category['parent_id'];
} else {
$last_category['parent_id'] = $cats[($cp_size-1)];
}

if ( !isset($cats[(int)$current_category_id]) ){
$current_category['parent_id'] = $category_parent[$i]['parent_id'];
$cats[(int)$current_category_id] = $current_category['parent_id'];
} else {
$current_category['parent_id'] = (int)$current_category_id;
}

if ($last_category['parent_id'] == $current_category['parent_id']) {
for ($j=0; $j<($cp_size-1); $j++) {
$cPath_new .= '_' . $cPath_array[$j];
}
} else {
for ($j=0; $j<$cp_size; $j++) {
$cPath_new .= '_' . $cPath_array[$j];
}
}
$cPath_new .= '_' . $current_category_id;

if (substr($cPath_new, 0, 1) == '_') {
$cPath_new = substr($cPath_new, 1);
$cPath_list[$category_parent[$i]['categories_id']] = 'cPath=' . $cPath_new;
}
}
} else {
$cPath_new = implode('_', $cPath_array);
$cPath_list[$category_parent[$i]['categories_id']] = 'cPath=' . $cPath_new;
}
}
unset($counted, $category_parent, $parent_by_category, $cp_size, $cPath_new, $last_category);
return $cPath_list;
}
//End Multiple Cats Query
    global $languages_id;

    if (!is_array($categories_array)) $categories_array = array();

    $categories_query = tep_db_query("select c.categories_id, cd.categories_name from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where parent_id = '" . (int)$parent_id . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' order by sort_order, cd.categories_name");
    while ($categories = tep_db_fetch_array($categories_query)) {
      if ($parent_id=='0'){
	$categories_array[] = array('id' => $categories['categories_id'],
                                      'text' => $indent . $categories['categories_name']);
      }
      else{
	$categories_array[] = array('id' => $path . $parent_id . '_' .$categories['categories_id'],
        	                          'text' => $indent . $categories['categories_name']);
      }

      if ($categories['categories_id'] != $parent_id) {
	$this_path=$path;
	if ($parent_id != '0')
	  $this_path = $path . $parent_id . '_';
        $categories_array = tep_get_paths($categories_array, $categories['categories_id'], $indent . '&nbsp;', $this_path);
      }
    }

    return $categories_array;
  }
  $info_box_contents[] = array('form' => '<form action="' . tep_href_link(FILENAME_DEFAULT) . '" method="get">' . tep_hide_session_id(),
                               'align' => 'left',
                               'text'  => '<b>' . 'Go to..' . '<br>' . tep_draw_pull_down_menu('cPath', tep_get_paths(array(array('id' => '', 'text' => PULL_DOWN_DEFAULT))), $cPath, 'onchange="this.form.submit();" style="width: 100%"')
                              );
  new infoBox($info_box_contents);
?>

            </td>
          </tr>
<!-- categories_eof //-->