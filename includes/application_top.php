<?php

if(!$app_top_included)
{
    
  

ob_start();
//check referrer

/*
if(strlen($_SERVER['HTTP_REFERER'])>0 && strpos(substr($_SERVER['HTTP_REFERER'],1,strlen('https://'.$_SERVER['HTTP_HOST'])),$_SERVER['HTTP_HOST'])<1)
{
	//referrer found. Log it!
	//check for previous cookie with referer id
	if(strlen($_COOKIE['rulsid'])<1)
	{
		//create new rulsid (referer urls id)
		$rulsid=uniqid('',TRUE);
		setcookie('rulsid',$rulsid,time()+60*60*24*365);
	}
	else
	{
		$rulsid=$_COOKIE['rulsid'];
	}
	
	//Log referrer
	//tep_db_query('insert into referers("rulsid","referer_url") values("'.tep_db_input($rulsid).'","'.tep_db_input($_SERVER['HTTP_REFERER']).'")');

}
*/

//PHP 5.0 Upgrade workaround
//PHP 5.0 Upgrade workaround
$HTTP_GET_VARS=& $_GET;                                       
$HTTP_POST_VARS=& $_POST;
$HTTP_COOKIE_VARS=& $_COOKIE;
$HTTP_SERVER_VARS=& $_SERVER;


$HTTP_SESSION_VARS=& $_SESSION;

// IIS Fix
    if(is_null($_SERVER['HTTPS']))
    {
        $_SERVER['HTTPS']='off';
    }

                      

//$language='english';
//$languages_id=1;
//$currency='USD';


function redir301($url301='/')
{

    //Function to make 301 redirs.
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: ' . $url301);
    exit();

}





//////////////////////////////////////////////////////////////////////////////////////////
//CONFIGURE///////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////

$hide_cart=false;

// Set the local configuration parameters - mainly for developers

// include server parameters
  require($_SERVER['DOCUMENT_ROOT'].'/includes/configure.php');

// define the project version
  define('PROJECT_VERSION', 'osCommerce 2.2-MS2');

// set the type of request (secure or not)
 $request_type = ($_SERVER['HTTPS'] == 'on') ? 'SSL' : 'NONSSL';

// set php_self in the local scope
  if (!isset($PHP_SELF)) $PHP_SELF = $HTTP_SERVER_VARS['PHP_SELF'];
  if ($request_type == 'NONSSL') {
    define('DIR_WS_CATALOG', DIR_WS_HTTP_CATALOG);
  } else {
    define('DIR_WS_CATALOG', DIR_WS_HTTPS_CATALOG);
  }

// check urls
 require_once(DIR_WS_INCLUDES . '301urls.php');


// include the list of project filenames
  require(DIR_WS_INCLUDES . 'filenames.php');

// include the list of project database tables
  require(DIR_WS_INCLUDES . 'database_tables.php');

// customization for the design layout
  define('BOX_WIDTH', 0); // how wide the boxes should be in pixels (default: 125)

// include the database functions
  require(DIR_WS_FUNCTIONS . 'database.php');

require_once 'php-activerecord/ActiveRecord.php';

require_once (DIR_WS_CLASSES.'couchdb.php'); // Load Couch DB connector

ActiveRecord\Config::initialize(function($cfg)
{
    $cfg->set_model_directory(DIR_WS_INCLUDES . '/db_models');
    $cfg->set_connections(array(
        DB_DATABASE => 'mysql://'.DB_SERVER_USERNAME.':'.DB_SERVER_PASSWORD.'@'.DB_SERVER.'/'.DB_DATABASE));
    
    $cfg->set_default_connection(DB_DATABASE);

});
  
// define general functions used application-wide
  require(DIR_WS_FUNCTIONS . 'general.php');
  require(DIR_WS_FUNCTIONS . 'html_output.php');

// make a connection to the database... now
  tep_db_connect() or die('Unable to connect to database server!');
  
// see if configuration file exists, and how old.
   require(DIR_WS_CLASSES . 'megacache.php');
   
   $cache=new megacache(600, 'application_top');
   if(!$evcode=$cache->doCache('constants', false))
   {
    $evcode='';
  // set the application parameters
    $configuration_query = tep_db_query('select distinct configuration_key as cfgKey, configuration_value as cfgValue from ' . TABLE_CONFIGURATION);

    while ($configuration = tep_db_fetch_array($configuration_query)) {
      if(!defined($configuration["cfgKey"]))
      {
        $evcode.='define(\''.$configuration["cfgKey"].'\', \''.$configuration["cfgValue"].'\');';
        define($configuration["cfgKey"],  $configuration["cfgValue"]);
      }
    }
    $cache->addCache('constants', $evcode);
   }
   else
   {
     eval($evcode);
   }
   



// set the cookie domain
  $cookie_domain = (($request_type == 'NONSSL') ? HTTP_COOKIE_DOMAIN : HTTPS_COOKIE_DOMAIN);
  $cookie_path = (($request_type == 'NONSSL') ? HTTP_COOKIE_PATH : HTTPS_COOKIE_PATH);

// capture search referrals
if(isset($_SERVER['HTTP_REFERER']))
  {
  if((strpos($_SERVER['HTTP_REFERER'],'health=')>0 || strpos($_SERVER['HTTP_REFERER'],'q=')>0 || strpos($_SERVER['HTTP_REFERER'],'p=')>0) && strpos($_SERVER['HTTP_REFERER'],'cache:')<1 && strpos($_SERVER['HTTP_USER_AGENT'],'scanalert')<1)
  {   
      $searchq=parse_section($_SERVER['HTTP_REFERER'].'<eof>','?','<eof>');
      $searchq=preg_split('/&/',$searchq);
      $ntc=true;
      foreach($searchq as $item)
      {
          $item='<bof>'.$item;
          if((strpos($item,'health=')>0 || strpos($item,'q=')>0 || strpos($item,'p=')>0) && $ntc==true)
          {
              $search_ref=trim(urldecode(parse_section($item.'<eof>','=','<eof>')));
              $test=preg_split('/=/',str_replace('<bof>','',$item));
  
              if($test[0]=='q' || $test[0]=='p')
              {
                  //no need to go further.
                  $ntc=false;
                  break;
              }
              
          }
       }
      if(strpos('<bof>'.$search_ref,'site:')>0)
      {
          //modify for site: queries
          $search_ref=trim(substr($search_ref,strpos($search_ref,' ')+1));
      }
      
       if(strlen($search_ref)>0)
       {
          //keyword found. Capitalize on the opportunity.
          $page_param=$_SERVER['REQUEST_URI'];
          if(strlen($page_param)>0)
          {
              if(tep_db_fetch_array(tep_db_query('SELECT * FROM site_queries WHERE param_id="'.tep_db_input($page_param).'" and query="'.tep_db_input($search_ref).'"')))
              {
                  tep_db_query('UPDATE site_queries SET hits=hits+1 WHERE param_id="'.tep_db_input($page_param).'" and query="'.tep_db_input($search_ref).'"');
                  
              }
              else
              {
                  tep_db_query('INSERT INTO site_queries(param_id,query) VALUES("'.tep_db_input($page_param).'","'.tep_db_input(strtolower($search_ref)).'")');
              }
          }   
      }
  }    
}

// include modURLs for URL Rewrite
  require(DIR_WS_MODULES.'modURLs.php');  

// include shopping cart class
  require(DIR_WS_CLASSES . 'shopping_cart.php');

// include navigation history class
  require(DIR_WS_CLASSES . 'navigation_history.php');

// some code to solve compatibility issues
  require(DIR_WS_FUNCTIONS . 'compatibility.php');


// define how the session functions will be used
  require(DIR_WS_FUNCTIONS . 'sessions.php');

// set the session cookie parameters
   if (function_exists('session_set_cookie_params')) {
    session_set_cookie_params(30*24*60*60, $cookie_path, $cookie_domain);
  } elseif (function_exists('ini_set')) {
    ini_set('session.cookie_lifetime', time()+30*24*60*60);
    ini_set('session.gc_maxlifetime', time()+30*24*60*60);
    ini_set('session.cookie_path', $cookie_path);
    ini_set('session.cookie_domain', $cookie_domain);
  }

// set the session name and save path
  tep_session_name('osCsid');
  tep_session_save_path(SESSION_WRITE_DIRECTORY);

// set the session ID if it exists
  if (isset($_COOKIE[tep_session_name()])) {
     tep_session_id($_COOKIE[tep_session_name()]);
   }

  $session_started = tep_session_start();
  tep_session_is_registered('osCsid') ;
  
  // Log http_referer chain
  /*
  if(!tep_session_is_registered('referer'))
  {
      tep_session_register('referer');
      $referer=Array();
  }
  if(!strpos($_SERVER['HTTP_REFERER'],' '.HTTP_SERVER))
    {
       echo strpos($_SERVER['HTTP_REFERER'],' '.HTTP_SERVER); exit(); 
        array_push($referer,$_SERVER['HTTP_REFERER']);
        
    }  
                                          
                                          echo serialize($referer);exit();      */

// set SID once, even if empty
  $SID = (defined('SID') ? SID : '');

// verify the ssl_session_id if the feature is enabled
  if ( ($request_type == 'SSL') && (SESSION_CHECK_SSL_SESSION_ID == 'True') && (ENABLE_SSL == true) && ($session_started == true) ) {
    $ssl_session_id = getenv('SSL_SESSION_ID');
    if (!tep_session_is_registered('SSL_SESSION_ID')) {
      $SESSION_SSL_ID = $ssl_session_id;
      tep_session_register('SESSION_SSL_ID');
    }

    if ($SESSION_SSL_ID != $ssl_session_id) {
      tep_session_destroy();
      tep_redirect(tep_href_link(FILENAME_SSL_CHECK));
    }
  }

// verify the browser user agent if the feature is enabled
  if (SESSION_CHECK_USER_AGENT == 'True') {
    $http_user_agent = getenv('HTTP_USER_AGENT');
    if (!tep_session_is_registered('SESSION_USER_AGENT')) {
      $SESSION_USER_AGENT = $http_user_agent;
      tep_session_register('SESSION_USER_AGENT');
    }

    if ($SESSION_USER_AGENT != $http_user_agent) {
      tep_session_destroy();
      tep_redirect(tep_href_link(FILENAME_LOGIN));
    }
  }


// create the shopping cart & fix the cart if necesary
  if (tep_session_is_registered('cart') && is_object($_SESSION['cart'])) {
	$cart=&$_SESSION['cart'];
  } else {
    $cart = new shoppingCart;
	$_SESSION['cart']=&$cart;
    //tep_session_register('cart');
  }

// include currencies class and create an instance
  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

// include the mail classes
  require(DIR_WS_CLASSES . 'mime.php');
  require(DIR_WS_CLASSES . 'email.php');

// set the language
  if (!tep_session_is_registered('language')) {

    if (!tep_session_is_registered('language')) {
      tep_session_register('language');
      tep_session_register('languages_id');
    }
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
  
  
  require(DIR_WS_LANGUAGES . $language . '.php');

// currency
  if (!tep_session_is_registered('currency') || ( (USE_DEFAULT_LANGUAGE_CURRENCY == 'true') && (LANGUAGE_CURRENCY != $currency) ) ) {
    if (!tep_session_is_registered('currency')) tep_session_register('currency');
    if (isset($HTTP_GET_VARS['currency'])) {
      if (!$currency = tep_currency_exists($HTTP_GET_VARS['currency'])) $currency = (USE_DEFAULT_LANGUAGE_CURRENCY == 'true') ? LANGUAGE_CURRENCY : DEFAULT_CURRENCY;
    } else {
      $currency = (USE_DEFAULT_LANGUAGE_CURRENCY == 'true') ? LANGUAGE_CURRENCY : DEFAULT_CURRENCY;
    }
  }
  else
  {
    $currency=&$_SESSION['currency'];

  }
  




// navigation history
  if (tep_session_is_registered('navigation')) {
     $navigation=&$_SESSION['navigation'];
   } else {
     tep_session_register('navigation');
     $navigation=&$_SESSION['navigation'];
     $navigation = new navigationHistory;
  }
  $navigation->add_current_page();
  
  
  if($_POST['country'])
{
    $_SESSION['country']=$_POST['country'];
}
if($_POST['postcode'])
{
    $_SESSION['postcode']=$_POST['postcode'];
}


//if($cart->in_cart(CM_FTPID)) {$_SESSION['cm_is_member']=true;}
// Shopping cart actions
  if (isset($_REQUEST['action'])) {
// redirect the customer to a friendly cookie-must-be-enabled page if cookies are disabled
    if ($session_started == false) {
      tep_redirect(tep_href_link(FILENAME_COOKIE_USAGE));
    }
    if (DISPLAY_CART == 'true') {
      $goto =  FILENAME_SHOPPING_CART ;
      $parameters = array('action', 'cPath', 'products_id', 'pid');
    } else {
      $goto = basename($PHP_SELF);
      if ($HTTP_GET_VARS['action'] == 'buy_now') {
        $parameters = array('action', 'pid', 'products_id');
      } else {
        $parameters = array('action', 'pid');
      }
    }

    switch ($_REQUEST['action']) {
       // customer wants to update the product quantity in their shopping cart
       case 'update_product' : for ($i=0, $n=sizeof($HTTP_POST_VARS['products_id']); $i<$n; $i++) {
                                 if (in_array($HTTP_POST_VARS['products_id'][$i], (is_array($HTTP_POST_VARS['cart_delete']) ? $HTTP_POST_VARS['cart_delete'] : array()))) {
                                   $cart->remove($HTTP_POST_VARS['products_id'][$i]);
                                   //if($HTTP_POST_VARS['products_id'][$i]==CM_FTPID){$_SESSION['cm_is_member']=false;}
                                 } else {

                                    $attributes = ($HTTP_POST_VARS['id'][$HTTP_POST_VARS['products_id'][$i]]) ? $HTTP_POST_VARS['id'][$HTTP_POST_VARS['products_id'][$i]] : '';
                                  $cart->add_cart($HTTP_POST_VARS['products_id'][$i], $HTTP_POST_VARS['cart_quantity'][$i], $attributes, false);
                                }
                              }
                              tep_redirect(tep_href_link($goto, tep_get_all_get_params($parameters)));
                              break;

      // customer adds a product from the products page
      case 'add_product' :
      case 'buy_now'  :
      		if (isset($_REQUEST['products_id']) && is_numeric($_REQUEST['products_id'])) {

      		$tmp_qty=(int)$_REQUEST['qty'] == 0 ? 1 : (int)$_REQUEST['qty'];
                   $cart->add_cart(  $_REQUEST['products_id'], $tmp_qty);
                              }

                          if($_REQUEST['cm_freetrial']=='true' && !$_SESSION['cm_is_member'])
                          {
				$cart->remove(CM_FTPID);
                          	$cart->add_cart(CM_FTPID,1);

                          }
                          elseif($_REQUEST['cm_full']=='true' && !$_SESSION['cm_is_member'])
                          {
                          	$cart->remove(CM_PID);
                          	$cart->add_cart(CM_PID,1);

                          }

                              tep_redirect(tep_href_link($goto, tep_get_all_get_params($parameters)));

                              break;


      case 'cust_order' :     if (tep_session_is_registered('customer_id') && isset($HTTP_GET_VARS['pid'])) {
                                if (tep_has_product_attributes($HTTP_GET_VARS['pid'])) {
                                  tep_redirect(tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $HTTP_GET_VARS['pid']));
                                } else {
                                  $cart->add_cart($HTTP_GET_VARS['pid'], $cart->get_quantity($HTTP_GET_VARS['pid'])+1);
                                }
                              }
                              tep_redirect(tep_href_link($goto, tep_get_all_get_params($parameters)));
                              break;

	  // re-order product segment
	  case 'reorder' : $reorder_result = tep_reorder($_GET['order_id']);
								if ($reorder_result == '') {
								tep_redirect(tep_href_link(FILENAME_CHECKOUT_SHIPPING));
								}
								echo $reorder_result;
								break;

    }

  }
// include the password crypto functions
  require(DIR_WS_FUNCTIONS . 'password_funcs.php');

// include validation functions (right now only email address)
  require(DIR_WS_FUNCTIONS . 'validations.php');

// split-page-results
  require(DIR_WS_CLASSES . 'split_page_results.php');

// infobox
  require(DIR_WS_CLASSES . 'boxes.php');

// auto expire special products
  require(DIR_WS_FUNCTIONS . 'specials.php');

// calculate category path
  if (isset($HTTP_GET_VARS['cPath'])) {
    $cPath = $HTTP_GET_VARS['cPath'];
  } /*elseif (isset($HTTP_GET_VARS['products_id']) && !isset($HTTP_GET_VARS['manufacturers_id'])) {
    $cPath = tep_get_product_path($HTTP_GET_VARS['products_id']);
 }*/
  else {
    $cPath = '';
  }

  if (tep_not_null($cPath)) {
     $cPath_array = tep_parse_category_path($cPath);
     $cPath = implode('_', $cPath_array);
     $current_category_id = $cPath_array[(sizeof($cPath_array)-1)];
   } else {
     $current_category_id = 0;
   }

 // include the breadcrumb class and start the breadcrumb trail
   require(DIR_WS_CLASSES . 'breadcrumb.php');
   $breadcrumb = new breadcrumb;

 // $breadcrumb->add(HEADER_TITLE_TOP, HTTP_SERVER);
    //$breadcrumb->add(HEADER_TITLE_TOP, '/');
 // $breadcrumb->add(HEADER_TITLE_CATALOG, tep_href_link(FILENAME_DEFAULT));

// add the products name to the breadcrumb trail
  if (isset($HTTP_GET_VARS['products_id'])) {
    $model_query = tep_db_query("select products_name from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" .
(int)$HTTP_GET_VARS['products_id'] . "'");
if (tep_db_num_rows($model_query)) {
      $model = tep_db_fetch_array($model_query);
      $breadcrumb->add($model['products_name'], tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' .
$HTTP_GET_VARS['products_id']));
    }
  }

