<?php

ob_start();


//PHP 5.0 Upgrade workaround
$HTTP_GET_VARS=& $_GET;
$HTTP_POST_VARS=& $_POST;
$HTTP_COOKIE_VARS=& $_COOKIE;
$HTTP_SERVER_VARS=& $_SERVER;
$HTTP_SESSION_VARS=& $_SESSION;


////
// This funstion validates a plain text password with an
// encrpyted password
  function tep_validate_password_local($plain, $encrypted) {
    if (tep_not_null($plain) && tep_not_null($encrypted)) {
// split apart the hash / salt
      $stack = explode(':', $encrypted);

      if (sizeof($stack) != 2) return false;

      if (md5($stack[1] . $plain) == $stack[0]) {
        return true;
      }
    }

    return false;
  }


// Start the clock for the page parse time log

  define('PAGE_PARSE_START_TIME', microtime());

// Set the local configuration parameters - mainly for developers
  if (file_exists('includes/local/configure.php')) include('includes/local/configure.php');

// Include application configuration parameters
  require(str_replace('admin/admin','admin',$_SERVER['DOCUMENT_ROOT'].'/admin/includes/configure.php'));

// Define the project version
  define('PROJECT_VERSION', 'osCommerce 2.2-MS2');
// set php_self in the local scope

  $PHP_SELF = (isset($HTTP_SERVER_VARS['PHP_SELF']) ? $HTTP_SERVER_VARS['PHP_SELF'] : $HTTP_SERVER_VARS['SCRIPT_NAME']);

// include the list of project filenames
  require(DIR_WS_INCLUDES . 'filenames.php');

// include the list of project database tables
  require(DIR_WS_INCLUDES . 'database_tables.php');

// customization for the design layout
  define('BOX_WIDTH', 125); // how wide the boxes should be in pixels (default: 125)

// Define how do we update currency exchange rates

// Possible values are 'oanda' 'xe' or ''
  define('CURRENCY_SERVER_PRIMARY', 'oanda');
  define('CURRENCY_SERVER_BACKUP', 'xe');

// include the database functions
  require(DIR_WS_FUNCTIONS . 'database.php');

// make a connection to the database... now
  tep_db_connect() or die('Unable to connect to database server!');
  



// set application wide parameters
  $configuration_query = tep_db_query('select distinct configuration_key as cfgKey, configuration_value as cfgValue from ' . TABLE_CONFIGURATION);

  while ($configuration = tep_db_fetch_array($configuration_query)) {
    if(!defined($configuration['cfgKey']))
      define($configuration['cfgKey'], $configuration['cfgValue']);
  }
  
  require_once 'php-activerecord/ActiveRecord.php';

ActiveRecord\Config::initialize(function($cfg)
{
    $cfg->set_model_directory(DIR_WS_INCLUDES . '/db_models');
    $cfg->set_connections(array(
        DB_DATABASE => 'mysql://'.DB_SERVER_USERNAME.':'.DB_SERVER_PASSWORD.'@'.DB_SERVER.'/'.DB_DATABASE));
    
    $cfg->set_default_connection(DB_DATABASE);

});  

// define our general functions used application-wide
  require(DIR_WS_FUNCTIONS . 'general.php');
  require(DIR_WS_FUNCTIONS . 'html_output.php');

// initialize the logger class
  require(DIR_WS_CLASSES . 'logger.php');

// include shopping cart class
  require(DIR_WS_CLASSES . 'shopping_cart.php');

// some code to solve compatibility issues

  require(DIR_WS_FUNCTIONS . 'compatibility.php');

// define how the session functions will be used
  require(DIR_WS_FUNCTIONS . 'sessions.php');

// set the session name and save path
  tep_session_name('osCAdminID');
  tep_session_save_path(SESSION_WRITE_DIRECTORY);

// authenticate user 


if(!tep_session_is_registered('authenticated'))
{
    $authenticated=false;
    tep_session_register($authenticated);
}

if(!tep_session_is_registered('tries'))
{
    $tries=1; 
    tep_session_register($tries); 
}  

if( isset($_SERVER['PHP_AUTH_USER']) && !$authenticated)
{
    // Authenticate user  
    
    $get_user=tep_db_fetch_array(tep_db_query('select * from customers c join user_rights ur on ur.customers_id=c.customers_id where customers_email_address="'.$_SERVER['PHP_AUTH_USER'].'" and user_rights like "%admin%"'));
    
    if(tep_validate_password_local($_SERVER['PHP_AUTH_PW'], $get_user['customers_password']))
    {
        $authenticated=true;
    }
                  
}

if (!$authenticated && !$system_login) {
    if($tries>=3)
    {
        $tries=1;
        tep_redirect('http://www.seacoastvitamins.com/');
    }
    header('WWW-Authenticate: Basic realm="Seacoast"');
    header('HTTP/1.0 401 Unauthorized');  
    $tries+=1;  
    exit();
}
    


    

    
     

