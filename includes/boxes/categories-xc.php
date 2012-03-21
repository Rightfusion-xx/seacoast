<?php
/*
  $Id: categories-xc.php, v 1.0 2006/05/08

  Released under the GNU General Public License

  OSC Implementation - Copyright (c) 2006 Grant Perry
  http://www.grantusmaximus.com

  Javascript XC Menu - Copyright (c) 2003 Ben Boyle
  http://inspire.server101.com/js/xc/
*/

// Categories_tree written by Gideon Romm from Symbio Technologies, LLC

function tep_show_categoryxc($cid, $cpath, $display_empty) {
  global $categoriesxc_string, $languages_id, $HTTP_GET_VARS;
  global $level;
  $selectedPath = array();

// Get all of the categories on this level

  $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = " . $cid . " and c.categories_id = cd.categories_id and cd.language_id='" . $languages_id ."' order by sort_order, cd.categories_name");
  
  while ($categories = tep_db_fetch_array($categories_query))  {
    if ($level{$categories['parent_id']} == "") { $level{$categories['parent_id']} = 0; }
    $level{$categories['categories_id']} = $level{$categories['parent_id']} + 1;

// Add category link to $categoriesxc_string
	$products_in_category = tep_count_products_in_category($categories['categories_id']);
	if ($display_empty == 0 && $products_in_category < 1) {
		$display_category = false;
	} else {
		$display_category = true;
	}
	
	
	if ($display_category == true) {
	
		$categoriesxc_string .= "\t<li><a href=\"";	
	
		$cPath_new = $cpath;
		if ($level{$categories['parent_id']} > 0) {
		  $cPath_new .= "_";
		}
		$cPath_new .= $categories['categories_id'];
	
		$cPath_new_text = "cPath=" . $cPath_new;
	
		$categoriesxc_string .= tep_href_link(FILENAME_DEFAULT, $cPath_new_text);
		$categoriesxc_string .= '" title="'.$categories['categories_name'].'">';
	
		if ($HTTP_GET_VARS['cPath']) {
		  $selectedPath = preg_split("/_/", $HTTP_GET_VARS['cPath']);
		}
	
		//if (in_array($categories['categories_id'], $selectedPath)) { $categoriesxc_string .= '<strong>'; }
	
		$categoriesxc_string .= $categories['categories_name'];
	   
		//if (in_array($categories['categories_id'], $selectedPath)) { $categoriesxc_string .= '</strong>'; }
	
		$categoriesxc_string .= '</a>';
	
		if (SHOW_COUNTS) {
		  if ($products_in_category > 0) {
			$categoriesxc_string .= '&nbsp;(' . $products_in_category . ')';
		  }
		}

		// If I have subcategories, get them and show them
		if (tep_has_category_subcategories($categories['categories_id'])) {
			$categoriesxc_string .= "<ul id=\"catid".$categories['categories_id']."\" title=\"".$categories['categories_name']."\">\n" ;
			tep_show_categoryxc($categories['categories_id'], $cPath_new, $display_empty);
			$categoriesxc_string .= "</ul>\n";
		}
	   $categoriesxc_string .= "</li>\n";
	}	
  }
}
?>


<!--categories XC //-->
          <tr>
            <td>
<?php
  $info_box_contents = array();
  $info_box_contents[] = array( 'align' => 'left',
  								'text'  => '<span style="color:' . $font_color . ';">' . BOX_HEADING_CATEGORIES . '</span>'
                              );
  new infoBoxHeading($info_box_contents, false, false);

  $categoriesxc_string = '';
  $categoriesxc_string .= "<script type=\"text/javascript\" src=\"/includes/xc.js\"></script>\n";
  $categoriesxc_string .= "<div id=\"catxcdiv\">\n";
  $categoriesxc_string .= "<ul id=\"catxc\">\n";
  
  // tep_show_categoryxc(<top category_id>, <top cpath>, <1=Show empty Categories, 0=Hide empty Categories>)
  tep_show_categoryxc(0,'',0);
  
  $categoriesxc_string .= "</ul>\n";
  $categoriesxc_string .= "<script type=\"text/javascript\">\n<!--\n";
  $categoriesxc_string .= "window.onload = function() {xcSet('catxc', 'xc', '";
  
  // Checks Category path and opens menu when there are child categories
  if ($HTTP_GET_VARS['cPath']) {
      $selectedPath = preg_split("/_/", $HTTP_GET_VARS['cPath']);
	  if (count($selectedPath) > 1) {
	  	if (tep_has_category_subcategories($selectedPath[count($selectedPath)-2])) {
			$categoriesxc_string .= "catid".$selectedPath[count($selectedPath)-2];
		};		
	  } elseif (count($selectedPath) == 1) {
	  	if (tep_has_category_subcategories($selectedPath[0])) {
			$categoriesxc_string .= "catid".$selectedPath[0];
		};
	  };
    };
  $categoriesxc_string .= "');};\n";
  $categoriesxc_string .= "//-->\n</script>\n";
  $categoriesxc_string .= "</div>\n";

  

  $info_box_contents = array();
  $info_box_contents[] = array('text'  => $categoriesxc_string);
  new infoBox($info_box_contents);
?>
		    </td>
          </tr>
<!--categories_eof XC //-->
