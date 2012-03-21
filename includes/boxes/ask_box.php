
<?php
/*
  $Id: ask_question.php,v 1.5 2002/01/11 22:04:06 dgw_ Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2001 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- information //-->
<tr>
<td>

<?php
require(DIR_WS_INCLUDES . '/boxes/boxTop.php');
$info_box_contents = array();
$info_box_contents[] = array('text' => BOX_HEADING_ASK_QUESTION);
	    new infoBoxHeading($info_box_contents);
		require(DIR_WS_INCLUDES . '/boxes/boxMiddle.php');
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'center',
                               'text'  => 
					'<table border="0" cellspacing="0" cellpadding="2">
					<tr>
					<td class="infoBoxContents">
					<a href="' . tep_href_link(FILENAME_ASK_QUESTION, 'products_id=' . $HTTP_GET_VARS['products_id']) . '">
					' . tep_image(DIR_WS_IMAGES . 'box_ask_question.gif', IMAGE_BUTTON_ASK_QUESTION) . '
					</a>
					</td>
					<td class="infoBoxContents">
					<a href="' . tep_href_link(FILENAME_ASK_QUESTION, 'products_id=' . $HTTP_GET_VARS['products_id']) . '">' .'</a>
					</td></tr></table>' 
                              );  
  new infoBox($info_box_contents);
  require(DIR_WS_INCLUDES . '/boxes/boxBottom.php');
?>


</td>
</tr>
<!-- information_eof //-->