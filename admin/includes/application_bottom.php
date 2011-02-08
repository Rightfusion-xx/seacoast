<?php
/*
  $Id: application_bottom.php,v 1.8 2002/03/15 02:40:38 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
  
*/

$clean=ob_get_clean();
$buffer=str_replace('iso-8859-1','UTF-8', $clean);



  echo utf8_encode($buffer);

// close session (store variables)
  tep_session_close();                    
  
  
  ob_flush();
  


  if (STORE_PAGE_PARSE_TIME == 'true') {
    if (!is_object($logger)) $logger = new logger;
    echo $logger->timer_stop(DISPLAY_PAGE_PARSE_TIME);
  }
?>