<?php
/*
  $Id: footer.php,v 1.26 2003/02/10 22:30:54 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require(DIR_WS_INCLUDES . 'counter.php');
?>

<table border="0" width="100%" cellspacing="0" cellpadding="1">
  <tr>
  <?php	echo '<TD COLSPAN="2" BGCOLOR="#FFCC99" CLASS="headernavigation" ALIGN="center"><IMG SRC="/images/spacer.gif" height="1" width="1"></TD></TR><TR>' . 
              '<td colspan="2" align="center" class="footer" BGCOLOR="#336699">&nbsp;&nbsp; Order Online 24 Hours a Day, 7 Days a Week &nbsp;&nbsp;&nbsp; Call Toll-Free 800-555-6792 &nbsp;&nbsp;&nbsp; Office Hours: Mon - Fri, 9am - 6pm Eastern Time &nbsp;&nbsp;<br>&copy; 2008, Seacoast Natural Health</td>';
  //echo '<td class="footer" align="right" background="' . DIR_WS_IMAGES . 'slice_sets/' . INFOBOX_FOOTER_SLICE_SET . '/top_background.jpg" height="' . INFOBOX_FOOTER_HEIGHT . '">&nbsp;&nbsp; Office Hours: Mon - Fri, 9am - 6pm Eastern Time &nbsp;&nbsp;</td>';
  ?>
  </tr>
</table>

<?php
 // if ($banner = tep_banner_exists('dynamic', '468x50')) {
?>

<table border="0" width="100%" cellspacing="0" cellpadding="12">
  <tr>
    <td class="smallText"><?php //echo tep_display_banner('static', $banner); 
	                                    echo 'The products and/or claims made about specific products found on this website have not been evaluated by the United States Food and Drug Administration and are not intended to diagnose, cure or prevent disease.' .
										     ' The information presented on this site is for educational purposes only and is not intended to replace advise from your physician or other health care professional or any information found on any product label or packaging.' . 
											 ' You should always consult with a qualified health care professional before starting any exercise, diet or supplementation program, especially if you are taking prescription medications.'; 
					                  ?></td>
  </tr>
</table>
<?php
 // }
?>
<script src="https://ssl.google-analytics.com/urchin.js" type="text/javascript">
</script>
<script type="text/javascript">
  _uacct = "UA-207538-1";
  urchinTracker();
</script>
