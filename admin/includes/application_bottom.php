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

$buffer=str_replace('</head>','
                <link type="text/css" href="/jquery/css/ui-lightness/jquery-ui-1.8.13.custom.css" rel="Stylesheet" />
                <script type="text/javascript" src="/jquery/js/jquery-1.5.1.min.js"></script>
                <script type="text/javascript" src="/jquery/js/jquery-ui-1.8.13.custom.min.js"></script>'
                ,$buffer);



  echo utf8_encode($buffer);

// close session (store variables)
  tep_session_close();                    
  
  
  ob_flush();
  


  if (STORE_PAGE_PARSE_TIME == 'true') {
    if (!is_object($logger)) $logger = new logger;
    echo $logger->timer_stop(DISPLAY_PAGE_PARSE_TIME);
  }
?>