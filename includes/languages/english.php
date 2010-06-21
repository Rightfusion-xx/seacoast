<?php







/*







  $Id: english.php,v 1.114 2003/07/09 18:13:39 dgw_ Exp $















  osCommerce, Open Source E-Commerce Solutions







  http://www.oscommerce.com















  Copyright (c) 2003 osCommerce















  Released under the GNU General Public License







*/















// look in your $PATH_LOCALE/locale directory for available locales







// or type locale -a on the server.







// Examples:







// on RedHat try 'en_US'







// on FreeBSD try 'en_US.ISO_8859-1'







// on Windows try 'en', or 'English'







@setlocale(LC_TIME, 'en_US.ISO_8859-1');















define('DATE_FORMAT_SHORT', '%m/%d/%Y');  // this is used for strftime()







define('DATE_FORMAT_LONG', '%A %d %B, %Y'); // this is used for strftime()







define('DATE_FORMAT', 'm/d/Y'); // this is used for date()







define('DATE_TIME_FORMAT', DATE_FORMAT_SHORT . ' %H:%M:%S');







// +Country-State Selector







define ('DEFAULT_COUNTRY', '223');







// -Country-State Selector 







////







// Return date in raw format







// $date should be in format mm/dd/yyyy







// raw date is in format YYYYMMDD, or DDMMYYYY







function tep_date_raw($date, $reverse = false) {







  if ($reverse) {







    return substr($date, 3, 2) . substr($date, 0, 2) . substr($date, 6, 4);







  } else {







    return substr($date, 6, 4) . substr($date, 0, 2) . substr($date, 3, 2);







  }







}















// if USE_DEFAULT_LANGUAGE_CURRENCY is true, use the following currency, instead of the applications default currency (used when changing language)







define('LANGUAGE_CURRENCY', 'USD');















// Global entries for the <html> tag







define('HTML_PARAMS','dir="LTR" lang="en"');















// charset for web pages and emails







define('CHARSET', 'iso-8859-1');















// page title







define('TITLE', 'Seacoast Vitamins');















// header text in includes/header.php







define('HEADER_TITLE_CREATE_ACCOUNT', 'Create an Account');







define('HEADER_TITLE_MY_ACCOUNT', 'My Account');







define('HEADER_TITLE_CART_CONTENTS', 'Cart Contents');







define('HEADER_TITLE_CHECKOUT', 'Checkout');







define('HEADER_TITLE_TOP', 'Home');







define('HEADER_TITLE_CATALOG', 'Home');







define('HEADER_TITLE_LOGOFF', 'Log Off');







define('HEADER_TITLE_LOGIN', 'Log In');







// footer text in includes/footer.php







define('FOOTER_TEXT_REQUESTS_SINCE', 'requests since');















// text for gender







define('MALE', 'Male');







define('FEMALE', 'Female');







define('MALE_ADDRESS', 'Mr.');







define('FEMALE_ADDRESS', 'Ms.');















// text for date of birth example







define('DOB_FORMAT_STRING', 'mm/dd/yyyy');















// categories box text in includes/boxes/categories.php







define('BOX_HEADING_CATEGORIES', 'Shop by Category');







define('BOX_INFORMATION_PRODUCTS_NEW', 'Newest Products');







define('ENTRY_ISBN_NO', 'ISBN:');







// manufacturers box text in includes/boxes/manufacturers.php







define('BOX_HEADING_MANUFACTURERS', 'Shop by Brand');







define('BOX_HEADING_SPONSORED_LINKS', 'Sponsored Links');



define('BOX_HEADING_FEATURED', 'Featured Products');
define('BOX_HEADING_SPONSORED', 'Sponsored By');

define('BOX_HEADING_LIVEHELP', 'Help Center');

define('BOX_HEADING_HEALTH', 'Health Encyclopaedia');

define('BOX_HEADING_SUPPORT', 'Customer Support');







// whats_new box text in includes/boxes/whats_new.php







define('BOX_HEADING_WHATS_NEW', 'New Products');















// quick_find box text in includes/boxes/quick_find.php







define('BOX_HEADING_SEARCH', 'Search Seacoast');







define('BOX_SEARCH_TEXT', '');







