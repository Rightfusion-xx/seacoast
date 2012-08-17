<?php
/*
  $Id: links.php,v 1.00 2003/10/03 Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

////
// Construct a path to the link
// TABLES: links_to_link_categories
  function tep_get_link_path($links_id) {
    $lPath = '';

    $category_query = tep_db_query("select l2c.link_categories_id from " . TABLE_LINKS . " l, " . TABLE_LINKS_TO_LINK_CATEGORIES . " l2c where l.links_id = '" . (int)$links_id . "' and l.links_id = l2c.links_id limit 1");
    if (tep_db_num_rows($category_query)) {
      $category = tep_db_fetch_array($category_query);

      $lPath .= $category['link_categories_id'];
    }

    return $lPath;
  }


////
// The HTML image wrapper function
  function tep_links_image($src, $alt = '', $width = '', $height = '', $parameters = '') {
    if ( (empty($src) || ($src == DIR_WS_IMAGES)) && (IMAGE_REQUIRED == 'false') ) {
      return false;
    }

// alt is added to the img tag even if it is null to prevent browsers from outputting
// the image filename as default
    $image = '<img src="' . tep_output_string($src) . '" border="0" alt="' . tep_output_string($alt) . '"';

    if (tep_not_null($alt)) {
      $image .= ' title=" ' . tep_output_string($alt) . ' "';
    }

    if ( (CONFIG_CALCULATE_IMAGE_SIZE == 'true') && (empty($width) || empty($height)) ) {
      if ($image_size = @getimagesize($src)) {
        if (empty($width) && tep_not_null($height)) {
          $ratio = $height / $image_size[1];
          $width = $image_size[0] * $ratio;
        } elseif (tep_not_null($width) && empty($height)) {
          $ratio = $width / $image_size[0];
          $height = $image_size[1] * $ratio;
        } elseif (empty($width) && empty($height)) {
          $width = $image_size[0];
          $height = $image_size[1];
        }
      } elseif (IMAGE_REQUIRED == 'false') {
        return false;
      }
    }

    // VJ begin maintain image proportion
    $calculate_image_proportion = 'true';

    if( ($calculate_image_proportion == 'true') && (!empty($width) && !empty($height))) {
      if ($image_size = @getimagesize($src)) {
        $image_width = $image_size[0];
        $image_height = $image_size[1];

        if (($image_width != 1) && ($image_height != 1)) {
          $whfactor = $image_width/$image_height;
          $hwfactor = $image_height/$image_width;

          if ( !($image_width > $width) && !($image_height > $height)) {
            $width = $image_width;
            $height = $image_height;
          } elseif ( ($image_width > $width) && !($image_height > $height)) {
            $height = $width * $hwfactor;
          } elseif ( !($image_width > $width) && ($image_height > $height)) {
            $width = $height * $whfactor;
          } elseif ( ($image_width > $width) && ($image_height > $height)) {
            if ($image_width > $image_height) {
              $height = $width * $hwfactor;
            } else {
              $width = $height * $whfactor;
            }
          }
        }
      }
    }
    //VJ end maintain image proportion

    if (tep_not_null($width) && tep_not_null($height)) {
      $image .= ' width="' . tep_output_string($width) . '" height="' . tep_output_string($height) . '"';
    }

    if (tep_not_null($parameters)) $image .= ' ' . $parameters;

    $image .= '>';

    return $image;
  }

////
// Return the links url, based on whether click count is turned on/off
  function tep_get_links_url($identifier) {
    $links_query = tep_db_query("select links_id, links_url from " . TABLE_LINKS . " where links_id = '" . (int)$identifier . "'");

    $link = tep_db_fetch_array($links_query);

    if (ENABLE_LINKS_COUNT == 'True') {
      if (ENABLE_SPIDER_FRIENDLY_LINKS == 'True') {
        $user_agent = strtolower(getenv('HTTP_USER_AGENT'));
        $spider_flag = false;

        if (tep_not_null($user_agent)) {
          $spiders = file(DIR_WS_INCLUDES . 'spiders.txt');

          for ($i=0, $n=sizeof($spiders); $i<$n; $i++) {
            if (tep_not_null($spiders[$i])) {
              if (is_integer(strpos($user_agent, trim($spiders[$i])))) {
                $spider_flag = true;
                break;
              }
            }
          }
        }

        if ($spider_flag == true) {
          $links_string = $link['links_url'];
        } else {
          $links_string = tep_href_link(FILENAME_REDIRECT, 'action=links&goto=' . $link['links_id']);
        }
      } else {
          $links_string = tep_href_link(FILENAME_REDIRECT, 'action=links&goto=' . $link['links_id']);
      }
    } else {
      $links_string = $link['links_url'];
    }

    return $links_string;
  }

////
// Update the links click statistics
  function tep_update_links_click_count($links_id) {
    tep_db_query("update " . TABLE_LINKS . " set links_clicked = links_clicked + 1 where links_id = '" . (int)$links_id . "'");
  }
?>
