<?php

// MySQL options

$sql_host = "localhost";
$sql_username = "root";
$sql_passwd = "seamarquette11";
$basename="contest";

// Debug mode on
define ("DEBUG_MODE",0);

// Path to url - for example: "http://www.scv.edakers.com/script/" 
$script_url="http://scv.edakers.com/contest";


// Paypal fee to enroll in sweepstakes if enabled
$paypal_fee="0.00";

// admin options

$admin_email= "seacoastadmin";
$admin_password = "seamarquette11";

// mail options

// This email will be displayed in 'from' field of the mail messages
$robot_email = "robot@sweepstakes.com";

// This name will be displayed in 'from' field of the mail messages
$robot_name = "Robot";  

// Subject of message that will be sent to user to confirm registration
$confirm_email_subject = "Confirm E-Mail";  

// Subject of message that will be sent to Winner
$winner_email_subject = "You won!";

// Paths for data & included files

$INCLUDE_PATH="inc/";
$DATA_PATH="data/";

// Error Messages

$err[11] = "Password fields must be identical";
$err[12] = "You must fill all fields";
$err[13] = "Undefined code";
$err[15] = "No such email or password mismatch";

$err[21] = "Name field is too long";
$err[22] = "Password field is too long";
$err[23] = "Address field is too long";
$err[24] = "Country field is too long";
$err[25] = "State field is too long";
$err[26] = "City field is too long";
$err[27] = "E-Mail field is too long";
$err[28] = "ZIP field is too long";
$err[29] = "Phone field is too long";

$err[31] = "Can't upload html-file";
$err[32] = "Can't upload attached file";

$err[40] = "Such user is already enroll in this sweepstake";
$err[41] = "Sorry but you must pay paypal fee to enroll in sweepstakes";

$err[51] = "You must fill 'link' field'";
$err[52] = "Nobody wins";
$err[53] = "Password or e-mail incorrect";
?>