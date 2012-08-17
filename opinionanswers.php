<?php

// get posted data into local variables
$EmailFrom = "Seacoast Vitamins Better";
$EmailTo = "webmaster@seacoastvitamins.com";
$Subject = "Opinions";
$name = Trim(stripslashes($_POST['name'])); 
$email = Trim(stripslashes($_POST['email'])); 
$questionscomments = ($_POST['questionscomments']);

// validation
$validationOK=true;
if (!$validationOK) {
  print "<meta http-equiv=\"refresh\" content=\"0;URL=error.htm\">";
  exit;
}

// prepare email body text
$Body = "";
$Body .= "Name: ";
$Body .= $name;
$Body .= "\n";
$Body .= "Email: ";
$Body .= $email;
$Body .= "\n";
$Body .= "Questions / Comments: ";
$Body .= $questionscomments;
$Body .= "\n";

// send email 
$success = mail($EmailTo, $Subject, $Body, "From: <$EmailFrom>");

// redirect to success page 
if ($success){
  print "<meta http-equiv=\"refresh\" content=\"0;URL=index.php\">";
}
else{
  print "<meta http-equiv=\"refresh\" content=\"0;URL=index.php\">";
}
?>