// set the session cookie parameters

   if (function_exists('session_set_cookie_params')) {
    session_set_cookie_params(0, DIR_WS_ADMIN);
  } elseif (function_exists('ini_set')) {
    ini_set('session.cookie_lifetime', '0');
    ini_set('session.cookie_path', DIR_WS_ADMIN);
  }

// lets start our session
  tep_session_start();


// set the language
  if (!tep_session_is_registered('language')) {

      tep_session_register('language');
      tep_session_register('languages_id');

    include(DIR_WS_CLASSES . 'language.php');

    $lng = new language();
    $lng->get_browser_language();

    $language = $lng->language['directory'];
    $languages_id = $lng->language['id'];

  }
 else
 {
      $language=&$_SESSION['language'];
      $languages_id=&$_SESSION['languages_id'];
 }

// include the language translations

  require(DIR_WS_LANGUAGES . $language . '.php');
  $current_page = basename($PHP_SELF);

  if (file_exists(DIR_WS_LANGUAGES . $language . '/' . $current_page)) {
    //$current_page='currencies.php';
    include(DIR_WS_LANGUAGES . $language . '/' . $current_page);
  }

// define our localization functions
  require(DIR_WS_FUNCTIONS . 'localization.php');

// Include validation functions (right now only email address)
  require(DIR_WS_FUNCTIONS . 'validations.php');

// setup our boxes
  require(DIR_WS_CLASSES . 'table_block.php');
  require(DIR_WS_CLASSES . 'box.php');

// initialize the message stack for output messages
  require(DIR_WS_CLASSES . 'message_stack.php');
  $messageStack = new messageStack;

// split-page-results
  require(DIR_WS_CLASSES . 'split_page_results.php');

// entry/item info classes
  require(DIR_WS_CLASSES . 'object_info.php');

// email classes
  require(DIR_WS_CLASSES . 'mime.php');
  require(DIR_WS_CLASSES . 'email.php');

// file uploading class
  require(DIR_WS_CLASSES . 'upload.php');

// calculate category path
  if (isset($HTTP_GET_VARS['cPath'])) {
    $cPath = $HTTP_GET_VARS['cPath'];
  } else {
    $cPath = '';
  }

  if (tep_not_null($cPath)) {
    $cPath_array = tep_parse_category_path($cPath);
    $cPath = implode('_', $cPath_array);
    $current_category_id = $cPath_array[(sizeof($cPath_array)-1)];
  } else {
    $current_category_id = 0;
  }

// default open navigation box
  if (!tep_session_is_registered('selected_box')) {
    tep_session_register('selected_box');
    $selected_box = 'configuration';
  }
  else
  {
    $selected_box=&$_SESSION['selected_box'];
  }

  if (isset($HTTP_GET_VARS['selected_box'])) {
    $selected_box = $HTTP_GET_VARS['selected_box'];
  }

// the following cache blocks are used in the Tools->Cache section
// ('language' in the filename is automatically replaced by available languages)
  $cache_blocks = array(array('title' => TEXT_CACHE_CATEGORIES, 'code' => 'categories', 'file' => 'categories_box-language.cache', 'multiple' => true),
                        array('title' => TEXT_CACHE_MANUFACTURERS, 'code' => 'manufacturers', 'file' => 'manufacturers_box-language.cache', 'multiple' => true),
                        array('title' => TEXT_CACHE_ALSO_PURCHASED, 'code' => 'also_purchased', 'file' => 'also_purchased-language.cache', 'multiple' => true)
                       );

// check if a default currency is set
  if (!defined('DEFAULT_CURRENCY')) {
    $messageStack->add(ERROR_NO_DEFAULT_CURRENCY_DEFINED, 'error');
  }

// check if a default language is set
  if (!defined('DEFAULT_LANGUAGE')) {
    $messageStack->add(ERROR_NO_DEFAULT_LANGUAGE_DEFINED, 'error');
  }

  if (function_exists('ini_get') && ((bool)ini_get('file_uploads') == false) ) {
    $messageStack->add(WARNING_FILE_UPLOADS_DISABLED, 'warning');
  }

require(DIR_WS_INCLUDES . 'add_ccgvdc_application_top.php'); //ICW CREDIT CLASS GIFT VOUCHER ADDITION

// include the articles functions
  require(DIR_WS_FUNCTIONS . 'articles.php');

// Article Manager
  if (isset($HTTP_GET_VARS['tPath'])) {
    $tPath = $HTTP_GET_VARS['tPath'];
  } else {
    $tPath = '';
  }

  if (tep_not_null($tPath)) {
    $tPath_array = tep_parse_topic_path($tPath);
    $tPath = implode('_', $tPath_array);
    $current_topic_id = $tPath_array[(sizeof($tPath_array)-1)];
  } else {
    $current_topic_id = 0;
  }

define('FILENAME_STATS_SALES_REPORT', 'stats_sales_report.php');

?>