define('BOX_SEARCH_ADVANCED_SEARCH', 'Advanced Search');















// specials box text in includes/boxes/specials.php







define('BOX_HEADING_SPECIALS', 'Whats On Sale!!');















// reviews box text in includes/boxes/reviews.php







define('BOX_HEADING_REVIEWS', 'Product Reviews');







define('BOX_REVIEWS_WRITE_REVIEW', 'Write a review on this product!');







define('BOX_REVIEWS_NO_REVIEWS', 'There are currently no product reviews');







define('BOX_REVIEWS_TEXT_OF_5_STARS', '%s of 5 Stars!');















// shopping_cart box text in includes/boxes/shopping_cart.php







define('BOX_HEADING_SHOPPING_CART', 'Shopping Cart');







define('BOX_SHOPPING_CART_EMPTY', '0 items');







define('HEALTH_SITES', 'HealthSights');















// order_history box text in includes/boxes/order_history.php







define('BOX_HEADING_CUSTOMER_ORDERS', 'Order History');















// best_sellers box text in includes/boxes/best_sellers.php







define('BOX_HEADING_BESTSELLERS', 'Most Popular');
define('BOX_HEADING_NEWPRODUCTS', 'New Products');






define('BOX_HEADING_BESTSELLERS_IN', 'Bestsellers in<br>&nbsp;&nbsp;');















// notifications box text in includes/boxes/products_notifications.php







define('BOX_HEADING_NOTIFICATIONS', 'Notifications');







define('BOX_NOTIFICATIONS_NOTIFY', 'Notify me of updates to <b>%s</b>');







define('BOX_NOTIFICATIONS_NOTIFY_REMOVE', 'Do not notify me of updates to <b>%s</b>');















// manufacturer box text







define('BOX_HEADING_MANUFACTURER_INFO', 'Manufacturer Info');







define('BOX_MANUFACTURER_INFO_HOMEPAGE', '%s Homepage');







define('BOX_MANUFACTURER_INFO_OTHER_PRODUCTS', 'Other products');















// languages box text in includes/boxes/languages.php







define('BOX_HEADING_LANGUAGES', 'Languages');















// currencies box text in includes/boxes/currencies.php







define('BOX_HEADING_CURRENCIES', 'Currencies');















// information box text in includes/boxes/information.php







define('BOX_HEADING_INFORMATION', 'Information');







define('BOX_INFORMATION_PRIVACY', 'Privacy Notice');







define('BOX_INFORMATION_CONDITIONS', 'How to Shop');







define('BOX_INFORMATION_SHIPPING', 'Shipping & Returns');







define('BOX_INFORMATION_CONTACT', 'Contact Us');







// VJ Links Manager v1.00 begin







define('BOX_INFORMATION_LINKS', 'More Links');







define('BOX_INFORMATION_LINKS_SUBMIT', 'Link With Us');







// VJ Links Manager v1.00 end







define('BOX_HEADING_SIMILAR_PRODUCTS', 'Similar Products');















// tell a friend box text in includes/boxes/tell_a_friend.php







define('BOX_HEADING_TELL_A_FRIEND', 'Tell A Friend');







define('BOX_TELL_A_FRIEND_TEXT', 'Tell someone you know about this product.');















// checkout procedure text







define('CHECKOUT_BAR_DELIVERY', 'Delivery Information');







define('CHECKOUT_BAR_PAYMENT', 'Payment Information');







define('CHECKOUT_BAR_CONFIRMATION', 'Confirmation');







define('CHECKOUT_BAR_FINISHED', 'Finished!');















// pull down default text







define('PULL_DOWN_DEFAULT', 'Please Select');







define('TYPE_BELOW', 'Type Below');















// javascript messages







define('JS_ERROR', 'Errors have occured during the process of your form.\n\nPlease make the following corrections:\n\n');















define('JS_REVIEW_TEXT', '* The \'Review Text\' must have at least ' . REVIEW_TEXT_MIN_LENGTH . ' characters.\n');







define('JS_REVIEW_RATING', '* You must rate the product for your review.\n');















define('JS_ERROR_NO_PAYMENT_MODULE_SELECTED', '* Please select a payment method for your order.\n');















