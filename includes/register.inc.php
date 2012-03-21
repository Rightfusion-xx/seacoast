<?php

/*
	(c) Kaavren
*/

include_once("utils.php");

$link = mysql_connect ($sql_host, $sql_username, $sql_passwd) or die ("Could not connect to database. Try later<BR>");
@mysql_select_db($basename, $link);

class class_register {

function confirm_email($url,$email,$code) {
  global $INCLUDE_PATH;
  global $robot_name;
  global $robot_email;
  global $confirm_email_subject;
//  global $script_url;

  $body="";
  $fp=fopen("$INCLUDE_PATH"."confirm_mail.inc","r"); 
  while (!feof ($fp)) {
    $buffer = chop(fgets($fp, 4096));
    if ($buffer=="<<<confirmation email>>>") {
//      $confirm_url="$url"."?action=2&code="."$code";
//      $body.="<a href=\""."$confirm_url"."\">".$confirm_url."</a><br>";
        $body.="$url"."?action=2&code="."$code \n";

    }
    else {
      $body.="$buffer"."\n";
    }
  }
  fclose ($fp);

//  $body=nl2br($body);  

//  echo "$body";  

  $template = new class_mail();
  $template->mailer("$robot_name<".$robot_email.">",$email,$confirm_email_subject,$body);

  return(0);
}

function format_activation_code($id) {
  $id=(int)$id;
  $code="$id";

  srand($id);

  for ($i=0;$i<3;$i++) {
    $rand_value=rand(1000,9999);
    srand($rand_value);
    $code.=$rand_value;
  }  

  return($code);
}

function check_fields()
{
  $user_name=htmlspecialchars($_POST['user_name']);
  $password=$_POST['password'];
  $password2=$_POST['password2'];
  $user_address=htmlspecialchars($_POST['address']);
  $user_country=htmlspecialchars($_POST['country']);
  $user_state=htmlspecialchars($_POST['state']);
  $user_city=htmlspecialchars($_POST['city']);
  $user_zip=$_POST['zip'];
  $user_email=$_POST['email'];
  $user_phone=$_POST['phone'];

  if (strlen($user_name) > 50) {
    $err=21; 
    $this->error_report($err);
    return($err);
  }
  if (strlen($password) > 50) {
    $err=22; 
    $this->error_report($err);
    return($err);
  }
  if (strlen($user_address) > 50) {
    $err=23; 
    $this->error_report($err);
    return($err);
  }
  if (strlen($user_country) > 50) {
    $err=24; 
    $this->error_report($err);
    return($err);
  }
  if (strlen($user_state) > 50) {
    $err=25; 
    $this->error_report($err);
    return($err);
  }
  if (strlen($user_city) > 50) {
    $err=26; 
    $this->error_report($err);
    return($err);
  }
  if (strlen($user_email) > 100) {
    $err=27; 
    $this->error_report($err);
    return($err);
  }
  if (strlen($user_zip) > 20) {
    $err=28; 
    $this->error_report($err);
    return($err);
  }
  if (strlen($user_phone) > 20) {
    $err=29; 
    $this->error_report($err);
    return($err);
  }

  if ((!$user_name) || (!$user_address) || (!$password) || (!$user_country) || (!$user_state) || (!$user_city) || (!$user_zip) || (!$user_phone) || (!$user_email)) {
    $this->error_report(12);
    return(12);
  }

  if ($password!=$password2) {
    $this->error_report(11);
    return(11);
  }

  return(0);
}

function error_report($errcode) {
  error_report($errcode);
  echo "<br><center><a href=\"register.php\">Go Back</a></center>";

  return(0);
}

function user_register_step1() {
  global $INCLUDE_PATH;
  echo "<form name=\"reg_form\" action=\"register.php?action=1\" method=\"POST\">";
  echo "<input type=\"hidden\" name=\"script_url\">";
  include_once("$INCLUDE_PATH"."registration_form.inc.php");
  echo "</form>";
  echo "<script language=\"JavaScript\"> reg_form.script_url.value=window.location.href; </script>";

  return(0);
}

function user_register_step2() {

  global $link;

  $user_name=htmlspecialchars($_POST['user_name']);
  $password=$_POST['password'];
  $password2=$_POST['password2'];
  $user_address=htmlspecialchars($_POST['address']);
  $user_country=htmlspecialchars($_POST['country']);
  $user_state=htmlspecialchars($_POST['state']);
  $user_city=htmlspecialchars($_POST['city']);
  $user_zip=$_POST['zip'];
  $user_email=$_POST['email'];
  $user_phone=$_POST['phone'];
  $url=$_POST['script_url'];

  $err=$this->check_fields();
  if ($err) { return($err); }

  $r=mysql_query("select max(id) from users");
  if ($r) {
    list($id) = mysql_fetch_row($r);
    $id = (int)$id;
    $id++;
  }
  else {
    $id=1;
  }

  $activation_code=$this->format_activation_code($id);
  
//  $activation_code = "$id"."$rand_value";

//  echo "<h2>$activation_code</h2>";

  $r = mysql_query("insert into users(user_name,user_password,user_address,user_country,
  user_state,user_city,user_zip,user_phone,user_email,activation_code) 
  values(
  '".mysql_escape_string($user_name)."',
  '".mysql_escape_string($password)."',
  '".mysql_escape_string($user_address)."',
  '".mysql_escape_string($user_country)."',
  '".mysql_escape_string($user_state)."',
  '".mysql_escape_string($user_city)."',
  '".mysql_escape_string($user_zip)."',
  '".mysql_escape_string($user_phone)."',
  '".mysql_escape_string($user_email)."',
  '".mysql_escape_string($activation_code)."')",$link);

  if (!$r) {  error_report(10);  }

/*
  $r = mysql_query("insert into mailing_list(id,email) 
  values(
  '$id',
  '".mysql_escape_string($user_email)."')",$link);

  if (!$r) {  error_report(10);  }
*/

  $this->user_activate($activation_code);

  return(0);
}

function user_activate($code='') {
  global $link;

  if (strlen($code)<1) {
    if (DEBUG_MODE) { error_report(13); }
    return(13);
  }
  //$code=$_GET['code']; Not needed since it's being passed in.
  
  $r=mysql_query("select id,user_email from users where activation_code='".mysql_escape_string($code)."'",$link);

  if (!$r) {
    if (DEBUG_MODE) { error_report(13); }
    return(13);
  }

  list($id,$user_email)=mysql_fetch_row($r);
  
  $r=mysql_query("update users set activation_code='0' where id='$id'",$link);  
  if (!$r) {
    if (DEBUG_MODE) { error_report(10); }
    return(10);
  }

  $r = mysql_query("insert into mailing_list(email) 
  values('".mysql_escape_string($user_email)."')",$link);

  if (!$r) {  error_report(10);  }

/*
  $r=mysql_query("update mailing_list set enable='Y' where id='$id'",$link);  
  if (!$r) {
    if (DEBUG_MODE) { error_report(10); }
    return(10);
  }
*/

  echo "<center>Your account has succesfully registered</center>";
  echo "<center><a href=\"sweepstakes.php\">Here</a> you can see information about sweepstakes</center>";

  return(0);
}


function class_register() {
  if (isset($_GET['action'])) {
    $action=$_GET['action'];
    $r = 0;

    switch ($action) {
    case 1:
      $r = $this->user_register_step2();
    break;
    case 2:
      $r = $this->user_activate();
    break;
    }
  }
  else {
    $r = $this->user_register_step1();
  }
  
  return($r);
}
}
?>