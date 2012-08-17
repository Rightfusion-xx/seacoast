<?php

// get posted data into local variables
$EmailFrom = "Seacoast Vitamins Newsletter";
$EmailTo = "webmaster@seacoastvitamins.com";
$Subject = "Newsletter Remove please";
$emailaddress = Trim(stripslashes($_POST['emailaddress'])); 
// validation
$validationOK=true;
if (!$validationOK) {
  print "<meta http-equiv=\"refresh\" content=\"0;URL=error.php\">";
  exit;
}

// prepare email body text
$Body = "";
$Body .= "emailaddress: ";
$Body .= $emailaddress;
$Body .= "\n";

// send email 
$success = mail($EmailTo, $Subject, $Body, "From: <$EmailFrom>");

// redirect to success page 
if ($success){
  print "<meta http-equiv=\"refresh\" content=\"0;URL=thankyouc.php\">";
}
else{
  print "<meta http-equiv=\"refresh\" content=\"0;URL=error.php\">";
}
?>
