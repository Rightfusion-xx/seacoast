<?php

// get posted data into local variables
$EmailFrom = "Seacoast Vitamins Catalog";
$EmailTo = "orders@seacoastvitamins.com";
$Subject = "Catalog Request";
$Name = Trim(stripslashes($_POST['Name'])); 
$Address1 = Trim(stripslashes($_POST['Address1'])); 
$Address2 = Trim(stripslashes($_POST['Address2'])); 
$City = Trim(stripslashes($_POST['City']));
$State = ($_POST['State']);
$Zipcode = ($_POST['Zipcode']);
// validation
$validationOK=true;
if (!$validationOK) {
  print "<meta http-equiv=\"refresh\" content=\"0;URL=error.htm\">";
  exit;
}

// prepare email body text
$Body = "";
$Body .= "Name: ";
$Body .= $Name;
$Body .= "\n";
$Body .= "Address1: ";
$Body .= $Address1;
$Body .= "\n";
$Body .= "Address2: ";
$Body .= $Address2;
$Body .= "\n";
$Body .= "City: ";
$Body .= $City;
$Body .= "\n";
$Body .= "State: ";
$Body .= $State;
$Body .= "\n";
$Body .= "Zipcode: ";
$Body .= $Zipcode;
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
