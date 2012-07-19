<?php
/*
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

// Define the webserver and path parameters
// * DIR_FS_* = Filesystem directories (local/physical)
// * DIR_WS_* = Webserver directories (virtual/URL)
  define('HTTP_SERVER', 'http://www.dev.nealbozeman.com'); // eg, http://localhost - should not be empty for productive servers
  define('HTTPS_SERVER', 'https://www.dev.nealbozeman.com'); // eg, https://localhost - should not be empty for productive servers
  define('ENABLE_SSL', 'false'); // secure webserver for checkout procedure?
  define('HTTP_COOKIE_DOMAIN', 'www.dev.nealbozeman.com');
  define('HTTPS_COOKIE_DOMAIN', 'www.dev.nealbozeman.com');
  define('HTTP_COOKIE_PATH', '/');
  define('HTTPS_COOKIE_PATH', '/');
  define('DIR_WS_HTTP_CATALOG', '/');
  define('DIR_WS_HTTPS_CATALOG', '/');
  define('DIR_WS_IMAGES', '/images/');
  define('DIR_WS_ICONS', DIR_WS_IMAGES . 'icons/');
  define('DIR_WS_INCLUDES', $_SERVER['DOCUMENT_ROOT'].'/includes/');
  define('DIR_WS_BOXES', DIR_WS_INCLUDES . 'boxes/');
  define('DIR_WS_FUNCTIONS', DIR_WS_INCLUDES . 'functions/');
  define('DIR_WS_CLASSES', DIR_WS_INCLUDES . 'classes/');
  define('DIR_WS_MODULES', DIR_WS_INCLUDES . 'modules/');
  define('DIR_WS_LANGUAGES', $_SERVER['DOCUMENT_ROOT'] . '/includes/languages/');

  define('DIR_WS_DOWNLOAD_PUBLIC', 'pub/');
  define('DIR_FS_CATALOG', '/seacoast/www/');
  define('DIR_FS_DOWNLOAD', DIR_FS_CATALOG . 'download/');
  define('DIR_FS_DOWNLOAD_PUBLIC', DIR_FS_CATALOG . 'pub/');

// define our database connection
  define('DB_SERVER', 'localhost'); // eg, localhost - should not be NULL for productive servers
  define('DB_SERVER_USERNAME', 'root');
  define('DB_SERVER_PASSWORD', 'seamarquette11');
  define('DB_DATABASE', 'seacoast');
  define('USE_PCONNECT', 'true'); // use persistent connections?
  define('STORE_SESSIONS', 'mysql'); // leave empty '' for default handler or set to 'mysql'
  
  define('GOOGLE_SEARCH_URL', 'http://216.176.184.164/search?output=xml_no_dtd&client=seacoast&');
  
//define membership product ids
  define('CM_FTPID','2531'); //free trial
  define('CM_PID','2532'); //full membership

  define('FILE_CACHE_LOCATION','c:\\tmp\\');
  
  define('ENABLE_MEGACACHE',true);
  
  define('FB_APP_ID','322579717834677');
define('FB_APP_SECRET','518d413dd48ca39f82bd9f521156b689');
?>