<?php
/*
  $Id: cc.php,v 1.10 2002/11/01 05:14:11 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  define('MODULE_PAYMENT_CC_TEXT_TITLE', 'Credit Card');
  define('MODULE_PAYMENT_CC_TEXT_DESCRIPTION', 'Credit Card Test Info:<br><br>CC#: 4111111111111111<br>Expiry: Any');
  define('MODULE_PAYMENT_CC_TEXT_CREDIT_CARD_TYPE', 'Credit Card Type:');
  define('MODULE_PAYMENT_CC_TEXT_CREDIT_CARD_OWNER', 'Credit Card Owner:');
  define('MODULE_PAYMENT_CC_TEXT_CREDIT_CARD_NUMBER', 'Credit Card Number:');
  define('MODULE_PAYMENT_CC_TEXT_CREDIT_CARD_EXPIRES', 'Credit Card Expiry Date:');
  define('MODULE_PAYMENT_CC_TEXT_JS_CC_OWNER', '* The owner\'s name of the credit card must be at least ' . CC_OWNER_MIN_LENGTH . ' characters.\n');
  define('MODULE_PAYMENT_CC_TEXT_JS_CC_NUMBER', '* The credit card number must be at least ' . CC_NUMBER_MIN_LENGTH . ' characters.\n');
  define('MODULE_PAYMENT_CC_TEXT_ERROR', 'Credit Card Error!');
  // begin cvv contribution

  define('MODULE_PAYMENT_CC_TEXT_CREDIT_CARD_CVVNUMBER', '3 or 4 Digit Security Code:');

  define('TEXT_CVV_LINK', '<u>[help?]</u>');

  define('HEADING_CVV', 'Security Code Help Screen');

  define('TEXT_CVV', '<table align="center" cellspacing="2" cellpadding="5" width="400"><tr><td><span class="fancyText"><b>Visa, Mastercard, Discover 3 Digit Card Verification Number</b></span></td></tr><tr><td><span class="fancyText">For your safety and security, we require that you enter your card\'s verification number. The verification number is a 3-digit number printed on the back of your card. It appears after and to the right of your card number\'s last four digits.</span></td></tr><tr><td align="center"><IMG src="images/cv_card.gif"></td></tr></table><hr><table align="center" cellspacing="2" cellpadding="5" width="400"><tr><td><span class="fancyText"><b>American Express 4 Digit Card Verification Number</b> </span></td></tr><tr><td><span class="fancyText">For your safety and security, we require that you enter your card\'s verification number. The American Express verification number is a 4-digit number printed on the front of your card. It appears after and to the right of your card number.</span></td></tr><tr><td align="center"><IMG src="images/cv_amex_card.gif"></td></tr></table>');

  define('TEXT_CLOSE_WINDOW', '<u>Close Window</u> [x]');

  define('MODULE_PAYMENT_CC_TEXT_JS_CVVNUMBER', '*** The credit card validation number must be at least' . CVVNUMBER_MIN_LENGTH . 'digits . \n'); 

  define('MODULE_PAYMENT_CC_TEXT_JS_MAXCVVNUMBER', '*** The credit card validation number must be' . CVVNUMBER_MAX_LENGTH . 'digits or less. \n');

// end cvv contribution
?>