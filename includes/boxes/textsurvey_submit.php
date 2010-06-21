<?php
/*
  $Id: textsurvey.php,v 1.0 2003/05/03 22:30:51 Contribution by Chris Chong of AuraDev Web Development chris@auradev.com,
random picker script by cj-design.com

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2001 osCommerce

  Released under the GNU General Public License
*/
?>
<?php if ($results == $null) {

	header("Location: $HTTP_REFERER");

	exit;
	}

	else {

	$to = "webmaster@seacoastvitamins.com";
	$from = "survery@seacoastvitamins.com";
	$subject = "Customer Feed Back";

	
	
	$msg = "$question\n\n";
	$msg .= "$results";
	
mail($to, $subject, $mailheaders, $msg);

header("Location: $HTTP_REFERER?&textsurvey=sent");

exit;
	} ?>