define('JS_ERROR_SUBMITTED', 'This form has already been submitted. Please press Ok and wait for this process to be completed.');















define('ERROR_NO_PAYMENT_MODULE_SELECTED', 'Please select a payment method for your order.');















define('CATEGORY_COMPANY', 'Company Details');







define('CATEGORY_PERSONAL', 'Your Personal Details');







define('CATEGORY_ADDRESS', 'Your Address');







define('CATEGORY_CONTACT', 'Your Contact Information');







define('CATEGORY_OPTIONS', 'Options');







define('CATEGORY_PASSWORD', 'Your Password');







define('CATEGORY_COUNTRY', 'Your Country');















define('ENTRY_COMPANY', 'Company Name:');







define('ENTRY_COMPANY_ERROR', '');







define('ENTRY_COMPANY_TEXT', '');







define('ENTRY_GENDER', 'Gender:');







define('ENTRY_GENDER_ERROR', 'Please select your Gender.');







define('ENTRY_GENDER_TEXT', '*');







define('ENTRY_FIRST_NAME', 'First Name:');







define('ENTRY_FIRST_NAME_ERROR', 'Your First Name must contain a minimum of ' . ENTRY_FIRST_NAME_MIN_LENGTH . ' characters.');







define('ENTRY_FIRST_NAME_TEXT', '*');







define('ENTRY_LAST_NAME', 'Last Name:');







define('ENTRY_LAST_NAME_ERROR', 'Your Last Name must contain a minimum of ' . ENTRY_LAST_NAME_MIN_LENGTH . ' characters.');







define('ENTRY_LAST_NAME_TEXT', '*');







define('ENTRY_DATE_OF_BIRTH', 'Date of Birth:');







define('ENTRY_DATE_OF_BIRTH_ERROR', 'Your Date of Birth must be in this format: MM/DD/YYYY (eg 05/21/1970)');







define('ENTRY_DATE_OF_BIRTH_TEXT', '* (eg. 05/21/1970)');







define('ENTRY_EMAIL_ADDRESS', 'E-Mail Address:');







define('ENTRY_EMAIL_ADDRESS_ERROR', 'Your E-Mail Address must contain a minimum of ' . ENTRY_EMAIL_ADDRESS_MIN_LENGTH . ' characters.');







define('ENTRY_EMAIL_ADDRESS_CHECK_ERROR', 'Your E-Mail Address does not appear to be valid - please make any necessary corrections.');







define('ENTRY_EMAIL_ADDRESS_ERROR_EXISTS', 'Your E-Mail Address already exists in our records - please log in with the e-mail address or create an account with a different address.');







define('ENTRY_EMAIL_ADDRESS_TEXT', '*');







define('ENTRY_STREET_ADDRESS', 'Street Address:');







define('ENTRY_STREET_ADDRESS_ERROR', 'Your Street Address must contain a minimum of ' . ENTRY_STREET_ADDRESS_MIN_LENGTH . ' characters.');







define('ENTRY_STREET_ADDRESS_TEXT', '*');







define('ENTRY_SUBURB', 'Suburb:');







define('ENTRY_SUBURB_ERROR', '');







define('ENTRY_SUBURB_TEXT', '');







define('ENTRY_POST_CODE', 'Post Code:');







define('ENTRY_POST_CODE_ERROR', 'Your Post Code must contain a minimum of ' . ENTRY_POSTCODE_MIN_LENGTH . ' characters.');







define('ENTRY_POST_CODE_TEXT', '*');







define('ENTRY_CITY', 'City:');







define('ENTRY_CITY_ERROR', 'Your City must contain a minimum of ' . ENTRY_CITY_MIN_LENGTH . ' characters.');







define('ENTRY_CITY_TEXT', '*');







define('ENTRY_STATE', 'State/Province:');







define('ENTRY_STATE_ERROR', 'Your State must contain a minimum of ' . ENTRY_STATE_MIN_LENGTH . ' characters.');







define('ENTRY_STATE_ERROR_SELECT', 'Please select a state from the States pull down menu.');







define('ENTRY_STATE_TEXT', '*');







define('ENTRY_COUNTRY', 'Country:');







define('ENTRY_COUNTRY_ERROR', 'You must select a country from the Countries pull down menu.');







