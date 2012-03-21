<?php

/*

  $Id: login.php,v 1.14 2003/06/09 22:46:46 hpdl Exp $



  osCommerce, Open Source E-Commerce Solutions

  http://www.oscommerce.com



  Copyright (c) 2003 osCommerce



  Released under the GNU General Public License

*/



define('NAVBAR_TITLE', 'Login');

define('HEADING_TITLE', 'Welcome to Seacoast Vitamins, Please Sign In');



define('HEADING_NEW_CUSTOMER', 'New Customer');

define('TEXT_NEW_CUSTOMER', 'If you are a new customer or are a returning customer but have not created an account on the new website please click on continue below.');

define('TEXT_NEW_CUSTOMER_INTRODUCTION', 'By creating an account at ' . STORE_NAME . ' you will be able to shop faster, keep track of the orders you have previously made and receive our newsletter as well as product updates.');



define('HEADING_RETURNING_CUSTOMER', 'Returning Customer');

define('TEXT_RETURNING_CUSTOMER', 'If you already have created an account with our new site, please log in below.');



define('TEXT_PASSWORD_FORGOTTEN', 'Forget your password? Click here.');



define('TEXT_LOGIN_ERROR', 'Error: No match for E-Mail Address and/or Password. Please verify that you have created an account with our new website and that your email and password were entered correctly.');

define('TEXT_VISITORS_CART', '<font color="#ff0000"><b>Note:</b></font> Your "Visitors Cart" contents will be merged with your "Members Cart" contents once you have logged on. <a href="javascript:session_win();">[More Info]</a>');

// PWA

define('PWA_FAIL_ACCOUNT_EXISTS', 'An account already exists for the email address <i>{EMAIL_ADDRESS}</i>.  You must login here with the password for that account before proceeding to checkout.');

define('HEADING_CHECKOUT', '<font size="2">Proceed Directly to Checkout</font>');

define('TEXT_CHECKOUT_INTRODUCTION', 'Proceed to Checkout without creating an account. By choosing this option none of your user information will be kept in our records, and you will not be able to review your order status, nor keep track of your previous orders.');

define('PROCEED_TO_CHECKOUT', 'Proceed to Checkout without Registering');

// Begin Checkout Without Account v0.70 changes

define('PWA_FAIL_ACCOUNT_EXISTS', 'An account already exists for the email address <i>{EMAIL_ADDRESS}</i>.  You must login here with the password for that account before proceeding to checkout.');
// Begin Checkout Without Account v0.60 changes
define('HEADING_CHECKOUT', '<font size="2">Proceed Directly to Checkout</font>');
define('TEXT_CHECKOUT_INTRODUCTION', 'Proceed to Checkout without creating an account. By choosing this option none of your user information will be kept in our records, and you will not be able to review your order status, nor keep track of your previous orders.');
define('PROCEED_TO_CHECKOUT', 'Proceed to Checkout without Registering');
// End Checkout Without Account changes
?>



