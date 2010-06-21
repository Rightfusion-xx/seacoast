<?php
/*
   YourFrontPage Module  
   Version 0.5 - May 4, 2004

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 Ken Linder
  www.radstream.com
  www.thepainstop.com

  Released under the GNU General Public License
*/
?>
<!-- yourfrontpage_osc -->
<?php
  $info_box_contents = array();
  $info_box_contents[] = array('text' => TABLE_HEADING_YOURFRONTPAGE);

  new contentBoxHeading($info_box_contents);

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_YOURFRONTPAGE);
  
  $info_box_contents = array();
  $info_box_contents[0][0] = array('align' => 'left',
                                           'params' => 'class="main" valign="top"',
                                           'text' => YOURFRONTPAGE_CONTENT);

  new contentBox($info_box_contents);
?>
<!-- yourfrontpage_osc_eof -->
