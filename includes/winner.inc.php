<?

/*
	(c) Kaavren
*/

include_once("utils.php");

$link = mysql_connect ($sql_host, $sql_username, $sql_passwd) or die ("Could not connect to database. Try later<BR>");
@mysql_select_db($basename, $link);

function user_authorize() {
  global $INCLUDE_PATH;

  $id=$_GET['id'];

  echo "<form name=\"auth_form\" action=\"winner.php?action=0&id=$id\" method=\"POST\">";
  include_once("$INCLUDE_PATH"."auth_form.inc.php");
  echo "</form>";

  return(0);
}

function see_information() {
  global $INCLUDE_PATH;
  global $DATA_PATH;
  global $link;

  $email=$_POST['email'];
  $password=$_POST['password'];
  $id=$_GET['id'];

  if ((!$email) || (!$password)) {
    $this->error_report(12);
    return(12);
  }

  $r=mysql_query("select u.user_email from users u, players pl, prizes pr where pr.id='$id' and pl.id=pr.winner_id and pl.email=u.user_email and u.user_email='".mysql_escape_string($email)."' and u.activation_code='0' and u.user_password='".mysql_escape_string($password)."'",$link);
  if (!$r) {
    error_report(10);
    return(10);
  }
  if (mysql_num_rows($r) == 0) {
    error_report(53);
    return(53);
  }
  else {
    list($user_email)=mysql_fetch_row($r);
  }
  
  $r=mysql_query("select prize_type from prizes where id='$id'",$link);
  if (!$r) {
    error_report(10);
    return(10);
  }
  list($prize_type)=mysql_fetch_row($r);

  if ($prize_type=="C") {
    echo "<form name=\"auth_form2\" action=\"winner.php?action=1&id=$id\" method=\"POST\">";
    echo "<input type=\"hidden\" name=\"user_email\" value=\"$user_email\">";
    include_once("$INCLUDE_PATH"."winner_cash.inc");
    echo "</form>";
  }
  if ($prize_type=="D") {
    $r=mysql_query("select prize_link from links where prize_id='$id'",$link);
    if (!$r) {
      error_report(10);
      return(10);
    }
    list($prize_link)=mysql_fetch_row($r);
    
   $body="";

    $fp=fopen("$INCLUDE_PATH"."winner_downloadable.inc","r");
    while (!feof ($fp)) {
      $buffer = chop(fgets($fp, 10000));
      if (strchr($buffer,"<<<link>>>")) {
        $buffer=str_replace("<<<link>>>","<a href=\"$prize_link\">$prize_link</a>",$buffer);
      }
      $body.="$buffer"."\n";
    }
    fclose ($fp);
   
    echo "$body";
  }

  if ($prize_type=="I") {
    $filepath="$DATA_PATH"."$id".".ins";
    $fp=fopen("$filepath","r");
    $instructions=fread($fp,filesize($filepath));
    fclose($fp);
    
    $body="";

    $fp=fopen("$INCLUDE_PATH"."winner_items.inc","r");
    while (!feof ($fp)) {
      $buffer = chop(fgets($fp, 10000));
      if (strchr($buffer,"<<<instructions>>>")) {
        $buffer=str_replace("<<<instructions>>>","$instructions",$buffer);
      }
      $body.="$buffer"."\n";
    }
    fclose ($fp);
   
    echo "$buffer";
  }


  return(0);
}

function email_to_admin() {
  global $robot_name;
  global $robot_email;
  global $admin_email;

  $paypal_address=$_POST['paypal_address'];
  $email=$_POST['user_email'];

  $body="Winner $email paypal address:"."\n";  
  $body.="$paypal_address";

  $template=new class_mail();
  $template->mailer($robot_email,$admin_email,"Paypal Address",$body); 

  echo "Thanks! You paypal address successfully e-mailed to admin";

  return(0);
}

if (!isset($_GET['action'])) {
  user_authorize();
}
else {
  $action=$_GET['action'];
  if ($action==0) {
    see_information();
  }
  else {
    email_to_admin();
  }
}


?>