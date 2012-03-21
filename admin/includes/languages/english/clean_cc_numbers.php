<?php
/*
  $Id: clean_cc_numbers.php,v 0.9 2004/12/29 00:28:44 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('HEADING_TITLE', 'Clean Credit Card Numbers');

define('DESCRIPTION_TEXT_INIT', 'Credit Card numbers are kept in the database of the system for every order placed. 
Although is very unlikely that someone besides you gets access to this data, it is not impossible. In the event that 
an intruder reaches this data, he could empty all of your customers pockets.');

define('DESCRIPTION_TEXT_SEC', 'This tool allows you to turn into XXXXXX every digit except for the last four of the credit 
card database. Order that have a status of "Pending" will not be processed. Be warned that this process is not undoable and
once done all numbers will be lost.');

define('DESCRIPTION_TEXT_THRD', 'Before securing your database this way, make sure that all orders have been processed
as you will lose the credit card information instantly. Use at your own risk.');

define('BODY_TEXT_STATUSIS','Delete credit card numbers from orders that are ');
define('BODY_TEXT_MORETHAN',' and are more than ');
define('BODY_TEXT_DAYSOLD',' days old.');


define('CONFIRM_SCRIPT', 'Are you sure you want to clean de Credit Card Numbers?');
define('IMAGE_CLEAN', 'Clean Numbers');
define('ERROR_EMPTY_LOG', 'The orders log does not have credit card entries or is empty');


?>
