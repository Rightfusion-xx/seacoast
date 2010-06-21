<?php
/*
  $Id: information.php,v 1.6 2003/02/10 22:31:00 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- information //-->
<tr>
            <td>
<?php
  require(DIR_WS_INCLUDES . '/boxes/boxTop.php');
  echo 'Additional Resources';

  require(DIR_WS_INCLUDES . '/boxes/boxMiddle.php');

  function tep_show_topic($counter) {
    global $tree, $topics_string, $tPath_array;

    for ($i=0; $i<$tree[$counter]['level']; $i++) {
      $topics_string .= "&nbsp;&nbsp;";
    }

    $topics_string .= '<a href="';

    if ($tree[$counter]['parent'] == 0) {
      $tPath_new = 'tPath=' . $counter;
    } else {
      $tPath_new = 'tPath=' . $tree[$counter]['path'];
    }

    $topics_string .= tep_href_link(FILENAME_ARTICLES, $tPath_new) . '">';

    if (isset($tPath_array) && in_array($counter, $tPath_array)) {
      $topics_string .= '<b>';
    }

// display topic name
    $topics_string .= $tree[$counter]['name'];

    if (isset($tPath_array) && in_array($counter, $tPath_array)) {
      $topics_string .= '</b>';
    }

    if (tep_has_topic_subtopics($counter)) {
      $topics_string .= ' -&gt;';
    }

    $topics_string .= '</a>';

    if (SHOW_ARTICLE_COUNTS == 'true') {
      $articles_in_topic = tep_count_articles_in_topic($counter);
      if ($articles_in_topic > 0) {
        $topics_string .= '&nbsp;(' . $articles_in_topic . ')';
      }
    }

    $topics_string .= '<br>';

    if ($tree[$counter]['next_id'] != false) {
      tep_show_topic($tree[$counter]['next_id']);
    }
  }
?>

    <?php
	  $topics_string = '';
  $tree = array();

  $topics_query = tep_db_query("select t.topics_id, td.topics_name, t.parent_id from " . TABLE_TOPICS . " t, " . TABLE_TOPICS_DESCRIPTION . " td where t.parent_id = '0' and t.topics_id = td.topics_id and td.language_id = '" . (int)$languages_id . "' order by sort_order, td.topics_name");
  while ($topics = tep_db_fetch_array($topics_query))  {
    $tree[$topics['topics_id']] = array('name' => $topics['topics_name'],
                                        'parent' => $topics['parent_id'],
                                        'level' => 0,
                                        'path' => $topics['topics_id'],
                                        'next_id' => false);

    if (isset($parent_id)) {
      $tree[$parent_id]['next_id'] = $topics['topics_id'];
    }

    $parent_id = $topics['topics_id'];

    if (!isset($first_topic_element)) {
      $first_topic_element = $topics['topics_id'];
    }
  }

  //------------------------
  if (tep_not_null($tPath)) {
    $new_path = '';
    reset($tPath_array);
    while (list($key, $value) = each($tPath_array)) {
      unset($parent_id);
      unset($first_id);
      $topics_query = tep_db_query("select t.topics_id, td.topics_name, t.parent_id from " . TABLE_TOPICS . " t, " . TABLE_TOPICS_DESCRIPTION . " td where t.parent_id = '" . (int)$value . "' and t.topics_id = td.topics_id and td.language_id = '" . (int)$languages_id . "' order by sort_order, td.topics_name");
      if (tep_db_num_rows($topics_query)) {
        $new_path .= $value;
        while ($row = tep_db_fetch_array($topics_query)) {
          $tree[$row['topics_id']] = array('name' => $row['topics_name'],
                                           'parent' => $row['parent_id'],
                                           'level' => $key+1,
                                           'path' => $new_path . '_' . $row['topics_id'],
                                           'next_id' => false);

          if (isset($parent_id)) {
            $tree[$parent_id]['next_id'] = $row['topics_id'];
          }

          $parent_id = $row['topics_id'];

          if (!isset($first_id)) {
            $first_id = $row['topics_id'];
          }

          $last_id = $row['topics_id'];
        }
        $tree[$last_id]['next_id'] = $tree[$value]['next_id'];
        $tree[$value]['next_id'] = $first_id;
        $new_path .= '_';
      } else {
        break;
      }
    }
  }
  tep_show_topic($first_topic_element);

  $info_box_contents = array();
  $new_articles_string = '';
  $all_articles_string = '';

  if (DISPLAY_NEW_ARTICLES=='true') {
    if (SHOW_ARTICLE_COUNTS == 'true') {
      $articles_new_query = tep_db_query("select a.articles_id from " . TABLE_ARTICLES . " a, " . TABLE_ARTICLES_TO_TOPICS . " a2t left join " . TABLE_TOPICS_DESCRIPTION . " td on a2t.topics_id = td.topics_id left join " . TABLE_AUTHORS . " au on a.authors_id = au.authors_id, " . TABLE_ARTICLES_DESCRIPTION . " ad where (a.articles_date_available IS NULL or to_days(a.articles_date_available) <= to_days(now())) and a.articles_id = a2t.articles_id and a.articles_status = '1' and a.articles_id = ad.articles_id and ad.language_id = '" . (int)$languages_id . "' and td.language_id = '" . (int)$languages_id . "' and a.articles_date_added > SUBDATE(now( ), INTERVAL '" . NEW_ARTICLES_DAYS_DISPLAY . "' DAY)");
      $articles_new_count = ' (' . tep_db_num_rows($articles_new_query) . ')';
    } else {
      $articles_new_count = '';
    }

    if (strstr($HTTP_SERVER_VARS['PHP_SELF'],FILENAME_ARTICLES_NEW) or strstr($PHP_SELF,FILENAME_ARTICLES_NEW)) {
      $new_articles_string = '<b>';
    }

    $new_articles_string .= '<a href="' . tep_href_link(FILENAME_ARTICLES_NEW, '', 'NONSSL') . '">' . BOX_NEW_ARTICLES . '</a>';

    if (strstr($HTTP_SERVER_VARS['PHP_SELF'],FILENAME_ARTICLES_NEW) or strstr($PHP_SELF,FILENAME_ARTICLES_NEW)) {
      $new_articles_string .= '</b>';
    }

    $new_articles_string .= $articles_new_count . '<br>';

  }

  if (DISPLAY_ALL_ARTICLES=='true') {
    if (SHOW_ARTICLE_COUNTS == 'true') {
      $articles_all_query = tep_db_query("select a.articles_id from " . TABLE_ARTICLES . " a, " . TABLE_ARTICLES_TO_TOPICS . " a2t left join " . TABLE_TOPICS_DESCRIPTION . " td on a2t.topics_id = td.topics_id left join " . TABLE_AUTHORS . " au on a.authors_id = au.authors_id, " . TABLE_ARTICLES_DESCRIPTION . " ad where (a.articles_date_available IS NULL or to_days(a.articles_date_available) <= to_days(now())) and a.articles_id = a2t.articles_id and a.articles_status = '1' and a.articles_id = ad.articles_id and ad.language_id = '" . (int)$languages_id . "' and td.language_id = '" . (int)$languages_id . "'");
      $articles_all_count = ' (' . tep_db_num_rows($articles_all_query) . ')';
    } else {
      $articles_all_count = '';
    }

    if ($topic_depth == 'top') {
      $all_articles_string = '<b>';
    }

    $all_articles_string .= '<a href="' . tep_href_link(FILENAME_ARTICLES, '', 'NONSSL') . '">' . BOX_ALL_ARTICLES . '</a>';

    if ($topic_depth == 'top') {
      $all_articles_string .= '</b>';
    }

    $all_articles_string .= $articles_all_count . '<br>';

  }



  echo '<b>Helpful Links</b><hr width="100%" size="1">' .
'<div id="HCLInitiate" style="position:absolute; z-index:1; top: 40%; left:40%; visibility: hidden;"><a href="javascript:initiate_accept()"><img src="http://seacoastvitamins.com/hcl/inc/skins/default/images/lh/initiate.gif" border="0"></a><br><a href="javascript:initiate_decline()"><img src="http://seacoastvitamins.com/hcl/inc/skins/default/images/lh/initiate_close.gif" border="0"></a></div>
<script type="text/javascript" language="javascript" src="http://seacoastvitamins.com/hcl/lh/live.php"></script>' .
		'<p>' .
		'<a href="/Store/links.php?lPath=1">Information Links</a></a><br>' .
		'<a href="/Store/links.php">Our Links</a><br>' . 
            '<a href="/Store/links_submit.php">Link with Us</a><br><br>' . 
		'<b>Informative Articles</b><hr width="100%" size="1">' .
		$new_articles_string . $all_articles_string . $topics_string .
		'<br><b>Customer Service</b><hr width="100%" size="1">' .
		'<a href="' . tep_href_link(FILENAME_SHIPPING) . '">' . BOX_INFORMATION_SHIPPING . '</a><br>' .
       '<a href="' . tep_href_link(FILENAME_PRIVACY) . '">' . BOX_INFORMATION_PRIVACY . '</a><br>' .
       '<a href="' . tep_href_link(FILENAME_CONDITIONS) . '">' . BOX_INFORMATION_CONDITIONS . '</a><br>' .
       '<a href="' . tep_href_link(FILENAME_CONTACT_US) . '">' . BOX_INFORMATION_CONTACT . '</a><br>';
		//'<a href="' . tep_href_link(FILENAME_GV_FAQ, '', 'NONSSL') . '">' . BOX_INFORMATION_GV . '</a>'); //ICW ORDER TOTAL CREDIT CLASS/GV

  require(DIR_WS_INCLUDES . '/boxes/boxBottom.php');
?>
     </td>
          </tr>
<!-- information_eof //-->
