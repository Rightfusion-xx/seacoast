<?

/*
	(c) Kaavren
*/

include_once("utils.php");

$link = mysql_connect ($sql_host, $sql_username, $sql_passwd) or die ("Could not connect to database. Try later<BR>");
@mysql_select_db($basename, $link);


class class_unsubscribe {

function error_report($errcode) {
  error_report($errcode);
  echo "<br><center><a href=\"unsubscribe.php\">Go Back</a></center>";

  return(0);
}


function user_unsubscribe_step1() {
  global $INCLUDE_PATH;

  echo "<form name=\"unsubscribe_form\" action=\"unsubscribe.php?action=1\" method=\"POST\">";
  include_once("$INCLUDE_PATH"."unsubscribe_form.inc.php");
  echo "</form>";

  return(0);
}

function user_unsubscribe_step2() {
  global $link;

  $email=$_POST['email'];
  $password=$_POST['password'];
  if ((!$email) || (!$password)) {
    $this->error_report(12);
    return(12);
  }
  
  $r=mysql_query("select * from users where user_email='".mysql_escape_string($email)."' and   user_password='".mysql_escape_string($password)."'",$link);
  
  if (mysql_num_rows($r) == 0) {
    $this->error_report(15);
    return(15);
  }

  $r=mysql_query("delete from mailing_list where email='".mysql_escape_string($email)."'",$link);

  echo "<center>Thank you, you have successfully unsubscribed</center>";

  return(0);
}



function class_unsubscribe() {
  if (!isset($_GET['action'])) {
    $this->user_unsubscribe_step1();
  }
  else {
    $this->user_unsubscribe_step2();
  }

  return(0);
}

}
?>