define('ENTRY_COUNTRY_TEXT', '*');







define('ENTRY_TELEPHONE_NUMBER', 'Telephone Number:');







define('ENTRY_TELEPHONE_NUMBER_ERROR', 'Your Telephone Number must contain a minimum of ' . ENTRY_TELEPHONE_MIN_LENGTH . ' characters.');







define('ENTRY_TELEPHONE_NUMBER_TEXT', '*');







define('ENTRY_FAX_NUMBER', 'Fax Number:');







define('ENTRY_FAX_NUMBER_ERROR', '');







define('ENTRY_FAX_NUMBER_TEXT', '');







define('ENTRY_NEWSLETTER', 'Newsletter:');







define('ENTRY_NEWSLETTER_TEXT', '');







define('ENTRY_NEWSLETTER_YES', 'Subscribed');



// text in includes/modules/new_products.php

define('TABLE_HEADING_MODEL', 'Model');

define('TABLE_HEADING_PRODUCTS', 'Product Name');

define('TABLE_HEADING_MANUFACTURER', 'Manufacturer');

define('TABLE_HEADING_QUANTITY', 'Quantity');

define('TABLE_HEADING_PRICE', 'Price');

define('TABLE_HEADING_WEIGHT', 'Weight');

define('TABLE_HEADING_BUY_NOW', 'Buy Now');

define('TABLE_HEADING_RATING', 'Rating');

define('TABLE_HEADING_FIRST_TO_RATE', 'Be the first to rate');

define('TABLE_HEADING_TEXT_OF_5_STARS', ' of 5 stars!');





define('ENTRY_NEWSLETTER_NO', 'Unsubscribed');







define('ENTRY_NEWSLETTER_ERROR', '');







define('ENTRY_PASSWORD', 'Password:');







define('ENTRY_PASSWORD_ERROR', 'Your Password must contain a minimum of ' . ENTRY_PASSWORD_MIN_LENGTH . ' characters.');







define('ENTRY_PASSWORD_ERROR_NOT_MATCHING', 'The Password Confirmation must match your Password.');







define('ENTRY_PASSWORD_TEXT', '*');







define('ENTRY_PASSWORD_CONFIRMATION', 'Password Confirmation:');







define('ENTRY_PASSWORD_CONFIRMATION_TEXT', '*');







define('ENTRY_PASSWORD_CURRENT', 'Current Password:');







define('ENTRY_PASSWORD_CURRENT_TEXT', '*');







define('ENTRY_PASSWORD_CURRENT_ERROR', 'Your Password must contain a minimum of ' . ENTRY_PASSWORD_MIN_LENGTH . ' characters.');







define('ENTRY_PASSWORD_NEW', 'New Password:');







define('ENTRY_PASSWORD_NEW_TEXT', '*');







define('ENTRY_PASSWORD_NEW_ERROR', 'Your new Password must contain a minimum of ' . ENTRY_PASSWORD_MIN_LENGTH . ' characters.');







define('ENTRY_PASSWORD_NEW_ERROR_NOT_MATCHING', 'The Password Confirmation must match your new Password.');







define('PASSWORD_HIDDEN', '--HIDDEN--');















define('FORM_REQUIRED_INFORMATION', '* Required information');















// constants for use in tep_prev_next_display function







define('TEXT_RESULT_PAGE', 'Result Pages:');







define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> products)');







define('TEXT_DISPLAY_NUMBER_OF_ORDERS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> orders)');







define('TEXT_DISPLAY_NUMBER_OF_REVIEWS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> reviews)');







define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS_NEW', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> new products)');







define('TEXT_DISPLAY_NUMBER_OF_SPECIALS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> specials)');















define('PREVNEXT_TITLE_FIRST_PAGE', 'First Page');







define('PREVNEXT_TITLE_PREVIOUS_PAGE', 'Previous Page');







define('PREVNEXT_TITLE_NEXT_PAGE', 'Next Page');







define('PREVNEXT_TITLE_LAST_PAGE', 'Last Page');







define('PREVNEXT_TITLE_PAGE_NO', 'Page %d');







define('PREVNEXT_TITLE_PREV_SET_OF_NO_PAGE', 'Previous Set of %d Pages');







