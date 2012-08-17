<?php
/*
  $Id: search.php,v 1.22 2003/02/10 22:31:05 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- search //-->
          <tr>
            <td>
<?php
//  $info_box_contents = array();
//  $info_box_contents[] = array('text' => BOX_HEADING_SEARCH);

//  new infoBoxHeading($info_box_contents, false, false);

//  $info_box_contents = array();
//  $info_box_contents[] = array('form' => tep_draw_form('quick_find', tep_href_link(FILENAME_ADVANCED_SEARCH_RESULT, '', 'NONSSL', false), 'get'),
//                               'align' => 'center',
//                               'text' => tep_draw_input_field('keywords', '', 'size="10" maxlength="30" style="width: ' . (BOX_WIDTH-30) . 'px"') . '&nbsp;' . tep_hide_session_id() . tep_image_submit('button_quick_find.gif', BOX_HEADING_SEARCH) . '<br>' . tep_draw_hidden_field('search_in_description', '1', 'true') . '</a>');

//  new infoBox($info_box_contents);
?>
<TABLE BORDER="0" WIDTH="100%" CELLPADDING="3" CELLSPACING="0" CLASS="infoBox">
<TR>
<TD>
<?php echo tep_image(DIR_WS_IMAGES . 'searchHeader.gif') .
            '<BR><IMG SRC="/images/spacer.gif" height="1" width="140"><BR>' .
            tep_draw_form('quick_find', tep_href_link(FILENAME_ADVANCED_SEARCH_RESULT, '', 'NONSSL', false), 'get') .
            tep_draw_input_field('keywords', '', 'size="10" maxlength="30" style="width: ' . (BOX_WIDTH-30) . 'px"') . '&nbsp;' . tep_hide_session_id() . tep_image_submit('button_quick_find.gif', BOX_HEADING_SEARCH) . '<br>' . tep_draw_hidden_field('search_in_description', '1', 'true') . '</a></FORM>';
?>
</TD>
</TR>
</TABLE>

            </td>
          </tr>
<!-- search_eof //-->
