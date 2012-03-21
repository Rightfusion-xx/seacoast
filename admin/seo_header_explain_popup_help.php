<?php
 
  require("includes/application_top.php");
  require(DIR_WS_LANGUAGES . $language . '/seo_header_explain_popup_help.php');
 
  define('TEXT_CLOSE_WINDOW', 'Close Window [x]'); 
?> 
<p class="smallText" align="right"><?php echo '<a href="javascript:window.close()">' . TEXT_CLOSE_WINDOW . '</a>'; ?></p>