define('PREVNEXT_TITLE_NEXT_SET_OF_NO_PAGE', 'Next Set of %d Pages');







define('PREVNEXT_BUTTON_FIRST', '&lt;&lt;FIRST');







define('PREVNEXT_BUTTON_PREV', '[&lt;&lt;&nbsp;Prev]');







define('PREVNEXT_BUTTON_NEXT', '[Next&nbsp;&gt;&gt;]');







define('PREVNEXT_BUTTON_LAST', 'LAST&gt;&gt;');















define('IMAGE_BUTTON_ADD_ADDRESS', 'Add Address');







define('IMAGE_BUTTON_ADDRESS_BOOK', 'Address Book');







define('IMAGE_BUTTON_BACK', 'Back');







define('IMAGE_BUTTON_BUY_NOW', 'Buy Now');







define('IMAGE_BUTTON_CHANGE_ADDRESS', 'Change Address');







define('IMAGE_BUTTON_CHECKOUT', 'Checkout');







define('IMAGE_BUTTON_CONFIRM_ORDER', 'Confirm Order');







define('IMAGE_BUTTON_CONTINUE', 'Continue');







define('IMAGE_BUTTON_CONTINUE_SHOPPING', 'Continue Shopping');







define('IMAGE_BUTTON_DELETE', 'Delete');







define('IMAGE_BUTTON_EDIT_ACCOUNT', 'Edit Account');







define('IMAGE_BUTTON_HISTORY', 'Order History');







define('IMAGE_BUTTON_LOGIN', 'Sign In');







define('IMAGE_BUTTON_IN_CART', 'Add to Cart');







define('IMAGE_BUTTON_NOTIFICATIONS', 'Notifications');







define('IMAGE_BUTTON_QUICK_FIND', 'Quick Find');







define('IMAGE_BUTTON_REMOVE_NOTIFICATIONS', 'Remove Notifications');







define('IMAGE_BUTTON_REVIEWS', 'Reviews');







define('IMAGE_BUTTON_SEARCH', 'Search');







define('IMAGE_BUTTON_SHIPPING_OPTIONS', 'Shipping Options');







define('IMAGE_BUTTON_TELL_A_FRIEND', 'Tell a Friend');







define('IMAGE_BUTTON_UPDATE', 'Update');







define('IMAGE_BUTTON_UPDATE_CART', 'Update Cart');







define('IMAGE_BUTTON_WRITE_REVIEW', 'Write Review');















define('SMALL_IMAGE_BUTTON_DELETE', 'Delete');







define('SMALL_IMAGE_BUTTON_EDIT', 'Edit');







define('SMALL_IMAGE_BUTTON_VIEW', 'View');















define('ICON_ARROW_RIGHT', 'more');







define('ICON_CART', 'In Cart');







define('ICON_ERROR', 'Error');







define('ICON_SUCCESS', 'Success');







define('ICON_WARNING', 'Warning');















define('TEXT_GREETING_PERSONAL', 'Welcome back <span class="greetUser">%s!</span> Thank you for choosing Seacoast Vitamins, we appreciate your business!');







define('TEXT_GREETING_PERSONAL_RELOGON', '<small>If you are not %s, please <a href="%s"><u>log yourself in</u></a> with your account information.</small>');







define('TEXT_GREETING_GUEST', '<span class="greetUser">First time on our new site? Please <a href="/Store/create_account.php"><u>create an account</u></a>? ' . 







                              '<BR>If you already have an account, please <a href="/Store/login.php"><u>log yourself in</u></a>.</span>');















define('TEXT_SORT_PRODUCTS', 'Sort products ');







define('TEXT_DESCENDINGLY', 'descending');







define('TEXT_ASCENDINGLY', 'ascending');







define('TEXT_BY', ' by ');















define('TEXT_REVIEW_BY', 'by %s');







define('TEXT_REVIEW_WORD_COUNT', '%s words');







define('TEXT_REVIEW_RATING', 'Rating: %s [%s]');







define('TEXT_REVIEW_DATE_ADDED', 'Date Added: %s');







define('TEXT_NO_REVIEWS', 'There are currently no product reviews.');















define('TEXT_NO_NEW_PRODUCTS', 'There are currently no products.');















