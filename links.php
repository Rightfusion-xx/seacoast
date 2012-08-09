<?php
/*
  $Id: links.php,v 1.00 2003/10/03 Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

// define our link functions
  require(DIR_WS_FUNCTIONS . 'links.php');

// calculate link category path
  if (isset($HTTP_GET_VARS['lPath'])) {
    $lPath = $HTTP_GET_VARS['lPath'];
    $current_category_id = $lPath;
    $display_mode = 'links';
  } elseif (isset($HTTP_GET_VARS['links_id'])) {
    $lPath = tep_get_link_path($HTTP_GET_VARS['links_id']);
  } else {
    $lPath = '';
    $display_mode = 'categories';
  }

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_LINKS);

  // links breadcrumb
  $link_categories_query = tep_db_query("select link_categories_name from " . TABLE_LINK_CATEGORIES_DESCRIPTION . " where link_categories_id = '" . (int)$lPath . "' and language_id = '" . (int)$languages_id . "'");
  $link_categories_value = tep_db_fetch_array($link_categories_query);

  if ($display_mode == 'links') {
    $breadcrumb->add(NAVBAR_TITLE, FILENAME_LINKS);
    $breadcrumb->add($link_categories_value['link_categories_name'], FILENAME_LINKS . '?lPath=' . $lPath);
  } else {
    $breadcrumb->add(NAVBAR_TITLE, FILENAME_LINKS);
  }
?>
<!doctype html>
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>"> 
<title><?php echo TITLE; ?></title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="3" cellpadding="3">
  <tr>
    <TD WIDTH="<?php echo BOX_WIDTH; ?>" VALIGN="top" rowspan="2"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<td valign="top" colspan="2" valign="top"><?php require(DIR_WS_INCLUDES . 'titlebar.php'); ?></td></tr><tr><!-- body_text //-->
<?php
  if ($display_mode == 'categories') {
?>
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td valign="top">
<TABLE WIDTH="100%" BORDER="0" CELLSPACING="0" CELLPADDING="1" BGCOLOR="#336699"><TR><TD valign="top">
<TABLE WIDTH="100%" BORDER="0" CELLSPACING="0" CELLPADDING="7" BGCOLOR="#FFFFFF"><TR><TD><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
<?php
    $categories_query = tep_db_query("select lc.link_categories_id, lcd.link_categories_name, lcd.link_categories_description, lc.link_categories_image from " . TABLE_LINK_CATEGORIES . " lc, " . TABLE_LINK_CATEGORIES_DESCRIPTION . " lcd where lc.link_categories_id = lcd.link_categories_id and lc.link_categories_status = '1' and lcd.language_id = '" . (int)$languages_id . "' order by lcd.link_categories_name");

    $number_of_categories = tep_db_num_rows($categories_query);

    if ($number_of_categories > 0) {
      $rows = 0;
      while ($categories = tep_db_fetch_array($categories_query)) {
        $rows++;
        $lPath_new = 'lPath=' . $categories['link_categories_id'];
        $width = (int)(100 / MAX_DISPLAY_CATEGORIES_PER_ROW) . '%';

        echo '                <td align="center" class="smallText" width="' . $width . '" valign="top"><UL><LI><a href="' . tep_href_link(FILENAME_LINKS, $lPath_new) . '">';

        echo $categories['link_categories_name'] . '<br><br>' . $categories['link_categories_description'] . '</UL></td>' . "\n";
        if ((($rows / MAX_DISPLAY_CATEGORIES_PER_ROW) == floor($rows / MAX_DISPLAY_CATEGORIES_PER_ROW)) && ($rows != $number_of_categories)) {
          echo '              </tr>' . "\n";
          echo '              <tr>' . "\n";
        }
      }
    } else {
?>
                <td><?php new infoBox(array(array('text' => TEXT_NO_CATEGORIES))); ?></td>
<?php
    }
?>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
          </tr>
          <tr>
            <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
              <tr class="infoBoxContents">
                <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                    <td class="main" align="right"><?php echo '<a href="' . tep_href_link(FILENAME_LINKS_SUBMIT, tep_get_all_get_params()) . '">' . tep_image_button('button_submit_link.gif', IMAGE_BUTTON_SUBMIT_LINK) . '</a>'; ?></td>
                    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
          </tr>
        </table></TD></TR></TABLE></TD></TR></TABLE></td>
      </tr>
    </table></td>
<?php
  } elseif ($display_mode == 'links') {
// create column list
    $define_list = array('LINK_LIST_TITLE' => LINK_LIST_TITLE,
                         'LINK_LIST_URL' => LINK_LIST_URL,
                         'LINK_LIST_IMAGE' => LINK_LIST_IMAGE,
                         'LINK_LIST_DESCRIPTION' => LINK_LIST_DESCRIPTION, 
                         'LINK_LIST_COUNT' => LINK_LIST_COUNT);

    asort($define_list);

    $column_list = array();
    reset($define_list);
    while (list($key, $value) = each($define_list)) {
      if ($value > 0) $column_list[] = $key;
    }

    $select_column_list = '';

    for ($i=0, $n=sizeof($column_list); $i<$n; $i++) {
      switch ($column_list[$i]) {
        case 'LINK_LIST_TITLE':
          $select_column_list .= 'ld.links_title, ';
          break;
        case 'LINK_LIST_URL':
          $select_column_list .= 'l.links_url, ';
          break;
        //case 'LINK_LIST_IMAGE':
        // $select_column_list .= 'l.links_image_url, ';
        // break;
        case 'LINK_LIST_DESCRIPTION':
          $select_column_list .= 'ld.links_description, ';
          break;
        case 'LINK_LIST_COUNT':
          $select_column_list .= 'l.links_clicked, ';
          break;
      }
    }

// show the links in a given category
// We show them all
    $listing_sql = "select " . $select_column_list . " l.links_id from " . TABLE_LINKS_DESCRIPTION . " ld, " . TABLE_LINKS . " l, " . TABLE_LINKS_TO_LINK_CATEGORIES . " l2lc where l.links_status = '2' and l.links_id = l2lc.links_id and ld.links_id = l2lc.links_id and ld.language_id = '" . (int)$languages_id . "' and l2lc.link_categories_id = '" . (int)$current_category_id . "'";

    if ( (!isset($HTTP_GET_VARS['sort'])) || (!ereg('[1-8][ad]', $HTTP_GET_VARS['sort'])) || (substr($HTTP_GET_VARS['sort'], 0, 1) > sizeof($column_list)) ) {
      for ($i=0, $n=sizeof($column_list); $i<$n; $i++) {
        if ($column_list[$i] == 'LINK_LIST_TITLE') {
          $HTTP_GET_VARS['sort'] = $i+1 . 'a';
          $listing_sql .= " order by ld.links_title";
          break;
        }
      }
    } else {
      $sort_col = substr($HTTP_GET_VARS['sort'], 0 , 1);
      $sort_order = substr($HTTP_GET_VARS['sort'], 1);
      $listing_sql .= ' order by ';
      switch ($column_list[$sort_col-1]) {
        case 'LINK_LIST_TITLE':
          $listing_sql .= "ld.links_title " . ($sort_order == 'd' ? 'desc' : '');
          break;
        case 'LINK_LIST_URL':
          $listing_sql .= "l.links_url " . ($sort_order == 'd' ? 'desc' : '') . ", ld.links_title";
          break;
        case 'LINK_LIST_IMAGE':
          $listing_sql .= "ld.links_title";
          break;
        case 'LINK_LIST_DESCRIPTION':
          $listing_sql .= "ld.links_description " . ($sort_order == 'd' ? 'desc' : '') . ", ld.links_title";
          break;
        case 'LINK_LIST_COUNT':
          $listing_sql .= "l.links_clicked " . ($sort_order == 'd' ? 'desc' : '') . ", ld.links_title";
          break;
      }
    }
?>
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td>
<TABLE WIDTH="100%" BORDER="0" CELLSPACING="0" CELLPADDING="1" BGCOLOR="#336699"><TR><TD valign="top">
<TABLE WIDTH="100%" BORDER="0" CELLSPACING="0" CELLPADDING="7" BGCOLOR="#FFFFFF"><TR><TD><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
<?php
// Get the right image for the top-right ;-)
    $image = 'table_background_list.gif';
    if ($current_category_id) {
      $image_query = tep_db_query("select link_categories_image from " . TABLE_LINK_CATEGORIES . " where link_categories_id = '" . (int)$current_category_id . "'");
      $image_value = tep_db_fetch_array($image_query);

      if (tep_not_null($image_value['link_categories_image'])) {
        $image = $image_value['link_categories_image'];
      }
    }
?>
                            <td align="right">&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><?php include(DIR_WS_MODULES . FILENAME_LINK_LISTING); ?></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td class="main" align="right"><?php echo '<a href="' . tep_href_link(FILENAME_LINKS_SUBMIT, tep_get_all_get_params()) . '">' . tep_image_button('button_submit_link.gif', IMAGE_BUTTON_SUBMIT_LINK) . '</a>'; ?></td>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></TD></TR></TABLE></TD></TR></TABLE></td>
      </tr>
    </table></td>
<?php
  }
?>
<!-- body_text_eof //-->
    <TD WIDTH="<?php echo BOX_WIDTH; ?>" VALIGN="top" rowspan="2"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
<!-- right_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_right.php'); ?>
<!-- right_navigation_eof //-->
    </table></td>
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
