<?php
/*
  $Id: rss_news.php,v 1.6 2003/02/10 22:31:00 hpdl Exp $

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
  include(DIR_WS_MODULES . '/' . FILENAME_RSS_READER);
  $info_box_contents = array();
  $info_box_contents[] = array('text' => BOX_HEADING_RSS_NEWS);

  new infoBoxHeading($info_box_contents, false, false);
		 
	$text =  array();
	
	if (isset($rss_channel["ITEMS"])) {
	if (count($rss_channel["ITEMS"]) > 0) {
	  $maxLength = MAX_CHARACTERS;
		$maxCount = (count($rss_channel["ITEMS"]) > MAX_ARTICLE) ? MAX_ARTICLE : count($rss_channel["ITEMS"]); 
    $text_string = '<div class="smallText" style="color: #000000;">';
		for($i = 0;$i < $maxCount; $i++) {
		 $text_string .= '<strong>' . $rss_channel["ITEMS"][$i]["TITLE"] . '</strong><br>';
		 $length = strlen(html_entity_decode($rss_channel["ITEMS"][$i]["DESCRIPTION"]));
	   $snip = substr(html_entity_decode($rss_channel["ITEMS"][$i]["DESCRIPTION"]),0, ($length > $maxlength) ? $maxLength : $length);
	   $text_string .=  $snip;
		 if ($length > $maxLength) {
		  $text_string .= '...<br>';
      $text_string .= '<a target="_blank" href="' . $rss_channel["ITEMS"][$i]["LINK"] . '"><font color="#dd0000">[see article]</font></a><hr>';
		 }
		 else
		  $text_string .= '<br><hr>'; 
		}	 
		$text_string .= '</div>';
		if (count($rss_channel["ITEMS"]) > 5)
		  $text_string .= '<a title="More Feeds" href="' . tep_href_link(FILENAME_RSS_READER, '', 'NONSSL') . '"><font color="#dd0000">More News...</font></a>';
	}
} 
	
  $info_box_contents = array();
  $info_box_contents[] = array('text' => $text_string);
                                     

  new infoBox($info_box_contents);
?>
            </td>
          </tr>
<!-- information_eof //-->
