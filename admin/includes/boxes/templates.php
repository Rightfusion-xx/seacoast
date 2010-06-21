<?php
/*
  $Id: templates.php,v 1.21 2003/07/09 01:18:53 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- tools //-->
          <tr>
            <td>
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('text'  => BOX_HEADING_TEMPLATES,
                     'link'  => tep_href_link(FILENAME_INFOBOX_SKIN, 'selected_box=templates'));

  if ($selected_box == 'templates') {
    $contents[] = array('text'  => '<a href="' . tep_href_link(FILENAME_INFOBOX_SKIN) . '" class="menuBoxContentLink">' . BOX_TEMPLATES_INFOBOX . '</a><br>');
    $contents[] = array('text'  => '<a href="' . tep_href_link(FILENAME_INFOBOX_SKIN_MAPPING) . '" class="menuBoxContentLink">' . BOX_TEMPLATES_INFOBOX_MAPPING . '</a>' );
  }

  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- tools_eof //-->
