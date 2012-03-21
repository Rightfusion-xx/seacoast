<?php

/*
	(c) Kaavren
*/

include_once("utils.php");    

$link = mysql_connect ($sql_host, $sql_username, $sql_passwd) or die ("Could not connect to database. Try later<BR>");
@mysql_select_db($basename, $link);

class class_sweepstakes {

function error_report($code) {
  error_report($code);
  echo "<center><br><a href=\"sweepstakes.php\">Go Back</a></center>";

  return(0);
}

function view_sweepstakes() {
  global $link;

  echo "<table border=\"1\" cellspacing=\"0\" cellpadding=\"3\" width=\"100%\"><tr>";
  echo "<td width=\"20%\">Begin</td><td width=\"20%\">End</td><td width=\"40%\">Prizes</td>";
  echo "<td align=\"center\" width=\"20%\">&nbsp;</td></tr>";
//  echo "<td align=\"center\" colspan=\"2\" width=\"30%\">Actions</td></tr>";

  $r=mysql_query("select * from sweepstakes where enable='Y'",$link);
  for($i=0; $i<mysql_numrows($r); $i++) {
    $f=mysql_fetch_array($r);
    $sweepstake_id=$f['id'];
    $r1=mysql_query("select prize_name from prizes where sweepstake_id='$sweepstake_id'",$link);
    $num=mysql_num_rows($r1);
    echo "<tr>";
    echo "<td rowspan=\"$num\" width=\"20%\">$f[date_begins]</td>";
    echo "<td rowspan=\"$num\" width=\"20%\">$f[date_ends]</td>";

    list($prize_name)=mysql_fetch_row($r1);
    echo "<td width=\"20%\">$prize_name</td>";

    echo "<td align=\"center\" rowspan=\"$num\" width=\"20%\"><a href=\"sweepstakes.php?action=0&id=$f[id]\">Enroll</a></td>";
    echo "</tr>";
    
    for ($j=1;$j<$num;$j++) {
      list($prize_name)=mysql_fetch_row($r1);
      echo "<tr><td width=\"20%\">$prize_name</td></tr>";
    }

  }

  echo "</table>";

  return(0);
}

function add_player_step1() {
  global $INCLUDE_PATH;
  $id=$_GET['id'];

  echo "<form name=\"new_player\" action=\"sweepstakes.php?action=1&id=$id\" method=\"POST\">";
  echo "<input type=\"hidden\" name=\"script_url\">";

/*  echo "<b>E-Mail:</b> &nbsp; <input type=\"text\" name=\"email\"><br><br>";
  echo "<b>Password:</b> &nbsp; <input type=\"password\" name=\"password\"><br><br>";
  echo "<input type=\"submit\" value=\"  OK  \">";
*/
  include_once("$INCLUDE_PATH"."enroll_form.inc.php");  

  echo "</form>";  
  echo "<script language=\"JavaScript\"> new_player.script_url.value=window.location.href; </script>";

  return(0);
}

function add_player_step2() {
  global $link;
  global $paypal_fee;
  global $admin_email;


  $sweepstake_id=$_GET['id'];
  $email=$_POST['email'];
  $password=$_POST['password'];

  $url=$_POST['script_url'];
  $arr=explode("?",$url);
  $script_url=$arr[0];  

  if ((!$email) || (!$password)) {
    $this->error_report(12);
    return(12);
  }
  
  $r=mysql_query("select * from users where user_email='".mysql_escape_string($email)."' and   user_password='".mysql_escape_string($password)."'",$link);
  
  if (mysql_num_rows($r) == 0) {
    $this->error_report(15);
    return(15);
  }

  $r=mysql_query("select * from players where sweepstake_id='$sweepstake_id' and 
  email='".mysql_escape_string($email)."'",$link);

  if (!$r) {
    error_report(10);
    return(10);
  }

  if (mysql_num_rows($r)>0) {
    $this->error_report(40);
    return(40);
  }

  $r=mysql_query("select free from sweepstakes where id='$sweepstake_id'",$link); 
  if (!$r) {
    error_report(10);
    return(10);
  }
  list($free)=mysql_fetch_row($r);


  if ($free=="N") {
    $custom="ok@"."$email";

    echo "Please, click ok button and pay paypal fee to enroll in sweepstakes";
    echo "<form method=\"post\" action= \"https://www.paypal.com/cgi-bin/webscr\">";
    echo "<input type=\"hidden\" name=\"cmd\" value=\"_xclick\">";
    echo "<input type=\"hidden\" name=\"business\" value=\"$admin_email\">";
    echo "<input type=\"hidden\" name=\"item_name\" value=\"enroll in sweepstakes\">";
    echo "<input type=\"hidden\" name=\"item_number\" value=\"$sweepstake_id\">";
    echo "<input type=\"hidden\" name=\"amount\" value=\"$paypal_fee\">";
    echo "<input type=\"hidden\" name=\"rm\" value=\"2\">";
    echo "<input type=\"hidden\" name=\"notify_url\" value=\"$script_url?action=3\">";
    echo "<input type=\"hidden\" name=\"return\" value=\"$script_url?action=4\">";
    echo "<input type=\"hidden\" name=\"custom\" value=\"$custom\">";
    echo "<input type=\"hidden\" name=\"no_shipping\" value=\"1\">";
    echo "<input type=\"submit\" value=\"  OK  \">";
    echo "</form>";
  }
  else {

    $r=mysql_query("insert into players(sweepstake_id,email) values(
    '".mysql_escape_string($sweepstake_id)."',
    '".mysql_escape_string($email)."')",$link);
    if (!$r) {
      error_report(10);
      return(10);
    }

    $this->add_player_step4();
  }  

  return(0);
}

function add_player_step3() {
  global $link;

  if (isset($_POST['item_number'])) { 
    $sweepstake_id=$_POST['item_number'];
  }
  else {
    error_report(41);
    return(41);
  }

  if (isset($_POST['custom'])) { 
    $custom=$_POST['custom'];
    $arr=explode("@","$custom");
    if ($arr[0]=="ok") {
      $email="$arr[1]"."@"."$arr[2]";
    }
    else {
      error_report(41);
      return(41);
    }
  }
  else {
    error_report(41);
    return(41);
  }


  $r=mysql_query("insert into players(sweepstake_id,email) values(
  '".mysql_escape_string($sweepstake_id)."',
  '".mysql_escape_string($email)."')",$link);

  if (!$r) {
    error_report(10);
    return(10);
  }


//  echo "Thank you, you have successfully included in player list";

  return(0);


}

function add_player_step4() {
  echo "Thank you, you have successfully included in player list";

  return(0);
}


function winners_list() {
  global $link;

  $m=date("m",time());
  $y=date("y",time());
  $mask="$m/__/$y";

  $r = mysql_query("select s.date_ends, u.user_name, pr.prize_name  
  from sweepstakes s, prizes pr, users u, players pl
  where s.date_ends like '$mask' and s.id=pr.sweepstake_id 
  and pr.winner_id=pl.id and u.user_email=pl.email order by s.date_ends desc"
  ,$link);

  if (!$r) {
    error_report(10);
    return(10);
  }

  if (mysql_num_rows($r)==0) {
    echo "No winners in this month";
  } 
  else {
    echo "<table border=\"1\" cellspacing=\"0\" cellpadding=\"3\" width=\"100%\"><tr>";
    echo "<td width=\"20%\">Date</td><td width=\"40%\">Name</td><td width=\"40%\">Prize</td></tr>";
    for ($i=0;$i<mysql_num_rows($r);$i++) {
      list($date,$name,$prize)=mysql_fetch_row($r);
      echo "<td width=\"20%\">$date</td><td width=\"40%\">$name</td><td width=\"40%\">$prize</td></tr>";
    }
   }
  return 0;
}

function class_sweepstakes() {
  if (!isset($_GET['action'])) {
    $this->view_sweepstakes();
  }
  else {
    $action=$_GET['action'];

    switch ($action) {
    case 0:
      $this->add_player_step1();
    break;
    case 1:
      $this->add_player_step2();
    break;
    case 2:
      $this->winners_list();
    break;
    case 3:
      $this->add_player_step3();
    break;
    case 4:
      $this->add_player_step4();
    break;

    }
  }
}

}
?>