define('TEXT_UNKNOWN_TAX_RATE', 'Unknown tax rate');















define('TEXT_REQUIRED', '<span class="errorText">Required</span>');















define('ERROR_TEP_MAIL', '<font face="Verdana, Arial" size="2" color="#ff0000"><b><small>TEP ERROR:</small> Cannot send the email through the specified SMTP server. Please check your php.ini setting and correct the SMTP server if necessary.</b></font>');







define('WARNING_INSTALL_DIRECTORY_EXISTS', 'Warning: Installation directory exists at: ' . dirname($HTTP_SERVER_VARS['SCRIPT_FILENAME']) . '/install. Please remove this directory for security reasons.');







define('WARNING_CONFIG_FILE_WRITEABLE', 'Warning: I am able to write to the configuration file: ' . dirname($HTTP_SERVER_VARS['SCRIPT_FILENAME']) . '/includes/configure.php. This is a potential security risk - please set the right user permissions on this file.');







define('WARNING_SESSION_DIRECTORY_NON_EXISTENT', 'Warning: The sessions directory does not exist: ' . tep_session_save_path() . '. Sessions will not work until this directory is created.');







define('WARNING_SESSION_DIRECTORY_NOT_WRITEABLE', 'Warning: I am not able to write to the sessions directory: ' . tep_session_save_path() . '. Sessions will not work until the right user permissions are set.');







define('WARNING_SESSION_AUTO_START', 'Warning: session.auto_start is enabled - please disable this php feature in php.ini and restart the web server.');







define('WARNING_DOWNLOAD_DIRECTORY_NON_EXISTENT', 'Warning: The downloadable products directory does not exist: ' . DIR_FS_DOWNLOAD . '. Downloadable products will not work until this directory is valid.');















define('TEXT_CCVAL_ERROR_INVALID_DATE', 'The expiry date entered for the credit card is invalid.<br>Please check the date and try again.');







define('TEXT_CCVAL_ERROR_INVALID_NUMBER', 'The credit card number entered is invalid.<br>Please check the number and try again.');







define('TEXT_CCVAL_ERROR_UNKNOWN_CARD', 'The first four digits of the number entered are: %s<br>If that number is correct, we do not accept that type of credit card.<br>If it is wrong, please try again.');















// Article Manager







define('BOX_HEADING_ARTICLES', 'Articles');







define('BOX_ALL_ARTICLES', 'All Articles');







define('BOX_NEW_ARTICLES', 'New Articles');







define('TEXT_DISPLAY_NUMBER_OF_ARTICLES', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> articles)');







define('TEXT_DISPLAY_NUMBER_OF_ARTICLES_NEW', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> new articles)');







define('TABLE_HEADING_AUTHOR', 'Author');







define('TABLE_HEADING_ABSTRACT', 'Abstract');







define('BOX_HEADING_AUTHORS', 'Articles by Author');







define('NAVBAR_TITLE_DEFAULT', 'Articles');















/*







  The following copyright announcement can only be







  appropriately modified or removed if the layout of







  the site theme has been modified to distinguish







  itself from the default osCommerce-copyrighted







  theme.















  For more information please read the following







  Frequently Asked Questions entry on the osCommerce







  support site:















  http://www.oscommerce.com/community.php/faq,26/q,50















  Please leave this comment intact together with the







  following copyright announcement.







*/







define('FOOTER_TEXT_BODY', 'Copyright &copy; 2003 <a href="http://www.oscommerce.com" target="_blank">osCommerce</a><br>Powered by <a href="http://www.oscommerce.com" target="_blank">osCommerce</a>');







// PWA







define('IMAGE_BUTTON_CREATE_ACCOUNT', 'Create Account');







define('NAV_ORDER_INFO', 'Order Info');








// dangling carrot v1.9 in includes/boxes/gifts/php







define('BOX_HEADING_GIFTS', 'Special Gifts');















// BOF: Product Specials Module v1.0







define('TABLE_HEADING_PRODUCT_SPECIALS', 'Specials');







// EOF: Product Specials Module v1.0





// BEGIN estimated shipping

	define('TABLE_HEADING_ESTIMATED_SHIPPING', 'Estimated Shipping Costs');

	// END estimated shipping









?>