// add category names or the manufacturer name to the breadcrumb trail
  if (isset($cPath)) {
  $CurrPath=$cPath;
  $categories_query = tep_db_query("select categories_name, c.categories_id, parent_id from " . TABLE_CATEGORIES_DESCRIPTION . " cd join categories c on c.categories_id=cd.categories_id where c.categories_id = '" . (int)$CurrPath . "' and language_id = '" . (int)$languages_id . "'");
  while(tep_db_num_rows($categories_query) > 0)
  {
        $categories = tep_db_fetch_array($categories_query);
        if((int)$categories['categories_id']<>$cPath)
        {
          $breadcrumb->add($categories['categories_name'], tep_href_link(FILENAME_DEFAULT, 'cPath=' . $categories['categories_id']));
        }
        $categories_query = tep_db_query("select categories_name, c.categories_id, parent_id from " . TABLE_CATEGORIES_DESCRIPTION . " cd join categories c on c.categories_id=cd.categories_id where c.categories_id = '" . (int)$categories['parent_id'] . "' and language_id = '" . (int)$languages_id . "'");
  }

  } /*elseif (isset($HTTP_GET_VARS['manufacturers_id'])) {
    $manufacturers_query = tep_db_query("select manufacturers_name from " . TABLE_MANUFACTURERS . " where manufacturers_id = '" . (int)$HTTP_GET_VARS['manufacturers_id'] . "'");
    if (tep_db_num_rows($manufacturers_query)) {
      $manufacturers = tep_db_fetch_array($manufacturers_query);
      $breadcrumb->add($manufacturers['manufacturers_name'], tep_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . $HTTP_GET_VARS['manufacturers_id']));
    }
  } */

