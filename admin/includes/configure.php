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

  define('HTTP_DEV_SERVER', 'seacoast');

  define('HTTP_SERVER', 'admin.seacoast'); // eg, http://localhost - should not be empty for productive servers
//  define('HTTP_CATALOG_SERVER', 'http://seacoast');
  define('HTTP_CATALOG_SERVER', 'http://admin.seacoast');
  define('HTTPS_CATALOG_SERVER', 'http://admin.seacoast');
  
  define('PASSSWORD_FUNCTION_FILE', HTTP_SERVER.'/password_funcs.php' );
  
  define('ENABLE_SSL_CATALOG', 'false'); // secure webserver for catalog module
  define('DIR_FS_DOCUMENT_ROOT', '/seacoast/www/'); // where the pages are located on the server
  define('DIR_WS_ADMIN', '/'); // absolute path required
  define('DIR_FS_ADMIN', '/seacoast/www/admin/'); // absolute pate required
  define('DIR_WS_CATALOG', '/'); // absolute path required
  define('DIR_FS_CATALOG', '/seacoast/www/'); // absolute path required
  define('DIR_WS_IMAGES', 'images/');
  define('DIR_WS_ICONS', DIR_WS_IMAGES . 'icons/');
  define('DIR_WS_CATALOG_IMAGES', DIR_WS_CATALOG . 'images/');
  define('DIR_WS_INCLUDES', 'includes/');
  
  define('DIR_TEMP_FULL_PATH_INCLUDES','seacoast/includes/functions'); //(AH added to make change password work)
  
  define('DIR_WS_BOXES', DIR_WS_INCLUDES . 'boxes/');
  define('DIR_WS_FUNCTIONS', DIR_WS_INCLUDES . 'functions/');
  define('DIR_WS_CLASSES', DIR_WS_INCLUDES . 'classes/');
  define('DIR_WS_MODULES', DIR_WS_INCLUDES . 'modules/');
  define('DIR_WS_LANGUAGES', DIR_WS_INCLUDES . 'languages/');
  define('DIR_WS_CATALOG_LANGUAGES', DIR_WS_CATALOG . 'includes/languages/');
  define('DIR_FS_CATALOG_LANGUAGES', DIR_FS_CATALOG . 'includes/languages/');
  define('DIR_FS_CATALOG_IMAGES', DIR_FS_CATALOG . 'images/');
  define('DIR_FS_CATALOG_MODULES', DIR_FS_CATALOG . 'includes/modules/');
  define('DIR_FS_BACKUP', DIR_FS_ADMIN . 'backups/');

// define our database connection
  define('DB_SERVER', '127.0.0.1'); // eg, localhost - should not be empty for productive servers
  define('DB_SERVER_USERNAME', 'root');
  define('DB_SERVER_PASSWORD', '');
  define('DB_DATABASE', 'seacoast');
  define('USE_PCONNECT', 'true'); // use persisstent connections?
  define('STORE_SESSIONS', 'mysql'); // leave empty '' for default handler or set to 'mysql'


//  define PayflowPro details
  define('PFP_HOST', 'payflowpro.verisign.com'); 
  define('PFP_PARTNER', 'wfb'); 
  define('PFP_VENDOR', 'sea227303849');
  define('PFP_USER', 'sea227303849'); 
  define('PFP_PASSWORD', 'naturalhealth22'); 
  
//define membership product ids
  define('CM_FTPID','2531'); //free trial
  define('CM_PID','2532'); //full membership

define('X_TEST_REQUEST','TRUE');

define('PREFERRED_ENCRYPTION', 'twofish');
define('ENCRYPTION_KEY','XF;F,7b !<K:-8)492M=f');

// CouchDB locations
  define('COUCH_DB_ADDRESS','http://127.0.0.1:5984/');

?>