//added for article_manager_1.2
// include the articles functions
/*
if($_REQUEST['do_admin']=='true')
{
    //Make sure user is logged in
    tep_session_register('do_admin');
    $do_admin=true;

}

tep_session_is_registered('do_admin');
  
  if($do_admin)
  {
      
    if(!tep_session_is_registered('authenticated'))
    {
        
        tep_session_register($authenticated);
        $authenticated=false;   
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
        

        if(tep_validate_password($_SERVER['PHP_AUTH_PW'], $get_user['customers_password']))
        {
            $authenticated=true;
        }
                      
    }

    if (!$authenticated && !$system_login) {
        if($tries>=3)
        {
            $tries=1;
            tep_redirect(HTTP_SERVER);
        }
        header('WWW-Authenticate: Basic realm="Seacoast"');
        header('HTTP/1.0 401 Unauthorized');  
        $tries+=1;  
        exit();
    }
  }
  
  */

  require(DIR_WS_FUNCTIONS . 'articles.php');
  require(DIR_WS_FUNCTIONS . 'article_header_tags.php');

// calculate topic path
  if (isset($HTTP_GET_VARS['tPath'])) {
    $tPath = $HTTP_GET_VARS['tPath'];
  } elseif (isset($HTTP_GET_VARS['articles_id']) && !isset($HTTP_GET_VARS['authors_id'])) {
    $tPath = tep_get_article_path($HTTP_GET_VARS['articles_id']);
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

// add topic names or the author name to the breadcrumb trail
  if (isset($tPath_array)) {
    for ($i=0, $n=sizeof($tPath_array); $i<$n; $i++) {
      $topics_query = tep_db_query("select topics_name from " . TABLE_TOPICS_DESCRIPTION . " where topics_id = '" . (int)$tPath_array[$i] . "' and language_id = '" . (int)$languages_id . "'");
      if (tep_db_num_rows($topics_query) > 0) {
        $topics = tep_db_fetch_array($topics_query);
        $breadcrumb->add($topics['topics_name'], tep_href_link(FILENAME_ARTICLES, 'tPath=' . implode('_', array_slice($tPath_array, 0, ($i+1)))));
      } else {
        break;
      }
    }
  } elseif (isset($HTTP_GET_VARS['authors_id'])) {
    $authors_query = tep_db_query("select authors_name from " . TABLE_AUTHORS . " where authors_id = '" . (int)$HTTP_GET_VARS['authors_id'] . "'");
    if (tep_db_num_rows($authors_query)) {
      $authors = tep_db_fetch_array($authors_query);
      $breadcrumb->add('Articles by ' . $authors['authors_name'], tep_href_link(FILENAME_ARTICLES, 'authors_id=' . $HTTP_GET_VARS['authors_id']));
    }
  }

// add the articles name to the breadcrumb trail
  if (isset($HTTP_GET_VARS['articles_id'])) {
    $article_query = tep_db_query("select articles_name from " . TABLE_ARTICLES_DESCRIPTION . " where articles_id = '" . (int)$HTTP_GET_VARS['articles_id'] . "'");
    if (tep_db_num_rows($article_query)) {
      $article = tep_db_fetch_array($article_query);
      if (isset($HTTP_GET_VARS['authors_id'])) {
        $breadcrumb->add($article['articles_name'], tep_href_link(FILENAME_ARTICLE_INFO, 'authors_id=' . $HTTP_GET_VARS['authors_id'] . '&articles_id=' . $HTTP_GET_VARS['articles_id']));
      } else {
        $breadcrumb->add($article['articles_name'], tep_href_link(FILENAME_ARTICLE_INFO, 'tPath=' . $tPath . '&articles_id=' . $HTTP_GET_VARS['articles_id']));
      }
    }
  }

// initialize the message stack for output messages
  require(DIR_WS_CLASSES . 'message_stack.php');
  $messageStack = new messageStack;

// set which precautions should be checked
  define('WARN_INSTALL_EXISTENCE', 'true');
  define('WARN_CONFIG_WRITEABLE', 'true');
  define('WARN_SESSION_DIRECTORY_NOT_WRITEABLE', 'true');
  define('WARN_SESSION_AUTO_START', 'true');
  define('WARN_DOWNLOAD_DIRECTORY_NOT_READABLE', 'true');


define('MAIN_CATEGORIES', 'main_categories.php');
// get the cart totals once so we do not need to recalculate the cart multiple times
if ($cart->count_contents() > 0) {
  $cart_weight = $cart->show_weight();
  $cart_total = $cart->show_total();
} else {
  $cart_total = 0;
  $cart_weight = 0;
}

//Check for alternate URL handlers
$handler=tep_db_fetch_array(tep_db_query('select * from url_override where URL="'.tep_db_input($_SERVER['REQUEST_URI']).'"'));

if(strlen($handler['redirect'])>0)
{
	redir301($handler['redirect']);
	exit();

}elseif(strlen($handler['handler'])>0)
{
	require($_SERVER['DOCUMENT_ROOT'].$handler['handler']);
	exit();
	
}



  tep_expire_specials();
  
  

  
    ////////////////////////////////////////////////////////////////////
//Price modifier

$pmod=1.0;



////////////////////////////////////////////////////////////////////

$app_top_included=true;
}

ob_clean();
?>